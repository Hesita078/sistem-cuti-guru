<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// AUTH ROUTES (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard (Semua role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil (Semua role)
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // ============================================
    // GURU ROUTES
    // ============================================
    Route::middleware('role:guru')->group(function () {

        // Pengajuan Cuti
        Route::get('/pengajuan', [PengajuanCutiController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PengajuanCutiController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanCutiController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{id}', [PengajuanCutiController::class, 'show'])->name('pengajuan.show');
        Route::delete('/pengajuan/{id}', [PengajuanCutiController::class, 'destroy'])->name('pengajuan.destroy');

        // Edit Pengajuan
        Route::get('/pengajuan/{id}/edit', [PengajuanCutiController::class, 'edit'])->name('pengajuan.edit');
        Route::put('/pengajuan/{id}', [PengajuanCutiController::class, 'update'])->name('pengajuan.update');

    });

    // ============================================
    // ADMIN ROUTES
    // ============================================
    Route::middleware('role:admin')->group(function () {

        // Verifikasi Pengajuan
        Route::get('/verifikasi', [PengajuanCutiController::class, 'indexVerifikasi'])->name('verifikasi.index');
        Route::post('/verifikasi/{id}/setuju', [PengajuanCutiController::class, 'verifikasi'])->name('verifikasi.setuju');
        Route::post('/verifikasi/{id}/tolak', [PengajuanCutiController::class, 'tolakVerifikasi'])->name('verifikasi.tolak');

    });

    // ============================================
    // KEPALA SEKOLAH ROUTES
    // ============================================
    Route::middleware('role:kepala_sekolah')->group(function () {

        // Persetujuan Pengajuan
        Route::get('/persetujuan', [PengajuanCutiController::class, 'indexPersetujuan'])->name('persetujuan.index');
        Route::post('/persetujuan/{id}/setuju', [PengajuanCutiController::class, 'setujui'])->name('persetujuan.setuju');
        Route::post('/persetujuan/{id}/tolak', [PengajuanCutiController::class, 'tolak'])->name('persetujuan.tolak');

    });

    // ============================================
    // LAPORAN ROUTES (Admin & Kepala Sekolah)
    // ============================================
    Route::middleware('role:admin,kepala_sekolah')->group(function () {

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pengajuan', [LaporanController::class, 'filterPengajuan'])->name('laporan.pengajuan');
        Route::get('/laporan/histori', [LaporanController::class, 'histori'])->name('laporan.histori');
        Route::get('/laporan/hak-cuti', [LaporanController::class, 'hakCuti'])->name('laporan.hak-cuti');

    });

    // ============================================
    // SHARED ROUTES (Semua role bisa akses detail)
    // ============================================
    Route::get('/pengajuan/{id}/detail', [PengajuanCutiController::class, 'show'])->name('pengajuan.detail');

    // USER
    Route::post('/admin/guru/{id}/activate', [UserController::class, 'activate'])->name('admin.guru.activate');
    Route::post('/admin/guru/{id}/reset-hak-cuti', [UserController::class, 'resetHakCuti'])->name('admin.guru.reset-hak-cuti');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('guru', GuruController::class);
    });

    Route::middleware(['auth'])->group(function () {

        // hanya admin
        Route::middleware('admin')->group(function () {
            // Route::resource('admin/guru', GuruController::class);
        });

    });
});
