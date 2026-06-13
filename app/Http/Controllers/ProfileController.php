<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    // =========================
    // UPDATE PROFILE
    // =========================
    public function update(Request $request)
    {
        $request->validate([
            'nama'  => 'required',
            'email' => 'required|email',
        ]);

        $user = auth()->user();

        $user->update([
            'nama'    => $request->nama,
            'email'   => $request->email,
            'nip'     => $request->nip,        // ← tambah
            'jabatan' => $request->jabatan,    // ← tambah
            'no_telp' => $request->no_telp,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
