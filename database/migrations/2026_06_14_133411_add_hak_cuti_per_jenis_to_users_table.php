<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename kolom lama hak_cuti -> hak_cuti_tahunan
            $table->renameColumn('hak_cuti', 'hak_cuti_tahunan');

            // Tambah kolom hak cuti per jenis
            $table->integer('hak_cuti_sakit')->default(14)->after('hak_cuti_tahunan');
            $table->integer('hak_cuti_melahirkan')->default(90)->after('hak_cuti_sakit');
            $table->integer('hak_cuti_haji')->default(40)->after('hak_cuti_melahirkan');
            $table->integer('hak_cuti_penting')->default(5)->after('hak_cuti_haji');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('hak_cuti_tahunan', 'hak_cuti');
            $table->dropColumn([
                'hak_cuti_sakit',
                'hak_cuti_melahirkan',
                'hak_cuti_haji',
                'hak_cuti_penting',
            ]);
        });
    }
};
