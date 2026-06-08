<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('backend.setting.index', [
            'title' => 'Pengaturan Website',
            'settings' => $settings
        ]);
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
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->hasFile('village_logo')) {
            $file = $request->file('village_logo');
            $filename = 'logo_desa.' . $file->extension();
            $file->storeAs('settings', $filename, 'public');

            Setting::updateOrCreate(['key' => 'village_logo'], ['value' => 'storage/settings/' . $filename]);
        }

        // Clear cache
        \App\Facades\Setting::clearCache();
        
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
