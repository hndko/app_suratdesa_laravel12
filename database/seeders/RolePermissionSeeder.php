<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Role
        $roleSuperAdmin = \Spatie\Permission\Models\Role::updateOrCreate(['name' => 'super-admin']);
        $roleKades = \Spatie\Permission\Models\Role::updateOrCreate(['name' => 'kades']);
        $roleOperator = \Spatie\Permission\Models\Role::updateOrCreate(['name' => 'operator']);

        // 2. Buat Permission (Contoh dasar)
        $permissions = [
            'manage-users',
            'manage-penduduk',
            'manage-surat',
            'manage-pengaduan',
            'manage-pengumuman',
            'view-dashboard',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::updateOrCreate(['name' => $permission]);
        }

        // 3. Assign Permission ke Role
        $roleSuperAdmin->syncPermissions($permissions);
        $roleKades->syncPermissions(['view-dashboard', 'manage-surat', 'manage-pengaduan', 'manage-pengumuman']);
        $roleOperator->syncPermissions(['view-dashboard', 'manage-penduduk', 'manage-surat', 'manage-pengaduan', 'manage-pengumuman']);

        // 4. Buat User Default
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@mail.com',
                'password' => 'password',
                'role' => 'super-admin',
            ],
            [
                'name' => 'Kepala Desa',
                'email' => 'kades@mail.com',
                'password' => 'password',
                'role' => 'kades',
            ],
            [
                'name' => 'Operator Desa',
                'email' => 'operator@mail.com',
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
