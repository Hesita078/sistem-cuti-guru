<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\HistoriCuti;


class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $guru = $query->latest()->paginate(10)->withQueryString();

        return view('admin.guru.index', compact('guru'));
    }

    public function dataGuru(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                ->orWhere('nip', 'like', '%'.$request->search.'%');
            });
        }

        $guru = $query->orderBy('nama')->paginate(10);

        return view('admin.guru.data-guru', compact('guru'));
    }

    public function dataGuruShow($id)
    {
        $guru = User::findOrFail($id);
        $pengajuanCuti = PengajuanCuti::where('user_id', $id)->latest()->paginate(5);
        $historiCuti = HistoriCuti::where('user_id', $id)->latest()->paginate(5);
        return view('admin.guru.data-guru-show', compact('guru', 'pengajuanCuti', 'historiCuti'));
    }

    public function dataGuruEdit($id)
    {
        $guru = User::findOrFail($id);
        return view('admin.guru.data-guru-edit', compact('guru'));
    }

    public function dataGuruUpdate(Request $request, $id)
    {
        $guru = User::findOrFail($id);

        $request->validate([
            'nip'     => 'required|unique:users,nip,' . $id,
            'nama'    => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string',
            'hak_cuti'=> 'required|integer|min:0',
        ]);

        $guru->update([
            'nip'      => $request->nip,
            'nama'     => $request->nama,
            'jabatan'  => $request->jabatan,
            'no_telp'  => $request->no_telp,
            'alamat'   => $request->alamat,
            'hak_cuti' => $request->hak_cuti,
            'is_active'=> $request->has('is_active') ? $request->is_active : 1,
        ]);

        return redirect()->route('admin.data-guru.index')
            ->with('success', 'Data guru berhasil diupdate');
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
            'no_telp' => $request->telepon,
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
            'no_telp' => $request->telepon,
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

    public function dataGuruDestroy($id)
    {
        try {
            $guru = User::findOrFail($id);
            $guru->delete();

            return redirect()
                ->route('admin.data-guru.index')
                ->with('success', 'Data guru berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.data-guru.index')
                ->with('error', 'Data guru gagal dihapus.');
        }
    }
}
