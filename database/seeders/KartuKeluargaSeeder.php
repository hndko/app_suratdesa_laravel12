<?php

namespace Database\Seeders;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Database\Seeder;

class KartuKeluargaSeeder extends Seeder
{
    public function run(): void
    {
        $penduduks = Penduduk::orderBy('id')->get();

        foreach ($penduduks->chunk(4) as $index => $anggota) {
            $kepala = $anggota->first();

            if (! $kepala) {
                continue;
            }

            $kk = KartuKeluarga::updateOrCreate(
                ['no_kk' => str_pad((string) (3201000000000000 + $index + 1), 16, '0', STR_PAD_LEFT)],
                [
                    'kepala_keluarga' => $kepala->nama,
                    'alamat' => $kepala->alamat,
                    'rt' => $kepala->rt,
                    'rw' => $kepala->rw,
                    'desa' => 'Desa SIMADES',
                    'kecamatan' => 'Kecamatan SIMADES',
                    'kabupaten' => 'Kabupaten SIMADES',
                    'provinsi' => 'Provinsi SIMADES',
                    'kode_pos' => '00000',
                ]
            );

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
