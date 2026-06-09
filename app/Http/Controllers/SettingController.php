<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key');

        $data = [
            'title' => 'Pengaturan Website',
            'settings' => $settings,
        ];

        return view('backend.setting.index', $data);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:100',
            'site_description' => 'nullable|string|max:500',
            'contact_whatsapp' => 'nullable|string|max:20',
            'village_nama' => 'nullable|string|max:150',
            'village_kecamatan' => 'nullable|string|max:150',
            'village_kabupaten' => 'nullable|string|max:150',
            'village_provinsi' => 'nullable|string|max:150',
            'village_alamat' => 'nullable|string|max:500',
            'village_email' => 'nullable|email|max:150',
            'village_telepon' => 'nullable|string|max:50',
            'village_website' => 'nullable|string|max:150',
            'village_nama_kades' => 'nullable|string|max:150',
            'village_nip_kades' => 'nullable|string|max:50',
            'village_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        unset($validated['village_logo']);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value,
                'group' => str_starts_with($key, 'village_') ? 'village' : 'general',
            ]);
        }

        if ($request->hasFile('village_logo')) {
            $oldLogo = Setting::where('key', 'village_logo')->value('value');
            if ($oldLogo && str_starts_with($oldLogo, 'storage/settings/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldLogo));
            }

            $file = $request->file('village_logo');
            $path = $file->store('settings', 'public');

            Setting::updateOrCreate(['key' => 'village_logo'], [
                'value' => 'storage/' . $path,
                'group' => 'village',
            ]);
        }

        // Clear cache
        \App\Facades\Setting::clearCache();
        
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
