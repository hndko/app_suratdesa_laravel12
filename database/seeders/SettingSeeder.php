<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $villageConfig = config('village');

        $keyMap = [
            'nama_desa' => 'village_nama',
            'kecamatan' => 'village_kecamatan',
            'kabupaten' => 'village_kabupaten',
            'alamat' => 'village_alamat',
            'email' => 'village_email',
            'website' => 'village_website',
            'telepon' => 'village_telepon',
            'logo' => 'village_logo',
            'nama_kades' => 'village_nama_kades',
            'nip_kades' => 'village_nip_kades',
        ];

        foreach ($villageConfig as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $keyMap[$key] ?? 'village_' . $key],
                ['value' => $value, 'group' => 'village']
            );
        }

        // Add some website specific settings
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => 'SIMADES', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'site_description'], ['value' => 'Sistem Informasi Desa Terintegrasi', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'contact_whatsapp'], ['value' => '08123456789', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'public_brand_tagline'], ['value' => 'Portal Layanan Desa', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_footer_description'], ['value' => 'Portal layanan mandiri untuk surat, pengaduan, verifikasi dokumen, dan informasi publik desa.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_footer_cta_title'], ['value' => 'Layanan Mandiri', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_footer_cta_text'], ['value' => 'Ajukan surat, cek status layanan, atau kirim pengaduan langsung dari portal publik.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_footer_cta_button'], ['value' => 'Mulai Pengajuan', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_home_hero_eyebrow'], ['value' => 'Portal Resmi Desa', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_home_hero_title'], ['value' => 'Pelayanan desa digital yang mudah dipantau dari rumah.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_home_hero_description'], ['value' => 'Ajukan surat, kirim pengaduan, lacak proses layanan, dan cek keaslian dokumen melalui satu portal publik SIMADES.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_home_service_title'], ['value' => 'Pilih layanan sesuai kebutuhan warga', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_home_flow_title'], ['value' => 'Proses dibuat jelas dari awal sampai selesai', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_home_flow_description'], ['value' => 'Setiap layanan publik memberi kode pelacakan agar warga bisa memantau proses dan menerima informasi status layanan dengan lebih transparan.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_surat_create_hero_title'], ['value' => 'Ajukan surat desa tanpa antre berulang.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_surat_create_hero_description'], ['value' => 'Isi NIK, pilih jenis surat, tulis keperluan, lalu simpan kode tracking yang diberikan sistem untuk memantau proses pengajuan.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_surat_track_hero_title'], ['value' => 'Lacak status pengajuan surat secara mandiri.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_surat_track_hero_description'], ['value' => 'Masukkan kode tracking dan NIK pemohon untuk melihat posisi pengajuan, jenis surat, nomor surat, dan catatan approval bila tersedia.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_pengaduan_create_hero_title'], ['value' => 'Sampaikan laporan warga dengan jelas dan mudah ditindaklanjuti.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_pengaduan_create_hero_description'], ['value' => 'Gunakan formulir ini untuk mengirim keluhan, aspirasi, atau laporan kejadian. Setelah terkirim, sistem akan membuat kode tiket yang bisa dipakai untuk melacak status aduan.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_pengaduan_track_hero_title'], ['value' => 'Pantau progres tindak lanjut pengaduan Anda.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_pengaduan_track_hero_description'], ['value' => 'Masukkan kode tiket dan NIK pelapor untuk melihat status antrian, kategori laporan, isi aduan, dan tanggapan petugas bila sudah tersedia.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_verifikasi_hero_title'], ['value' => 'Pastikan surat desa benar-benar diterbitkan oleh SIMADES.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_verifikasi_hero_description'], ['value' => 'Masukkan kode verifikasi dari QR atau PDF surat untuk melihat status validitas dokumen tanpa membuka data pribadi warga secara lengkap.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_pengumuman_hero_title'], ['value' => 'Informasi resmi desa dalam satu halaman yang mudah dipantau.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'public_pengumuman_hero_description'], ['value' => 'Lihat pengumuman terbaru, agenda layanan, informasi kegiatan, dan kabar penting yang dipublikasikan oleh petugas SIMADES.', 'group' => 'public']);
        Setting::updateOrCreate(['key' => 'site_logo'], ['value' => 'assets/img/favicon.png', 'group' => 'branding']);
        Setting::updateOrCreate(['key' => 'site_favicon'], ['value' => 'assets/img/favicon.png', 'group' => 'branding']);
        Setting::updateOrCreate(['key' => 'seo_title'], ['value' => 'SIMADES - Sistem Informasi Manajemen Desa', 'group' => 'seo']);
        Setting::updateOrCreate(['key' => 'seo_description'], ['value' => 'Portal pelayanan digital desa untuk pengajuan surat, pengaduan warga, dan informasi desa.', 'group' => 'seo']);
        Setting::updateOrCreate(['key' => 'seo_keywords'], ['value' => 'simades, sistem informasi desa, surat desa, pengaduan warga, pelayanan desa', 'group' => 'seo']);
        Setting::updateOrCreate(['key' => 'seo_author'], ['value' => 'SIMADES', 'group' => 'seo']);
        Setting::updateOrCreate(['key' => 'seo_robots'], ['value' => 'index, follow', 'group' => 'seo']);
        Setting::updateOrCreate(['key' => 'seo_og_title'], ['value' => 'SIMADES - Portal Pelayanan Desa', 'group' => 'seo']);
        Setting::updateOrCreate(['key' => 'seo_og_description'], ['value' => 'Akses layanan surat, pengaduan, dan informasi desa secara digital melalui SIMADES.', 'group' => 'seo']);
        Setting::updateOrCreate(['key' => 'seo_og_image'], ['value' => 'assets/img/favicon.png', 'group' => 'seo']);
    }
}
