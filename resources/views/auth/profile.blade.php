@extends('layouts.app')

@section('title', 'Profil')
@section('page-title', 'Profil Saya')

@section('content')

<div class="row">

    <!-- ALERT SUCCESS -->
    @if(session('success'))
        <div class="col-12">
            <div class="alert-box alert-box-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- ALERT ERROR -->
    @if($errors->any())
        <div class="col-12">
            <div class="alert-box alert-box-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- ================= INFORMASI PROFIL ================= -->
    <div class="col-lg-6 mb-4">
        <div class="prof-card h-100">

            <div class="prof-card-header">
                <i class="fas fa-user-circle me-2"></i>
                Informasi Profil
            </div>

            <div class="prof-card-body">

                <!-- AVATAR INISIAL -->
                <div class="text-center mb-4">
                    <div class="avatar-circle">
                        @php
                            $nama  = auth()->user()->nama;
                            $parts = explode(' ', trim($nama));
                            $init  = strtoupper(substr($parts[0], 0, 1));
                            if (count($parts) > 1) $init .= strtoupper(substr($parts[1], 0, 1));
                        @endphp
                        {{ $init }}
                    </div>
                    <h5 class="fw-bold mb-1 mt-3" style="color:#1a1f3d;">{{ auth()->user()->nama }}</h5>
                    <span class="role-badge">{{ auth()->user()->role }}</span>
                </div>

                <!-- DATA PROFIL -->
                <div class="profile-info">

                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-user me-2"></i>Nama</span>
                        <span class="info-value">{{ auth()->user()->nama }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-id-card me-2"></i>NIP</span>
                        <span class="info-value">{{ auth()->user()->nip ?? '-' }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-briefcase me-2"></i>Jabatan</span>
                        <span class="info-value">{{ auth()->user()->jabatan ?? '-' }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-phone-alt me-2"></i>No. Telp</span>
                        <span class="info-value">{{ auth()->user()->no_telp ?? '-' }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-envelope me-2"></i>Email</span>
                        <span class="info-value">{{ auth()->user()->email }}</span>
                    </div>

                    <div class="info-item" style="border-bottom:none; padding-bottom:0;">
                        <span class="info-label"><i class="fas fa-user-shield me-2"></i>Role</span>
                        <span class="role-badge">{{ auth()->user()->role }}</span>
                    </div>

                </div>

                <!-- BUTTON EDIT -->
                <div class="mt-4">
                    <button class="prof-btn prof-btn-primary"
                            data-bs-toggle="collapse"
                            data-bs-target="#editForm">
                        <i class="fas fa-edit me-1"></i> Edit Profil
                    </button>
                </div>

                <!-- FORM EDIT -->
                <div class="collapse mt-4" id="editForm">
                    <div class="edit-form-box">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="prof-field">
                                <label>Nama</label>
                                <input type="text" name="nama"
                                       value="{{ old('nama', auth()->user()->nama) }}"
                                       class="prof-input @error('nama') is-invalid @enderror"
                                       required>
                                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="prof-field">
                                <label>Email</label>
                                <input type="email" name="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="prof-input @error('email') is-invalid @enderror"
                                       required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="prof-field">
                                <label>No. Telp</label>
                                <input type="text" name="no_telp"
                                       value="{{ old('no_telp', auth()->user()->no_telp) }}"
                                       class="prof-input">
                            </div>

                            <button class="prof-btn prof-btn-success mt-1">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= UBAH PASSWORD ================= -->
    <div class="col-lg-6 mb-4">
        <div class="prof-card h-100">

            <div class="prof-card-header" style="background:#6c757d;">
                <i class="fas fa-lock me-2"></i>
                Ubah Password
            </div>

            <div class="prof-card-body">

                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="prof-field">
                        <label>Password Baru</label>
                        <div class="prof-input-wrap">
                            <i class="fas fa-key"></i>
                            <input type="password" name="password_baru"
                                   class="prof-input-inner @error('password_baru') is-invalid @enderror"
                                   required>
                        </div>
                        @error('password_baru')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="prof-field">
                        <label>Konfirmasi Password</label>
                        <div class="prof-input-wrap">
                            <i class="fas fa-check-circle"></i>
                            <input type="password" name="password_baru_confirmation"
                                   class="prof-input-inner"
                                   required>
                        </div>
                    </div>

                    <button class="prof-btn prof-btn-success mt-2">
                        <i class="fas fa-save me-1"></i> Simpan Password
                    </button>

                </form>

            </div>
        </div>
    </div>

</div>

<style>

    /* ===== ALERT ===== */
    .alert-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        margin-bottom: 16px;
    }
    .alert-box-success { background: #dff6ea; color: #2f8f5b; }
    .alert-box-error   { background: #ffe5e8; color: #c45f6e; }

    /* ===== CARD ===== */
    .prof-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(74,95,193,.10);
        overflow: hidden;
        transition: .25s;
    }
    .prof-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(74,95,193,.15);
    }

    .prof-card-header {
        background: #4a5fc1;
        color: #fff;
        padding: 16px 24px;
        font-size: 15px;
        font-weight: 600;
    }

    .prof-card-body {
        padding: 28px 24px;
    }

    /* ===== AVATAR ===== */
    .avatar-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4a5fc1, #7b8fe8);
        color: #fff;
        font-size: 30px;
        font-weight: 700;
        letter-spacing: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 8px 24px rgba(74,95,193,.30);
        user-select: none;
    }

    /* ===== BADGE ===== */
    .role-badge {
        display: inline-block;
        background: #e8ecfa;
        color: #4a5fc1;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 20px;
        text-transform: capitalize;
    }

    /* ===== INFO LIST ===== */
    .profile-info { margin-top: 8px; }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 13px 0;
        border-bottom: 1px solid #f0f2fa;
    }

    .info-label {
        font-weight: 600;
        color: #7a80a8;
        font-size: 13px;
    }

    .info-value {
        color: #1a1f3d;
        font-weight: 500;
        font-size: 13px;
        text-align: right;
    }

    /* ===== BUTTONS ===== */
    .prof-btn {
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .15s;
    }
    .prof-btn:hover { transform: translateY(-1px); }

    .prof-btn-primary { background: #4a5fc1; color: #fff; }
    .prof-btn-primary:hover { background: #5a6fd6; }

    .prof-btn-success { background: #2f8f5b; color: #fff; }
    .prof-btn-success:hover { background: #3aaa6e; }

    /* ===== FORM EDIT ===== */
    .edit-form-box {
        background: #f7f9ff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #dce0f0;
    }

    .prof-field {
        margin-bottom: 14px;
    }

    .prof-field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #5a6180;
        margin-bottom: 6px;
    }

    .prof-input {
        width: 100%;
        background: #fff;
        border: 1px solid #dce0f0;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
        color: #1a1f3d;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        box-sizing: border-box;
    }
    .prof-input:focus {
        border-color: #4a5fc1;
        box-shadow: 0 0 0 3px rgba(74,95,193,.10);
    }

    /* Input dengan icon (password) */
    .prof-input-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f7f9ff;
        border: 1px solid #dce0f0;
        border-radius: 10px;
        padding: 0 12px;
        transition: border-color .2s, box-shadow .2s;
    }
    .prof-input-wrap:focus-within {
        border-color: #4a5fc1;
        box-shadow: 0 0 0 3px rgba(74,95,193,.10);
    }
    .prof-input-wrap i {
        font-size: 14px;
        color: #9ba3cc;
        flex-shrink: 0;
    }
    .prof-input-inner {
        border: none;
        background: transparent;
        padding: 10px 0;
        font-size: 13px;
        color: #1a1f3d;
        width: 100%;
        outline: none;
    }

</style>

@endsection
