<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_cuti';

    protected $fillable = [
        'user_id',
        'kode_pengajuan',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'alasan',
        'file_pendukung',
        'status',
        'catatan_admin',
        'catatan_kepala_sekolah',
        'tanggal_verifikasi_admin',
        'tanggal_persetujuan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_verifikasi_admin' => 'datetime',
        'tanggal_persetujuan' => 'datetime',
    ];

    // Relationship: Pengajuan cuti milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Pengajuan cuti memiliki satu histori (jika disetujui)
    public function histori()
    {
        return $this->hasOne(HistoriCuti::class);
    }

    // Helper: Generate kode pengajuan otomatis
    public static function generateKodePengajuan()
    {
        $date = now()->format('Ymd');
        $lastCuti = self::whereDate('created_at', now()->toDateString())
                        ->latest()
                        ->first();

        if ($lastCuti) {
            $lastNumber = (int) substr($lastCuti->kode_pengajuan, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return 'CUTI-' . $date . '-' . $newNumber;
    }

    public function getStatusBadgeClass()
{
    return match ($this->status) {
        'menunggu' => 'secondary',
        'disetujui_admin' => 'info',
        'disetujui_kepsek' => 'success',
        'ditolak' => 'danger',
        default => 'secondary',
    };
}
}
