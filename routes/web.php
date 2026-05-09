<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\PublicController::class, 'home'])->name('public.home');

// NOTE: Public Services (No Login)
Route::name('public.')->group(function () {
    Route::get('/layanan-surat', [App\Http\Controllers\PublicController::class, 'suratCreate'])->name('surat.create');
    Route::post('/layanan-surat', [App\Http\Controllers\PublicController::class, 'suratStore'])->name('surat.store');

    Route::get('/kirim-pengaduan', [App\Http\Controllers\PublicController::class, 'pengaduanCreate'])->name('pengaduan.create');
    Route::post('/kirim-pengaduan', [App\Http\Controllers\PublicController::class, 'pengaduanStore'])->name('pengaduan.store');
    Route::get('/lacak-pengaduan', [App\Http\Controllers\PublicController::class, 'pengaduanTrack'])->name('pengaduan.track');
    Route::post('/lacak-pengaduan', [App\Http\Controllers\PublicController::class, 'pengaduanStatus'])->name('pengaduan.status');
});

// NOTE: Middleware Guest Group
Route::middleware('guest')->group(function () {
    // NOTE: Halaman Login
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    // NOTE: Proses Login
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
});

// NOTE: Middleware Auth Group
Route::middleware('auth')->group(function () {
    // NOTE: Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // NOTE: Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // NOTE: Master Data Penduduk Group
    Route::controller(App\Http\Controllers\PendudukController::class)->prefix('penduduk')->name('penduduk.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // NOTE: Master Data Jenis Surat Group
    Route::controller(App\Http\Controllers\JenisSuratController::class)->prefix('jenis-surat')->name('jenis-surat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{id}/template', 'template')->name('template');
        Route::put('/{id}/template', 'updateTemplate')->name('template.update');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // NOTE: Transaksi Surat Group
    Route::controller(App\Http\Controllers\SuratController::class)->prefix('surat')->name('surat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/preview', 'preview')->name('preview');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // NOTE: Informasi & Pengumuman Group
    Route::resource('post', App\Http\Controllers\PostController::class)->except(['show']);

    // NOTE: Pengaduan Warga Group
    Route::resource('pengaduan', App\Http\Controllers\PengaduanController::class)->only(['index', 'edit', 'update', 'destroy']);

    // NOTE: Edit Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // NOTE: User Management (Super Admin Only)
    Route::middleware(['role:super-admin'])->group(function () {
        Route::resource('user', App\Http\Controllers\UserController::class);
    });
});
