<?php

namespace Database\Seeders;

use App\Models\Penduduk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        $pekerjaans = ['PNS', 'Wiraswasta', 'Petani', 'Buruh', 'Mahasiswa', 'Pelajar', 'Ibu Rumah Tangga', 'Karyawan Swasta'];
        $status = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
        $pendidikan = ['SD/Sederajat', 'SLTP/Sederajat', 'SLTA/Sederajat', 'D3', 'S1', 'S2'];
        $shdk = ['Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain'];

        for ($i = 0; $i < 10; $i++) {
            Penduduk::create([
                'nik' => $faker->unique()->numerify('16##############'),
                'nama' => $faker->name,
                'phone' => '08' . $faker->numerify('##########'),
                'tempat_lahir' => $faker->city,
                'tgl_lahir' => $faker->date(),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'alamat' => $faker->address,
                'rt' => $faker->numberBetween(1, 10),
                'rw' => $faker->numberBetween(1, 5),
                'agama' => $faker->randomElement($agamas),
                'pendidikan' => $faker->randomElement($pendidikan),
                'golongan_darah' => $faker->randomElement(['A', 'B', 'AB', 'O', null]),
                'shdk' => $faker->randomElement($shdk),
                'status_perkawinan' => $faker->randomElement($status),
                'pekerjaan' => $faker->randomElement($pekerjaans),
                // foto_ktp nullable
            ]);
        }
    }
}
