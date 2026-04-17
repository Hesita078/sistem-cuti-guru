<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriCuti extends Model
{
    use HasFactory;

    protected $table = 'histori_cuti';

    protected $fillable = [
        'kode_pengajuan',
        'pengajuan_cuti_id',
        'user_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'hak_cuti_sebelum',
        'hak_cuti_sesudah',
        'tanggal_persetujuan'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_persetujuan' => 'datetime',
    ];

    // Relationship: Histori milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Histori dari satu pengajuan cuti
    public function pengajuanCuti()
    {
        return $this->belongsTo(PengajuanCuti::class);
    }
}
