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
    Route::controller(App\Http\Controllers\PendudukController::class)->prefix('penduduk')->name('penduduk.')->middleware('permission:penduduk-index')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create')->middleware('permission:penduduk-create');
        Route::post('/', 'store')->name('store')->middleware('permission:penduduk-create');
        Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:penduduk-edit');
        Route::put('/{id}', 'update')->name('update')->middleware('permission:penduduk-edit');
        Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:penduduk-destroy');
    });

    // NOTE: Master Data Jenis Surat Group
    Route::controller(App\Http\Controllers\JenisSuratController::class)->prefix('jenis-surat')->name('jenis-surat.')->middleware('permission:jenis-surat-index')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create')->middleware('permission:jenis-surat-create');
        Route::post('/', 'store')->name('store')->middleware('permission:jenis-surat-create');
        Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:jenis-surat-edit');
        Route::get('/{id}/template', 'template')->name('template')->middleware('permission:jenis-surat-template');
        Route::put('/{id}/template', 'updateTemplate')->name('template.update')->middleware('permission:jenis-surat-template');
        Route::put('/{id}', 'update')->name('update')->middleware('permission:jenis-surat-edit');
        Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:jenis-surat-destroy');
    });

    // NOTE: Transaksi Surat Group
    Route::controller(App\Http\Controllers\SuratController::class)->prefix('surat')->name('surat.')->middleware('permission:surat-index')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create')->middleware('permission:surat-create');
        Route::post('/preview', 'preview')->name('preview')->middleware('permission:surat-create');
        Route::post('/', 'store')->name('store')->middleware('permission:surat-create');
        Route::get('/{id}', 'show')->name('show')->middleware('permission:surat-show');
        Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:surat-edit');
        Route::put('/{id}', 'update')->name('update')->middleware('permission:surat-edit');
        Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:surat-destroy');
    });

    // NOTE: Informasi & Pengumuman Group
    Route::resource('post', App\Http\Controllers\PostController::class)->except(['show']);

    // NOTE: Pengaduan Warga Group
    Route::resource('pengaduan', App\Http\Controllers\PengaduanController::class)->only(['index', 'edit', 'update', 'destroy']);

    // NOTE: Edit Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // NOTE: User Management (Super Admin Only)
    Route::middleware(['permission:user-index'])->group(function () {
        Route::resource('user', App\Http\Controllers\UserController::class);
        Route::resource('role', App\Http\Controllers\RoleController::class);
        Route::get('/setting', [App\Http\Controllers\SettingController::class, 'index'])->name('setting.index');
        Route::put('/setting', [App\Http\Controllers\SettingController::class, 'update'])->name('setting.update');

        // Report Management
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
            Route::get('/penduduk/excel', [App\Http\Controllers\ReportController::class, 'pendudukExcel'])->name('penduduk.excel');
            Route::get('/surat/excel', [App\Http\Controllers\ReportController::class, 'suratExcel'])->name('surat.excel');
            Route::get('/surat/pdf', [App\Http\Controllers\ReportController::class, 'suratPdf'])->name('surat.pdf');
            Route::get('/pengaduan/excel', [App\Http\Controllers\ReportController::class, 'pengaduanExcel'])->name('pengaduan.excel');
        });

        // WhatsApp Test
        Route::middleware('permission:whatsapp-test')->group(function () {
            Route::get('/whatsapp-test', [App\Http\Controllers\WhatsAppTestController::class, 'index'])->name('whatsapp.test.index');
            Route::post('/whatsapp-test', [App\Http\Controllers\WhatsAppTestController::class, 'send'])->name('whatsapp.test.send');
        });
    });
});
