<?php

namespace App\Exports;

use App\Models\Surat;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuratExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected ?string $startDate;
    protected ?string $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        $query = Surat::query()->with(['penduduk', 'jenisSurat', 'user']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_surat', [$this->startDate, $this->endDate]);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No. Surat',
            'Kode Tracking',
            'Nama Penduduk',
            'NIK',
            'Jenis Surat',
            'Tanggal Surat',
            'Keperluan',
            'Admin Pembuat',
            'Status',
        ];
    }

    public function map(mixed $surat): array
    {
        return [
            $surat->no_surat,
            $surat->tracking_code,
            $surat->penduduk->nama ?? '-',
            $surat->penduduk->nik ?? '-',
            $surat->jenisSurat->nama_surat ?? '-',
            $surat->tanggal_surat->format('d/m/Y'),
            $surat->keperluan,
            $surat->user->name ?? '-',
            $surat->status,
        ];
    }
}
