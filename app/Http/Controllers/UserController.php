<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // ✅ TAMPILKAN SEMUA USER (BUKAN CUMA GURU)
    public function index()
    {
        $guru = User::latest()->paginate(10);
        return view('admin.guru.index', compact('guru'));
    }

    // ✅ FORM TAMBAH USER
    public function create()
    {
        return view('admin.guru.create');
    }

    // ✅ SIMPAN USER (GURU / ADMIN / KEPSEK)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|unique:users,nip|max:50',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'hak_cuti_tahunan' => 'required|integer|min:0|max:30',
            'role' => 'required|in:guru,kepala_sekolah,admin',
        ]);

        // HASH PASSWORD
        $validated['password'] = Hash::make($validated['password']);

        // LOGIKA ROLE
        if ($validated['role'] != 'guru') {
            $validated['hak_cuti_tahunan'] = 0;
            $validated['sisa_hak_cuti'] = 0;
        } else {
            $validated['sisa_hak_cuti'] = $validated['hak_cuti_tahunan'];
        }

        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    // ✅ DETAIL USER (khusus guru masih aman)
    public function show(string $id)
    {
        $guru = User::findOrFail($id);

        $pengajuanCuti = $guru->pengajuanCuti()
            ->latest()
            ->paginate(5);

        $historiCuti = $guru->historiCuti()
            ->with('pengajuanCuti')
            ->latest()
            ->paginate(5);

        return view('admin.guru.show', compact('guru', 'pengajuanCuti', 'historiCuti'));
    }

    // ✅ FORM EDIT (INI YANG TADI KAMU SALAH)
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.guru.edit', compact('user'));
    }

    // ✅ UPDATE USER
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:50', Rule::unique('users', 'nip')->ignore($user->id)],
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'role' => 'required|in:guru,admin,kepala_sekolah',
            'is_active' => 'required|boolean',
        ]);

        // ✅ VALIDASI KHUSUS GURU
        if ($request->role == 'guru') {
            $validated['hak_cuti_tahunan'] = $request->hak_cuti_tahunan ?? $user->hak_cuti_tahunan ?? 0;

            $validated['sisa_hak_cuti'] = $request->sisa_hak_cuti
                ?? $user->sisa_hak_cuti
                ?? $validated['hak_cuti_tahunan'];

        } else {
            // selain guru
            $validated['hak_cuti_tahunan'] = 0;
            $validated['sisa_hak_cuti'] = 0;
        }

        // ✅ PASSWORD OPTIONAL
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    // ✅ NONAKTIFKAN USER
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $pengajuanAktif = $user->pengajuanCuti()
            ->whereIn('status', [
                'Menunggu Verifikasi Admin',
                'Menunggu Persetujuan Kepala Sekolah'
            ])
            ->count();

        if ($pengajuanAktif > 0) {
            return back()->with('error', 'Tidak dapat menghapus user. Masih ada pengajuan cuti aktif.');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('admin.guru.index')
            ->with('success', 'User berhasil dinonaktifkan.');
    }

    // ✅ AKTIFKAN KEMBALI
    public function activate(string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true]);

        return back()->with('success', 'User berhasil diaktifkan.');
    }

    // ✅ RESET CUTI (KHUSUS GURU)
    public function resetHakCuti(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role != 'guru') {
            return back()->with('error', 'Hanya guru yang memiliki hak cuti.');
        }

        $user->resetHakCuti();

        return back()->with(
            'success',
            "Hak cuti {$user->nama} berhasil direset menjadi {$user->hak_cuti_tahunan} hari."
        );
    }
}
