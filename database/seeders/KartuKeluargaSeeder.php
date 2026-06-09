<?php

namespace Database\Seeders;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class KartuKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $jumlahKk = 20;

        for ($index = 0; $index < $jumlahKk; $index++) {
            KartuKeluarga::updateOrCreate(
                ['no_kk' => (string) (3201000000000000 + $index + 1)],
                [
                    'kepala_keluarga' => $faker->name(),
                    'alamat' => $faker->streetAddress(),
                    'rt' => str_pad((string) $faker->numberBetween(1, 12), 3, '0', STR_PAD_LEFT),
                    'rw' => str_pad((string) $faker->numberBetween(1, 6), 3, '0', STR_PAD_LEFT),
                    'desa' => 'Desa SIMADES',
                    'kecamatan' => 'Kecamatan SIMADES',
                    'kabupaten' => 'Kabupaten SIMADES',
                    'provinsi' => 'Provinsi SIMADES',
                    'kode_pos' => (string) $faker->numberBetween(40100, 40999),
                ]
            );
        }

        $kartuKeluargas = KartuKeluarga::orderBy('id')->get();
        $penduduks = Penduduk::orderBy('id')->get();

        foreach ($penduduks->chunk(4) as $index => $anggota) {
            $kk = $kartuKeluargas->get($index % max($kartuKeluargas->count(), 1));
            $kepala = $anggota->first();

            if (! $kk || ! $kepala) {
                continue;
            }

            $kk->update([
                'kepala_keluarga' => $kepala->nama,
                'alamat' => $kepala->alamat,
                'rt' => str_pad((string) $kepala->rt, 3, '0', STR_PAD_LEFT),
                'rw' => str_pad((string) $kepala->rw, 3, '0', STR_PAD_LEFT),
            ]);

            foreach ($anggota as $memberIndex => $penduduk) {
                $penduduk->update([
                    'kartu_keluarga_id' => $kk->id,
                    'pendidikan' => $penduduk->pendidikan ?: 'SLTA/Sederajat',
                    'golongan_darah' => $penduduk->golongan_darah ?: null,
                    'shdk' => $penduduk->shdk ?: ($memberIndex === 0 ? 'Kepala Keluarga' : 'Anak'),
                ]);
            }
        }
    }
}
