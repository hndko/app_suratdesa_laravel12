<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $villageConfig = config('village');

        $keyMap = [
            'nama_desa' => 'village_nama',
            'kecamatan' => 'village_kecamatan',
            'kabupaten' => 'village_kabupaten',
            'alamat' => 'village_alamat',
            'email' => 'village_email',
            'website' => 'village_website',
            'telepon' => 'village_telepon',
            'logo' => 'village_logo',
            'nama_kades' => 'village_nama_kades',
            'nip_kades' => 'village_nip_kades',
        ];

        foreach ($villageConfig as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $keyMap[$key] ?? 'village_' . $key],
                ['value' => $value, 'group' => 'village']
            );
        }

        // Add some website specific settings
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => 'SIMADES', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'site_description'], ['value' => 'Sistem Informasi Desa Terintegrasi', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'contact_whatsapp'], ['value' => '08123456789', 'group' => 'general']);
    }
}
