<?php

namespace App\Http\Controllers;

use App\Models\CutiBersama;
use Illuminate\Http\Request;

class CutiBersamaController extends Controller
{
    // =============================================
    // ADMIN: Daftar Cuti Bersama
    // =============================================
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $cutiBersama = CutiBersama::where('tahun', $tahun)
            ->orderBy('tanggal')
            ->get();

        // Range dari tahun berjalan sampai 3000
        $tahunList = range((int) date('Y'), 3000);

        return view('admin.cuti-bersama.index', compact(
            'cutiBersama',
            'tahun',
            'tahunList'
        ));
    }


    // =============================================
    // ADMIN: Simpan Cuti Bersama
    // =============================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'tanggal'    => 'required|date|unique:cuti_bersama,tanggal',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $validated['tahun'] = date('Y', strtotime($validated['tanggal']));

        CutiBersama::create($validated);

        return redirect()->back()
            ->with('success', 'Cuti bersama berhasil ditambahkan.');
    }


    // =============================================
    // ADMIN: Update Cuti Bersama
    // =============================================
    public function update(Request $request, CutiBersama $cutiBersama)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'tanggal'    => 'required|date|unique:cuti_bersama,tanggal,' . $cutiBersama->id,
            'keterangan' => 'nullable|string|max:500',
        ]);

        $validated['tahun'] = date('Y', strtotime($validated['tanggal']));

        $cutiBersama->update($validated);

        return redirect()
            ->route('admin.cuti-bersama.index', ['tahun' => $validated['tahun']])
            ->with('success', 'Cuti bersama berhasil diperbarui.');
    }


    // =============================================
    // ADMIN: Hapus Cuti Bersama
    // =============================================
    public function destroy(CutiBersama $cutiBersama)
    {
        $cutiBersama->delete();

        return redirect()->back()
            ->with('success', 'Cuti bersama berhasil dihapus.');
    }


    // =============================================
    // API: Kirim tanggal cuti bersama ke JS (JSON)
    // =============================================
    public function apiTanggal(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $tanggal = CutiBersama::where('tahun', $tahun)
            ->orderBy('tanggal')
            ->get(['tanggal', 'nama', 'keterangan'])
            ->map(fn($item) => [
                'tanggal'    => $item->tanggal->format('Y-m-d'),
                'nama'       => $item->nama,
                'keterangan' => $item->keterangan,
            ]);

        return response()->json($tanggal);
    }
}
