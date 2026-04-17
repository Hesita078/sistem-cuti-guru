<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histori_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pengajuan_cuti_id')->constrained('pengajuan_cuti')->onDelete('cascade');
            $table->string('kode_pengajuan');
            $table->enum('jenis_cuti', [
                'Cuti Tahunan',
                'Cuti Sakit',
                'Cuti Melahirkan',
                'Cuti Bersama',
                'Cuti Penting'
            ]);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            $table->integer('hak_cuti_sebelum');
            $table->integer('hak_cuti_sesudah');
            $table->enum('status', ['Disetujui', 'Ditolak']);
            $table->timestamp('tanggal_persetujuan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_cuti');
    }
};
