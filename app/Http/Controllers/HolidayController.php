<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $holidays = Holiday::where('year', $tahun)
            ->orderBy('date')
            ->get()
            ->map(fn($h) => [
                'tanggal'    => $h->date->format('Y-m-d'),
                'nama'       => $h->name,
                'keterangan' => $h->type === 'joint_leave' ? 'Cuti Bersama' : 'Libur Nasional',
            ]);

        return response()->json($holidays);
    }
}
