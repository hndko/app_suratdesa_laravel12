<?php

namespace App\Http\Controllers;

use App\Exports\PendudukExport;
use App\Exports\SuratExport;
use App\Exports\PengaduanExport;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\Surat;
use App\Models\KartuKeluarga;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Laporan & Rekapitulasi',
            'totalPenduduk' => Penduduk::count(),
            'totalKartuKeluarga' => KartuKeluarga::count(),
            'totalSurat' => Surat::count(),
            'totalSuratBulanIni' => Surat::whereYear('tanggal_surat', now()->year)
                ->whereMonth('tanggal_surat', now()->month)
                ->count(),
            'totalPengaduan' => Pengaduan::count(),
            'totalPengaduanPending' => Pengaduan::whereIn('status', ['pending', 'process'])->count(),
            'defaultStartDate' => now()->startOfMonth()->format('Y-m-d'),
            'defaultEndDate' => now()->format('Y-m-d'),
        ];

        return view('backend.report.index', $data);
    }

    public function pendudukExcel()
    {
        return Excel::download(new PendudukExport, 'data-penduduk-' . date('Y-m-d') . '.xlsx');
    }

    public function suratExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        return (new SuratExport($startDate, $endDate))->download('rekap-surat-' . date('Y-m-d') . '.xlsx');
    }

    public function pengaduanExcel()
    {
        return Excel::download(new PengaduanExport, 'rekap-pengaduan-' . date('Y-m-d') . '.xlsx');
    }

    public function suratPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Surat::with(['penduduk', 'jenisSurat', 'user']);
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_surat', [$startDate, $endDate]);
        }
        $surats = $query->latest()->limit(1000)->get();

        $pdf = Pdf::loadView('backend.report.pdf_surat', [
            'surats' => $surats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'title' => 'Laporan Arsip Surat'
        ]);

        return $pdf->stream('laporan-surat.pdf');
    }
}
