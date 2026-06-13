@extends('layouts.app')

@section('title', 'Edit Data Guru')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Edit Data Guru</h4>
            <small class="text-muted">Perbarui informasi guru</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.data-guru.show', $guru->id) }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
            </a>
            <a href="{{ route('admin.data-guru.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-list me-1"></i> Semua Guru
            </a>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terdapat kesalahan input:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person-gear me-1 text-warning"></i> Form Edit Guru
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.data-guru.update', $guru->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- NIP --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIP <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="nip"
                                    class="form-control @error('nip') is-invalid @enderror"
                                    value="{{ old('nip', $guru->nip) }}"
                                    placeholder="Masukkan NIP"
                                    required
                                >
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nama --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $guru->nama) }}"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                >
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Jabatan --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jabatan</label>
                                <input
                                    type="text"
                                    name="jabatan"
                                    class="form-control @error('jabatan') is-invalid @enderror"
                                    value="{{ old('jabatan', $guru->jabatan) }}"
                                    placeholder="Contoh: Guru Matematika"
                                >
                                @error('jabatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- No Telp --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">No. Telepon</label>
                                <input
                                    type="text"
                                    name="no_telp"
                                    class="form-control @error('no_telp') is-invalid @enderror"
                                    value="{{ old('no_telp', $guru->no_telp) }}"
                                    placeholder="Contoh: 08123456789"
                                >
                                @error('no_telp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Hak Cuti --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hak Cuti (hari/tahun) <span class="text-danger">*</span></label>
                                <input
                                    type="number"
                                    name="hak_cuti"
                                    class="form-control @error('hak_cuti') is-invalid @enderror"
                                    value="{{ old('hak_cuti', $guru->hak_cuti) }}"
                                    min="0"
                                    placeholder="Contoh: 12"
                                    required
                                >
                                @error('hak_cuti')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status Aktif --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                                    <option value="1" {{ old('is_active', $guru->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_active', $guru->is_active) == 0 ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea
                                    name="alamat"
                                    class="form-control @error('alamat') is-invalid @enderror"
                                    rows="3"
                                    placeholder="Masukkan alamat lengkap"
                                >{{ old('alamat', $guru->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>{{-- end row --}}

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.data-guru.show', $guru->id) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection
