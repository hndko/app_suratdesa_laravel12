<?php

namespace App\Services;

use App\Models\JenisSurat;
use App\Models\SuratAiSuggestion;
use App\Services\AI\AiGatewayService;

class SuratAiService
{
    public function __construct(private AiGatewayService $aiGateway)
    {
    }

    public function suggestTemplate(JenisSurat $jenisSurat): SuratAiSuggestion
    {
        $messages = [
            [
                'role' => 'system',
                'content' => 'Anda adalah editor surat resmi desa. Perbaiki redaksi agar formal, jelas, dan tetap mempertahankan placeholder dalam tanda kurung siku. Balas JSON valid dengan keys: suggested_text, placeholder_report.',
            ],
            [
                'role' => 'user',
                'content' => "Jenis surat: {$jenisSurat->nama_surat}\nTemplate:\n{$jenisSurat->template_isi}",
            ],
        ];

        $result = $this->aiGateway->chat($messages, 'surat-template-suggestion', [
            'response_format' => ['type' => 'json_object'],
        ]);

        $parsed = $this->parseJson($result['content']);

        return SuratAiSuggestion::create([
            'jenis_surat_id' => $jenisSurat->id,
            'ai_provider_id' => $result['provider']->id ?? null,
            'user_id' => auth()->id(),
            'suggestion_type' => 'template',
            'original_text' => $jenisSurat->template_isi,
            'suggested_text' => $parsed['suggested_text'] ?? $result['content'],
            'placeholder_report' => $parsed['placeholder_report'] ?? $this->placeholderReport($jenisSurat->template_isi),
            'raw_response' => $result['raw'] ?? [],
        ]);
    }

    public function placeholderReport(string $template): array
    {
        preg_match_all('/\[[a-zA-Z0-9_]+\]/', $template, $matches);
        $found = array_values(array_unique($matches[0] ?? []));
        $recommended = ['[nama]', '[nik]', '[alamat]', '[keperluan]'];

        return [
            'found' => $found,
            'missing_recommended' => array_values(array_diff($recommended, $found)),
        ];
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
