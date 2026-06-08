<?php

namespace App\Exports;

use App\Models\Pengaduan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PengaduanExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Pengaduan::query()->with('repliedBy')->latest();
    }

    public function headings(): array
    {
        return [
            'Kode Tiket',
            'Nama Pelapor',
            'NIK',
            'WhatsApp',
            'Kategori',
            'Isi Aduan',
            'Status',
            'Tanggapan',
            'Ditanggapi Oleh',
            'Tanggal Lapor',
        ];
    }

    public function map(mixed $pengaduan): array
    {
        return [
            $pengaduan->ticket_code,
            $pengaduan->name,
            $pengaduan->nik,
            $pengaduan->phone,
            $pengaduan->category,
            $pengaduan->content,
            $pengaduan->status,
            $pengaduan->reply,
            $pengaduan->repliedBy->name ?? '-',
            $pengaduan->created_at->format('d/m/Y H:i'),
        ];
    }
}
