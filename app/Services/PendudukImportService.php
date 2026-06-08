<?php

namespace App\Services;

use App\Models\ImportBatch;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PendudukImportService
{
    public function preview(UploadedFile $file): ImportBatch
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $rows = collect($spreadsheet->getActiveSheet()->toArray())->filter(fn ($row) => array_filter($row))->values();
        $header = $rows->shift();
        $keys = collect($header)->map(fn ($value) => str($value)->lower()->replace(' ', '_')->toString())->all();

        $batch = ImportBatch::create([
            'user_id' => auth()->id(),
            'type' => 'penduduk_kk',
            'file_name' => $file->getClientOriginalName(),
            'status' => 'preview',
        ]);

        $validRows = 0;
        $invalidRows = 0;
        $seenNik = [];

        foreach ($rows->take(1000) as $index => $row) {
            $payload = array_combine($keys, array_pad($row, count($keys), null));
            $payload = $this->normalizePayload($payload);
            $errors = $this->validatePayload($payload);

            if (!empty($payload['nik']) && in_array($payload['nik'], $seenNik, true)) {
                $errors[] = 'nik duplikat di file import';
            }

            if (!empty($payload['nik'])) {
                $seenNik[] = $payload['nik'];
            }

            $batch->rows()->create([
                'row_number' => $index + 2,
                'payload' => $payload,
                'errors' => $errors ?: null,
                'status' => $errors ? 'invalid' : 'valid',
            ]);

            $errors ? $invalidRows++ : $validRows++;
        }

        $batch->update([
            'total_rows' => $validRows + $invalidRows,
            'valid_rows' => $validRows,
            'invalid_rows' => $invalidRows,
        ]);

        return $batch->load('rows');
    }

    public function process(ImportBatch $batch): void
    {
        DB::transaction(function () use ($batch) {
            $processed = 0;

            foreach ($batch->rows()->where('status', 'valid')->get() as $row) {
                $payload = $row->payload;

                $kk = KartuKeluarga::updateOrCreate(
                    ['no_kk' => $payload['no_kk']],
                    [
                        'kepala_keluarga' => $payload['kepala_keluarga'] ?: $payload['nama'],
                        'alamat' => $payload['alamat'],
                        'rt' => $payload['rt'],
                        'rw' => $payload['rw'],
                        'desa' => $payload['desa'] ?? null,
                        'kecamatan' => $payload['kecamatan'] ?? null,
                        'kabupaten' => $payload['kabupaten'] ?? null,
                        'provinsi' => $payload['provinsi'] ?? null,
                    ]
                );

                Penduduk::updateOrCreate(
                    ['nik' => $payload['nik']],
                    [
                        'kartu_keluarga_id' => $kk->id,
                        'nama' => $payload['nama'],
                        'phone' => $payload['phone'] ?? null,
                        'tempat_lahir' => $payload['tempat_lahir'],
                        'tgl_lahir' => $payload['tgl_lahir'],
                        'jenis_kelamin' => strtoupper($payload['jenis_kelamin']),
                        'alamat' => $payload['alamat'],
                        'rt' => $payload['rt'],
                        'rw' => $payload['rw'],
                        'agama' => $payload['agama'],
                        'pendidikan' => $payload['pendidikan'] ?? null,
                        'golongan_darah' => $payload['golongan_darah'] ?? null,
                        'shdk' => $payload['shdk'] ?? null,
                        'status_perkawinan' => $payload['status_perkawinan'],
                        'pekerjaan' => $payload['pekerjaan'],
                    ]
                );

                $row->update(['status' => 'processed']);
                $processed++;
            }

            $batch->update([
                'status' => 'processed',
                'processed_rows' => $processed,
            ]);
        });
    }

    private function normalizePayload(array $payload): array
    {
        $normalized = collect($payload)->mapWithKeys(function ($value, $key) {
            return [$key => is_string($value) ? trim($value) : $value];
        })->all();

        if (!empty($normalized['tgl_lahir']) && is_numeric($normalized['tgl_lahir'])) {
            $normalized['tgl_lahir'] = ExcelDate::excelToDateTimeObject((float) $normalized['tgl_lahir'])->format('Y-m-d');
        }

        return $normalized;
    }

    private function validatePayload(array $payload): array
    {
        $errors = [];
        $required = ['no_kk', 'nik', 'nama', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin', 'alamat', 'rt', 'rw', 'agama', 'status_perkawinan', 'pekerjaan'];

        foreach ($required as $field) {
            if (empty($payload[$field])) {
                $errors[] = $field . ' wajib diisi';
            }
        }

        if (!empty($payload['nik']) && !preg_match('/^\d{16}$/', (string) $payload['nik'])) {
            $errors[] = 'nik harus 16 digit';
        }

        if (!empty($payload['no_kk']) && !preg_match('/^\d{16}$/', (string) $payload['no_kk'])) {
            $errors[] = 'no_kk harus 16 digit';
        }

        if (!empty($payload['jenis_kelamin']) && !in_array(strtoupper($payload['jenis_kelamin']), ['L', 'P'], true)) {
            $errors[] = 'jenis_kelamin harus L atau P';
        }

        return $errors;
    }
}
