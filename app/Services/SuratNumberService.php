<?php

namespace App\Services;

use App\Models\JenisSurat;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class SuratNumberService
{
    public static function generate(JenisSurat $jenisSurat, string|\DateTimeInterface $tanggal): string
    {
        $date = $tanggal instanceof \DateTimeInterface ? $tanggal : new \DateTimeImmutable($tanggal);
        $tahun = (int) $date->format('Y');
        $bulan = $date->format('m');

        for ($attempt = 1; $attempt <= 3; $attempt++) {
            try {
                $number = DB::transaction(function () use ($jenisSurat, $tahun) {
                    $counter = DB::table('surat_counters')
                        ->where('jenis_surat_id', $jenisSurat->id)
                        ->where('tahun', $tahun)
                        ->lockForUpdate()
                        ->first();

                    if (! $counter) {
                        DB::table('surat_counters')->insert([
                            'jenis_surat_id' => $jenisSurat->id,
                            'tahun' => $tahun,
                            'next_number' => 2,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        return 1;
                    }

                    DB::table('surat_counters')
                        ->where('id', $counter->id)
                        ->update([
                            'next_number' => $counter->next_number + 1,
                            'updated_at' => now(),
                        ]);

                    return (int) $counter->next_number;
                });

                return sprintf('%s/%03d/%s/%s', $jenisSurat->kode_surat, $number, $bulan, $tahun);
            } catch (QueryException $e) {
                if ($attempt === 3) {
                    throw $e;
                }

                usleep(100000);
            }
        }

        throw new \RuntimeException('Gagal membuat nomor surat.');
    }
}
