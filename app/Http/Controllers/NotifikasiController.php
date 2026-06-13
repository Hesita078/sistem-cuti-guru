<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FonnteService;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    public function kirimWa($no_telp = null, $status = 'diverifikasi_admin')
    {
        // CEK NOMOR KOSONG
        if (empty($no_telp)) {
            Log::warning('[NOTIF WA] Nomor telepon kosong, status: ' . $status);
            return 'Nomor telepon kosong';
        }

        // UBAH 08 MENJADI 628
        $no_telp_asli = $no_telp;
        $no_telp = preg_replace('/^0/', '62', $no_telp);

        Log::info('[NOTIF WA] Mencoba kirim WA', [
            'no_telp_asli' => $no_telp_asli,
            'no_telp_fix'  => $no_telp,
            'status'       => $status,
            'token_ada'    => !empty(config('services.fonnte.token')),
        ]);

        // PESAN BERDASARKAN STATUS
        switch ($status) {
            case 'pengajuan_baru':
                $pesan = "📢 *SISTEM CUTI SEKOLAH*\n\nAda pengajuan cuti baru yang perlu diverifikasi.\n\nSilakan login ke sistem untuk memeriksa pengajuan.";
                break;
            case 'diverifikasi_admin':
                $pesan = "✅ *SISTEM CUTI SEKOLAH*\n\nPengajuan cuti Anda telah diverifikasi oleh admin dan sedang menunggu persetujuan Kepala Sekolah.";
                break;
            case 'ditolak_admin':
                $pesan = "❌ *SISTEM CUTI SEKOLAH*\n\nMaaf, pengajuan cuti Anda ditolak oleh admin.\n\nSilakan login ke sistem untuk melihat alasan penolakan.";
                break;
            case 'menunggu_kepsek':
                $pesan = "📋 *SISTEM CUTI SEKOLAH*\n\nAda pengajuan cuti yang perlu disetujui.\n\nSilakan login ke sistem untuk memeriksa pengajuan.";
                break;
            case 'disetujui_kepsek':
                $pesan = "🎉 *SISTEM CUTI SEKOLAH*\n\nSelamat! Pengajuan cuti Anda telah disetujui oleh Kepala Sekolah.";
                break;
            case 'ditolak_kepsek':
                $pesan = "❌ *SISTEM CUTI SEKOLAH*\n\nMaaf, pengajuan cuti Anda ditolak oleh Kepala Sekolah.\n\nSilakan login ke sistem untuk melihat alasan penolakan.";
                break;
            default:
                $pesan = "📢 *SISTEM CUTI SEKOLAH*\n\nStatus pengajuan cuti Anda telah diperbarui.\n\nSilakan login ke sistem untuk melihat informasi terbaru.";
                break;
        }

        // KIRIM VIA FONNTE SERVICE
        try {
            $response = FonnteService::send($no_telp, $pesan);

            Log::info('[NOTIF WA] Response Fonnte', [
                'no_telp'  => $no_telp,
                'status'   => $status,
                'response' => $response,
            ]);

            return $response;

        } catch (\Exception $e) {
            Log::error('[NOTIF WA] Gagal kirim', [
                'no_telp' => $no_telp,
                'status'  => $status,
                'error'   => $e->getMessage(),
            ]);
            return null;
        }
    }
}
