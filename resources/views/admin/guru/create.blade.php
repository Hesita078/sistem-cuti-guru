@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@push('styles')
<style>
    /* ── Card ── */
    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 16px rgba(0,0,0,.07);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #4f6ef7 0%, #6c8aff 100%);
        color: #fff;
        font-weight: 600;
        font-size: .95rem;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: .5rem;
        letter-spacing: .01em;
        border-bottom: none;
    }

    .card-header i { font-size: 1.1rem; }

    .card-body { padding: 2rem 1.75rem; background: #fff; }

    /* ── Section heading ── */
    .section-heading {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #94a3b8;
        margin: 0 0 1.25rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .section-heading::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e8edf5;
    }

    /* ── Form grid ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0 1.5rem;
    }
    .form-grid .full { grid-column: 1 / -1; }
    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-grid .full { grid-column: unset; }
    }

    /* ── Labels ── */
    .form-label {
        font-size: .8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .45rem;
        display: flex;
        align-items: center;
        gap: .2rem;
    }
    .req { color: #ef4444; font-size: .75rem; }

    /* ── Inputs ── */
    .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: .6rem .9rem;
        font-size: .875rem;
        color: #1e293b;
        background: #f8fafc;
        transition: border-color .2s, box-shadow .2s, background .2s;
        width: 100%;
    }
    .form-control:focus {
        outline: none;
        border-color: #4f6ef7;
        box-shadow: 0 0 0 3px rgba(79,110,247,.12);
        background: #fff;
    }
    .form-control.is-invalid { border-color: #ef4444; }
    .form-control[readonly] { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }

    select.form-control { cursor: pointer; appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .75rem center;
        padding-right: 2.25rem;
    }

    textarea.form-control { resize: vertical; min-height: 90px; }

    /* ── Input with icon ── */
    .input-wrap { position: relative; }
    .input-wrap .form-control { padding-right: 2.8rem; }
    .input-icon {
        position: absolute;
        right: .75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-size: 1rem;
        line-height: 1;
        transition: color .15s;
    }
    .input-icon:hover { color: #4f6ef7; }

    /* ── Validation ── */
    .invalid-feedback {
        font-size: .775rem;
        color: #ef4444;
        margin-top: .35rem;
        display: flex;
        align-items: center;
        gap: .25rem;
    }
    .form-hint {
        font-size: .75rem;
        color: #94a3b8;
        margin-top: .3rem;
    }

    /* ── Divider ── */
    .form-divider {
        grid-column: 1 / -1;
        border: none;
        border-top: 1.5px dashed #e8edf5;
        margin: .5rem 0 1.25rem;
    }

    /* ── Actions ── */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f5f9;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .6rem 1.4rem;
        border-radius: 10px;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: background .2s, box-shadow .2s, transform .1s;
    }
    .btn:active { transform: scale(.97); }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }
    .btn-secondary:hover { background: #e2e8f0; color: #1e293b; }

    .btn-primary {
        background: linear-gradient(135deg, #4f6ef7 0%, #6c8aff 100%);
        color: #fff;
        box-shadow: 0 4px 14px rgba(79,110,247,.35);
    }
    .btn-primary:hover { box-shadow: 0 6px 18px rgba(79,110,247,.45); }

    /* ── Role badge preview ── */
    .role-badges { display: flex; gap: .5rem; flex-wrap: wrap; margin-top: .5rem; }
    .role-badge {
        font-size: .7rem;
        font-weight: 600;
        padding: .2rem .65rem;
        border-radius: 20px;
        border: 1.5px solid currentColor;
        opacity: .4;
        transition: opacity .2s;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .role-badge.active { opacity: 1; }
    .badge-guru        { color: #059669; border-color: #059669; background: #ecfdf5; }
    .badge-admin       { color: #d97706; border-color: #d97706; background: #fffbeb; }
    .badge-kepala      { color: #7c3aed; border-color: #7c3aed; background: #f5f3ff; }
</style>
@endpush

@section('content')

<div class="card">
    <div class="card-header">
        <i class="ti ti-user-plus"></i> Form Tambah User
    </div>

    <div class="card-body">
        <form action="{{ route('admin.guru.store') }}" method="POST">
            @csrf

            <div class="form-grid">

                {{-- ── Informasi Akun ── --}}
                <div class="full">
                    <p class="section-heading"><i class="ti ti-id-badge"></i> Informasi Identitas</p>
                </div>

                {{-- NIP --}}
                <div class="mb-3">
                    <label class="form-label" for="nip">
                        NIP <span class="req">*</span>
                    </label>
                    <input type="text"
                           id="nip"
                           name="nip"
                           class="form-control @error('nip') is-invalid @enderror"
                           value="{{ old('nip') }}"
                           placeholder="Contoh: 198501012010011001">
                    @error('nip')
                        <div class="invalid-feedback">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="form-label" for="nama">
                        Nama Lengkap <span class="req">*</span>
                    </label>
                    <input type="text"
                           id="nama"
                           name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           value="{{ old('nama') }}"
                           placeholder="Masukkan nama lengkap">
                    @error('nama')
                        <div class="invalid-feedback">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- JABATAN --}}
                <div class="mb-3">
                    <label class="form-label" for="jabatan">Jabatan</label>
                    <input type="text"
                           id="jabatan"
                           name="jabatan"
                           class="form-control @error('jabatan') is-invalid @enderror"
                           value="{{ old('jabatan') }}"
                           placeholder="Contoh: Guru Kelas 5A">
                    @error('jabatan')
                        <div class="invalid-feedback">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- NO TELEPON --}}
                <div class="mb-3">
                    <label class="form-label" for="telepon">No. Telepon</label>
                    <input type="text"
                           id="telepon"
                           name="telepon"
                           class="form-control @error('telepon') is-invalid @enderror"
                           value="{{ old('telepon') }}"
                           placeholder="Contoh: 08123456789">
                    @error('telepon')
                        <div class="invalid-feedback">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <hr class="form-divider">

                {{-- ── Akun & Akses ── --}}
                <div class="full">
                    <p class="section-heading"><i class="ti ti-lock"></i> Akun &amp; Akses</p>
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label" for="email">
                        Email <span class="req">*</span>
                    </label>
                    <div class="input-wrap">
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="contoh@sd-example.sch.id">
                        <i class="ti ti-mail input-icon"></i>
                    </div>
                    @error('email')
                        <div class="invalid-feedback">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- ROLE --}}
                <div class="mb-3">
                    <label class="form-label" for="role">
                        Role <span class="req">*</span>
                    </label>
                    <select id="role"
                            name="role"
                            class="form-control @error('role') is-invalid @enderror">
                        <option value="">-- Pilih Role --</option>
                        <option value="guru"           {{ old('role') == 'guru'           ? 'selected' : '' }}>Guru</option>
                        <option value="admin"          {{ old('role') == 'admin'          ? 'selected' : '' }}>Admin</option>
                        <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                    </select>
                    <div class="role-badges">
                        <span class="role-badge badge-guru"   data-val="guru">Guru</span>
                        <span class="role-badge badge-admin"  data-val="admin">Admin</span>
                        <span class="role-badge badge-kepala" data-val="kepala_sekolah">Kepala Sekolah</span>
                    </div>
                    @error('role')
                        <div class="invalid-feedback">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label" for="password">
                        Password <span class="req">*</span>
                    </label>
                    <div class="input-wrap">
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Minimal 8 karakter">
                        <button type="button" class="input-icon" onclick="togglePassword('password','icon-pw')">
                            <i class="ti ti-eye" id="icon-pw"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="form-hint">Minimal 8 karakter, kombinasi huruf &amp; angka</div>
                </div>

                {{-- KONFIRMASI PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label" for="password_confirmation">
                        Konfirmasi Password <span class="req">*</span>
                    </label>
                    <div class="input-wrap">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Ulangi password">
                        <button type="button" class="input-icon" onclick="togglePassword('password_confirmation','icon-kpw')">
                            <i class="ti ti-eye" id="icon-kpw"></i>
                        </button>
                    </div>
                </div>

                <hr class="form-divider">

                {{-- ── Cuti ── --}}
                <div class="full">
                    <p class="section-heading"><i class="ti ti-calendar-off"></i> Hak Cuti</p>
                </div>

                {{-- HAK CUTI --}}
                <div class="mb-3">
                    <label class="form-label" for="hak_cuti_tahunan">Hak Cuti Tahunan</label>
                    <input type="number"
                           id="hak_cuti_tahunan"
                           name="hak_cuti_tahunan"
                           class="form-control @error('hak_cuti_tahunan') is-invalid @enderror"
                           value="{{ old('hak_cuti_tahunan', 12) }}"
                           min="0" max="30">
                    @error('hak_cuti_tahunan')
                        <div class="invalid-feedback">
                            <i class="ti ti-alert-circle"></i> {{ $message }}
                        </div>
                    @enderror
                    <div class="form-hint">Jumlah hari cuti tahunan yang diberikan</div>
                </div>

            </div>{{-- /form-grid --}}

            <div class="form-actions">
                <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy"></i> Simpan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    /* ── Toggle password visibility ── */
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        if (field.type === 'password') {
            field.type     = 'text';
            icon.className = 'ti ti-eye-off';
        } else {
            field.type     = 'password';
            icon.className = 'ti ti-eye';
        }
    }

    /* ── Role badge sync ── */
    const roleSelect = document.getElementById('role');
    const badges     = document.querySelectorAll('.role-badge');

    function syncBadges(val) {
        badges.forEach(b => b.classList.toggle('active', b.dataset.val === val));
    }

    badges.forEach(b => {
        b.addEventListener('click', () => {
            roleSelect.value = b.dataset.val;
            roleSelect.dispatchEvent(new Event('change'));
        });
    });

    /* ── Hak cuti logic ── */
    const hakCuti = document.getElementById('hak_cuti_tahunan');

    function toggleCuti() {
        if (roleSelect.value === 'guru') {
            hakCuti.removeAttribute('readonly');
            if (!hakCuti.dataset.userEdited) hakCuti.value = 12;
        } else {
            hakCuti.value = 0;
            hakCuti.setAttribute('readonly', true);
        }
        syncBadges(roleSelect.value);
    }

    hakCuti.addEventListener('input',  () => { hakCuti.dataset.userEdited = '1'; });
    roleSelect.addEventListener('change', () => { delete hakCuti.dataset.userEdited; toggleCuti(); });
    window.addEventListener('load', () => {
        toggleCuti();
        syncBadges(roleSelect.value);
    });
</script>
@endpush
