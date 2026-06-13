@extends('layouts.app')

@section('title', 'Ajukan Cuti Baru')
@section('page-title', 'Ajukan Cuti Baru')

@section('content')

{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">

<style>
/* ========================
   FLASH MESSAGES
======================== */
.pcr-msg {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 14px;
}
.pcr-msg i { font-size: 15px; flex-shrink: 0; margin-top: 1px; }
.pcr-msg-success { background: #dff6ea; color: #2f8f5b; border: 1px solid #b6e8d0; }
.pcr-msg-error   { background: #ffe5e8; color: #c45f6e; border: 1px solid #f5c0c8; }

/* ========================
   LAYOUT WRAPPER
======================== */
.pcr-wrap {
    max-width: 720px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* ========================
   HAK CUTI BANNER
======================== */
.pcr-banner {
    background: linear-gradient(135deg, #4a5fc1 0%, #7b8fe8 100%);
    border-radius: 16px;
    padding: 1.375rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    box-shadow: 0 8px 24px rgba(74,95,193,.25);
}
.pcr-banner-left  { display: flex; align-items: center; gap: 12px; }
.pcr-banner-icon  {
    width: 46px; height: 46px; border-radius: 12px;
    background: rgba(255,255,255,.18);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.pcr-banner-icon i  { font-size: 20px; color: #fff; }
.pcr-banner-title   { font-size: 14px; font-weight: 700; color: #fff; }
.pcr-banner-sub     { font-size: 12px; color: rgba(255,255,255,.75); margin-top: 2px; }
.pcr-banner-right   { display: flex; align-items: baseline; gap: 5px; }
.pcr-banner-num     { font-size: 40px; font-weight: 800; color: #fff; line-height: 1; }
.pcr-banner-unit    { font-size: 13px; color: rgba(255,255,255,.80); font-weight: 500; }

/* ========================
   FORM CARD
======================== */
.pcr-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e6f0;
    overflow: hidden;
}
.pcr-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #eef0f8;
    background: #f7f9ff;
}
.pcr-header-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: #eef1fb;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.pcr-header-icon i { font-size: 17px; color: #4a5fc1; }
.pcr-card-title { font-size: 14px; font-weight: 700; color: #1a1f3d; margin: 0; }
.pcr-card-sub   { font-size: 12px; color: #7a80a8; margin-top: 2px; }
.pcr-card-body  { padding: 1.5rem; }

/* ========================
   FORM FIELDS
======================== */
.pcr-field { margin-bottom: 16px; }
.pcr-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #5a6180;
    margin-bottom: 6px;
}
.pcr-required { color: #c45f6e; }

.pcr-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #dce0f0;
    border-radius: 10px;
    transition: border-color .2s, box-shadow .2s;
}
.pcr-input-wrap:focus-within {
    border-color: #4a5fc1;
    box-shadow: 0 0 0 3px rgba(74,95,193,.10);
}
.pcr-input-icon {
    position: absolute;
    left: 12px;
    font-size: 13px;
    color: #b0b8d8;
    pointer-events: none;
    top: 50%;
    transform: translateY(-50%);
}
.pcr-input {
    width: 100%;
    background: transparent;
    border: none;
    outline: none;
    padding: 9px 12px 9px 36px;
    font-size: 13px;
    color: #1a1f3d;
    font-family: inherit;
}
.pcr-input::placeholder { color: #c0c5de; }
.pcr-select { cursor: pointer; }
.pcr-textarea-wrap { align-items: flex-start; }
.pcr-textarea { resize: vertical; min-height: 96px; padding-top: 10px; }
.pcr-hint      { font-size: 11.5px; color: #9ba3cc; margin-top: 5px; }
.pcr-error-msg { font-size: 12px; color: #c45f6e; margin-top: 4px; min-height: 16px; }

.pcr-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

/* ========================
   FILE INPUT
======================== */
.pcr-file-wrap {
    background: #fff;
    border: 1.5px dashed #c5d7fa;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: border-color .2s;
}
.pcr-file-wrap:hover { border-color: #4a5fc1; }
.pcr-file-wrap i { font-size: 18px; color: #4a5fc1; flex-shrink: 0; }
.pcr-file-wrap input[type="file"] {
    border: none; background: transparent;
    font-size: 13px; color: #5a6090;
    width: 100%; outline: none; cursor: pointer;
}

/* ========================
   HARI INFO BOX
======================== */
.pcr-info-hari {
    background: #eef1fb;
    border: 1px solid #c5d7fa;
    border-radius: 10px;
    padding: 11px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.pcr-info-hari-left  { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #4a5fc1; }
.pcr-info-hari-left i { font-size: 15px; }
.pcr-info-hari-right { font-size: 12px; color: #7a80a8; }

/* ========================
   ALERT BOXES
======================== */
.pcr-alert {
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 13px;
    margin-bottom: 16px;
}
.pcr-alert-icon { font-size: 16px; flex-shrink: 0; margin-top: 1px; }
.pcr-alert-title { font-weight: 700; margin-bottom: 4px; font-size: 13px; }
.pcr-alert-body  { line-height: 1.7; font-size: 12.5px; }
.pcr-alert-warning  { background: #fff3cd; color: #b45309; border: 1px solid #ffe69c; }
.pcr-alert-warning .pcr-alert-icon  { color: #b45309; }
.pcr-alert-danger   { background: #ffe5e8; color: #c45f6e; border: 1px solid #f5c0c8; }
.pcr-alert-danger .pcr-alert-icon   { color: #c45f6e; }
.pcr-alert-primary  { background: #eef1fb; color: #4a5fc1; border: 1px solid #c5d7fa; }
.pcr-alert-primary .pcr-alert-icon  { color: #4a5fc1; }
.pcr-alert-success  { background: #dff6ea; color: #2f8f5b; border: 1px solid #b6e8d0; }
.pcr-alert-success .pcr-alert-icon  { color: #2f8f5b; }
.pcr-alert-info     { background: #e8ecfa; color: #4a5fc1; border: 1px solid #c5d7fa; }
.pcr-alert-info .pcr-alert-icon     { color: #4a5fc1; }
.pcr-alert-secondary{ background: #f0f2fa; color: #7a80a8; border: 1px solid #dce0f0; }
.pcr-alert-secondary .pcr-alert-icon{ color: #7a80a8; }
.d-none { display: none !important; }

/* ========================
   BUTTONS
======================== */
.pcr-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-top: 8px;
    padding-top: 16px;
    border-top: 1px solid #eef0f8;
    flex-wrap: wrap;
}
.pcr-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 22px;
    background: #4a5fc1;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s, transform .15s;
    font-family: inherit;
    white-space: nowrap;
}
.pcr-btn-primary:hover { background: #5a6fd6; transform: translateY(-1px); color: #fff; }
.pcr-btn-primary:disabled { opacity: .6; cursor: not-allowed; transform: none; }
.pcr-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    background: #f7f9ff;
    color: #5a6090;
    border: 1px solid #dce0f0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s;
    font-family: inherit;
    white-space: nowrap;
}
.pcr-btn-secondary:hover { background: #edf0fc; color: #1a1f3d; text-decoration: none; }

/* ========================
   FLATPICKR CUSTOM
======================== */
.flatpickr-calendar {
    font-family: inherit;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(74,95,193,.15);
    border: 1px solid #dce0f0;
    width: 252px !important;
    font-size: 12px;
    padding: 4px;
}
.flatpickr-days, .dayContainer {
    width: 240px !important;
    min-width: 240px !important;
    max-width: 240px !important;
}
.flatpickr-day {
    max-width: 30px !important;
    height: 30px !important;
    line-height: 30px !important;
    font-size: 12px;
    border-radius: 7px;
    margin: 1px;
}
.flatpickr-day.selected,
.flatpickr-day.selected:hover { background: #4a5fc1; border-color: #4a5fc1; }
.flatpickr-day:hover { background: #eef1fb; }
.flatpickr-months .flatpickr-month {
    background: #4a5fc1;
    border-radius: 9px 9px 0 0;
    height: 36px;
}
.flatpickr-current-month { font-size: 13px; padding-top: 7px; }
.flatpickr-current-month,
.flatpickr-monthDropdown-months,
.flatpickr-current-month .cur-year { color: #fff !important; fill: #fff !important; }
.flatpickr-weekdays { margin: 3px 0 2px; }
.flatpickr-weekday { color: #4a5fc1; font-weight: 700; font-size: 11px; }
.flatpickr-prev-month, .flatpickr-next-month { padding: 7px 10px; }
.flatpickr-prev-month svg, .flatpickr-next-month svg { fill: #fff !important; width: 11px; height: 11px; }
.flatpickr-day.flatpickr-disabled { color: #c45f6e !important; text-decoration: line-through; opacity: .5; }
.flatpickr-input { cursor: pointer; }

/* ========================
   RESPONSIVE
======================== */
@media (max-width: 600px) {
    .pcr-row-2     { grid-template-columns: 1fr; }
    .pcr-card-body { padding: 1rem; }
    .pcr-card-header, .pcr-banner { padding: 1rem; }
    .pcr-banner-num { font-size: 32px; }
}
</style>

{{-- FLASH MESSAGES --}}
@if(session('error'))
<div class="pcr-msg pcr-msg-error">
    <i class="bi bi-exclamation-circle-fill"></i>
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="pcr-msg pcr-msg-success">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="pcr-msg pcr-msg-error">
    <i class="bi bi-exclamation-circle-fill"></i>
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="pcr-wrap">

    {{-- HAK CUTI BANNER --}}
    <div class="pcr-banner">
        <div class="pcr-banner-left">
            <div class="pcr-banner-icon">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <div>
                <div class="pcr-banner-title">Sisa Hak Cuti Tahunan</div>
                <div class="pcr-banner-sub">Pastikan pengajuan sesuai ketentuan</div>
            </div>
        </div>
        <div class="pcr-banner-right">
            <span class="pcr-banner-num">{{ auth()->user()->hak_cuti }}</span>
            <span class="pcr-banner-unit">hari</span>
        </div>
    </div>

    {{-- FORM CARD --}}
    <div class="pcr-card">

        <div class="pcr-card-header">
            <div class="pcr-header-icon">
                <i class="bi bi-file-earmark-plus"></i>
            </div>
            <div>
                <div class="pcr-card-title">Form Pengajuan Cuti</div>
                <div class="pcr-card-sub">Isi semua field yang diperlukan</div>
            </div>
        </div>

        <div class="pcr-card-body">

            <form method="POST"
                  action="{{ route('pengajuan.store') }}"
                  enctype="multipart/form-data"
                  id="formPengajuan"
                  novalidate>
                @csrf

                {{-- ✅ FIX: Hidden input langsung di HTML, bukan dibuat via JS --}}
                <input type="hidden" name="tanggal_mulai" id="hiddenMulai">
                <input type="hidden" name="tanggal_selesai" id="hiddenSelesai">

                {{-- JENIS CUTI --}}
                <div class="pcr-field">
                    <label class="pcr-label">Jenis Cuti <span class="pcr-required">*</span></label>
                    <div class="pcr-input-wrap">
                        <i class="bi bi-tag pcr-input-icon"></i>
                        <select class="pcr-input pcr-select @error('jenis_cuti') is-invalid @enderror"
                                name="jenis_cuti" id="jenis_cuti" required>
                            <option value="">— Pilih Jenis Cuti —</option>
                            <option value="Cuti Tahunan"    {{ old('jenis_cuti') == 'Cuti Tahunan'    ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="Cuti Sakit"      {{ old('jenis_cuti') == 'Cuti Sakit'      ? 'selected' : '' }}>Cuti Sakit</option>
                            <option value="Cuti Melahirkan" {{ old('jenis_cuti') == 'Cuti Melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                            <option value="Cuti Ibadah Haji"{{ old('jenis_cuti') == 'Cuti Ibadah Haji'? 'selected' : '' }}>Cuti Ibadah Haji</option>
                            <option value="Cuti Penting"    {{ old('jenis_cuti') == 'Cuti Penting'    ? 'selected' : '' }}>Cuti Alasan Penting</option>
                        </select>
                    </div>
                </div>

                {{-- INFO DINAMIS JENIS CUTI --}}
                <div id="dynamicInfo"></div>

                {{-- TANGGAL --}}
                <div class="pcr-row-2">
                    <div class="pcr-field">
                        <label class="pcr-label">Tanggal Mulai <span class="pcr-required">*</span></label>
                        <div class="pcr-input-wrap">
                            <i class="bi bi-calendar-event pcr-input-icon"></i>
                            {{-- ✅ Tidak pakai name di sini, sudah ada hidden input di atas --}}
                            <input type="text" class="pcr-input flatpickr-input"
                                   id="tanggalMulai"
                                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
                        </div>
                        <div class="pcr-error-msg" id="errorMulai"></div>
                    </div>
                    <div class="pcr-field">
                        <label class="pcr-label">Tanggal Selesai <span class="pcr-required">*</span></label>
                        <div class="pcr-input-wrap">
                            <i class="bi bi-calendar-event pcr-input-icon"></i>
                            {{-- ✅ Tidak pakai name di sini, sudah ada hidden input di atas --}}
                            <input type="text" class="pcr-input flatpickr-input"
                                   id="tanggalSelesai"
                                   placeholder="dd/mm/yyyy" autocomplete="off" readonly>
                        </div>
                        <div class="pcr-error-msg" id="errorSelesai"></div>
                    </div>
                </div>

                {{-- INFO HARI --}}
                <div id="infoHari" style="display:none;">
                    <div class="pcr-info-hari">
                        <div class="pcr-info-hari-left">
                            <i class="bi bi-clock-history"></i>
                            <span>Jumlah Hari Kerja: <strong id="jumlahHari">0</strong> hari</span>
                        </div>
                        <div class="pcr-info-hari-right">
                            Sisa hak cuti: <strong id="sisaCuti">{{ auth()->user()->hak_cuti }}</strong> hari
                        </div>
                    </div>
                </div>

                {{-- PERINGATAN CUTI BERSAMA --}}
                <div class="pcr-alert pcr-alert-danger d-none" id="alertCutiBersamaRange">
                    <div class="pcr-alert-icon"><i class="bi bi-x-circle-fill"></i></div>
                    <div>
                        <div class="pcr-alert-title">Perhatian: Ada Cuti Bersama di Rentang Ini</div>
                        <div id="pesanCutiBersamaRange" class="pcr-alert-body"></div>
                    </div>
                </div>

                {{-- ALASAN --}}
                <div class="pcr-field">
                    <label class="pcr-label">Alasan Cuti <span class="pcr-required">*</span></label>
                    <div class="pcr-input-wrap pcr-textarea-wrap">
                        <i class="bi bi-chat-left-text pcr-input-icon" style="top:14px;transform:none;"></i>
                        <textarea class="pcr-input pcr-textarea"
                                  name="alasan" rows="4"
                                  placeholder="Jelaskan alasan cuti…" required>{{ old('alasan') }}</textarea>
                    </div>
                    <div class="pcr-hint">Minimal 10 karakter</div>
                </div>

                {{-- FILE DINAMIS --}}
                <div id="dynamicFile"></div>

                {{-- ACTIONS --}}
                <div class="pcr-actions">
                    <a href="{{ route('pengajuan.index') }}" class="pcr-btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="pcr-btn-primary" id="btnSubmit">
                        <i class="bi bi-send-fill"></i> Ajukan Cuti
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/id.min.js"></script>
<script>

const hakCutiAwal = {{ auth()->user()->hak_cuti }};
const tahunIni    = new Date().getFullYear();
let cutiBersamaMap = {};

// ✅ FIX: Langsung ambil dari hidden input yang sudah ada di HTML
const hiddenMulai   = document.getElementById('hiddenMulai');
const hiddenSelesai = document.getElementById('hiddenSelesai');

async function fetchCutiBersama(tahun) {
    try {
        const res  = await fetch(`/api/cuti-bersama?tahun=${tahun}`);
        const data = await res.json();
        cutiBersamaMap = {};
        data.forEach(item => {
            cutiBersamaMap[item.tanggal] = { nama: item.nama, keterangan: item.keterangan };
        });
        fpMulai.redraw();
        fpSelesai.redraw();
    } catch (e) { console.error('Gagal mengambil data cuti bersama:', e); }
}

function isCutiBersama(dateStr)    { return cutiBersamaMap.hasOwnProperty(dateStr); }
function getNamaCutiBersama(dateStr){ return cutiBersamaMap[dateStr]?.nama || 'Cuti Bersama'; }
function formatTanggal(dateStr) {
    return new Date(dateStr + 'T00:00:00').toLocaleDateString('id-ID',
        { weekday:'long', day:'numeric', month:'long', year:'numeric' });
}

const disableFn = function(date) {
    if (date.getDay() === 0 || date.getDay() === 6) return true;
    return isCutiBersama(date.toISOString().split('T')[0]);
};

// ✅ FIX: Flatpickr hanya mengisi hidden input, tidak perlu manipulasi nama attribute
const fpMulai = flatpickr('#tanggalMulai', {
    locale: 'id', dateFormat: 'd/m/Y', minDate: 'today',
    disable: [disableFn],
    onChange(selectedDates) {
        hiddenMulai.value = selectedDates[0]
            ? selectedDates[0].toISOString().split('T')[0]
            : '';
        document.getElementById('errorMulai').textContent = '';
        if (selectedDates[0]) fpSelesai.set('minDate', selectedDates[0]);
        updateHari();
        if (selectedDates[0]) {
            const th = selectedDates[0].getFullYear();
            if (th !== tahunIni) fetchCutiBersama(th);
        }
    },
});

const fpSelesai = flatpickr('#tanggalSelesai', {
    locale: 'id', dateFormat: 'd/m/Y', minDate: 'today',
    disable: [disableFn],
    onChange(selectedDates) {
        hiddenSelesai.value = selectedDates[0]
            ? selectedDates[0].toISOString().split('T')[0]
            : '';
        document.getElementById('errorSelesai').textContent = '';
        updateHari();
    },
});

function hitungHariKerja(start, end) {
    let count = 0, current = new Date(start);
    while (current <= end) {
        const day = current.getDay();
        const dateStr = current.toISOString().split('T')[0];
        if (day !== 0 && day !== 6 && !isCutiBersama(dateStr)) count++;
        current.setDate(current.getDate() + 1);
    }
    return count;
}

function getCutiBersamaDalamRange(start, end) {
    const hasil = []; let current = new Date(start);
    while (current <= end) {
        const dateStr = current.toISOString().split('T')[0];
        if (isCutiBersama(dateStr)) hasil.push({ tanggal: dateStr, nama: getNamaCutiBersama(dateStr) });
        current.setDate(current.getDate() + 1);
    }
    return hasil;
}

function updateHari() {
    const alertRange = document.getElementById('alertCutiBersamaRange');
    const pesanRange = document.getElementById('pesanCutiBersamaRange');
    alertRange.classList.add('d-none');

    if (!hiddenMulai.value || !hiddenSelesai.value) return;

    const mulai   = new Date(hiddenMulai.value + 'T00:00:00');
    const selesai = new Date(hiddenSelesai.value + 'T00:00:00');
    if (selesai < mulai) {
        document.getElementById('errorSelesai').textContent = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
        return;
    }
    const cbRange = getCutiBersamaDalamRange(mulai, selesai);
    if (cbRange.length > 0) {
        alertRange.classList.remove('d-none');
        pesanRange.innerHTML = 'Hari berikut otomatis dikecualikan dari hitungan:<br>'
            + cbRange.map(cb => `&bull; <strong>${cb.nama}</strong> — ${formatTanggal(cb.tanggal)}`).join('<br>');
    }
    const totalHari = hitungHariKerja(mulai, selesai);
    document.getElementById('jumlahHari').textContent = totalHari;
    document.getElementById('sisaCuti').textContent   = jenisCuti.value === 'Cuti Tahunan' ? hakCutiAwal - totalHari : hakCutiAwal;
    document.getElementById('infoHari').style.display = 'block';
    validasiJenisCuti(totalHari);
}

const jenisCuti   = document.getElementById('jenis_cuti');
const dynamicInfo = document.getElementById('dynamicInfo');
const dynamicFile = document.getElementById('dynamicFile');
const btnSubmit   = document.getElementById('btnSubmit');

function buatInfoAlert(tipe, iconClass, judul, poin) {
    const map = {
        primary: 'pcr-alert-primary', warning: 'pcr-alert-warning',
        success: 'pcr-alert-success', info: 'pcr-alert-info', secondary: 'pcr-alert-secondary'
    };
    return `<div class="pcr-alert ${map[tipe] || 'pcr-alert-primary'}">
        <div class="pcr-alert-icon"><i class="${iconClass}"></i></div>
        <div>
            <div class="pcr-alert-title">${judul}</div>
            <div class="pcr-alert-body"><ul style="margin:0;padding-left:16px;">${poin.map(p=>`<li>${p}</li>`).join('')}</ul></div>
        </div>
    </div>`;
}

function buatFileInput(name, label) {
    return `<div class="pcr-field">
        <label class="pcr-label">${label} <span class="pcr-required">*</span></label>
        <div class="pcr-file-wrap">
            <i class="bi bi-paperclip"></i>
            <input type="file" name="${name}" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>
        <div class="pcr-hint">Format: PDF, JPG, PNG. Maks. 2MB</div>
    </div>`;
}

function validasiJenisCuti(totalHari = 0) {
    dynamicInfo.innerHTML = '';
    dynamicFile.innerHTML = '';
    btnSubmit.disabled    = false;
    const jenis = jenisCuti.value;
    if (jenis === 'Cuti Tahunan') {
        dynamicInfo.innerHTML = buatInfoAlert('primary', 'bi bi-info-circle-fill', 'Informasi Cuti Tahunan', [
            `Sisa hak cuti: <strong>${hakCutiAwal} hari</strong>`,
            'Maksimal <strong>12 hari</strong> per tahun',
            'Minimal pengambilan <strong>3 hari</strong>',
        ]);
        if (totalHari > 12 || (totalHari < 3 && totalHari > 0) || hakCutiAwal <= 0 || totalHari > hakCutiAwal) {
            btnSubmit.disabled = true;
        }
    } else if (jenis === 'Cuti Sakit') {
        dynamicInfo.innerHTML = buatInfoAlert('warning', 'bi bi-heart-pulse-fill', 'Informasi Cuti Sakit', [
            'Tidak mengurangi hak cuti tahunan',
            'Maksimal <strong>14 hari</strong> untuk sakit ringan',
            'Wajib upload surat dokter',
        ]);
        dynamicFile.innerHTML = buatFileInput('surat_dokter', 'Surat Dokter');
    } else if (jenis === 'Cuti Melahirkan') {
        dynamicInfo.innerHTML = buatInfoAlert('success', 'bi bi-gender-female', 'Informasi Cuti Melahirkan', [
            'Maksimal <strong>3 bulan</strong>',
            'Maksimal <strong>3 kali</strong> selama menjadi PNS',
            'Tidak mengurangi cuti tahunan',
        ]);
        dynamicFile.innerHTML = buatFileInput('surat_melahirkan', 'Surat Keterangan Melahirkan');
    } else if (jenis === 'Cuti Ibadah Haji') {
        dynamicInfo.innerHTML = buatInfoAlert('info', 'bi bi-moon-stars-fill', 'Informasi Cuti Ibadah Haji', [
            'Hanya diberikan <strong>1 kali</strong> selama menjadi PNS',
            'Rata-rata sekitar <strong>40 hari</strong>',
            'Tidak mengurangi hak cuti tahunan',
        ]);
        dynamicFile.innerHTML = buatFileInput('surat_haji', 'Surat Keberangkatan Haji');
    } else if (jenis === 'Cuti Penting') {
        dynamicInfo.innerHTML = buatInfoAlert('secondary', 'bi bi-exclamation-circle-fill', 'Informasi Cuti Alasan Penting', [
            'Maksimal <strong>1 bulan</strong>',
            'Pernikahan / keluarga meninggal maks. <strong>3 hari</strong>',
            'Wajib upload dokumen pendukung',
        ]);
        dynamicFile.innerHTML = buatFileInput('dokumen_penting', 'Dokumen Pendukung');
    }
}

jenisCuti.addEventListener('change', function () { validasiJenisCuti(); updateHari(); });

document.getElementById('formPengajuan').addEventListener('submit', function(e) {
    e.preventDefault();
    const jenis = jenisCuti.value;
    if (!jenis)               { tampilError('Jenis cuti harus dipilih.'); return; }
    if (!hiddenMulai.value)   { tampilError('Tanggal mulai harus diisi.'); return; }
    if (!hiddenSelesai.value) { tampilError('Tanggal selesai harus diisi.'); return; }
    if (isCutiBersama(hiddenMulai.value))   { tampilError(`Tanggal mulai adalah Cuti Bersama: "${getNamaCutiBersama(hiddenMulai.value)}".`); return; }
    if (isCutiBersama(hiddenSelesai.value)) { tampilError(`Tanggal selesai adalah Cuti Bersama: "${getNamaCutiBersama(hiddenSelesai.value)}".`); return; }
    const alasan = document.querySelector('textarea[name="alasan"]').value;
    if (alasan.trim().length < 10) { tampilError('Alasan cuti minimal 10 karakter.'); return; }
    const fileInputs = document.querySelectorAll('#dynamicFile input[type="file"]');
    for (const fi of fileInputs) {
        if (fi.required && fi.files.length === 0) {
            const label = fi.closest('.pcr-field')?.querySelector('label')?.textContent?.trim() || 'Dokumen';
            tampilError(`${label} wajib diunggah.`); return;
        }
    }
    this.submit();
});

function tampilError(pesan) {
    const existing = document.getElementById('validasiError');
    if (existing) existing.remove();
    const el = document.createElement('div');
    el.id = 'validasiError';
    el.className = 'pcr-msg pcr-msg-error';
    el.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> ${pesan}`;
    const actions = document.querySelector('.pcr-actions');
    actions.parentNode.insertBefore(el, actions);
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    setTimeout(() => el.remove(), 5000);
}

fetchCutiBersama(tahunIni);

// ✅ FIX: Restore old value jika ada validasi error dari server
@if(old('tanggal_mulai'))
    hiddenMulai.value = '{{ old('tanggal_mulai') }}';
    fpMulai.setDate('{{ old('tanggal_mulai') }}', false, 'Y-m-d');
@endif
@if(old('tanggal_selesai'))
    hiddenSelesai.value = '{{ old('tanggal_selesai') }}';
    fpSelesai.setDate('{{ old('tanggal_selesai') }}', false, 'Y-m-d');
@endif
@if(old('jenis_cuti'))
    validasiJenisCuti();
    updateHari();
@endif
</script>
@endpush
