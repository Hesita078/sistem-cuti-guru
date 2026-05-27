<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\HistoriCuti;
use App\Models\User;
use App\Models\CutiBersama;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\NotifikasiController;

class PengajuanCutiController extends Controller
{
    // ============================================
    // GURU METHODS
    // ============================================

    // GURU: Tampilkan daftar pengajuan cuti milik guru
    public function index()
    {
        $pengajuan = PengajuanCuti::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('pengajuan.index', compact('pengajuan'));
    }

    // GURU: Form buat pengajuan baru
    public function create()
    {
        $user = auth()->user();

        if ($user->hak_cuti <= 0) {
            return redirect()->route('pengajuan.index')
                ->with('error', 'Hak cuti Anda sudah habis. Tidak dapat mengajukan cuti.');
        }

        return view('pengajuan.create');
    }

    // GURU: Simpan pengajuan baru
    public function store(Request $request)
    {
        $user = auth()->user();

        // ============================================
        // VALIDASI DASAR
        // ============================================
        $validated = $request->validate([
            'jenis_cuti'       => 'required|in:Cuti Tahunan,Cuti Sakit,Cuti Melahirkan,Cuti Ibadah Haji,Cuti Penting',
            'tanggal_mulai'    => 'required|date|after_or_equal:today',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'alasan'           => 'required|string|min:10',
            'surat_dokter'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_melahirkan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'surat_haji'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'dokumen_penting'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $tanggalMulai   = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);


        // ============================================
        // VALIDASI: TIDAK BOLEH HARI WEEKEND
        // ============================================
        if ($tanggalMulai->isWeekend()) {
            return back()->withErrors([
                'tanggal_mulai' => 'Tanggal mulai tidak boleh hari Sabtu atau Minggu.'
            ])->withInput();
        }

        if ($tanggalSelesai->isWeekend()) {
            return back()->withErrors([
                'tanggal_selesai' => 'Tanggal selesai tidak boleh hari Sabtu atau Minggu.'
            ])->withInput();
        }


        // ============================================
        // VALIDASI: TIDAK BOLEH TANGGAL CUTI BERSAMA
        // ============================================
        $cutiBersamaMulai = CutiBersama::where('tanggal', $tanggalMulai->format('Y-m-d'))->first();

        if ($cutiBersamaMulai) {
            return back()->withErrors([
                'tanggal_mulai' => "Tanggal mulai adalah Cuti Bersama: \"{$cutiBersamaMulai->nama}\". Silakan pilih tanggal lain."
            ])->withInput();
        }

        $cutiBersamaSelesai = CutiBersama::where('tanggal', $tanggalSelesai->format('Y-m-d'))->first();

        if ($cutiBersamaSelesai) {
            return back()->withErrors([
                'tanggal_selesai' => "Tanggal selesai adalah Cuti Bersama: \"{$cutiBersamaSelesai->nama}\". Silakan pilih tanggal lain."
            ])->withInput();
        }


        // ============================================
        // HITUNG HARI KERJA (exclude weekend & cuti bersama)
        // ============================================
        $cutiBersamaDalamRange = CutiBersama::whereBetween('tanggal', [
            $tanggalMulai->format('Y-m-d'),
            $tanggalSelesai->format('Y-m-d'),
        ])->pluck('tanggal')
          ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
          ->toArray();

        $jumlahHari = 0;
        $current    = $tanggalMulai->copy();

        while ($current->lte($tanggalSelesai)) {

            if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $cutiBersamaDalamRange)) {
                $jumlahHari++;
            }

            $current->addDay();
        }


        // ============================================
        // VALIDASI CUTI TAHUNAN
        // ============================================
        if ($request->jenis_cuti == 'Cuti Tahunan') {

            if ($user->hak_cuti <= 0) {
                return back()
                    ->with('error', 'Hak cuti tahunan Anda sudah habis.')
                    ->withInput();
            }

            if ($jumlahHari > $user->hak_cuti) {
                return back()
                    ->with('error', 'Pengajuan melebihi sisa hak cuti Anda.')
                    ->withInput();
            }

            if ($jumlahHari > 12) {
                return back()
                    ->with('error', 'Cuti tahunan maksimal 12 hari.')
                    ->withInput();
            }

            if ($jumlahHari < 3) {
                return back()
                    ->with('error', 'Minimal cuti tahunan adalah 3 hari.')
                    ->withInput();
            }
        }


        // ============================================
        // VALIDASI CUTI SAKIT
        // ============================================
        if ($request->jenis_cuti == 'Cuti Sakit') {

            if (!$request->hasFile('surat_dokter')) {
                return back()
                    ->with('error', 'Surat dokter wajib diupload untuk cuti sakit.')
                    ->withInput();
            }

            if ($jumlahHari > 14) {
                return back()
                    ->with('error', 'Cuti sakit ringan maksimal 14 hari.')
                    ->withInput();
            }
        }


        // ============================================
        // VALIDASI CUTI MELAHIRKAN
        // ============================================
        if ($request->jenis_cuti == 'Cuti Melahirkan') {

            if (!$request->hasFile('surat_melahirkan')) {
                return back()
                    ->with('error', 'Surat melahirkan wajib diupload.')
                    ->withInput();
            }

            if ($jumlahHari > 90) {
                return back()
                    ->with('error', 'Cuti melahirkan maksimal 3 bulan.')
                    ->withInput();
            }
        }


        // ============================================
        // VALIDASI CUTI HAJI
        // ============================================
        if ($request->jenis_cuti == 'Cuti Ibadah Haji') {

            if (!$request->hasFile('surat_haji')) {
                return back()
                    ->with('error', 'Surat keberangkatan haji wajib diupload.')
                    ->withInput();
            }
        }


        // ============================================
        // VALIDASI CUTI PENTING
        // ============================================
        if ($request->jenis_cuti == 'Cuti Penting') {

            if (!$request->hasFile('dokumen_penting')) {
                return back()
                    ->with('error', 'Dokumen pendukung wajib diupload.')
                    ->withInput();
            }

            if ($jumlahHari > 30) {
                return back()
                    ->with('error', 'Cuti alasan penting maksimal 30 hari.')
                    ->withInput();
            }
        }


        DB::beginTransaction();

        try {

            // ============================================
            // UPLOAD FILE
            // ============================================
            $filePath = null;

            if ($request->hasFile('surat_dokter')) {
                $filePath = $request->file('surat_dokter')->store('cuti-documents', 'public');
            } elseif ($request->hasFile('surat_melahirkan')) {
                $filePath = $request->file('surat_melahirkan')->store('cuti-documents', 'public');
            } elseif ($request->hasFile('surat_haji')) {
                $filePath = $request->file('surat_haji')->store('cuti-documents', 'public');
            } elseif ($request->hasFile('dokumen_penting')) {
                $filePath = $request->file('dokumen_penting')->store('cuti-documents', 'public');
            }


            // ============================================
            // GENERATE KODE & SIMPAN PENGAJUAN
            // ============================================
            $kodePengajuan = PengajuanCuti::generateKodePengajuan();

            $pengajuan = PengajuanCuti::create([
                'user_id'        => $user->id,
                'kode_pengajuan' => $kodePengajuan,
                'jenis_cuti'     => $request->jenis_cuti,
                'tanggal_mulai'  => $request->tanggal_mulai,
                'tanggal_selesai'=> $request->tanggal_selesai,
                'jumlah_hari'    => $jumlahHari,
                'alasan'         => $request->alasan,
                'file_pendukung' => $filePath,
                'status'         => 'Menunggu Verifikasi Admin',
            ]);


            // ============================================
            // WHATSAPP ADMIN
            // ============================================
            try {

                $admin = User::where('role', 'admin')->first();

                if ($admin) {

                    app(NotifikasiController::class)->kirimWa(
                        $admin->no_telp,
                        'pengajuan_baru'
                    );

                }

            } catch (\Exception $e) {

                \Log::error('WA gagal dikirim: ' . $e->getMessage());

            }

            DB::commit();

            return redirect()
                ->route('pengajuan.index')
                ->with('success', 'Pengajuan cuti berhasil diajukan dengan kode: ' . $kodePengajuan);

        } catch (\Exception $e) {

            DB::rollback();

            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }


    // GURU: Lihat detail pengajuan
    public function show($id)
    {
        $pengajuan = PengajuanCuti::with('user')->findOrFail($id);

        if (auth()->user()->role == 'guru' && $pengajuan->user_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('pengajuan.show', compact('pengajuan'));
    }


    // ============================================
    // ADMIN METHODS
    // ============================================

    // ADMIN: Halaman verifikasi
    public function indexVerifikasi()
    {
        $pengajuan = PengajuanCuti::with('user')
            ->where('status', 'Menunggu Verifikasi Admin')
            ->latest()
            ->paginate(10);

        return view('pengajuan.verifikasi', compact('pengajuan'));
    }

    // ADMIN: Proses verifikasi (Setuju)
    public function verifikasi($id)
    {
        $pengajuan = PengajuanCuti::findOrFail($id);

        if ($pengajuan->status != 'Menunggu Verifikasi Admin') {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {

            $pengajuan->update([
                'status'                   => 'Menunggu Persetujuan Kepala Sekolah',
                'tanggal_verifikasi_admin' => now(),
            ]);

            try {

                // WA KE GURU
                app(NotifikasiController::class)->kirimWa(
                    $pengajuan->user->no_telp,
                    'diverifikasi_admin'
                );

            } catch (\Exception $e) {
                \Log::error('WA gagal dikirim: ' . $e->getMessage());
            }

            DB::commit();

            return back()->with('success', 'Pengajuan berhasil diverifikasi.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ADMIN: Tolak verifikasi
    public function tolakVerifikasi(Request $request, $id)
{
    $request->validate([
        'catatan_admin' => 'required|string|min:10',
    ]);

    $pengajuan = PengajuanCuti::with('user')->findOrFail($id);

    if ($pengajuan->status != 'Menunggu Verifikasi Admin') {
        return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
    }

    DB::beginTransaction();

    try {

        // UPDATE STATUS
        $pengajuan->update([
            'status' => 'Ditolak Admin',
            'catatan_admin' => $request->catatan_admin,
            'tanggal_verifikasi_admin' => now(),
        ]);

        // KIRIM WHATSAPP
        try {

            app(NotifikasiController::class)->kirimWa(
                $pengajuan->user->no_telp,
                'ditolak_admin'
            );

        } catch (\Exception $e) {

            \Log::error('WA gagal dikirim: ' . $e->getMessage());

        }

        DB::commit();

        return back()->with('success', 'Pengajuan berhasil ditolak.');

    } catch (\Exception $e) {

        DB::rollback();

        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    // ============================================
    // KEPALA SEKOLAH METHODS
    // ============================================

    // KEPALA SEKOLAH: Halaman persetujuan
    public function indexPersetujuan()
    {
        $pengajuan = PengajuanCuti::with('user')
            ->where('status', 'Menunggu Persetujuan Kepala Sekolah')
            ->latest()
            ->paginate(10);

        return view('pengajuan.persetujuan', compact('pengajuan'));
    }

    // KEPALA SEKOLAH: Setujui cuti
    public function setujui($id)
{
    $data = PengajuanCuti::findOrFail($id);

    $pengajuan = PengajuanCuti::with('user')->findOrFail($id);

    if ($pengajuan->status != 'Menunggu Persetujuan Kepala Sekolah') {
        return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
    }

    DB::beginTransaction();

    try {

        $user = $pengajuan->user;

        $hakCutiSebelum = $user->hak_cuti;

        $hakCutiSesudah = $hakCutiSebelum - $pengajuan->jumlah_hari;

        // UPDATE STATUS
        $pengajuan->update([
            'status' => 'Disetujui Kepala Sekolah',
            'tanggal_persetujuan' => now(),
        ]);

        // UPDATE HAK CUTI
        $user->update([
            'hak_cuti' => $hakCutiSesudah
        ]);

        // HISTORI CUTI
        HistoriCuti::create([
            'kode_pengajuan' => $data->kode_pengajuan,
            'pengajuan_cuti_id' => $data->id,
            'user_id' => $data->user_id,
            'jenis_cuti' => $data->jenis_cuti,
            'tanggal_mulai' => $data->tanggal_mulai,
            'tanggal_selesai' => $data->tanggal_selesai,
            'jumlah_hari' => $data->jumlah_hari,
            'hak_cuti_sebelum' => $hakCutiSebelum,
            'hak_cuti_sesudah' => $hakCutiSesudah,
            'tanggal_persetujuan' => now(),
        ]);

        // KIRIM WHATSAPP
        try {

            app(NotifikasiController::class)->kirimWa(
                $user->no_telp,
                'disetujui_kepsek'
            );

        } catch (\Exception $e) {

            \Log::error('WA gagal dikirim: ' . $e->getMessage());

        }

        DB::commit();

        return back()->with(
            'success',
            'Pengajuan berhasil disetujui.'
        );

    } catch (\Exception $e) {

        DB::rollback();

        return back()->with(
            'error',
            'Terjadi kesalahan: ' . $e->getMessage()
        );
    }
}

    // KEPALA SEKOLAH: Tolak cuti
    public function tolak(Request $request, $id)
{
    $request->validate([
        'catatan_kepala_sekolah' => 'required|string|min:10',
    ]);

    $pengajuan = PengajuanCuti::with('user')->findOrFail($id);

    if ($pengajuan->status != 'Menunggu Persetujuan Kepala Sekolah') {
        return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
    }

    DB::beginTransaction();

    try {

        // UPDATE STATUS
        $pengajuan->update([
            'status' => 'Ditolak Kepala Sekolah',
            'catatan_kepala_sekolah' => $request->catatan_kepala_sekolah,
            'tanggal_persetujuan' => now(),
        ]);

        // KIRIM WHATSAPP
        try {

            app(NotifikasiController::class)->kirimWa(
                $pengajuan->user->no_telp,
                'ditolak_kepsek'
            );

        } catch (\Exception $e) {

            \Log::error('WA gagal dikirim: ' . $e->getMessage());

        }

        DB::commit();

        return back()->with('success', 'Pengajuan berhasil ditolak.');

    } catch (\Exception $e) {

        DB::rollback();

        return back()->with(
            'error',
            'Terjadi kesalahan: ' . $e->getMessage()
        );
    }
}

    // ============================================
    // CETAK PDF
    // ============================================
    public function cetakPdf($id)
    {
        $pengajuan = PengajuanCuti::with('user')->findOrFail($id);

        if (!str_contains($pengajuan->status, 'Disetujui')) {
            abort(403, 'Formulir hanya bisa dicetak jika pengajuan sudah disetujui.');
        }

        if (auth()->user()->role == 'guru' && $pengajuan->user_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $historiCuti = \App\Models\HistoriCuti::where('pengajuan_cuti_id', $pengajuan->id)->first();

        $pdf = Pdf::loadView('pengajuan.cetak-pdf', [
            'pengajuan'      => $pengajuan,
            'histori'        => $historiCuti,
            'kepala_sekolah' => 'Budi Santoso, S.Pd., M.Pd',
            'nip_kepala'     => '197001051995031010',
        ])->setPaper('a4', 'portrait');

        $namaFile = 'Formulir_Cuti_' . $pengajuan->kode_pengajuan . '.pdf';

        return $pdf->download($namaFile);
    }
}
