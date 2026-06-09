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
            'seo_title' => 'nullable|string|max:150',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|string|max:300',
            'seo_author' => 'nullable|string|max:100',
            'seo_robots' => 'nullable|string|max:100',
            'seo_og_title' => 'nullable|string|max:150',
            'seo_og_description' => 'nullable|string|max:300',
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
            'site_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'site_favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg,webp|max:1024',
            'village_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seo_og_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        unset($validated['site_logo']);
        unset($validated['site_favicon']);
        unset($validated['village_logo']);
        unset($validated['seo_og_image']);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], [
                'value' => $value,
                'group' => $this->settingGroup($key),
            ]);
        }

        $this->storeSettingFile($request, 'site_logo', 'branding');
        $this->storeSettingFile($request, 'site_favicon', 'branding');
        $this->storeSettingFile($request, 'village_logo', 'village');
        $this->storeSettingFile($request, 'seo_og_image', 'seo');

        // Clear cache
        \App\Facades\Setting::clearCache();
        
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    private function storeSettingFile(Request $request, string $key, string $group): void
    {
        if (!$request->hasFile($key)) {
            return;
        }

        $oldFile = Setting::where('key', $key)->value('value');
        if ($oldFile && str_starts_with($oldFile, 'storage/settings/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $oldFile));
        }

        $path = $request->file($key)->store('settings', 'public');

        Setting::updateOrCreate(['key' => $key], [
            'value' => 'storage/' . $path,
            'group' => $group,
        ]);
    }

    private function settingGroup(string $key): string
    {
        if (str_starts_with($key, 'village_')) {
            return 'village';
        }

        if (str_starts_with($key, 'seo_')) {
            return 'seo';
        }

        return 'general';
    }
}
