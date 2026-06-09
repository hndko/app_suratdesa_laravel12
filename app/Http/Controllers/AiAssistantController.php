<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Surat;
use App\Services\AI\AiGatewayService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'AI Assistant Internal',
            'totalSurat' => Surat::count(),
            'suratPending' => Surat::where('status', 'pending')->count(),
            'suratDone' => Surat::where('status', 'done')->count(),
            'totalPengaduan' => Pengaduan::count(),
            'pengaduanPending' => Pengaduan::where('status', 'pending')->count(),
            'pengaduanResolved' => Pengaduan::where('status', 'resolved')->count(),
        ];

        return view('backend.ai_assistant.index', $data);
    }

    public function send(Request $request, AiGatewayService $aiGateway)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $context = [
            'total_surat' => Surat::count(),
            'surat_pending' => Surat::where('status', 'pending')->count(),
            'surat_done' => Surat::where('status', 'done')->count(),
            'total_pengaduan' => Pengaduan::count(),
            'pengaduan_pending' => Pengaduan::where('status', 'pending')->count(),
            'pengaduan_resolved' => Pengaduan::where('status', 'resolved')->count(),
        ];

        try {
            $result = $aiGateway->chat([
                [
                    'role' => 'system',
                    'content' => 'Anda adalah assistant internal SIMADES. Jawab singkat, praktis, dan jangan meminta/menampilkan NIK lengkap atau data sensitif. Gunakan konteks statistik yang diberikan.',
                ],
                [
                    'role' => 'user',
                    'content' => 'Konteks sistem: ' . json_encode($context) . "\n\nPertanyaan: " . $request->message,
                ],
            ], 'backend-assistant');

            return redirect()->route('ai-assistant.index')
                ->with('success', 'AI Assistant berhasil menjawab.')
                ->with('ai_answer', $result['content'])
                ->with('ai_question', $request->message);
        } catch (\Throwable $e) {
            return redirect()->route('ai-assistant.index')->with('error', 'AI Assistant gagal: ' . $e->getMessage());
        }
    }
}
