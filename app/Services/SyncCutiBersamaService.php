<?php

namespace App\Services;

use App\Models\CutiBersama;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncCutiBersamaService
{
    protected string $baseUrl = 'https://api-hari-libur.vercel.app/api';

    /**
     * Sync cuti bersama dari API kalender nasional untuk tahun tertentu.
     * Hanya mengambil yang is_national_holiday = FALSE (cuti bersama saja).
     */
    public function sync(int $tahun): array
    {
        $response = Http::timeout(15)->get($this->baseUrl, [
            'year' => $tahun,
        ]);

        if ($response->failed()) {
            Log::error("SyncCutiBersama: Gagal fetch API untuk tahun {$tahun}", [
                'status' => $response->status(),
            ]);

            throw new \RuntimeException("Gagal mengambil data dari API kalender nasional.");
        }

        $data = $response->json();

        if (empty($data)) {
            return ['inserted' => 0, 'skipped' => 0];
        }

        $inserted = 0;
        $skipped  = 0;

        foreach ($data as $item) {
            // Filter: hanya cuti bersama (bukan hari libur nasional murni)
            // Sesuaikan kondisi ini jika Anda ingin sync semua termasuk libur nasional
            if (!empty($item['is_national_holiday'])) {
                $skipped++;
                continue;
            }

            $tanggal = $item['holiday_date'] ?? null;
            $nama    = $item['holiday_name'] ?? 'Cuti Bersama';

            if (!$tanggal) {
                $skipped++;
                continue;
            }

            // updateOrCreate agar tidak duplikat
            $result = CutiBersama::updateOrCreate(
                ['tanggal' => $tanggal],
                [
                    'nama'       => $nama,
                    'tahun'      => $tahun,
                    'keterangan' => 'Sinkronisasi otomatis dari kalender nasional',
                ]
            );

            $result->wasRecentlyCreated ? $inserted++ : $skipped++;
        }

        Log::info("SyncCutiBersama: Tahun {$tahun} — inserted: {$inserted}, skipped/updated: {$skipped}");

        return ['inserted' => $inserted, 'skipped' => $skipped];
    }
}
