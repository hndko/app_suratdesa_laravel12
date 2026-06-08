<?php

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('simades:sync-permissions {--no-cache-reset : Jangan reset cache permission setelah sync}', function () {
    $this->info('Menyinkronkan permission SIMADES dari RolePermissionSeeder...');

    $this->call('db:seed', [
        '--class' => RolePermissionSeeder::class,
        '--force' => true,
    ]);

    if (! $this->option('no-cache-reset')) {
        $this->call('permission:cache-reset');
    }

    $this->info('Permission SIMADES berhasil disinkronkan.');

    return 0;
})->purpose('Sinkronkan role dan permission default SIMADES secara aman');
