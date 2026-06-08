<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan cache permission Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Role
        $roleSuperAdmin = \Spatie\Permission\Models\Role::updateOrCreate(['name' => 'super-admin']);
        $roleKades = \Spatie\Permission\Models\Role::updateOrCreate(['name' => 'kades']);
        $roleOperator = \Spatie\Permission\Models\Role::updateOrCreate(['name' => 'operator']);

        // 2. Buat Permission Granular
        $permissions = [
            'dashboard-index',

            'kartu-keluarga-index',
            'kartu-keluarga-show',
            'kartu-keluarga-create',
            'kartu-keluarga-store',
            'kartu-keluarga-edit',
            'kartu-keluarga-update',
            'kartu-keluarga-destroy',

            'user-index',
            'user-create',
            'user-store',
            'user-edit',
            'user-update',
            'user-destroy',

            'role-index',
            'role-create',
            'role-store',
            'role-edit',
            'role-update',
            'role-destroy',
            'role-show',

            'penduduk-index',
            'penduduk-create',
            'penduduk-store',
            'penduduk-edit',
            'penduduk-update',
            'penduduk-destroy',

            'jenis-surat-index',
            'jenis-surat-create',
            'jenis-surat-store',
            'jenis-surat-edit',
            'jenis-surat-update',
            'jenis-surat-destroy',
            'jenis-surat-template',
            'jenis-surat-template-update',

            'surat-index',
            'surat-create',
            'surat-preview',
            'surat-store',
            'surat-edit',
            'surat-update-status',
            'surat-destroy',
            'surat-show',
            'surat-print',

            'post-index',
            'post-create',
            'post-store',
            'post-edit',
            'post-update',
            'post-destroy',

            'pengaduan-index',
            'pengaduan-edit',
            'pengaduan-update',
            'pengaduan-destroy',

            'setting-index',
            'setting-update',

            'whatsapp-test',
            'whatsapp-test-index',
            'whatsapp-test-send',

            'report-index',
            'report-export',
            'report-penduduk-excel',
            'report-surat-excel',
            'report-surat-pdf',
            'report-pengaduan-excel',

            'profile-index',
            'profile-update',
            'profile-edit',

            'activity-log-index',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::updateOrCreate(['name' => $permission]);
        }

        // 3. Assign Permission ke Role
        // Super Admin sudah memiliki Gate::before (bypass), tapi kita tetap sync semua untuk kelengkapan data
        $roleSuperAdmin->syncPermissions($permissions);

        // Kades: Monitoring dan Approval
        $roleKades->syncPermissions([
            'dashboard-index',
            'kartu-keluarga-index',
            'kartu-keluarga-show',
            'surat-index',
            'surat-show',
            'surat-print',
            'pengaduan-index',
            'pengaduan-edit',
            'pengaduan-update',
            'post-index',
            'report-index',
            'report-export',
            'report-penduduk-excel',
            'report-surat-excel',
            'report-surat-pdf',
            'report-pengaduan-excel',
            'profile-index',
            'profile-update',
            'profile-edit',
            'activity-log-index',
        ]);

        // Operator: Input data teknis
        $roleOperator->syncPermissions([
            'dashboard-index',
            'kartu-keluarga-index',
            'kartu-keluarga-show',
            'kartu-keluarga-create',
            'kartu-keluarga-store',
            'kartu-keluarga-edit',
            'kartu-keluarga-update',
            'penduduk-index',
            'penduduk-create',
            'penduduk-store',
            'penduduk-edit',
            'penduduk-update',
            'surat-index',
            'surat-create',
            'surat-preview',
            'surat-store',
            'surat-edit',
            'surat-update-status',
            'surat-show',
            'surat-print',
            'jenis-surat-index',
            'post-index',
            'post-create',
            'post-store',
            'post-edit',
            'post-update',
            'pengaduan-index',
            'setting-index',
            'setting-update',
            'report-index',
            'report-export',
            'report-penduduk-excel',
            'report-surat-excel',
            'report-surat-pdf',
            'report-pengaduan-excel',
            'profile-index',
            'profile-update',
            'profile-edit'
        ]);

        // 4. Buat User Default
        $defaultPassword = env('DEFAULT_ADMIN_PASSWORD');

        if (app()->environment('production') && empty($defaultPassword)) {
            throw new \RuntimeException('DEFAULT_ADMIN_PASSWORD wajib diisi saat seeding di production.');
        }

        $defaultPassword = $defaultPassword ?: 'password';

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => $defaultPassword,
                'role' => 'super-admin',
            ],
            [
                'name' => 'Kepala Desa',
                'email' => 'kades@example.com',
                'password' => $defaultPassword,
                'role' => 'kades',
            ],
            [
                'name' => 'Operator Desa',
                'email' => 'operator@example.com',
                'password' => $defaultPassword,
                'role' => 'operator',
            ],
        ];

        foreach ($users as $userData) {
            $roleName = $userData['role'];
            unset($userData['role']);

            $user = \App\Models\User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->assignRole($roleName);
        }
    }
}
