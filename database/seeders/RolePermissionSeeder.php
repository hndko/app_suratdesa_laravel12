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

            'user-index',
            'user-create',
            'user-edit',
            'user-destroy',
            'role-index',
            'role-create',
            'role-edit',
            'role-destroy',
            'role-show',

            'penduduk-index',
            'penduduk-create',
            'penduduk-edit',
            'penduduk-destroy',

            'jenis-surat-index',
            'jenis-surat-create',
            'jenis-surat-edit',
            'jenis-surat-destroy',
            'jenis-surat-template',

            'surat-index',
            'surat-create',
            'surat-edit',
            'surat-destroy',
            'surat-show',
            'surat-print',

            'post-index',
            'post-create',
            'post-edit',
            'post-destroy',

            'pengaduan-index',
            'pengaduan-edit',
            'pengaduan-destroy',

            'profile-edit'
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
            'surat-index',
            'surat-show',
            'surat-print',
            'pengaduan-index',
            'pengaduan-edit',
            'post-index',
            'profile-edit'
        ]);

        // Operator: Input data teknis
        $roleOperator->syncPermissions([
            'dashboard-index',
            'penduduk-index',
            'penduduk-create',
            'penduduk-edit',
            'surat-index',
            'surat-create',
            'surat-edit',
            'surat-show',
            'surat-print',
            'jenis-surat-index',
            'post-index',
            'post-create',
            'post-edit',
            'pengaduan-index',
            'profile-edit'
        ]);

        // 4. Buat User Default
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => 'password',
                'role' => 'super-admin',
            ],
            [
                'name' => 'Kepala Desa',
                'email' => 'kades@example.com',
                'password' => 'password',
                'role' => 'kades',
            ],
            [
                'name' => 'Operator Desa',
                'email' => 'operator@example.com',
                'password' => 'password',
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
