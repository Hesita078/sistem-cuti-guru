@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')

<div class="pce-wrap">

    {{-- USER BANNER --}}
    <div class="pce-banner">
        <div class="pce-banner-left">
            <div class="pce-avatar">
                {{ collect(explode(' ', $user->nama))->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('') }}
            </div>
            <div>
                <div class="pce-banner-name">{{ $user->nama }}</div>
                <div class="pce-banner-email">{{ $user->email }}</div>
            </div>
        </div>
        <div>
            @if($user->role == 'admin')
                <span class="pce-badge pce-badge-danger">Admin</span>
            @elseif($user->role == 'kepala_sekolah')
                <span class="pce-badge pce-badge-secondary">Kepala Sekolah</span>
            @else
                <span class="pce-badge pce-badge-blue">Guru</span>
            @endif
        </div>
    </div>

    {{-- FORM CARD --}}
    <div class="pce-card">

        <div class="pce-card-header">
            <div class="pce-header-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <div class="pce-card-title">Form Edit Data User</div>
                <div class="pce-card-sub">Perbarui informasi user di bawah ini</div>
            </div>
        </div>

        <div class="pce-card-body">

            <form action="{{ route('admin.guru.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- SECTION: Informasi Akun --}}
                <div class="pce-section-label">Informasi Akun</div>

                <div class="pce-row-2">
                    {{-- NIP --}}
                    <div class="pce-field">
                        <label class="pce-label">NIP <span class="pce-required">*</span></label>
                        <div class="pce-input-wrap @error('nip') pce-input-error @enderror">
                            <i class="bi bi-card-text pce-input-icon"></i>
                            <input type="text" name="nip" class="pce-input"
                                   value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai">
                        </div>
                        @error('nip')
                            <div class="pce-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama --}}
                    <div class="pce-field">
                        <label class="pce-label">Nama Lengkap <span class="pce-required">*</span></label>
                        <div class="pce-input-wrap @error('nama') pce-input-error @enderror">
                            <i class="bi bi-person pce-input-icon"></i>
                            <input type="text" name="nama" class="pce-input"
                                   value="{{ old('nama', $user->nama) }}" placeholder="Nama lengkap">
                        </div>
                        @error('nama')
                            <div class="pce-error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="pce-row-2">
                    {{-- Email --}}
                    <div class="pce-field">
                        <label class="pce-label">Email <span class="pce-required">*</span></label>
                        <div class="pce-input-wrap @error('email') pce-input-error @enderror">
                            <i class="bi bi-envelope pce-input-icon"></i>
                            <input type="email" name="email" class="pce-input"
                                   value="{{ old('email', $user->email) }}" placeholder="email@sekolah.sch.id">
                        </div>
                        @error('email')
                            <div class="pce-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                   {{-- No Telepon --}}
                   <div class="pce-field">
                    <label class="pce-label">No Telepon</label>
                    <div class="pce-input-wrap">
                        <i class="bi bi-telephone pce-input-icon"></i>
                        <input type="text" name="no_telp" class="pce-input"
                               value="{{ old('no_telp', $user->no_telp) }}" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>

            {{-- Jabatan --}}
            <div class="pce-field">
                <label class="pce-label">Jabatan</label>
                <div class="pce-input-wrap">
                    <i class="bi bi-briefcase pce-input-icon"></i>
                    <input type="text" name="jabatan" class="pce-input"
                           value="{{ old('jabatan', $user->jabatan) }}" placeholder="Contoh: Guru Kelas 5A">
                </div>
            </div>

            {{-- SECTION: Keamanan --}}

                {{-- SECTION: Keamanan --}}
                <div class="pce-section-label" style="margin-top:8px;">Keamanan</div>

                <div class="pce-row-2">
                    {{-- Password --}}
                    <div class="pce-field">
                        <label class="pce-label">Kata Sandi Baru <span class="pce-optional">(opsional)</span></label>
                        <div class="pce-input-wrap @error('password') pce-input-error @enderror">
                            <i class="bi bi-lock pce-input-icon"></i>
                            <input type="password" name="password" id="passwordInput"
                                class="pce-input" placeholder="••••••••"
                                autocomplete="new-password" value="">
                            <button type="button" class="pce-eye-btn" onclick="togglePassword('passwordInput', 'eyeIcon1')">
                                <i class="bi bi-eye" id="eyeIcon1"></i>
                            </button>
                        </div>
                        <div class="pce-hint">Kosongkan jika tidak ingin mengganti kata sandi</div>
                        @error('password')
                            <div class="pce-error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="pce-field">
                        <label class="pce-label">Konfirmasi Kata Sandi <span class="pce-optional">(opsional)</span></label>
                        <div class="pce-input-wrap">
                            <i class="bi bi-lock-fill pce-input-icon"></i>
                            <input type="password" name="password_confirmation" id="passwordConfirm"
                                class="pce-input" placeholder="••••••••"
                                autocomplete="new-password" value="">
                            <button type="button" class="pce-eye-btn" onclick="togglePassword('passwordConfirm', 'eyeIcon2')">
                                <i class="bi bi-eye" id="eyeIcon2"></i>
                            </button>
                        </div>
                        <div class="pce-hint">Ulangi kata sandi baru</div>
                    </div>
                </div>

                {{-- SECTION: Informasi Lainnya --}}
                <div class="pce-section-label" style="margin-top:8px;">Informasi Lainnya</div>

                <div class="pce-row-2">
                    {{-- Role --}}
                    <div class="pce-field">
                        <label class="pce-label">Peran <span class="pce-required">*</span></label>
                        <div class="pce-input-wrap">
                            <i class="bi bi-shield-check pce-input-icon"></i>
                            <select name="role" id="role" class="pce-input pce-select">
                                <option value="guru"           {{ $user->role == 'guru'           ? 'selected' : '' }}>Guru</option>
                                <option value="admin"          {{ $user->role == 'admin'          ? 'selected' : '' }}>Admin</option>
                                <option value="kepala_sekolah" {{ $user->role == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="pce-field">
                        <label class="pce-label">Status Akun</label>
                        <div class="pce-input-wrap">
                            <i class="bi bi-toggle-on pce-input-icon"></i>
                            <select name="is_active" class="pce-input pce-select">
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- SECTION: Hak Cuti (hanya guru) --}}
                <div id="seksi-cuti" style="{{ $user->role != 'guru' ? 'display:none;' : '' }}">
                    <div class="pce-section-label" style="margin-top:8px;">Hak Cuti</div>
                    <div class="pce-row-2">
                        <div class="pce-field">
                            <label class="pce-label">Hak Cuti Tahunan</label>
                            <div class="pce-input-wrap">
                                <i class="bi bi-calendar-check pce-input-icon"></i>
                                <input type="number" name="hak_cuti" class="pce-input" min="0"
                                       value="{{ old('hak_cuti', $user->hak_cuti) }}"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="pce-actions">
                    <a href="{{ route('admin.guru.index') }}" class="pce-btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="pce-btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<style>
.pce-wrap {
    max-width: 760px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 18px;
}
.pce-banner {
    background: linear-gradient(135deg, #4a5fc1 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 20px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: 0 4px 20px rgba(74,95,193,.28);
    flex-wrap: wrap;
}
.pce-banner-left { display: flex; align-items: center; gap: 14px; }
.pce-avatar {
    width: 48px; height: 48px;
    border-radius: 50%;
    background: rgba(255,255,255,.22);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 700;
    flex-shrink: 0;
}
.pce-banner-name  { font-size: 16px; font-weight: 700; color: #fff; }
.pce-banner-email { font-size: 12px; color: rgba(255,255,255,.75); margin-top: 2px; }
.pce-badge { display: inline-flex; align-items: center; padding: 5px 14px; border-radius: 999px; font-size: 12px; font-weight: 600; }
.pce-badge-blue      { background: rgba(255,255,255,.22); color: #fff; }
.pce-badge-danger    { background: rgba(255,255,255,.22); color: #fff; }
.pce-badge-secondary { background: rgba(255,255,255,.22); color: #fff; }
.pce-card { background: #fff; border-radius: 20px; border: 1px solid #dce0f0; box-shadow: 0 4px 24px rgba(30,40,90,.07); overflow: hidden; }
.pce-card-header { display: flex; align-items: center; gap: 14px; padding: 20px 28px; border-bottom: 1px solid #eef0f8; background: #fafbff; }
.pce-header-icon { width: 42px; height: 42px; border-radius: 12px; background: #eef1fb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.pce-header-icon i { font-size: 18px; color: #4a5fc1; }
.pce-card-title { font-size: 15px; font-weight: 700; color: #1a1f3d; }
.pce-card-sub   { font-size: 12px; color: #7a80a8; margin-top: 1px; }
.pce-card-body  { padding: 24px 28px; }
.pce-section-label { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #9ba3cc; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid #eef0f8; }
.pce-field { margin-bottom: 16px; }
.pce-label { display: block; font-size: 12.5px; font-weight: 600; color: #5a6180; margin-bottom: 7px; }
.pce-required { color: #c45f6e; }
.pce-optional { color: #9ba3cc; font-weight: 400; font-size: 11px; }
.pce-input-wrap { position: relative; display: flex; align-items: center; background: #f7f9ff; border: 1px solid #dce0f0; border-radius: 10px; transition: border-color .2s, box-shadow .2s; }
.pce-input-wrap:focus-within { border-color: #4a5fc1; box-shadow: 0 0 0 3px rgba(74,95,193,.10); }
.pce-input-wrap.pce-input-error { border-color: #c45f6e; }
.pce-input-icon { position: absolute; left: 13px; font-size: 14px; color: #9ba3cc; pointer-events: none; top: 50%; transform: translateY(-50%); }
.pce-input { width: 100%; background: transparent; border: none; outline: none; padding: 10px 12px 10px 38px; font-size: 13.5px; color: #1a1f3d; font-family: inherit; }
.pce-input::placeholder { color: #c0c5de; }
.pce-select { cursor: pointer; }
.pce-hint      { font-size: 11.5px; color: #9ba3cc; margin-top: 5px; }
.pce-error-msg { font-size: 12px; color: #c45f6e; margin-top: 5px; }
.pce-eye-btn { position: absolute; right: 12px; border: none; background: transparent; color: #9ba3cc; cursor: pointer; font-size: 15px; line-height: 1; }
.pce-eye-btn:hover { color: #4a5fc1; }
.pce-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.pce-actions { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 8px; flex-wrap: wrap; }
.pce-btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 24px; background: #4a5fc1; color: #fff; border: none; border-radius: 10px; font-size: 13.5px; font-weight: 600; cursor: pointer; text-decoration: none; font-family: inherit; transition: background .2s, transform .15s; }
.pce-btn-primary:hover { background: #5a6fd6; transform: translateY(-1px); color: #fff; }
.pce-btn-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #f7f9ff; color: #5a6090; border: 1px solid #dce0f0; border-radius: 10px; font-size: 13.5px; font-weight: 600; cursor: pointer; text-decoration: none; transition: background .2s; }
.pce-btn-secondary:hover { background: #eef0f8; color: #1a1f3d; }
@media (max-width: 600px) {
    .pce-row-2 { grid-template-columns: 1fr; }
    .pce-card-body { padding: 18px; }
    .pce-card-header { padding: 16px 18px; }
    .pce-banner { padding: 18px 20px; }
}
</style>

@endsection

@push('scripts')
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}

// Kosongkan field password saat halaman dimuat
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('passwordInput').value = '';
    document.getElementById('passwordConfirm').value = '';
});

document.getElementById('role').addEventListener('change', function () {
    const seksi = document.getElementById('seksi-cuti');
    seksi.style.display = this.value === 'guru' ? 'block' : 'none';
});
</script>
@endpush
