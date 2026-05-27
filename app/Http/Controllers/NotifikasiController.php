<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotifikasiController extends Controller
{
    public function kirimWa(
        $no_telp = null,
        $status = 'diverifikasi_admin'
    )
    {

        // CEK NOMOR KOSONG
        if (empty($no_telp)) {
            return 'Nomor telepon kosong';
        }

        // UBAH 08 MENJADI 628
        $no_telp = preg_replace('/^0/', '62', $no_telp);

        // PESAN BERDASARKAN STATUS
        switch ($status) {

            case 'pengajuan_baru':
                $pesan = "📢 Ada pengajuan cuti baru yang perlu diverifikasi admin.";
                break;

            case 'diverifikasi_admin':
                $pesan = "✅ Pengajuan cuti Anda telah diverifikasi admin dan sedang menunggu persetujuan kepala sekolah.";
                break;

            case 'ditolak_admin':
                $pesan = "❌ Maaf, pengajuan cuti Anda ditolak oleh admin.";
                break;

            case 'disetujui_kepsek':
                $pesan = "🎉 Selamat, pengajuan cuti Anda telah disetujui oleh kepala sekolah.";
                break;

            case 'ditolak_kepsek':
                $pesan = "❌ Maaf, pengajuan cuti Anda ditolak oleh kepala sekolah.";
                break;

            default:
                $pesan = "📢 Status pengajuan cuti Anda telah diperbarui.";
                break;
        }

        // KIRIM KE FONNTE
        $response = Http::withHeaders([
            'Authorization' => config('services.fonnte.token'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $no_telp,
            'message' => $pesan,
        ]);

        // DEBUG RESPONSE
        return $response->body();
    }
}
