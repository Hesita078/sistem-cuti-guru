<?php

namespace App\Console\Commands;

use App\Services\SyncCutiBersamaService;
use Illuminate\Console\Command;

class SyncCutiBersamaCommand extends Command
{
    protected $signature   = 'cuti-bersama:sync {tahun? : Tahun yang disync, default tahun ini}';
    protected $description = 'Sync cuti bersama dari API kalender nasional Indonesia';

    public function handle(SyncCutiBersamaService $service): int
    {
        $tahun = (int) ($this->argument('tahun') ?? date('Y'));

        $this->info("Mensinkronisasi cuti bersama untuk tahun {$tahun}...");

        try {
            $result = $service->sync($tahun);

            $this->table(
                ['Keterangan', 'Jumlah'],
                [
                    ['Data baru ditambahkan', $result['inserted']],
                    ['Data sudah ada / dilewati', $result['skipped']],
                ]
            );

            $this->info('Sinkronisasi selesai.');
            return self::SUCCESS;

        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
    }
}
