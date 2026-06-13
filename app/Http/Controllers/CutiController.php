<?php
use App\Models\PengajuanCuti;
use App\Services\FonnteService;
use App\Http\Controllers\Controller;

class CutiController extends Controller{

public function store(Request $request)
{
    // Hitung jumlah hari (opsional tapi bagus)
    $jumlahHari = \Carbon\Carbon::parse($request->tanggal_mulai)
        ->diffInDays(\Carbon\Carbon::parse($request->tanggal_selesai)) + 1;

    // Simpan ke pengajuan_cuti
    $cuti = PengajuanCuti::create([
        'user_id' => auth()->id(),
        'kode_pengajuan' => PengajuanCuti::generateKodePengajuan(),
        'jenis_cuti' => $request->jenis_cuti,
        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,
        'jumlah_hari' => $jumlahHari,
        'alasan' => $request->alasan,
        'status' => 'Menunggu Verifikasi',
    ]);

    // Panggil NotifikasiController
$notifikasi = new \App\Http\Controllers\NotifikasiController();
$notifikasi->kirimWa('62895399191838', 'pengajuan_baru');
    // Kirim notifikasi WA
    FonnteService::send(env('FONNTE_TARGET'), '📢 Pengajuan cuti baru...');

    return redirect()->back()->with('success', 'Pengajuan cuti berhasil dikirim.');
}
}
