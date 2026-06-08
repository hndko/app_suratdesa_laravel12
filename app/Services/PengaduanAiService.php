<?php

namespace App\Services;

use App\Models\Pengaduan;
use App\Models\PengaduanAiSuggestion;
use App\Services\AI\AiGatewayService;

class PengaduanAiService
{
    public function __construct(private AiGatewayService $aiGateway)
    {
    }

    public function analyze(Pengaduan $pengaduan): PengaduanAiSuggestion
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'Anda adalah asisten administrasi desa. Balas hanya JSON valid dengan keys: summary, recommended_category, priority, draft_reply. Kategori hanya: infrastruktur, keamanan, pelayanan, sosial, lainnya. Priority hanya: rendah, sedang, tinggi.',
            ],
            [
                'role' => 'user',
                'content' => "Analisis pengaduan warga berikut tanpa membuka data sensitif tambahan.\nNama: {$pengaduan->name}\nKategori awal: {$pengaduan->category}\nIsi: {$pengaduan->content}",
            ],
        ];

        $result = $this->aiGateway->chat($messages, 'pengaduan-analysis', [
            'response_format' => ['type' => 'json_object'],
        ]);

        $parsed = $this->parseJson($result['content']);

        return PengaduanAiSuggestion::create([
            'pengaduan_id' => $pengaduan->id,
            'ai_provider_id' => $result['provider']->id ?? null,
            'user_id' => auth()->id(),
            'summary' => $parsed['summary'] ?? $result['content'],
            'recommended_category' => $parsed['recommended_category'] ?? null,
            'priority' => $parsed['priority'] ?? null,
            'draft_reply' => $parsed['draft_reply'] ?? null,
            'raw_response' => $result['raw'] ?? [],
        ]);
    }

    private function parseJson(string $content): array
    {
        $decoded = json_decode($content, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $decoded = json_decode($matches[0], true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }
}
