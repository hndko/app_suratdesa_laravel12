<?php

namespace App\Services;

use App\Models\Surat;
use App\Models\SuratVerification;
use Illuminate\Support\Str;

class SuratVerificationService
{
    public function ensureVerification(Surat $surat): SuratVerification
    {
        if ($surat->verification) {
            return $surat->verification;
        }

        do {
            $code = 'VRF-' . strtoupper(Str::random(12));
        } while (SuratVerification::where('verification_code', $code)->exists());

        return SuratVerification::create([
            'surat_id' => $surat->id,
            'verification_code' => $code,
            'is_active' => true,
        ]);
    }
}
