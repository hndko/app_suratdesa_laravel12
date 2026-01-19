<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $penduduks = Penduduk::pluck('id')->toArray();
        $jenisSurats = JenisSurat::all();
        $userId = User::first()->id ?? 1; // Default admin

        if (empty($penduduks) || $jenisSurats->isEmpty()) {
            return; // Cannot seed text without parents
        }

        // Generate 30 Dummy Surat for Demo (Full Year Coverage)
        for ($i = 0; $i < 30; $i++) {
            $pendudukId = $faker->randomElement($penduduks);
            $jenisSurat = $jenisSurats->random();

            // Random date within current year (Jan - Dec)
            $tanggal = $faker->dateTimeBetween('first day of January ' . date('Y'), 'last day of December ' . date('Y'));

            // Format No Surat sederhana
            $bulan = $tanggal->format('m');
            $tahun = $tanggal->format('Y');
            $no_surat = sprintf("%s/%03d/%s/%s", $jenisSurat->kode_surat, $i + 1, $bulan, $tahun);

            Surat::create([
                'no_surat' => $no_surat,
                'penduduk_id' => $pendudukId,
                'jenis_surat_id' => $jenisSurat->id,
                'user_id' => $userId,
                'tanggal_surat' => $tanggal,
                'keperluan' => $faker->sentence(6),
                'keterangan' => $faker->optional()->sentence,
            ]);
        }
    }
}
