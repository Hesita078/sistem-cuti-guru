<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ResetCutiLog;
use Illuminate\Support\Facades\DB;

class ResetHakCutiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cuti:reset {--jumlah=12 : Jumlah hari cuti yang akan direset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset hak cuti semua guru ke jumlah tertentu (default: 12 hari)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jumlahCuti = $this->option('jumlah');

        $this->info('🔄 Memulai proses reset hak cuti...');
        $this->newLine();

        // Konfirmasi
        if (!$this->confirm("Apakah Anda yakin ingin mereset hak cuti semua guru menjadi {$jumlahCuti} hari?")) {
            $this->warn('❌ Proses dibatalkan.');
            return 0;
        }

        DB::beginTransaction();
        try {
            // Get semua guru
            $guru = User::where('role', 'Guru')->get();

            if ($guru->isEmpty()) {
                $this->warn('⚠️  Tidak ada guru yang ditemukan.');
                return 0;
            }

            $this->info("📊 Total guru: {$guru->count()}");
            $this->newLine();

            // Progress bar
            $bar = $this->output->createProgressBar($guru->count());
            $bar->start();

            // Reset hak cuti
            $jumlahDireset = 0;
            foreach ($guru as $item) {
                $item->update(['hak_cuti' => $jumlahCuti]);
                $jumlahDireset++;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            // Simpan log
            ResetCutiLog::create([
                'tahun' => date('Y'),
                'jumlah_user_direset' => $jumlahDireset,
                'tanggal_reset' => now(),
                'keterangan' => "Reset hak cuti tahunan menjadi {$jumlahCuti} hari untuk {$jumlahDireset} guru"
            ]);

            DB::commit();

            // Success message
            $this->info('✅ Reset hak cuti berhasil!');
            $this->table(
                ['Keterangan', 'Nilai'],
                [
                    ['Total Guru', $jumlahDireset],
                    ['Hak Cuti Baru', "{$jumlahCuti} hari"],
                    ['Waktu Reset', now()->format('d F Y H:i:s')],
                ]
            );

            return 0;

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
