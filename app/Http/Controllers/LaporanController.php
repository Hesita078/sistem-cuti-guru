<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\HistoriCuti;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'guru')->get();
        return view('laporan.index', compact('users'));
    }

    // ── Histori Pengajuan (semua status) ──────────────
    public function filterPengajuan(Request $request)
    {
        $query = PengajuanCuti::with('user');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_cuti')) {
            $query->where('jenis_cuti', $request->jenis_cuti);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $pengajuan = $query->latest()->paginate(20);
        $users = User::where('role', 'guru')->get();

        return view('laporan.pengajuan', compact('pengajuan', 'users'));
    }

    // ── Laporan Cuti Disetujui ─────────────────────────
    public function histori(Request $request)
    {
        $query = HistoriCuti::with('user');

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_persetujuan', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_persetujuan', $request->bulan);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $histori = $query->latest('tanggal_persetujuan')->paginate(5)->withQueryString();
        $users = User::where('role', 'guru')->get();

        return view('laporan.histori', compact('histori', 'users'));
    }

    // ── Sisa Hak Cuti ──────────────────────────────────
    public function hakCuti()
    {
        $guru = User::where('role', 'guru')->orderBy('nama')->get();
        return view('laporan.hak-cuti', compact('guru'));
    }

    // ── Cetak PDF Bulanan (dari HistoriCuti) ───────────
    public function cetakBulanan(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $data = HistoriCuti::with('user')
            ->whereMonth('tanggal_mulai', $bulan)
            ->whereYear('tanggal_mulai', $tahun)
            ->latest('tanggal_mulai')
            ->get();

        $pdf = Pdf::loadView('laporan.histori-pdf', [
            'tipe' => 'bulanan',
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'kepala_sekolah' => 'Budi Santoso, S.Pd., M.Pd',
            'nip_kepala' => '197001051995031010',
            'email' => 'sdnkincang01@gmail.com',
        ])->setPaper('a4', 'portrait');

        $namaBulan = Carbon::create($tahun, $bulan)->translatedFormat('F');
        return $pdf->download('Laporan_Cuti_Bulanan_' . $namaBulan . '_' . $tahun . '.pdf');
    }

    // ── Cetak PDF Tahunan (rekap per guru) ────────────
    public function cetakTahunan(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');

        $data = User::where('role', 'guru')
            ->orderBy('nama')
            ->get()
            ->map(function ($guru) use ($tahun) {
                $cutiDiambil = HistoriCuti::where('user_id', $guru->id)
                    ->whereYear('tanggal_mulai', $tahun)
                    ->sum('jumlah_hari');

                $jenisCuti = HistoriCuti::where('user_id', $guru->id)
                    ->whereYear('tanggal_mulai', $tahun)
                    ->pluck('jenis_cuti')
                    ->unique()
                    ->filter()
                    ->implode(', ');

                $adaDitangguhkan = PengajuanCuti::where('user_id', $guru->id)
                    ->whereYear('created_at', $tahun)
                    ->where('status', 'Ditangguhkan')
                    ->exists();

                $hakCuti = $guru->hak_cuti ?? 12;

                return (object) [
                    'nama' => $guru->nama,
                    'nip' => $guru->nip,
                    'jabatan' => $guru->jabatan,
                    'hak_cuti' => $hakCuti,
                    'cuti_diambil' => $cutiDiambil,
                    'sisa_cuti' => max(0, $hakCuti - $cutiDiambil),
                    'jenis_cuti_diambil' => $jenisCuti ?: '-',
                    'keterangan' => $adaDitangguhkan ? 'Ada pengajuan ditangguhkan' : '-',
                ];
            });

        $pdf = Pdf::loadView('laporan.histori-pdf', [
            'tipe' => 'tahunan',
            'data' => $data,
            'tahun' => $tahun,
            'kepala_sekolah' => 'Budi Santoso, S.Pd., M.Pd',
            'nip_kepala' => '197001051995031010',
            'email' => 'sdnkincang01@gmail.com',
        ])->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Cuti_Tahunan_' . $tahun . '.pdf');
    }

    // ── Cetak PDF Histori Pengajuan (semua status) ────
    public function cetakPengajuan(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $query = PengajuanCuti::with('user')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $data = $query->latest()->get();
        $namaBulan = Carbon::create($tahun, $bulan)->translatedFormat('F');

        $pdf = Pdf::loadView('laporan.pengajuan-pdf', [
            'pengajuan' => $data,
            'bulanNama' => $namaBulan,
            'tahun' => $tahun,
            'kepala_sekolah' => 'Budi Santoso, S.Pd., M.Pd',
            'nip_kepala' => '197001051995031010',
            'email' => 'sdnkincang01@gmail.com',
        ])->setPaper('a4', 'portrait');

        $namaBulan = Carbon::create($tahun, $bulan)->translatedFormat('F');
        return $pdf->download('Histori_Pengajuan_' . $namaBulan . '_' . $tahun . '.pdf');
    }
}
