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

        for ($i = 0; $i < 10; $i++) {
            Penduduk::create([
                'nik' => $faker->unique()->nik(), // Or numerify('16digits') if nik() not avail in all versions, usually safe to use numerify('32##############')
                // Faker id_ID might provide nik(), let's check or safe fallback
                'nik' => $faker->unique()->numerify('16##############'), // changed to numerify to be safe
                'nama' => $faker->name,
                'tempat_lahir' => $faker->city,
                'tgl_lahir' => $faker->date(),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'alamat' => $faker->address,
                'rt' => $faker->numberBetween(1, 10),
                'rw' => $faker->numberBetween(1, 5),
                'agama' => $faker->randomElement($agamas),
                'status_perkawinan' => $faker->randomElement($status),
                'pekerjaan' => $faker->randomElement($pekerjaans),
                // foto_ktp nullable
            ]);
        }
    }
}
