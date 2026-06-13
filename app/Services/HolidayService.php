<?php

namespace App\Services;

use App\Models\Holiday;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HolidayService
{
    private const API_URL     = 'https://api-hari-libur.vercel.app/api';
    private const CACHE_DAYS  = 7;

    /**
     * Hitung hari kerja efektif antara dua tanggal.
     * Weekend + hari libur otomatis dikecualikan.
     */
    public function countWorkDays(Carbon $start, Carbon $end, bool $includeJointLeave = true): int
    {
        if ($start->gt($end)) return 0;

        $holidayDates = Holiday::getHolidayDatesInRange($start, $end, $includeJointLeave);

        $workDays = 0;
        $current  = $start->copy()->startOfDay();

        while ($current->lte($end)) {
            $isWeekend = $current->isWeekend();
            $isHoliday = in_array($current->toDateString(), $holidayDates);

            if (!$isWeekend && !$isHoliday) {
                $workDays++;
            }

            $current->addDay();
        }

        return $workDays;
    }

    /**
     * Ambil daftar libur per bulan (dari DB, dengan cache).
     */
    public function getHolidaysByMonth(int $year, int $month): array
    {
        return Cache::remember(
            "holidays:{$year}:{$month}",
            now()->addDays(self::CACHE_DAYS),
            fn () => Holiday::ofMonth($year, $month)
                ->orderBy('date')
                ->get(['date', 'name', 'type'])
                ->toArray()
        );
    }

    /**
     * Sync data dari API ke database.
     * Dipanggil oleh Artisan command holiday:sync
     */
    public function syncFromApi(int $year): array
    {
        $result = ['inserted' => 0, 'skipped' => 0, 'error' => null];

        try {
            $response = Http::timeout(10)->retry(3, 500)
                ->get(self::API_URL, ['year' => $year]);

            if ($response->failed()) {
                throw new \RuntimeException("API gagal: HTTP {$response->status()}");
            }

            $data = $response->json('data') ?? $response->json() ?? [];

            foreach ($data as $item) {
                $date = Carbon::parse($item['date']);
                $desc = $item['description'] ?? $item['name'] ?? 'Hari Libur';
                $type = str_contains(strtolower($desc), 'cuti bersama')
                    ? 'joint_leave'
                    : 'national_holiday';

                $record = Holiday::updateOrCreate(
                    ['date' => $date->toDateString()],
                    [
                        'name'  => $desc,
                        'type'  => $type,
                        'year'  => $date->year,
                        'month' => $date->month,
                    ]
                );

                $record->wasRecentlyCreated ? $result['inserted']++ : $result['skipped']++;
            }

            // Hapus cache lama setelah sync
            for ($m = 1; $m <= 12; $m++) {
                Cache::forget("holidays:{$year}:{$m}");
            }

        } catch (\Throwable $e) {
            $result['error'] = $e->getMessage();
            Log::error('[HolidayService] Sync gagal', ['year' => $year, 'error' => $e->getMessage()]);
        }

        return $result;
    }
}
