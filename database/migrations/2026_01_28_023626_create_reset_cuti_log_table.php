<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reset_cuti_log', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->integer('jumlah_user_direset');
            $table->timestamp('tanggal_reset');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reset_cuti_log');
    }
};
