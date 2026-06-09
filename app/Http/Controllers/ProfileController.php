<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $linkedPenduduk = $this->linkedPenduduk($user);

        $data = [
            'title' => 'Edit Profil',
            'user' => $user,
            'roleContext' => $this->roleContext($user),
            'linkedPenduduk' => $linkedPenduduk,
            'linkedKartuKeluarga' => $linkedPenduduk?->kartuKeluarga,
        ];

        return view('backend.profile.index', $data);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->hasFile('avatar')) {
            if ($user->avatar && str_starts_with($user->avatar, 'avatars/')) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->password) {
            // Password will be hashed automatically by the Model Cast
            $user->password = $request->password;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    private function linkedPenduduk(User $user): ?Penduduk
    {
        return Penduduk::query()
            ->with('kartuKeluarga')
            ->when($user->phone, function ($query) use ($user) {
                $query->where('phone', $user->phone);
            }, function ($query) use ($user) {
                $query->where('nama', $user->name);
            })
            ->first();
    }

    private function roleContext(User $user): array
    {
        $role = $user->getRoleNames()->first();

        return match ($role) {
            'super-admin' => [
                'label' => 'Super Admin',
                'icon' => 'fas fa-crown',
                'description' => 'Akses penuh sistem, konfigurasi, role permission, dan seluruh modul operasional.',
                'items' => ['Semua modul backend', 'Manajemen role/user', 'Pengaturan sistem', 'Audit log'],
            ],
            'kades' => [
                'label' => 'Kepala Desa',
                'icon' => 'fas fa-user-tie',
                'description' => 'Fokus pada persetujuan surat, monitoring layanan, laporan, dan ringkasan desa.',
                'items' => ['Approval surat', 'Dashboard pimpinan', 'Laporan & rekapitulasi', 'Verifikasi dokumen'],
            ],
            'operator' => [
                'label' => 'Operator Desa',
                'icon' => 'fas fa-user-cog',
                'description' => 'Mengelola data warga, Kartu Keluarga, surat, pengaduan, import, dan notifikasi.',
                'items' => ['Data penduduk & KK', 'Pengajuan surat', 'Pengaduan warga', 'Import/validasi data'],
            ],
            default => [
                'label' => $role ? str($role)->headline()->toString() : 'Staff Desa',
                'icon' => 'fas fa-user-shield',
                'description' => 'Data yang tampil mengikuti role dan permission yang diberikan administrator.',
                'items' => ['Akses sesuai permission', 'Profil pengguna', 'Notifikasi akun'],
            ],
        };
    }
}
