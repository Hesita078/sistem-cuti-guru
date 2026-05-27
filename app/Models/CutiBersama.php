<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CutiBersama extends Model
{
    protected $table = 'cuti_bersama';

    protected $fillable = [
        'nama',
        'tanggal',
        'keterangan',
        'tahun',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Scope: filter by year
    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    // Get all dates as array of 'Y-m-d' strings (for JS)
    public static function getTanggalArray($tahun = null)
    {
        $query = static::query();

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        return $query->pluck('tanggal')
            ->map(fn($d) => $d->format('Y-m-d'))
            ->toArray();
    }
}
