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
use App\Http\Controllers\LeaveController;


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
    // VERIFIKASI ADMIN
    // ══════════════════════════════════════════════════
    Route::middleware('role:admin')->group(function () {
        Route::get('/verifikasi', [PengajuanCutiController::class, 'indexVerifikasi'])->name('verifikasi.index');
        Route::post('/verifikasi/{id}', [PengajuanCutiController::class, 'verifikasi'])->name('verifikasi.proses');
        Route::post('/verifikasi/{id}/tolak', [PengajuanCutiController::class, 'tolakVerifikasi'])->name('verifikasi.tolak');
    });

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
        Route::get('/laporan/cetak-bulanan', [LaporanController::class, 'cetakBulanan'])->name('laporan.cetak-bulanan');
        Route::get('/laporan/cetak-tahunan', [LaporanController::class, 'cetakTahunan'])->name('laporan.cetak-tahunan');
        Route::get('/laporan/cetak-pengajuan', [LaporanController::class, 'cetakPengajuan'])->name('laporan.cetak-pengajuan');
    });

    // ══════════════════════════════════════════════════
    // SHARED (semua role yang login)
    // ══════════════════════════════════════════════════
    Route::get('/pengajuan/{id}/detail', [PengajuanCutiController::class, 'show'])->name('pengajuan.detail');
    Route::get('/pengajuan/{id}/cetak-pdf', [PengajuanCutiController::class, 'cetakPdf'])->name('pengajuan.cetak-pdf');

    // ── Guru: pengajuan cuti (LeaveController) ──────────────
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/{leave}', [LeaveController::class, 'show'])->name('leaves.show');
    Route::get('/leaves/preview/workdays', [LeaveController::class, 'previewWorkDays'])->name('leaves.preview');

    // ══════════════════════════════════════════════════
    // ADMIN — semua route admin di satu grup
    // ══════════════════════════════════════════════════
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // Manajemen User (UserController)
        Route::get('/guru', [UserController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [UserController::class, 'create'])->name('guru.create');
        Route::post('/guru', [UserController::class, 'store'])->name('guru.store');
        Route::get('/guru/{id}', [UserController::class, 'show'])->name('guru.show');
        Route::get('/guru/{id}/edit', [UserController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{id}', [UserController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{id}', [UserController::class, 'destroy'])->name('guru.destroy');
        Route::post('/guru/{id}/activate', [UserController::class, 'activate'])->name('guru.activate');
        Route::post('/guru/{id}/reset-hak-cuti', [UserController::class, 'resetHakCuti'])->name('guru.reset-hak-cuti');

        // Aktivasi & reset hak cuti guru
        Route::post('/guru/{id}/activate', [UserController::class, 'activate'])->name('guru.activate');
        Route::post('/guru/{id}/reset-hak-cuti', [UserController::class, 'resetHakCuti'])->name('guru.reset-hak-cuti');

        // Data Guru (GuruController)
        Route::get('/data-guru', [GuruController::class, 'dataGuru'])->name('data-guru.index');
        Route::get('/data-guru/{id}', [GuruController::class, 'dataGuruShow'])->name('data-guru.show');
        Route::get('/data-guru/{id}/edit', [GuruController::class, 'dataGuruEdit'])->name('data-guru.edit');
        Route::put('/data-guru/{id}', [GuruController::class, 'dataGuruUpdate'])->name('data-guru.update');
        Route::delete('/data-guru/{id}', [GuruController::class, 'dataGuruDestroy'])->name('data-guru.destroy');

        // Cuti Bersama
        Route::get('/cuti-bersama', [CutiBersamaController::class, 'index'])->name('cuti-bersama.index');
        Route::post('/cuti-bersama', [CutiBersamaController::class, 'store'])->name('cuti-bersama.store');
        Route::put('/cuti-bersama/{cutiBersama}', [CutiBersamaController::class, 'update'])->name('cuti-bersama.update');
        Route::delete('/cuti-bersama/{cutiBersama}', [CutiBersamaController::class, 'destroy'])->name('cuti-bersama.destroy');
        Route::post('/cuti-bersama/sync', [CutiBersamaController::class, 'sync'])->name('cuti-bersama.sync');

        // Leaves (admin)
        Route::get('/leaves', [LeaveController::class, 'adminIndex'])->name('leaves.index');
        Route::patch('/leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
        Route::patch('/leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    });

});

Route::get('/api/cuti-bersama', [App\Http\Controllers\HolidayController::class, 'index']);
