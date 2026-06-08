<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\KartuKeluarga;
use App\Models\Pengaduan;
use App\Models\Post;
use App\Models\Surat;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Statistik Card
        $totalPenduduk = Penduduk::count();
        $totalKartuKeluarga = KartuKeluarga::count();
        $totalSurat = Surat::count();
        $totalPengaduan = Pengaduan::count();
        $totalPost = Post::count();

        $genderStats = Penduduk::selectRaw('jenis_kelamin, COUNT(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin')
            ->toArray();
        $totalLakiLaki = $genderStats['L'] ?? 0;
        $totalPerempuan = $genderStats['P'] ?? 0;
        $rasioJenisKelamin = $totalPerempuan > 0
            ? round($totalLakiLaki / $totalPerempuan, 2) . ' : 1'
            : ($totalLakiLaki > 0 ? $totalLakiLaki . ' : 0' : '0 : 0');

        // Pengaduan Status Stats
        $pengaduanStats = Pengaduan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();

        $chartPengaduanVal = [
            $pengaduanStats['pending'] ?? 0,
            $pengaduanStats['process'] ?? 0,
            $pengaduanStats['resolved'] ?? 0,
        ];
        $chartPengaduanLbl = ['Pending', 'Diproses', 'Selesai'];

        // Chart Data: Surat per Bulan (Dynamic Year)
        $latestSurat = Surat::latest('tanggal_surat')->first();
        $year = $latestSurat ? Carbon::parse($latestSurat->tanggal_surat)->year : date('Y');

        $suratPerBulan = Surat::selectRaw('MONTH(tanggal_surat) as bulan, COUNT(*) as total')
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
        $suratPerJenis = Surat::selectRaw('jenis_surat_id, COUNT(*) as total')
            ->groupBy('jenis_surat_id')
            ->with('jenisSurat')
            ->get();

        $chartJenisLbl = $suratPerJenis->pluck('jenisSurat.nama_surat')->toArray();
        $chartJenisVal = $suratPerJenis->pluck('total')->toArray();

        $data = [
            'title' => 'Dashboard',
            'year' => $year,
            'totalPenduduk' => $totalPenduduk,
            'totalKartuKeluarga' => $totalKartuKeluarga,
            'totalLakiLaki' => $totalLakiLaki,
            'totalPerempuan' => $totalPerempuan,
            'rasioJenisKelamin' => $rasioJenisKelamin,
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
