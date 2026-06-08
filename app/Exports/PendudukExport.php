<?php

namespace App\Exports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendudukExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Penduduk::query()->with('kartuKeluarga')->orderBy('nama');
    }

    public function headings(): array
    {
        return [
            'NIK',
            'No. KK',
            'Nama Lengkap',
            'No. Telepon',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Alamat',
            'RT',
            'RW',
            'Agama',
            'Pendidikan',
            'Golongan Darah',
            'SHDK',
            'Status Perkawinan',
            'Pekerjaan',
        ];
    }

    public function map($penduduk): array
    {
        return [
            $penduduk->nik,
            $penduduk->kartuKeluarga->no_kk ?? '-',
            $penduduk->nama,
            $penduduk->phone,
            $penduduk->tempat_lahir,
            $penduduk->tgl_lahir,
            $penduduk->jenis_kelamin,
            $penduduk->alamat,
            $penduduk->rt,
            $penduduk->rw,
            $penduduk->agama,
            $penduduk->pendidikan,
            $penduduk->golongan_darah,
            $penduduk->shdk,
            $penduduk->status_perkawinan,
            $penduduk->pekerjaan,
        ];
    }
}
