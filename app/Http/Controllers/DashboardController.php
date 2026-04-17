<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\User;
use App\Models\HistoriCuti;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Data berbeda berdasarkan role
        if ($user->isKepalaSekolah()) {
            return $this->dashboardKepalaSekolah();
        } elseif ($user->isAdmin()) {
            return $this->dashboardAdmin();
        } else {
            return $this->dashboardGuru();
        }
    }

    // Dashboard untuk Kepala Sekolah
    private function dashboardKepalaSekolah()
    {
        $data = [
            'totalGuru' => User::where('role', 'Guru')->count(),
            'pengajuanMenunggu' => PengajuanCuti::where('status', 'Menunggu Persetujuan Kepala Sekolah')->count(),
            'pengajuanDisetujui' => PengajuanCuti::where('status', 'Disetujui')->count(),
            'pengajuanDitolak' => PengajuanCuti::whereIn('status', ['Ditolak', 'Ditolak Admin'])->count(),
            'pengajuanTerbaru' => PengajuanCuti::with('user')
                ->where('status', 'Menunggu Persetujuan Kepala Sekolah')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.kepala-sekolah', $data);
    }

    // Dashboard untuk Admin
    private function dashboardAdmin()
    {
        $data = [
            'totalGuru' => User::where('role', 'Guru')->count(),
            'pengajuanMenunggu' => PengajuanCuti::where('status', 'Menunggu Verifikasi Admin')->count(),
            'pengajuanDiverifikasi' => PengajuanCuti::where('status', 'Diverifikasi Admin')->count(),
            'pengajuanDitolak' => PengajuanCuti::where('status', 'Ditolak Admin')->count(),
            'pengajuanTerbaru' => PengajuanCuti::with('user')
                ->where('status', 'Menunggu Verifikasi Admin')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.admin', $data);
    }

    // Dashboard untuk Guru
    private function dashboardGuru()
    {
        $user = auth()->user();

        $data = [
            'hakCuti' => $user->hak_cuti,
            'totalPengajuan' => PengajuanCuti::where('user_id', $user->id)->count(),
            'pengajuanMenunggu' => PengajuanCuti::where('user_id', $user->id)
                ->whereIn('status', ['Menunggu Verifikasi Admin', 'Menunggu Persetujuan Kepala Sekolah'])
                ->count(),
            'pengajuanDisetujui' => PengajuanCuti::where('user_id', $user->id)
                ->where('status', 'Disetujui')
                ->count(),
            'pengajuanDitolak' => PengajuanCuti::where('user_id', $user->id)
                ->whereIn('status', ['Ditolak', 'Ditolak Admin'])
                ->count(),
            'pengajuanTerbaru' => PengajuanCuti::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
            'historiCuti' => HistoriCuti::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('dashboard.guru', $data);
    }
}
