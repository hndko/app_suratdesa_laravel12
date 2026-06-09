<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\Surat;
use App\Services\SuratNumberService;
use App\Jobs\SendWhatsAppMessage;
use App\Models\SuratApproval;
use App\Services\SuratVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF; // Assuming we use dompdf or similar later, but for now just view

class SuratController extends Controller
{
    // NOTE: Arsip Surat (Index)
    public function index()
    {
        $data = [
            'title' => 'Arsip Surat',
            'surats' => Surat::with(['penduduk', 'jenisSurat', 'verification'])->latest()->paginate(25),
        ];

        return view('backend.surat.index', $data);
    }

    // NOTE: Form Buat Surat
    public function create()
    {
        $selectedPenduduk = old('penduduk_id')
            ? Penduduk::select('id', 'nik', 'nama', 'phone', 'alamat')->find(old('penduduk_id'))
            : null;

        $data = [
            'title' => 'Buat Surat Baru',
            'selectedPenduduk' => $selectedPenduduk,
            'jenis_surats' => JenisSurat::orderBy('nama_surat')->get(),
        ];

        return view('backend.surat.create', $data);
    }

    public function pendudukOptions(Request $request): JsonResponse
    {
        $search = trim((string) $request->input('q'));

        $penduduks = Penduduk::query()
            ->select('id', 'nik', 'nama', 'phone', 'alamat')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('nik', 'like', '%' . $search . '%')
                        ->orWhere('nama', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('alamat', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->limit(20)
            ->get()
            ->map(function (Penduduk $penduduk) {
                return [
                    'id' => $penduduk->id,
                    'text' => $penduduk->nik . ' - ' . $penduduk->nama,
                    'nik' => $penduduk->nik,
                    'nama' => $penduduk->nama,
                    'phone' => $penduduk->phone,
                    'alamat' => $penduduk->alamat,
                ];
            });

        return response()->json(['results' => $penduduks]);
    }

    // NOTE: Simpan Surat
    public function store(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'tanggal_surat' => 'required|date',
            'keperluan' => 'required|string|max:2000',
            'keterangan' => 'nullable|string|max:2000',
        ]);

        $jenisSurat = JenisSurat::findOrFail($request->jenis_surat_id);
        $no_surat = SuratNumberService::generate($jenisSurat, $request->tanggal_surat);

        $surat = Surat::create([
            'no_surat' => $no_surat,
            'tracking_code' => $this->generateTrackingCode(),
            'penduduk_id' => $request->penduduk_id,
            'jenis_surat_id' => $request->jenis_surat_id,
            'user_id' => Auth::id(),
            'tanggal_surat' => $request->tanggal_surat,
            'keperluan' => $request->keperluan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('surat.show', $surat->id)->with('success', 'Surat berhasil dibuat.');
    }

    // NOTE: Preview Surat (AJAX)
    public function preview(Request $request)
    {
        $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'tanggal_surat' => 'required|date',
        ]);

        $jenisSurat = JenisSurat::findOrFail($request->jenis_surat_id);
        $penduduk = Penduduk::findOrFail($request->penduduk_id);

        // Inject Keperluan & Keterangan from Request
        $extraData = [
            'keperluan' => $request->keperluan,
            'keterangan' => $request->keterangan,
        ];

        $content = $this->processTemplate($jenisSurat->template_isi, $penduduk, $extraData);

        // Render Partial View
        $previewHtml = view('backend.surat.print_template', [
            'header' => $jenisSurat->kop_judul,
            'nomor_surat' => 'XXX/000/' . date('m', strtotime($request->tanggal_surat)) . '/' . date('Y', strtotime($request->tanggal_surat)),
            'tanggal_surat' => $request->tanggal_surat,
            'content' => $content
        ])->render();

        return response()->json(['html' => $previewHtml]);
    }

    // NOTE: Detail / Cetak Surat
    public function show(string $id)
    {
        $surat = Surat::with(['penduduk', 'jenisSurat', 'verification'])->findOrFail($id);
        $penduduk = $surat->penduduk;

        // Inject Keperluan & Keterangan from Saved Surat
        $extraData = [
            'keperluan' => $surat->keperluan,
            'keterangan' => $surat->keterangan,
        ];

        $content = $this->processTemplate($surat->jenisSurat->template_isi, $penduduk, $extraData);

        return view('backend.surat.show', [
            'title' => 'Cetak Surat',
            'surat' => $surat,
            'content' => $content
        ]);
    }

    private function processTemplate(string $template, Penduduk $penduduk, array $extraData = [])
    {
        // Force Indonesian Locale for Date Formatting
        \Carbon\Carbon::setLocale('id');

        $search = [
            '[nama]',
            '[nik]',
            '[tempat_lahir]',
            '[tgl_lahir]',
            '[alamat]',
            '[agama]',
            '[pendidikan]',
            '[golongan_darah]',
            '[shdk]',
            '[status_perkawinan]',
            '[pekerjaan]',
        ];
        $replace = [
            $penduduk->nama,
            $penduduk->nik,
            $penduduk->tempat_lahir,
            \Carbon\Carbon::parse($penduduk->tgl_lahir)->isoFormat('D MMMM Y'),
            $penduduk->alamat,
            $penduduk->agama,
            $penduduk->pendidikan ?? '-',
            $penduduk->golongan_darah ?? '-',
            $penduduk->shdk ?? '-',
            $penduduk->status_perkawinan,
            $penduduk->pekerjaan
        ];

        // 1. Replace Placeholders
        $content = str_replace($search, $replace, $template);

        // 2. Format Identity Section (Nama, NIK, etc.) into a Table
        // Pattern to look for lines starting with "Label: Value"
        $lines = explode("\n", $content);
        $formattedLines = [];
        $inTable = false;

        $identityLabels = [
            'Nama',
            'NIK',
            'Tempat/Tgl Lahir',
            'TTL',
            'Alamat',
            'Agama',
            'Pendidikan',
            'Golongan Darah',
            'SHDK',
            'Pekerjaan',
            'Status Perkawinan',
        ];

        foreach ($lines as $line) {
            $trimLine = trim($line);
            $isIdentityLine = false;

            foreach ($identityLabels as $label) {
                if (stripos($trimLine, $label . ':') === 0) {
                    $isIdentityLine = true;
                    // Extract value
                    $parts = explode(':', $trimLine, 2);
                    $value = isset($parts[1]) ? trim($parts[1]) : '';

                    if (!$inTable) {
                        $formattedLines[] = '<table style="width: 100%; margin-bottom: 10px; border: none;">';
                        $inTable = true;
                    }

                    $formattedLines[] = '<tr>
                        <td style="width: 180px; vertical-align: top;">' . e($label) . '</td>
                        <td style="width: 10px; vertical-align: top;">:</td>
                        <td style="vertical-align: top;">' . e($value) . '</td>
                    </tr>';
                    break;
                }
            }

            if (!$isIdentityLine) {
                if ($inTable) {
                    $formattedLines[] = '</table>';
                    $inTable = false;
                }
                // Regular line, preserve empty lines as br
                if (empty($trimLine)) {
                    $formattedLines[] = '<br>';
                } else {
                    $formattedLines[] = '<p style="margin-bottom: 5px; text-align: justify;">' . e($trimLine) . '</p>';
                }
            }
        }

        if ($inTable) {
            $formattedLines[] = '</table>';
        }

        $content = implode("", $formattedLines);

        // 3. Auto-append Keperluan & Keterangan using a neat table
        if (!empty($extraData['keperluan']) || !empty($extraData['keterangan'])) {
            $content .= "<br><table style='width: 100%; border: none; font-size: 12pt;'>";

            if (!empty($extraData['keperluan'])) {
                $content .= "<tr><td style='width: 180px; vertical-align: top;'>Keperluan</td><td style='width: 10px; vertical-align: top;'>:</td><td style='vertical-align: top; text-align: justify;'>" . e($extraData['keperluan']) . "</td></tr>";
            }

            if (!empty($extraData['keterangan'])) {
                $content .= "<tr><td style='width: 180px; vertical-align: top;'>Keterangan</td><td style='width: 10px; vertical-align: top;'>:</td><td style='vertical-align: top; text-align: justify;'>" . e($extraData['keterangan']) . "</td></tr>";
            }

            $content .= "</table>";
        }

        return $content;
    }

    public function edit(string $id)
    {
        $surat = Surat::with(['penduduk', 'jenisSurat', 'approvals.user'])->findOrFail($id);
        $data = [
            'title' => 'Update Status Surat',
            'surat' => $surat,
        ];

        return view('backend.surat.edit', $data);
    }

    public function update(Request $request, string $id)
    {
        $surat = Surat::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,process,verified,approved,done,rejected',
            'note' => 'nullable|string|max:1000',
        ]);

        $requiredPermission = match ($request->status) {
            'verified' => 'surat-verify',
            'approved' => 'surat-approve',
            'rejected' => 'surat-reject',
            default => 'surat-update-status',
        };

        if (!$request->user()->can($requiredPermission)) {
            return redirect()->route('surat.edit', $surat->id)->with('error', 'Anda tidak memiliki permission untuk aksi status ini.');
        }

        $oldStatus = $surat->status;
        $allowedTransitions = [
            'pending' => ['pending', 'process', 'verified', 'rejected'],
            'process' => ['process', 'verified', 'rejected'],
            'verified' => ['verified', 'approved', 'rejected'],
            'approved' => ['approved', 'done', 'rejected'],
            'done' => ['done'],
            'rejected' => ['rejected'],
        ];

        if (!in_array($request->status, $allowedTransitions[$oldStatus] ?? [$oldStatus], true)) {
            return redirect()->route('surat.edit', $surat->id)->with('error', 'Transisi status surat tidak valid untuk alur approval.');
        }

        $update = ['status' => $request->status, 'approval_note' => $request->note];

        if ($request->status === 'verified') {
            $update['verified_at'] = now();
        }

        if ($request->status === 'approved') {
            $update['approved_at'] = now();
        }

        if ($request->status === 'rejected') {
            $update['rejected_at'] = now();
        }

        $surat->update($update);

        SuratApproval::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'action' => $request->status,
            'from_status' => $oldStatus,
            'to_status' => $request->status,
            'note' => $request->note,
        ]);

        if (in_array($request->status, ['approved', 'done'], true)) {
            app(SuratVerificationService::class)->ensureVerification($surat->fresh('verification'));
        }

        if ($request->status !== $oldStatus) {
            $penduduk = $surat->penduduk;
            $statusText = match ($request->status) {
                'process' => 'DIPROSES',
                'verified' => 'DIVERIFIKASI',
                'approved' => 'DISETUJUI',
                'done' => 'SELESAI',
                'rejected' => 'DITOLAK',
                default => 'MENUNGGU',
            };
            $message = "Halo {$penduduk->nama}, pengajuan surat {$surat->jenisSurat->nama_surat} Anda berstatus: {$statusText}. Kode Tracking: {$surat->tracking_code}.";

            if ($penduduk->phone) {
                SendWhatsAppMessage::dispatch($penduduk->phone, $message);
            }
        }

        return redirect()->route('surat.index')->with('success', 'Status surat berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Surat::findOrFail($id)->delete();
        return redirect()->route('surat.index')->with('success', 'Surat berhasil dihapus.');
    }

    private function generateTrackingCode(): string
    {
        do {
            $code = 'SRT-' . strtoupper(\Illuminate\Support\Str::random(10));
        } while (Surat::where('tracking_code', $code)->exists());

        return $code;
    }
}
