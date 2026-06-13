@extends('layouts.guest')

@section('title', 'Login - Sistem Pengajuan Cuti Guru')

@section('content')

<div class="split-card">

    <!-- KIRI: Identitas Sekolah -->
    <div class="panel-left">
        <div class="school-logo">
            <img src="{{ asset('images/logoSD.jpeg') }}" alt="Logo SD Negeri Kincang 01">
        </div>
        <h2>SD Negeri Kincang 01</h2>
        <p>Jl. Marsma TNI Anumerta R. Iswahjudi No.50,<br>Ds. Kincang Wetan,Kec. Jiwan, Kabupaten Madiun,<br>Jawa Timur 63161</p>
        <div class="divider-line"></div>
        <span style="font-size: 16px; font-weight: 600;">Sistem Pengajuan Cuti dan Izin Guru</span>
    </div>

    <!-- KANAN: Form Login -->
    <div class="panel-right">

        <div class="mb-4">
            <h1>Masuk</h1>
            <p class="subtitle">Gunakan akun yang terdaftar</p>
        </div>

        @if(session('success'))
            <div class="msg-box msg-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="msg-box msg-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="field">
                <label>Email</label>
                <div class="input-row">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@sekolah.sch.id" required autofocus>
                </div>
            </div>

            <div class="field">
                <label>Password</label>
                <div class="input-row">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" id="passwordInput"
                           placeholder="••••••••" required>
                    <button type="button" class="eye-btn" onclick="togglePassword()">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="loginBtn">
                <span id="btnText">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </span>
            </button>

        </form>

        <p class="copy">&copy; {{ date('Y') }} SD Negeri Kincang 01</p>

    </div>
</div>

<style>

/* === WARNA UTAMA (sinkron biru) === */
:root {
    --blue-main:   #2d6fe0;   /* biru primer – panel kiri & tombol */
    --blue-dark:   #1a55c4;   /* hover tombol & gradient */
    --blue-deep:   #0f2d72;   /* teks heading */
    --blue-mid:    #3a5699;   /* label */
    --blue-light:  #dce8fa;   /* border input normal */
    --blue-xlight: #eaf1fd;   /* background input */
    --blue-icon:   #6a90d0;   /* ikon & placeholder */
    --blue-focus:  rgba(45, 111, 224, .15);
}

.split-card {
    display: grid;
    grid-template-columns: 1fr 1fr;
    width: 100%;
    max-width: 780px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 12px 40px rgba(26, 85, 196, .15);
}

/* ---- KIRI ---- */
.panel-left {
    background: linear-gradient(160deg, var(--blue-main) 0%, var(--blue-dark) 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 2rem;
    text-align: center;
}

.school-logo {
    width: 130px;
    height: 130px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.25rem;
    filter: drop-shadow(0 4px 12px rgba(0,0,0,0.25));
}

.school-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.panel-left h2 {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    margin: 0 0 8px;
    line-height: 1.4;
}

.panel-left p {
    color: rgba(255,255,255,.75);
    font-size: 12px;
    margin: 0 0 2rem;
    line-height: 1.8;
}

.divider-line {
    width: 100%;
    height: 1px;
    background: rgba(255,255,255,.25);
    margin-bottom: 1.25rem;
}

.panel-left span {
    color: rgba(255,255,255,.90);
    font-size: 13px;
    font-weight: 500;
}

/* ---- KANAN ---- */
.panel-right {
    background: #fff;
    padding: 2.5rem 2rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.panel-right h1 {
    font-size: 22px;
    font-weight: 700;
    color: var(--blue-deep);
    margin: 0 0 4px;
}

.subtitle {
    font-size: 13px;
    color: var(--blue-icon);
    margin: 0;
}

.msg-box {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 16px;
}

.msg-success { background: #dff6ea; color: #2f8f5b; }
.msg-error   { background: #ffe5e8; color: #c45f6e; }

.field {
    margin-bottom: 14px;
}

.field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: var(--blue-mid);
    margin-bottom: 6px;
    letter-spacing: .2px;
}

/* === KOTAK INPUT – FULL BIRU === */
.input-row {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--blue-xlight);     /* latar biru sangat muda */
    border: 1.5px solid var(--blue-light); /* border biru muda */
    border-radius: 10px;
    padding: 0 12px;
    transition: border-color .2s, box-shadow .2s, background .2s;
}

.input-row:focus-within {
    border-color: var(--blue-main);
    background: #ddeaf9;               /* sedikit lebih biru saat fokus */
    box-shadow: 0 0 0 3px var(--blue-focus);
}

.input-row i {
    font-size: 15px;
    color: var(--blue-icon);
    flex-shrink: 0;
}

.input-row input {
    border: none;
    background: transparent;
    padding: 10px 0;
    font-size: 13px;
    color: var(--blue-deep);
    width: 100%;
    outline: none;
    flex: 1;
}

.input-row input::placeholder { color: #a8c0e8; }

.eye-btn {
    border: none;
    background: transparent;
    color: var(--blue-icon);
    padding: 0;
    cursor: pointer;
    line-height: 1;
    font-size: 15px;
}

.eye-btn:hover { color: var(--blue-main); }

/* === TOMBOL SUBMIT – SINKRON === */
.btn-submit {
    width: 100%;
    padding: 12px;
    background: var(--blue-main);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s, transform .15s, box-shadow .2s;
    box-shadow: 0 4px 14px rgba(45, 111, 224, .35);
    letter-spacing: .2px;
}

.btn-submit:hover {
    background: var(--blue-dark);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(26, 85, 196, .40);
}

.btn-submit:disabled {
    opacity: .75;
    cursor: not-allowed;
    transform: none;
}

.copy {
    text-align: center;
    font-size: 11px;
    color: #a8bfea;
    margin: 1.5rem 0 0;
}

@media (max-width: 600px) {
    .split-card { grid-template-columns: 1fr; }
    .panel-left { padding: 2rem 1.5rem; }
    .panel-right { padding: 2rem 1.5rem; }
}

</style>

@push('scripts')
<script>
    let passVisible = false;

    function togglePassword() {
        passVisible = !passVisible;
        document.getElementById('passwordInput').type = passVisible ? 'text' : 'password';
        document.getElementById('eyeIcon').className = passVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
    }

    document.getElementById('loginForm').addEventListener('submit', function () {
        const btn  = document.getElementById('loginBtn');
        const text = document.getElementById('btnText');
        btn.disabled = true;
        text.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });
</script>
@endpush

@endsection
