<?php

use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\CutiBersamaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    Route::get('/test-wa', [NotifikasiController::class, 'kirimWa']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // ══════════════════════════════════════════════════
    // GURU
    // ══════════════════════════════════════════════════
    Route::middleware('role:guru')->group(function () {
        Route::get('/pengajuan', [PengajuanCutiController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/create', [PengajuanCutiController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanCutiController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{id}', [PengajuanCutiController::class, 'show'])->name('pengajuan.show');
        Route::delete('/pengajuan/{id}', [PengajuanCutiController::class, 'destroy'])->name('pengajuan.destroy');
        Route::get('/pengajuan/{id}/edit', [PengajuanCutiController::class, 'edit'])->name('pengajuan.edit');
        Route::put('/pengajuan/{id}', [PengajuanCutiController::class, 'update'])->name('pengajuan.update');
    });

    // ══════════════════════════════════════════════════
    // ADMIN
    // ══════════════════════════════════════════════════
    Route::middleware('role:admin')->group(function () {
        Route::get('/verifikasi', [PengajuanCutiController::class, 'indexVerifikasi'])->name('verifikasi.index');
        Route::post('/verifikasi/{id}/setuju', [PengajuanCutiController::class, 'verifikasi'])->name('verifikasi.setuju');
        Route::post('/verifikasi/{id}/tolak', [PengajuanCutiController::class, 'tolakVerifikasi'])->name('verifikasi.tolak');
    });

    // API untuk frontend (tidak perlu auth admin)
    Route::get('/api/cuti-bersama', [CutiBersamaController::class, 'apiTanggal'])->name('api.cuti-bersama');

    // Admin routes
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/cuti-bersama', [CutiBersamaController::class, 'index'])->name('cuti-bersama.index');
    Route::post('/cuti-bersama', [CutiBersamaController::class, 'store'])->name('cuti-bersama.store');
    Route::put('/cuti-bersama/{cutiBersama}', [CutiBersamaController::class, 'update'])->name('cuti-bersama.update');
    Route::delete('/cuti-bersama/{cutiBersama}', [CutiBersamaController::class, 'destroy'])->name('cuti-bersama.destroy');
});

    // ══════════════════════════════════════════════════
    // KEPALA SEKOLAH
    // ══════════════════════════════════════════════════
    Route::middleware('role:kepala_sekolah')->group(function () {
        Route::get('/persetujuan', [PengajuanCutiController::class, 'indexPersetujuan'])->name('persetujuan.index');
        Route::post('/persetujuan/{id}/setuju', [PengajuanCutiController::class, 'setujui'])->name('persetujuan.setuju');
        Route::post('/persetujuan/{id}/tolak', [PengajuanCutiController::class, 'tolak'])->name('persetujuan.tolak');
    });

    // ══════════════════════════════════════════════════
    // LAPORAN (Admin & Kepala Sekolah)
    // ══════════════════════════════════════════════════
    Route::middleware('role:admin,kepala_sekolah')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pengajuan', [LaporanController::class, 'filterPengajuan'])->name('laporan.pengajuan');
        Route::get('/laporan/histori', [LaporanController::class, 'histori'])->name('laporan.histori');
        Route::get('/laporan/hak-cuti', [LaporanController::class, 'hakCuti'])->name('laporan.hak-cuti');

        // Cetak PDF Laporan
        Route::get('/laporan/cetak-bulanan', [LaporanController::class, 'cetakBulanan'])->name('laporan.cetak-bulanan');
        Route::get('/laporan/cetak-tahunan', [LaporanController::class, 'cetakTahunan'])->name('laporan.cetak-tahunan');
        Route::get('/laporan/cetak-pengajuan', [LaporanController::class, 'cetakPengajuan'])->name('laporan.cetak-pengajuan');
    });

    // ══════════════════════════════════════════════════
    // SHARED (semua role yang login)
    // ══════════════════════════════════════════════════
    Route::get('/pengajuan/{id}/detail', [PengajuanCutiController::class, 'show'])->name('pengajuan.detail');

    // Cetak formulir PDF per pengajuan (guru, admin, kepala sekolah)
    Route::get('/pengajuan/{id}/cetak-pdf', [PengajuanCutiController::class, 'cetakPdf'])->name('pengajuan.cetak-pdf');
    Route::post('/admin/guru/{id}/activate', [UserController::class, 'activate'])->name('admin.guru.activate');
    Route::post('/admin/guru/{id}/reset-hak-cuti', [UserController::class, 'resetHakCuti'])->name('admin.guru.reset-hak-cuti');
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('guru', GuruController::class);
    });
});
