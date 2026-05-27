@extends('layouts.guest')

@section('title', 'Login - Sistem Pengajuan Cuti Guru')

@section('content')
<div class="login-card">

    {{-- Header --}}
    <div class="login-header">
        <span class="header-icon">🏫</span>
        <h4>Sistem Pengajuan Cuti dan Izin Guru</h4>
        <small>SD Negeri Kincang 01</small>
        <div class="header-divider"></div>
    </div>

    {{-- Form Title --}}
    <p class="login-title">Silakan Login</p>

    {{-- Alert sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Alert error --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-envelope"></i>
                </span>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Masukkan email Anda"
                       required
                       autofocus>
            </div>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>
                <input type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password"
                       id="passwordInput"
                       placeholder="Masukkan password Anda"
                       required>
                <button type="button" class="input-group-text btn-eye" onclick="togglePassword()" title="Tampilkan password">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Ingat Saya --}}
        <div class="mb-2 form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Ingat Saya</label>
        </div>

        {{-- Tombol Login --}}
        <button type="submit" class="btn btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>
            Login
        </button>
    </form>

    <p class="login-footer">Sistem Informasi &copy; {{ date('Y') }} SD Negeri Kincang 01</p>
</div>

@push('scripts')
<script>
    let pwVisible = false;
    function togglePassword() {
        pwVisible = !pwVisible;
        const input = document.getElementById('passwordInput');
        const icon  = document.getElementById('eyeIcon');
        input.type = pwVisible ? 'text' : 'password';
        icon.className = pwVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
    }
</script>
@endpush

@endsection
