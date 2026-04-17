<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetCutiLog extends Model
{
    use HasFactory;

    protected $table = 'reset_cuti_log';

    protected $fillable = [
        'tahun',
        'jumlah_user_direset',
        'tanggal_reset',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_reset' => 'datetime',
    ];
}
