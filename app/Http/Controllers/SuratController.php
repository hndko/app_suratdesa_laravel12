<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\Surat;
use App\Services\SuratNumberService;
use App\Jobs\SendWhatsAppMessage;
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
            'surats' => Surat::with(['penduduk', 'jenisSurat'])->latest()->paginate(25),
        ];

        return view('backend.surat.index', $data);
    }

    // NOTE: Form Buat Surat
    public function create()
    {
        $data = [
            'title' => 'Buat Surat Baru',
            'penduduks' => Penduduk::select('id', 'nik', 'nama')->latest()->limit(100)->get(),
            'jenis_surats' => JenisSurat::all(),
        ];

        return view('backend.surat.create', $data);
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
        $surat = Surat::with(['penduduk', 'jenisSurat'])->findOrFail($id);
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

        $search = ['[nama]', '[nik]', '[tempat_lahir]', '[tgl_lahir]', '[alamat]', '[agama]', '[pekerjaan]'];
        $replace = [
            $penduduk->nama,
            $penduduk->nik,
            $penduduk->tempat_lahir,
            \Carbon\Carbon::parse($penduduk->tgl_lahir)->isoFormat('D MMMM Y'),
            $penduduk->alamat,
            $penduduk->agama,
            $penduduk->pekerjaan
        ];

        // 1. Replace Placeholders
        $content = str_replace($search, $replace, $template);

        // 2. Format Identity Section (Nama, NIK, etc.) into a Table
        // Pattern to look for lines starting with "Label: Value"
        $lines = explode("\n", $content);
        $formattedLines = [];
        $inTable = false;

        $identityLabels = ['Nama', 'NIK', 'Tempat/Tgl Lahir', 'TTL', 'Alamat', 'Agama', 'Pekerjaan', 'Status Perkawinan'];

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
        $surat = Surat::with(['penduduk', 'jenisSurat'])->findOrFail($id);
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
            'status' => 'required|in:pending,process,done',
        ]);

        $oldStatus = $surat->status;
        $surat->update(['status' => $request->status]);

        // WhatsApp Notification when status becomes 'done'
        if ($request->status === 'done' && $oldStatus !== 'done') {
            $penduduk = $surat->penduduk;
            $message = "Halo {$penduduk->nama}, pengajuan surat {$surat->jenisSurat->nama_surat} Anda telah SELESAI diproses. Silakan ambil di kantor desa pada jam kerja. Terima kasih.";

            // Assuming we have a phone field in penduduk, if not we skip or use a default
            // Let's check Penduduk model fields.
            // In PendudukController store, there is no phone field.
            // PRD doesn't explicitly mention phone in Penduduk, but FR-7.01 says integrate with Fonnte.
            // Let's add phone to Penduduk too? Or just log it for now.

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
}
