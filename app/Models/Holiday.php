<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Holiday extends Model
{
    protected $fillable = ['date', 'name', 'type', 'year', 'month'];

    protected $casts = ['date' => 'date'];

    // Scope filter per tahun
    public function scopeOfYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    // Scope filter per bulan
    public function scopeOfMonth($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    // Cek apakah tanggal tertentu adalah hari libur
    public static function isHoliday(Carbon $date, bool $includeJointLeave = true): bool
{
    $query = static::where('date', $date->toDateString());

    if (!$includeJointLeave) {
        $query->where('type', 'national_holiday');
    }

    return $query->exists();
}

    // Ambil semua tanggal libur dalam rentang sebagai array ['Y-m-d', ...]
    public static function getHolidayDatesInRange(Carbon $start, Carbon $end, bool $includeJointLeave = true): array
    {
        $query = static::whereBetween('date', [
            $start->toDateString(),
            $end->toDateString(),
        ]);

        if (!$includeJointLeave) {
            $query->where('type', 'national_holiday');
        }

        return $query->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();
    }
}
