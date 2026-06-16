@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Info Profil -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Informasi Profil</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   name="nama"
                                   value="{{ old('nama', auth()->user()->nama) }}"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email', auth()->user()->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text"
                                   class="form-control @error('nip') is-invalid @enderror"
                                   name="nip"
                                   value="{{ old('nip', auth()->user()->nip) }}">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text"
                                   class="form-control @error('no_telp') is-invalid @enderror"
                                   name="no_telp"
                                   value="{{ old('no_telp', auth()->user()->no_telp) }}">
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                      name="alamat"
                                      rows="3">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ auth()->user()->role }}"
                                   disabled>
                        </div>

                        @if(auth()->user()->isGuru())
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hak Cuti</label>
                                <input type="text"
                                       class="form-control"
                                       value="{{ auth()->user()->hak_cuti_tahunan }} hari"
                                       disabled>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Ubah Password -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lock me-2"></i>Ubah Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Lama <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('password_lama') is-invalid @enderror"
                               name="password_lama"
                               required>
                        @error('password_lama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('password_baru') is-invalid @enderror"
                               name="password_baru"
                               required>
                        <small class="text-muted">Minimal 8 karakter</small>
                        @error('password_baru')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('password_baru_confirmation') is-invalid @enderror"
                               name="password_baru_confirmation"
                               required>
                        @error('password_baru_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-key me-2"></i>Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
