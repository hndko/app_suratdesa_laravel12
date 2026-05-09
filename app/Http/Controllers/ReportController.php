<?php

namespace App\Http\Controllers;

use App\Exports\PendudukExport;
use App\Exports\SuratExport;
use App\Exports\PengaduanExport;
use App\Models\Surat;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('backend.report.index', [
            'title' => 'Laporan & Rekapitulasi'
        ]);
    }

    public function pendudukExcel()
    {
        return Excel::download(new PendudukExport, 'data-penduduk-' . date('Y-m-d') . '.xlsx');
    }

    public function suratExcel(Request $request)
    {
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
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Surat::with(['penduduk', 'jenisSurat', 'user']);
        if ($startDate && $endDate) {
            $query->whereBetween('tanggal_surat', [$startDate, $endDate]);
        }
        $surats = $query->latest()->get();

        $pdf = Pdf::loadView('backend.report.pdf_surat', [
            'surats' => $surats,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'title' => 'Laporan Arsip Surat'
        ]);

        return $pdf->stream('laporan-surat.pdf');
    }
}
