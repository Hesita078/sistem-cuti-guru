<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_pengajuan')->unique(); // AUTO: CUTI-YYYYMMDD-XXX
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
            $table->text('alasan');
            $table->string('file_pendukung')->nullable(); // Upload file
            $table->enum('status', [
                'Menunggu Verifikasi Admin',
                'Diverifikasi Admin',
                'Ditolak Admin',
                'Menunggu Persetujuan Kepala Sekolah',
                'Disetujui',
                'Ditolak'
            ])->default('Menunggu Verifikasi Admin');
            $table->text('catatan_admin')->nullable();
            $table->text('catatan_kepala_sekolah')->nullable();
            $table->timestamp('tanggal_verifikasi_admin')->nullable();
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
};
