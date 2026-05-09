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
        $data = $request->except('_token', '_method', 'village_logo');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        if ($request->hasFile('village_logo')) {
            $file = $request->file('village_logo');
            $path = $file->storeAs('assets/img', 'logo_desa_dynamic.png', 'public_dir'); // custom disk for public/assets/img
            
            // Or just move it manually to the directory we use
            $file->move(public_path('assets/img'), 'logo.png');
            
            Setting::updateOrCreate(['key' => 'village_logo'], ['value' => 'assets/img/logo.png']);
        }

        // Clear cache
        \App\Facades\Setting::clearCache();
        
        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
