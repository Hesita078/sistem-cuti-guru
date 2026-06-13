<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public static function send($no_telp, $pesan)
    {
        $token = config('services.fonnte.token');

        Log::info('[FONNTE] Mengirim WA', [
            'no_telp'   => $no_telp,
            'token_ada' => !empty($token),
            'token_len' => strlen($token ?? ''),
        ]);

        if (empty($token)) {
            Log::error('[FONNTE] Token kosong! Cek FONNTE_TOKEN di .env');
            return ['status' => false, 'error' => 'Token Fonnte tidak ditemukan'];
        }

        try {
            $response = Http::timeout(5)        // ← max 5 detik
                ->retry(1, 1000)                // ← coba ulang 1x jika gagal
                ->withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target'      => $no_telp,
                    'message'     => $pesan,
                    'countryCode' => '62',
                ]);

            Log::info('[FONNTE] Response', [
                'status_code' => $response->status(),
                'body'        => $response->body(),
            ]);

            return $response->json();

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[FONNTE] Koneksi gagal: ' . $e->getMessage());
            return ['status' => false, 'error' => 'Koneksi ke Fonnte gagal'];
        }
    }
}
