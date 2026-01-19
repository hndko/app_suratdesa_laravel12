<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\Surat;
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
            'surats' => Surat::with(['penduduk', 'jenisSurat'])->latest()->get(),
        ];

        return view('backend.surat.index', $data);
    }

    // NOTE: Form Buat Surat
    public function create()
    {
        $data = [
            'title' => 'Buat Surat Baru',
            'penduduks' => Penduduk::latest()->get(), // Optimize with Select2 AJAX later if many records
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
            'keperluan' => 'required|string',
        ]);

        // Generate No Surat (Simple format for now: KodeSurat/NoUrut/Bulan/Tahun)
        $jenisSurat = JenisSurat::find($request->jenis_surat_id);
        $count = Surat::whereYear('tanggal_surat', date('Y', strtotime($request->tanggal_surat)))->count() + 1;
        $bulan = date('m', strtotime($request->tanggal_surat)); // Romawi converter needed typically, use standard first
        $tahun = date('Y', strtotime($request->tanggal_surat));

        $no_surat = sprintf("%s/%03d/%s/%s", $jenisSurat->kode_surat, $count, $bulan, $tahun);

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
    public function show($id)
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

    private function processTemplate($template, $penduduk, $extraData = [])
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

        $content = str_replace($search, $replace, $template);

        // Auto-append Keperluan & Keterangan using a neat table
        if (!empty($extraData['keperluan']) || !empty($extraData['keterangan'])) {
            $content .= "<br><table style='width: 100%; border: none; font-size: 12pt;'>";

            if (!empty($extraData['keperluan'])) {
                $content .= "<tr><td style='width: 30%; vertical-align: top;'>Keperluan</td><td style='width: 2%; vertical-align: top;'>:</td><td style='vertical-align: top; text-align: justify;'>" . $extraData['keperluan'] . "</td></tr>";
            }

            if (!empty($extraData['keterangan'])) {
                $content .= "<tr><td style='width: 30%; vertical-align: top;'>Keterangan</td><td style='width: 2%; vertical-align: top;'>:</td><td style='vertical-align: top; text-align: justify;'>" . $extraData['keterangan'] . "</td></tr>";
            }

            $content .= "</table>";
        }

        return $content;
    }

    public function destroy($id)
    {
        Surat::findOrFail($id)->delete();
        return redirect()->route('surat.index')->with('success', 'Surat berhasil dihapus.');
    }
}
