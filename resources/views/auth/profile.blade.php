@extends('layouts.app')

@section('title', 'Profil')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row">

    <!-- ALERT SUCCESS -->
    @if(session('success'))
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- ALERT ERROR -->
    @if($errors->any())
        <div class="col-12">
            <div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- ================= PROFIL ================= -->
    <div class="col-md-6">
        <div class="card card-outline card-primary shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>Informasi Profil
                </h5>
            </div>

            <div class="card-body">

                <!-- DATA USER -->
                <table class="table table-borderless mb-3">
                    <tr>
                        <th width="150">Nama</th>
                        <td>: {{ auth()->user()->nama }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>: {{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>:
                            <span class="badge bg-info">
                                {{ auth()->user()->role }}
                            </span>
                        </td>
                    </tr>
                </table>

                <!-- BUTTON EDIT -->
                <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#editForm">
                    <i class="fas fa-edit me-1"></i> Edit Profil
                </button>

                <!-- FORM EDIT -->
                <div class="collapse mt-3" id="editForm">
                    <div class="card card-body bg-light border">

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama"
                                       value="{{ old('nama', auth()->user()->nama) }}"
                                       class="form-control @error('nama') is-invalid @enderror"
                                       required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-success btn-sm">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= UBAH PASSWORD ================= -->
    <div class="col-md-6">
        <div class="card card-outline card-secondary shadow-sm h-100">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-lock me-2"></i>Ubah Password
                </h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="password_lama"
                               class="form-control @error('password_lama') is-invalid @enderror"
                               required>
                        @error('password_lama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password_baru"
                               class="form-control @error('password_baru') is-invalid @enderror"
                               required>
                        @error('password_baru')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_baru_confirmation"
                               class="form-control"
                               required>
                    </div>

                    <button class="btn btn-success btn-sm">
                        <i class="fas fa-save me-1"></i> Simpan Password
                    </button>
                </form>

            </div>
        </div>
    </div>

</div>
@endsection
