<?php

namespace Database\Seeders;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengaduanSeeder extends Seeder
{
    public function run(): void
    {
        $operator = User::where('email', 'operator@example.com')->first()
            ?? User::where('email', 'admin@example.com')->first()
            ?? User::first();

        $pengaduans = [
            [
                'ticket_code' => 'ADU-RT01-0001',
                'name' => 'Ahmad Fauzi',
                'nik' => '3201010101900001',
                'phone' => '081234567001',
                'category' => 'Infrastruktur',
                'content' => 'Lampu penerangan jalan di sekitar RT 01 sudah mati selama beberapa malam sehingga jalan menjadi gelap dan kurang aman dilalui warga.',
                'status' => 'pending',
                'reply' => null,
                'days_ago' => 1,
            ],
            [
                'ticket_code' => 'ADU-RW02-0002',
                'name' => 'Siti Aminah',
                'nik' => '3201014102850002',
                'phone' => '081234567002',
                'category' => 'Kebersihan',
                'content' => 'Saluran air di depan rumah warga mulai tersumbat sampah daun dan plastik. Saat hujan, air meluap ke jalan lingkungan.',
                'status' => 'process',
                'reply' => 'Terima kasih atas laporannya. Petugas kebersihan desa sudah dijadwalkan mengecek lokasi dan membersihkan saluran air pada kerja bakti terdekat.',
                'days_ago' => 3,
            ],
            [
                'ticket_code' => 'ADU-KTR-0003',
                'name' => 'Budi Santoso',
                'nik' => '3201011503900003',
                'phone' => '081234567003',
                'category' => 'Pelayanan',
                'content' => 'Mohon informasi jadwal pelayanan administrasi karena beberapa warga datang saat loket sudah tutup dan belum mengetahui jam layanan terbaru.',
                'status' => 'resolved',
                'reply' => 'Jadwal pelayanan administrasi telah diperbarui di papan informasi desa dan website. Pelayanan dibuka pada hari kerja pukul 08.00 sampai 14.00.',
                'days_ago' => 6,
            ],
            [
                'ticket_code' => 'ADU-KMN-0004',
                'name' => 'Nur Hidayah',
                'nik' => '3201015204960004',
                'phone' => '081234567004',
                'category' => 'Keamanan',
                'content' => 'Beberapa kendaraan melaju terlalu cepat di jalan dekat sekolah. Warga berharap ada imbauan atau rambu peringatan untuk mengurangi risiko kecelakaan.',
                'status' => 'process',
                'reply' => 'Laporan sudah diteruskan ke perangkat wilayah. Pemerintah desa akan menyiapkan imbauan keselamatan dan koordinasi pemasangan rambu sederhana.',
                'days_ago' => 8,
            ],
            [
                'ticket_code' => 'ADU-KES-0005',
                'name' => 'Rahmat Hidayat',
                'nik' => '3201011005880005',
                'phone' => '081234567005',
                'category' => 'Kesehatan',
                'content' => 'Warga lansia membutuhkan informasi jadwal posyandu dan pemeriksaan kesehatan rutin agar bisa hadir tepat waktu.',
                'status' => 'resolved',
                'reply' => 'Jadwal posyandu dan pemeriksaan kesehatan rutin sudah diumumkan melalui kader wilayah. Informasi juga akan ditampilkan pada pengumuman desa.',
                'days_ago' => 12,
            ],
        ];

        foreach ($pengaduans as $pengaduanData) {
            $createdAt = now()->subDays($pengaduanData['days_ago']);
            $reply = $pengaduanData['reply'];

            unset($pengaduanData['days_ago']);

            $pengaduan = Pengaduan::updateOrCreate(
                ['ticket_code' => $pengaduanData['ticket_code']],
                [
                    'name' => $pengaduanData['name'],
                    'nik' => $pengaduanData['nik'],
                    'phone' => $pengaduanData['phone'],
                    'category' => $pengaduanData['category'],
                    'content' => $pengaduanData['content'],
                    'image' => null,
                    'status' => $pengaduanData['status'],
                    'reply' => $reply,
                    'replied_by' => $reply && $operator ? $operator->id : null,
                    'replied_at' => $reply ? $createdAt->copy()->addHours(6) : null,
                ]
            );

            $pengaduan->forceFill([
                'created_at' => $createdAt,
                'updated_at' => $reply ? $createdAt->copy()->addHours(6) : $createdAt,
            ])->save();
        }
    }
}
