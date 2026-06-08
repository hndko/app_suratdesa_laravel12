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
    Route::post('/layanan-surat', [App\Http\Controllers\PublicController::class, 'suratStore'])->name('surat.store')->middleware('throttle:10,1');
    Route::get('/lacak-surat', [App\Http\Controllers\PublicController::class, 'suratTrack'])->name('surat.track');
    Route::post('/lacak-surat', [App\Http\Controllers\PublicController::class, 'suratStatus'])->name('surat.status')->middleware('throttle:20,1');

    Route::get('/kirim-pengaduan', [App\Http\Controllers\PublicController::class, 'pengaduanCreate'])->name('pengaduan.create');
    Route::post('/kirim-pengaduan', [App\Http\Controllers\PublicController::class, 'pengaduanStore'])->name('pengaduan.store')->middleware('throttle:10,1');
    Route::get('/lacak-pengaduan', [App\Http\Controllers\PublicController::class, 'pengaduanTrack'])->name('pengaduan.track');
    Route::post('/lacak-pengaduan', [App\Http\Controllers\PublicController::class, 'pengaduanStatus'])->name('pengaduan.status')->middleware('throttle:20,1');
});

// NOTE: Middleware Guest Group
Route::middleware('guest')->group(function () {
    // NOTE: Halaman Login
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    // NOTE: Proses Login
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post')->middleware('throttle:5,1');
});

// NOTE: Middleware Auth Group
Route::middleware('auth')->group(function () {
    // NOTE: Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // NOTE: Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:dashboard-index');

    // NOTE: Master Data Penduduk Group
    Route::resource('kartu-keluarga', App\Http\Controllers\KartuKeluargaController::class)
        ->middlewareFor('index', 'permission:kartu-keluarga-index')
        ->middlewareFor('show', 'permission:kartu-keluarga-show')
        ->middlewareFor('create', 'permission:kartu-keluarga-create')
        ->middlewareFor('store', 'permission:kartu-keluarga-store')
        ->middlewareFor('edit', 'permission:kartu-keluarga-edit')
        ->middlewareFor('update', 'permission:kartu-keluarga-update')
        ->middlewareFor('destroy', 'permission:kartu-keluarga-destroy');

    Route::controller(App\Http\Controllers\PendudukController::class)->prefix('penduduk')->name('penduduk.')->middleware('permission:penduduk-index')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create')->middleware('permission:penduduk-create');
        Route::post('/', 'store')->name('store')->middleware('permission:penduduk-store');
        Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:penduduk-edit');
        Route::put('/{id}', 'update')->name('update')->middleware('permission:penduduk-update');
        Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:penduduk-destroy');
    });

    // NOTE: Master Data Jenis Surat Group
    Route::controller(App\Http\Controllers\JenisSuratController::class)->prefix('jenis-surat')->name('jenis-surat.')->middleware('permission:jenis-surat-index')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create')->middleware('permission:jenis-surat-create');
        Route::post('/', 'store')->name('store')->middleware('permission:jenis-surat-store');
        Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:jenis-surat-edit');
        Route::get('/{id}/template', 'template')->name('template')->middleware('permission:jenis-surat-template');
        Route::put('/{id}/template', 'updateTemplate')->name('template.update')->middleware('permission:jenis-surat-template-update');
        Route::put('/{id}', 'update')->name('update')->middleware('permission:jenis-surat-update');
        Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:jenis-surat-destroy');
    });

    // NOTE: Transaksi Surat Group
    Route::controller(App\Http\Controllers\SuratController::class)->prefix('surat')->name('surat.')->middleware('permission:surat-index')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create')->middleware('permission:surat-create');
        Route::post('/preview', 'preview')->name('preview')->middleware('permission:surat-preview');
        Route::post('/', 'store')->name('store')->middleware('permission:surat-store');
        Route::get('/{id}', 'show')->name('show')->middleware('permission:surat-show');
        Route::get('/{id}/edit', 'edit')->name('edit')->middleware('permission:surat-edit');
        Route::put('/{id}', 'update')->name('update')->middleware('permission:surat-update-status');
        Route::delete('/{id}', 'destroy')->name('destroy')->middleware('permission:surat-destroy');
    });

    // NOTE: Informasi & Pengumuman Group
    Route::resource('post', App\Http\Controllers\PostController::class)
        ->except(['show'])
        ->middlewareFor('index', 'permission:post-index')
        ->middlewareFor('create', 'permission:post-create')
        ->middlewareFor('store', 'permission:post-store')
        ->middlewareFor('edit', 'permission:post-edit')
        ->middlewareFor('update', 'permission:post-update')
        ->middlewareFor('destroy', 'permission:post-destroy');

    // NOTE: Pengaduan Warga Group
    Route::resource('pengaduan', App\Http\Controllers\PengaduanController::class)
        ->only(['index', 'edit', 'update', 'destroy'])
        ->middlewareFor('index', 'permission:pengaduan-index')
        ->middlewareFor('edit', 'permission:pengaduan-edit')
        ->middlewareFor('update', 'permission:pengaduan-update')
        ->middlewareFor('destroy', 'permission:pengaduan-destroy');

    // NOTE: Edit Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile')->middleware('permission:profile-index');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware('permission:profile-update');

    // NOTE: User & Role Management
    Route::resource('user', App\Http\Controllers\UserController::class)->except(['show'])
        ->middlewareFor('index', 'permission:user-index')
        ->middlewareFor('create', 'permission:user-create')
        ->middlewareFor('store', 'permission:user-store')
        ->middlewareFor('edit', 'permission:user-edit')
        ->middlewareFor('update', 'permission:user-update')
        ->middlewareFor('destroy', 'permission:user-destroy');

    Route::resource('role', App\Http\Controllers\RoleController::class)
        ->middlewareFor('index', 'permission:role-index')
        ->middlewareFor('show', 'permission:role-show')
        ->middlewareFor('create', 'permission:role-create')
        ->middlewareFor('store', 'permission:role-store')
        ->middlewareFor('edit', 'permission:role-edit')
        ->middlewareFor('update', 'permission:role-update')
        ->middlewareFor('destroy', 'permission:role-destroy');

    Route::get('/setting', [App\Http\Controllers\SettingController::class, 'index'])->name('setting.index')->middleware('permission:setting-index');
    Route::put('/setting', [App\Http\Controllers\SettingController::class, 'update'])->name('setting.update')->middleware('permission:setting-update');

    // Report Management
    Route::prefix('report')->name('report.')->middleware('permission:report-index')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/penduduk/excel', [App\Http\Controllers\ReportController::class, 'pendudukExcel'])->name('penduduk.excel')->middleware('permission:report-penduduk-excel');
        Route::get('/surat/excel', [App\Http\Controllers\ReportController::class, 'suratExcel'])->name('surat.excel')->middleware('permission:report-surat-excel');
        Route::get('/surat/pdf', [App\Http\Controllers\ReportController::class, 'suratPdf'])->name('surat.pdf')->middleware('permission:report-surat-pdf');
        Route::get('/pengaduan/excel', [App\Http\Controllers\ReportController::class, 'pengaduanExcel'])->name('pengaduan.excel')->middleware('permission:report-pengaduan-excel');
    });

    // WhatsApp Test
    Route::get('/whatsapp-test', [App\Http\Controllers\WhatsAppTestController::class, 'index'])->name('whatsapp.test.index')->middleware('permission:whatsapp-test-index');
    Route::post('/whatsapp-test', [App\Http\Controllers\WhatsAppTestController::class, 'send'])->name('whatsapp.test.send')->middleware('permission:whatsapp-test-send');

    Route::get('/activity-log', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-log.index')->middleware('permission:activity-log-index');
});
