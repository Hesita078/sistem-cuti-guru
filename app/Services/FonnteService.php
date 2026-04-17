<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public static function send($phone, $message)
    {
        return Http::withHeaders([
            'Authorization' => env('FONNTE_API_KEY'),
        ])->post(env('FONNTE_URL'), [
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62895399191838',
        ])->json();
    }
}
