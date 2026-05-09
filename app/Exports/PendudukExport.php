<?php

namespace App\Exports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendudukExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Penduduk::all();
    }

    public function headings(): array
    {
        return [
            'NIK',
            'Nama Lengkap',
            'No. Telepon',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Alamat',
            'RT',
            'RW',
            'Agama',
            'Status Perkawinan',
            'Pekerjaan',
        ];
    }

    public function map($penduduk): array
    {
        return [
            $penduduk->nik,
            $penduduk->nama,
            $penduduk->phone,
            $penduduk->tempat_lahir,
            $penduduk->tgl_lahir,
            $penduduk->jenis_kelamin,
            $penduduk->alamat,
            $penduduk->rt,
            $penduduk->rw,
            $penduduk->agama,
            $penduduk->status_perkawinan,
            $penduduk->pekerjaan,
        ];
    }
}
