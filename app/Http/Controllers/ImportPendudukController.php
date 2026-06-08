<?php

namespace App\Http\Controllers;

use App\Models\ImportBatch;
use App\Services\PendudukImportService;
use Illuminate\Http\Request;

class ImportPendudukController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Import Penduduk & KK',
            'batches' => ImportBatch::where('type', 'penduduk_kk')->latest()->paginate(10),
        ];

        return view('backend.import_penduduk.index', $data);
    }

    public function upload(Request $request, PendudukImportService $service)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:4096',
        ]);

        $batch = $service->preview($request->file('file'));

        return redirect()->route('import-penduduk.preview', $batch)->with('success', 'Preview import berhasil dibuat.');
    }

    public function preview(ImportBatch $batch)
    {
        $data = [
            'title' => 'Preview Import Penduduk & KK',
            'batch' => $batch->load('rows'),
        ];

        return view('backend.import_penduduk.preview', $data);
    }

    public function process(ImportBatch $batch, PendudukImportService $service)
    {
        if ($batch->invalid_rows > 0) {
            return redirect()->route('import-penduduk.preview', $batch)->with('error', 'Masih ada baris invalid. Perbaiki file lalu upload ulang.');
        }

        $service->process($batch);

        return redirect()->route('import-penduduk.index')->with('success', 'Import penduduk dan KK berhasil diproses.');
    }
}
