@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')

<!-- HEADER USER -->
<div class="card mb-4 shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ $user->nama }}</h5>
            <small class="text-muted">{{ $user->email }}</small>
        </div>

        <div>
            @if($user->role == 'guru')
                <span class="badge bg-primary">Guru</span>
            @elseif($user->role == 'admin')
                <span class="badge bg-dark">Admin</span>
            @else
                <span class="badge bg-success">Kepala Sekolah</span>
            @endif
        </div>
    </div>
</div>

<!-- FORM -->
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header fw-bold">
                Form Edit Data
            </div>

            <div class="card-body">
                <form action="{{ route('admin.guru.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- NIP -->
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip"
                            class="form-control @error('nip') is-invalid @enderror"
                            value="{{ old('nip', $user->nip) }}">
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nama -->
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama"
                            class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $user->nama) }}">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label">Password (Opsional)</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti password</small>
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="role"
                            class="form-control @error('role') is-invalid @enderror">
                            <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kepala_sekolah" {{ $user->role == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        </select>
                    </div>

                    <!-- Telepon -->
                    <div class="mb-3">
                        <label class="form-label">No Telepon</label>
                        <input type="text" name="telepon"
                            class="form-control"
                            value="{{ old('telepon', $user->telepon) }}">
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat"
                            class="form-control">{{ old('alamat', $user->alamat) }}</textarea>
                    </div>

                    <!-- Hak Cuti -->
                    <div class="mb-3">
                        <label class="form-label">Hak Cuti</label>
                        <input type="number" id="hak_cuti" name="hak_cuti_tahunan"
                            class="form-control"
                            value="{{ old('hak_cuti_tahunan', $user->hak_cuti_tahunan) }}">
                    </div>

                    <!-- Sisa Cuti -->
                    <div class="mb-3">
                        <label class="form-label">Sisa Cuti</label>
                        <input type="number" name="sisa_hak_cuti"
                            class="form-control"
                            value="{{ old('sisa_hak_cuti', $user->sisa_hak_cuti) }}">
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <!-- BUTTON -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
