<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\HistoriCuti;
use App\Models\User;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Halaman laporan
    public function index()
    {
        return view('laporan.index');
    }

    // Filter laporan pengajuan cuti
    public function filterPengajuan(Request $request)
    {
        $query = PengajuanCuti::with('user')
        ->whereIn('status', ['pending','diproses']);

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jenis cuti
        if ($request->filled('jenis_cuti')) {
            $query->where('jenis_cuti', $request->jenis_cuti);
        }

        // Filter berdasarkan guru (untuk admin/kepala sekolah)
        if ($request->filled('user_id') && !auth()->user()->isGuru()) {
            $query->where('user_id', $request->user_id);
        }

        $pengajuan = $query->latest()->paginate(20);
        $users = User::where('role', 'Guru')->get();

        $data = PengajuanCuti::whereIn('status', [
            'pending',
            'diverifikasi_admin'
        ])->get();

        return view('laporan.pengajuan', compact('pengajuan', 'users'));
    }

    // Laporan histori cuti
    public function histori(Request $request)
    {
        $query = HistoriCuti::with('user');

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_persetujuan', $request->tahun);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_persetujuan', $request->bulan);
        }

        // Filter berdasarkan guru
        if ($request->filled('user_id') && !auth()->user()->isGuru()) {
            $query->where('user_id', $request->user_id);
        }

        // Jika guru, hanya tampilkan miliknya
        if (auth()->user()->isGuru()) {
            $query->where('user_id', auth()->id());
        }

        $histori = $query->latest('tanggal_persetujuan')->paginate(20);
        $users = User::where('role', 'Guru')->get();

        $data = PengajuanCuti::whereIn('status', [
            'disetujui',
            'ditolak'
        ])->get();

        return view('laporan.histori', compact('histori', 'users'));
    }

    // Laporan sisa hak cuti semua guru
    public function hakCuti()
    {
        $guru = User::where('role', 'Guru')
            ->orderBy('nama')
            ->get();

        return view('laporan.hak-cuti', compact('guru'));
    }
}
