<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\HistoriCuti;


class GuruController extends Controller
{
    public function index()
{
    $guru = User::latest()->paginate(10);
    return view('admin.guru.index', compact('guru'));
}

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:users,nip',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,guru,kepala_sekolah',
        ]);

        User::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'hak_cuti_tahunan' => $request->role == 'guru' ? $request->hak_cuti_tahunan : 0,
            'sisa_hak_cuti' => $request->role == 'guru' ? $request->hak_cuti_tahunan : 0,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function show($id)
    {
        $guru = User::findOrFail($id);

        $pengajuanCuti = PengajuanCuti::where('user_id', $id)->latest()->paginate(5);
        $historiCuti = HistoriCuti::where('user_id', $id)->latest()->paginate(5);

        return view('admin.guru.show', compact('guru', 'pengajuanCuti', 'historiCuti'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.guru.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nip' => 'required|unique:users,nip,' . $id,
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,guru,kepala_sekolah',
        ]);

        $user->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'hak_cuti_tahunan' => $request->role == 'guru' ? $request->hak_cuti_tahunan : 0,
            'sisa_hak_cuti' => $request->role == 'guru' ? $request->sisa_hak_cuti : 0,
            'is_active' => $request->has('is_active') ? $request->is_active : 1,
        ]);

        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => 0]);

        return back()->with('success', 'User dinonaktifkan');
    }
}
