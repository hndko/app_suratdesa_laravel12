<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'operator@example.com')->first()
            ?? User::where('email', 'admin@example.com')->first()
            ?? User::first();

        if (!$user) {
            return;
        }

        $sourcePath = database_path('seeders/assets/posts');
        $targetPath = storage_path('app/public/posts/seed');

        File::ensureDirectoryExists($targetPath);

        $posts = [
            [
                'title' => 'Pemeriksaan Kesehatan Gratis untuk Warga',
                'slug' => 'pemeriksaan-kesehatan-gratis-untuk-warga',
                'image' => 'cek-kesehatan.png',
                'status' => 'published',
                'content' => '<p>Pemerintah Desa mengadakan pemeriksaan kesehatan gratis untuk warga sebagai bagian dari peningkatan layanan kesehatan masyarakat.</p><p>Layanan meliputi pengecekan tekanan darah, konsultasi kesehatan ringan, dan edukasi pola hidup sehat. Warga diharapkan datang sesuai jadwal dengan membawa identitas diri.</p>',
            ],
            [
                'title' => 'Kerja Bakti Lingkungan dan Pembersihan Saluran Air',
                'slug' => 'kerja-bakti-lingkungan-dan-pembersihan-saluran-air',
                'image' => 'gotong-royong.png',
                'status' => 'published',
                'content' => '<p>Dalam rangka menjaga kebersihan lingkungan desa, seluruh warga diundang mengikuti kerja bakti membersihkan jalan lingkungan, taman, dan saluran air.</p><p>Kegiatan ini menjadi bagian dari gotong royong rutin agar lingkungan tetap nyaman, sehat, dan siap menghadapi musim hujan.</p>',
            ],
            [
                'title' => 'Jadwal Pelayanan Administrasi Desa Minggu Ini',
                'slug' => 'jadwal-pelayanan-administrasi-desa-minggu-ini',
                'image' => 'pelayanan-administrasi.png',
                'status' => 'draft',
                'content' => '<p>Pelayanan administrasi desa minggu ini dibuka pada hari kerja sesuai jam operasional kantor desa.</p><p>Warga yang membutuhkan surat keterangan, pembaruan data keluarga, atau layanan administrasi lainnya dapat menyiapkan dokumen pendukung agar proses pelayanan berjalan lebih cepat.</p>',
            ],
        ];

        foreach ($posts as $postData) {
            $sourceImage = $sourcePath . DIRECTORY_SEPARATOR . $postData['image'];
            $targetImage = $targetPath . DIRECTORY_SEPARATOR . $postData['image'];

            if (File::exists($sourceImage)) {
                File::copy($sourceImage, $targetImage);
            }

            Post::updateOrCreate(
                ['slug' => $postData['slug']],
                [
                    'title' => $postData['title'],
                    'content' => $postData['content'],
                    'image' => 'posts/seed/' . $postData['image'],
                    'status' => $postData['status'],
                    'user_id' => $user->id,
                ]
            );
        }
    }
}
