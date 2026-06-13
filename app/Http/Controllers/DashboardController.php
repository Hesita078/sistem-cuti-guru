<?php

namespace App\Http\Controllers;

use App\Models\PengajuanCuti;
use App\Models\User;
use App\Models\HistoriCuti;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'kepala_sekolah') {
            return $this->dashboardKepalaSekolah();
        }

        if ($user->role === 'admin') {
            return $this->dashboardAdmin();
        }

        return $this->dashboardGuru();
    }

    /**
     * Dashboard Kepala Sekolah
     */
    private function dashboardKepalaSekolah()
    {
        return view('dashboard.kepala-sekolah', [

            'totalGuru' => User::where('role', 'guru')->count(),

            'pengajuanMenunggu' => PengajuanCuti::where(
                'status',
                'Menunggu Persetujuan Kepala Sekolah'
            )->count(),

            'pengajuanDisetujui' => PengajuanCuti::where(
                'status',
                'Disetujui Kepala Sekolah'
            )->count(),

            'pengajuanDitolak' => PengajuanCuti::whereIn('status', [
                'Ditolak Admin',
                'Ditolak Kepala Sekolah'
            ])->count(),

            'pengajuanTerbaru' => PengajuanCuti::with('user')
                ->where('status', 'Menunggu Persetujuan Kepala Sekolah')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    /**
     * Dashboard Admin
     */
    private function dashboardAdmin()
    {
        return view('dashboard.admin', [

            'totalGuru' => User::where('role', 'guru')->count(),

            'pengajuanMenunggu' => PengajuanCuti::where(
                'status',
                'Menunggu Verifikasi Admin'
            )->count(),

            'pengajuanDiverifikasi' => PengajuanCuti::where(
                'status',
                'Menunggu Persetujuan Kepala Sekolah'
            )->count(),

            'pengajuanDitolak' => PengajuanCuti::where(
                'status',
                'Ditolak Admin'
            )->count(),

            'pengajuanTerbaru' => PengajuanCuti::with('user')
                ->where('status', 'Menunggu Verifikasi Admin')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    /**
     * Dashboard Guru
     */
    private function dashboardGuru()
    {
        $user = auth()->user();

        return view('dashboard.guru', [

            'hakCuti' => $user->hak_cuti,

            'totalPengajuan' => PengajuanCuti::where(
                'user_id',
                $user->id
            )->count(),

            'pengajuanMenunggu' => PengajuanCuti::where('user_id', $user->id)
                ->whereIn('status', [
                    'Menunggu Verifikasi Admin',
                    'Menunggu Persetujuan Kepala Sekolah'
                ])
                ->count(),

            'pengajuanDisetujui' => PengajuanCuti::where('user_id', $user->id)
                ->where('status', 'Disetujui Kepala Sekolah')
                ->count(),

            'pengajuanDitolak' => PengajuanCuti::where('user_id', $user->id)
                ->whereIn('status', [
                    'Ditolak Admin',
                    'Ditolak Kepala Sekolah'
                ])
                ->count(),

            'pengajuanTerbaru' => PengajuanCuti::where(
                'user_id',
                $user->id
            )
                ->latest()
                ->take(5)
                ->get(),

            'historiCuti' => HistoriCuti::where(
                'user_id',
                $user->id
            )
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
