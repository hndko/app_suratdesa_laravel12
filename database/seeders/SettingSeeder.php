<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $villageConfig = config('village');

        foreach ($villageConfig as $key => $value) {
            Setting::updateOrCreate(
                ['key' => 'village_' . $key],
                ['value' => $value, 'group' => 'village']
            );
        }

        // Add some website specific settings
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => 'SIMADES', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'site_description'], ['value' => 'Sistem Informasi Desa Terintegrasi', 'group' => 'general']);
        Setting::updateOrCreate(['key' => 'contact_whatsapp'], ['value' => '08123456789', 'group' => 'general']);
    }
}
