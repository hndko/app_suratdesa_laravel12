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

Route::get('/', function () {
    return redirect()->route('login');
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
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    // NOTE: Transaksi Surat Group
    Route::controller(App\Http\Controllers\SuratController::class)->prefix('surat')->name('surat.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
});
