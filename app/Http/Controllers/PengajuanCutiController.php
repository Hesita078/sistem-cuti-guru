<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\HistoriCuti;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Mail\PengajuanCutiMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

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

        // Cek hak cuti
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

        // Validasi
        $validated = $request->validate([
            'jenis_cuti' => 'required|in:Cuti Tahunan,Cuti Sakit,Cuti Melahirkan,Cuti Bersama,Cuti Penting',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alasan' => 'required|string|min:10',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Hitung jumlah hari
        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        $jumlahHari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;

        // Cek hak cuti cukup atau tidak
        if ($jumlahHari > $user->hak_cuti) {
            return back()->with('error', 'Hak cuti Anda tidak mencukupi. Anda hanya memiliki ' . $user->hak_cuti . ' hari cuti.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Upload file jika ada
            $filePath = null;
            if ($request->hasFile('file_pendukung')) {
                $filePath = $request->file('file_pendukung')->store('cuti-documents', 'public');
            }

            // Generate kode pengajuan
            $kodePengajuan = PengajuanCuti::generateKodePengajuan();

            // Simpan pengajuan
            $pengajuan = PengajuanCuti::create([
                'user_id' => $user->id,
                'kode_pengajuan' => $kodePengajuan,
                'jenis_cuti' => $request->jenis_cuti,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jumlah_hari' => $jumlahHari,
                'alasan' => $request->alasan,
                'file_pendukung' => $filePath,
                'status' => 'Menunggu Verifikasi Admin',
            ]);

            // Kirim email notifikasi ke Admin
            try {
                $admin = User::where('role', 'Admin')->first();
                if ($admin) {
                    Mail::to($admin->email)->send(new PengajuanCutiMail($pengajuan, 'pengajuan_baru'));
                }
            } catch (\Exception $e) {
                // Log error tapi tetap lanjut
                \Log::error('Email gagal dikirim: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('pengajuan.index')
                ->with('success', 'Pengajuan cuti berhasil diajukan dengan kode: ' . $kodePengajuan);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // GURU: Lihat detail pengajuan
    public function show($id)
    {
        $pengajuan = PengajuanCuti::with('user')->findOrFail($id);

        // Pastikan guru hanya bisa lihat pengajuannya sendiri
        if (auth()->user()->isGuru() && $pengajuan->user_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        return view('pengajuan.show', compact('pengajuan'));
    }

    // GURU: Hapus pengajuan (hanya jika masih menunggu)
    public function destroy($id)
    {
        $pengajuan = PengajuanCuti::findOrFail($id);

        // Cek kepemilikan
        if ($pengajuan->user_id != auth()->id()) {
            abort(403);
        }

        // Hanya bisa hapus jika masih menunggu
        if ($pengajuan->status != 'Menunggu Verifikasi Admin') {
            return back()->with('error', 'Tidak dapat menghapus pengajuan yang sudah diproses.');
        }

        // Hapus file jika ada
        if ($pengajuan->file_pendukung) {
            Storage::disk('public')->delete($pengajuan->file_pendukung);
        }

        $pengajuan->delete();

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }

    // GURU: Edit Pengajuan
    public function edit($id)
    {
    $pengajuan = PengajuanCuti::findOrFail($id);

    // Optional: hanya boleh edit jika status masih pending
    if ($pengajuan->status != 'pending_admin'){
        return redirect()->route('pengajuan.index')
            ->with('error', 'Pengajuan tidak bisa diedit karena sudah diproses.');
    }

    return view('pengajuan.edit', compact('pengajuan'));
    }

    // GURU: Update Pengajuan
    public function update(Request $request, $id)
    {
    $pengajuan = PengajuanCuti::findOrFail($id);

    if ($pengajuan->status != 'Pending') {
        return redirect()->route('pengajuan.index')
            ->with('error', 'Pengajuan tidak bisa diedit.');
    }

    $request->validate([
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date',
        'alasan' => 'required|string'
    ]);

    $pengajuan->update([
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'alasan' => $request->alasan
    ]);

    return redirect()->route('pengajuan.index')
        ->with('success', 'Pengajuan berhasil diperbarui.');
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
                'status' => 'Menunggu Persetujuan Kepala Sekolah',
                'tanggal_verifikasi_admin' => now(),
            ]);

            // Kirim email ke Kepala Sekolah
            try {
                $kepalaSekolah = User::where('role', 'Kepala Sekolah')->first();
                if ($kepalaSekolah) {
                    Mail::to($kepalaSekolah->email)->send(new PengajuanCutiMail($pengajuan, 'menunggu_persetujuan'));
                }

                // Kirim email ke Guru
                Mail::to($pengajuan->user->email)->send(new PengajuanCutiMail($pengajuan, 'diverifikasi_admin'));
            } catch (\Exception $e) {
                \Log::error('Email gagal dikirim: ' . $e->getMessage());
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

        $pengajuan = PengajuanCuti::findOrFail($id);

        if ($pengajuan->status != 'Menunggu Verifikasi Admin') {
            return back()->with('error', 'Pengajuan sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $pengajuan->update([
                'status' => 'Ditolak Admin',
                'catatan_admin' => $request->catatan_admin,
                'tanggal_verifikasi_admin' => now(),
            ]);

            // Kirim email ke Guru
            try {
                Mail::to($pengajuan->user->email)->send(new PengajuanCutiMail($pengajuan, 'ditolak_admin'));
            } catch (\Exception $e) {
                \Log::error('Email gagal dikirim: ' . $e->getMessage());
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
        // Mengambil Data
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

            // Update status pengajuan
            $pengajuan->update([
                'status' => 'Disetujui Kepala Sekolah',
                'tanggal_persetujuan' => now(),
            ]);

            // Kurangi hak cuti user OTOMATIS
            $user->update([
                'hak_cuti' => $hakCutiSesudah
            ]);

            // Simpan ke histori cuti
            HistoriCuti::create([
                'kode_pengajuan' => $data->kode_pengajuan, //
                'pengajuan_cuti_id' => $data->id, // INI YANG KURANG
                'user_id' => $data->user_id,
                'jenis_cuti' => $data->jenis_cuti,
                'tanggal_mulai' => $data->tanggal_mulai,
                'tanggal_selesai' => $data->tanggal_selesai,
                'jumlah_hari' => $data->jumlah_hari,
                'hak_cuti_sebelum' => $hakCutiSebelum,
                'hak_cuti_sesudah' => $hakCutiSesudah,
                'tanggal_persetujuan' => now(),
            ]);

            // Kirim email ke Guru
            try {
                Mail::to($user->email)->send(new PengajuanCutiMail($pengajuan, 'disetujui'));
            } catch (\Exception $e) {
                \Log::error('Email gagal dikirim: ' . $e->getMessage());
            }

            DB::commit();

            return back()->with('success', 'Pengajuan berhasil disetujui. Hak cuti guru otomatis berkurang dari ' . $hakCutiSebelum . ' menjadi ' . $hakCutiSesudah . ' hari.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            $pengajuan->update([
                'status' => 'Ditolak Kepala Sekolah',
                'catatan_kepala_sekolah' => $request->catatan_kepala_sekolah,
                'tanggal_persetujuan' => now(),
            ]);

            // Kirim email ke Guru
            try {
                Mail::to($pengajuan->user->email)->send(new PengajuanCutiMail($pengajuan, 'ditolak kepala sekolah'));
            } catch (\Exception $e) {
                \Log::error('Email gagal dikirim: ' . $e->getMessage());
            }

            DB::commit();

            return back()->with('success', 'Pengajuan berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
