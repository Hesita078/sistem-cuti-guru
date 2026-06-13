<?php

namespace App\Console\Commands;

use App\Services\HolidayService;
use Illuminate\Console\Command;

class SyncHolidays extends Command
{
    protected $signature   = 'holiday:sync {year? : Tahun yang di-sync, default tahun ini} {--next : Sync tahun depan juga}';
    protected $description = 'Sync hari libur nasional & cuti bersama Indonesia dari API ke database';

    public function __construct(private readonly HolidayService $holidayService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $year  = (int) ($this->argument('year') ?? now()->year);
        $years = $this->option('next') ? [$year, $year + 1] : [$year];

        foreach ($years as $y) {
            $this->info("Sync tahun {$y}...");
            $result = $this->holidayService->syncFromApi($y);

            if ($result['error']) {
                $this->error("Gagal: {$result['error']}");
                continue;
            }

            $this->line("  ✔ Inserted : {$result['inserted']}");
            $this->line("  ✔ Skipped  : {$result['skipped']} (sudah ada)");
        }

        return self::SUCCESS;
    }
}
