<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Card
        $totalPenduduk = \App\Models\Penduduk::count();
        $totalSurat = \App\Models\Surat::count();
        $totalPengaduan = \App\Models\Pengaduan::count();
        $totalPost = \App\Models\Post::count();

        // Pengaduan Status Stats
        $pengaduanStats = \App\Models\Pengaduan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();

        $chartPengaduanVal = [
            $pengaduanStats['pending'] ?? 0,
            $pengaduanStats['process'] ?? 0,
            $pengaduanStats['resolved'] ?? 0,
        ];
        $chartPengaduanLbl = ['Pending', 'Diproses', 'Selesai'];

        // Chart Data: Surat per Bulan (Dynamic Year)
        $latestSurat = \App\Models\Surat::latest('tanggal_surat')->first();
        $year = $latestSurat ? \Carbon\Carbon::parse($latestSurat->tanggal_surat)->year : date('Y');

        $suratPerBulan = \App\Models\Surat::selectRaw('MONTH(tanggal_surat) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_surat', $year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')->toArray();

        // Fill missing months with 0
        $chartSuratBulanVal = [];
        $chartSuratBulanLbl = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthName = date('M', mktime(0, 0, 0, $m, 1));
            $chartSuratBulanLbl[] = $monthName;
            $chartSuratBulanVal[] = $suratPerBulan[$m] ?? 0;
        }

        // Chart Data: Komposisi Jenis Surat
        $suratPerJenis = \App\Models\Surat::selectRaw('jenis_surat_id, COUNT(*) as total')
            ->groupBy('jenis_surat_id')
            ->with('jenisSurat')
            ->get();

        $chartJenisLbl = $suratPerJenis->pluck('jenisSurat.nama_surat')->toArray();
        $chartJenisVal = $suratPerJenis->pluck('total')->toArray();

        $data = [
            'title' => 'Dashboard',
            'year' => $year,
            'totalPenduduk' => $totalPenduduk,
            'totalSurat' => $totalSurat,
            'totalPengaduan' => $totalPengaduan,
            'totalPost' => $totalPost,
            'chartSuratBulanLbl' => $chartSuratBulanLbl,
            'chartSuratBulanVal' => $chartSuratBulanVal,
            'chartJenisLbl' => $chartJenisLbl,
            'chartJenisVal' => $chartJenisVal,
            'chartPengaduanLbl' => $chartPengaduanLbl,
            'chartPengaduanVal' => $chartPengaduanVal,
        ];
        return view('backend.dashboard', $data);
    }
}
