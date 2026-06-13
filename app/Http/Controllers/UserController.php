<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // TAMPILKAN SEMUA USER
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                ->orWhere('nip', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $guru = $query->latest()->paginate(10);

        return view('admin.guru.index', compact('guru'));
    }

    // FORM TAMBAH USER
    public function create()
    {
        return view('admin.guru.create');
    }

    // SIMPAN USER (GURU / ADMIN / KEPSEK)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'      => 'required|string|unique:users,nip|max:50',
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'no_telp'  => 'nullable|string|max:20',
            'alamat'   => 'nullable|string|max:500',
            'hak_cuti' => 'required|integer|min:0|max:30',
            'role'     => 'required|in:guru,kepala_sekolah,admin',
        ]);

        // HASH PASSWORD
        $validated['password'] = Hash::make($validated['password']);

        // LOGIKA ROLE
        if ($validated['role'] != 'guru') {
            $validated['hak_cuti'] = 0;
        }

        $validated['is_active'] = true;

        User::create($validated);

        return redirect()->route('admin.guru.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    // DETAIL USER
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

    // FORM EDIT
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.guru.edit', compact('user'));
    }

    // UPDATE USER
    public function update(Request $request, string $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'nip'       => ['required', 'string', 'max:50', Rule::unique('users', 'nip')->ignore($user->id)],
        'nama'      => 'required|string|max:255',
        'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        'no_telp'   => 'nullable|string|max:20',
        'alamat'    => 'nullable|string|max:500',
        'jabatan'   => 'nullable|string|max:255',
        'role'      => 'required|in:guru,admin,kepala_sekolah',
        'is_active' => 'required|boolean',
    ]);

    // VALIDASI KHUSUS GURU
    if ($request->role == 'guru') {
        $validated['hak_cuti'] = $request->hak_cuti ?? $user->hak_cuti ?? 12;
    } else {
        $validated['hak_cuti'] = 0;
    }

    // PASSWORD OPTIONAL
    if ($request->filled('password')) {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'       => 'Kata sandi minimal 8 karakter.',
            'password.required'  => 'Kata sandi tidak boleh kosong.',
        ]);

        $validated['password'] = Hash::make($request->password);
    }

    $user->update($validated);

    return redirect()->route('admin.guru.index')
        ->with('success', 'User berhasil diperbarui.');
}

    // NONAKTIFKAN USER
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

        $user->delete(); // ganti dari update is_active ke delete

        return redirect()->route('admin.guru.index')
            ->with('success', 'User berhasil dihapus.');
    }

    // RESET HAK CUTI (KHUSUS GURU)
    public function resetHakCuti(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role != 'guru') {
            return back()->with('error', 'Hanya guru yang memiliki hak cuti.');
        }

        $user->resetHakCuti();

        return back()->with(
            'success',
            "Hak cuti {$user->nama} berhasil direset menjadi 12 hari."
        );
    }
}
