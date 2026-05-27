@extends('layouts.app')

@section('title', 'Ajukan Cuti Baru')
@section('page-title', 'Ajukan Cuti Baru')

@section('content')

<div class="row">
    <div class="col-lg-8 mx-auto">

        <!-- HAK CUTI -->
        <div class="card mb-4"
             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Sisa Hak Cuti Tahunan</h5>
                        <p class="mb-0 opacity-75">Pastikan pengajuan sesuai ketentuan</p>
                    </div>
                    <div>
                        <h1 class="mb-0">{{ auth()->user()->hak_cuti }}</h1>
                        <p class="mb-0 text-center">hari</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- INFO CUTI BERSAMA (muncul jika ada) -->
        <div class="alert alert-warning d-flex align-items-start gap-2 mb-4" id="infoCutiBersama" style="display:none!important">
            <i class="bi bi-calendar-x-fill fs-5 mt-1"></i>
            <div>
                <strong>Perhatian: Terdapat Cuti Bersama</strong>
                <div id="listCutiBersama" class="mt-1 small"></div>
            </div>
        </div>


        <!-- FORM -->
        <div class="card">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-plus me-2"></i>
                    Form Pengajuan Cuti
                </h5>
            </div>

            <div class="card-body">

                <form method="POST"
                      action="{{ route('pengajuan.store') }}"
                      enctype="multipart/form-data"
                      id="formPengajuan">

                    @csrf

                    <!-- JENIS CUTI -->
                    <div class="mb-3">
                        <label class="form-label">
                            Jenis Cuti <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('jenis_cuti') is-invalid @enderror"
                                name="jenis_cuti"
                                id="jenis_cuti"
                                required>
                            <option value="">-- Pilih Jenis Cuti --</option>
                            <option value="Cuti Tahunan">Cuti Tahunan</option>
                            <option value="Cuti Sakit">Cuti Sakit</option>
                            <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                            <option value="Cuti Ibadah Haji">Cuti Ibadah Haji</option>
                            <option value="Cuti Penting">Cuti Alasan Penting</option>
                        </select>
                    </div>

                    <!-- INFORMASI DINAMIS -->
                    <div id="dynamicInfo"></div>


                    <!-- TANGGAL -->
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date"
                                   class="form-control"
                                   name="tanggal_mulai"
                                   id="tanggalMulai"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            <div class="invalid-feedback" id="errorMulai"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date"
                                   class="form-control"
                                   name="tanggal_selesai"
                                   id="tanggalSelesai"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            <div class="invalid-feedback" id="errorSelesai"></div>
                        </div>

                    </div>


                    <!-- INFO HARI -->
                    <div class="mb-3">
                        <div class="alert alert-info d-none" id="infoHari">
                            <strong>Jumlah Hari Kerja: <span id="jumlahHari">0</span> hari</strong>
                            <br>
                            <small id="textSisaCuti">
                                Sisa hak cuti tahunan:
                                <span id="sisaCuti">{{ auth()->user()->hak_cuti }}</span> hari
                            </small>
                        </div>
                    </div>


                    <!-- PERINGATAN CUTI BERSAMA DALAM RANGE -->
                    <div class="alert alert-danger d-none" id="alertCutiBersamaRange">
                        <i class="bi bi-x-circle me-2"></i>
                        <strong>Tidak Dapat Mengajukan Cuti</strong>
                        <div id="pesanCutiBersamaRange" class="mt-1 small"></div>
                    </div>


                    <!-- ALASAN -->
                    <div class="mb-3">
                        <label class="form-label">Alasan Cuti</label>
                        <textarea class="form-control"
                                  name="alasan"
                                  rows="4"
                                  placeholder="Jelaskan alasan cuti..."
                                  required></textarea>
                        <small class="text-muted">Minimal 10 karakter</small>
                    </div>

                    <!-- FILE DINAMIS -->
                    <div id="dynamicFile"></div>

                    <!-- BUTTON -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="bi bi-send me-2"></i>Ajukan Cuti
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>

// =============================================
// DATA DARI SERVER
// =============================================
const hakCutiAwal = {{ auth()->user()->hak_cuti }};
const tahunIni    = new Date().getFullYear();

// Data cuti bersama: { 'YYYY-MM-DD': { nama, keterangan }, ... }
let cutiBersamaMap = {};

// =============================================
// FETCH CUTI BERSAMA DARI API
// =============================================
async function fetchCutiBersama(tahun) {
    try {
        const res = await fetch(`/api/cuti-bersama?tahun=${tahun}`);
        const data = await res.json();

        cutiBersamaMap = {};
        data.forEach(item => {
            cutiBersamaMap[item.tanggal] = {
                nama: item.nama,
                keterangan: item.keterangan
            };
        });

        tampilkanInfoCutiBersama(data);

    } catch (e) {
        console.error('Gagal mengambil data cuti bersama:', e);
    }
}

// =============================================
// TAMPILKAN INFO CUTI BERSAMA DI ATAS FORM
// =============================================
function tampilkanInfoCutiBersama(data) {
    const el = document.getElementById('infoCutiBersama');
    const list = document.getElementById('listCutiBersama');

    if (data.length === 0) {
        el.style.display = 'none';
        return;
    }

    el.style.removeProperty('display');

    list.innerHTML = data.map(item => {
        const tgl = new Date(item.tanggal + 'T00:00:00');
        const tglStr = tgl.toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });
        return `<div>• <strong>${item.nama}</strong> — ${tglStr}${item.keterangan ? ' <span class="text-muted">(' + item.keterangan + ')</span>' : ''}</div>`;
    }).join('');
}

// =============================================
// CEK WEEKEND
// =============================================
function isWeekend(date) {
    const day = date.getDay();
    return day === 0 || day === 6;
}

// =============================================
// CEK CUTI BERSAMA
// =============================================
function isCutiBersama(dateStr) {
    return cutiBersamaMap.hasOwnProperty(dateStr);
}

function getNamaCutiBersama(dateStr) {
    return cutiBersamaMap[dateStr]?.nama || 'Cuti Bersama';
}

// =============================================
// FORMAT TANGGAL
// =============================================
function formatTanggal(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });
}

// =============================================
// VALIDASI INPUT TANGGAL
// =============================================
const tanggalMulai   = document.getElementById('tanggalMulai');
const tanggalSelesai = document.getElementById('tanggalSelesai');
const jenisCuti      = document.getElementById('jenis_cuti');
const dynamicInfo    = document.getElementById('dynamicInfo');
const dynamicFile    = document.getElementById('dynamicFile');
const infoHari       = document.getElementById('infoHari');
const btnSubmit      = document.getElementById('btnSubmit');

function validasiTanggalInput(input, errorElId) {
    const errorEl = document.getElementById(errorElId);
    const val = input.value;

    if (!val) return true;

    const d = new Date(val + 'T00:00:00');
    const dateStr = val;

    if (isWeekend(d)) {
        input.classList.add('is-invalid');
        errorEl.textContent = 'Tanggal tidak boleh hari Sabtu atau Minggu.';
        input.value = '';
        return false;
    }

    if (isCutiBersama(dateStr)) {
        input.classList.add('is-invalid');
        errorEl.textContent = `Tanggal ini adalah Cuti Bersama: "${getNamaCutiBersama(dateStr)}". Tidak dapat dipilih.`;
        input.value = '';
        return false;
    }

    input.classList.remove('is-invalid');
    errorEl.textContent = '';
    return true;
}

tanggalMulai.addEventListener('change', function () {
    validasiTanggalInput(this, 'errorMulai');
    updateHari();
});

tanggalSelesai.addEventListener('change', function () {
    validasiTanggalInput(this, 'errorSelesai');
    updateHari();
});


// =============================================
// HITUNG HARI KERJA (exclude weekend & cuti bersama)
// =============================================
function hitungHariKerja(start, end) {
    let count = 0;
    let current = new Date(start);

    while (current <= end) {
        const day = current.getDay();
        const dateStr = current.toISOString().split('T')[0];

        if (day !== 0 && day !== 6 && !isCutiBersama(dateStr)) {
            count++;
        }

        current.setDate(current.getDate() + 1);
    }

    return count;
}

// =============================================
// AMBIL CUTI BERSAMA DALAM RANGE
// =============================================
function getCutiBersamaDalamRange(start, end) {
    const hasil = [];
    let current = new Date(start);

    while (current <= end) {
        const dateStr = current.toISOString().split('T')[0];
        if (isCutiBersama(dateStr)) {
            hasil.push({ tanggal: dateStr, nama: getNamaCutiBersama(dateStr) });
        }
        current.setDate(current.getDate() + 1);
    }

    return hasil;
}


// =============================================
// UPDATE INFO HARI
// =============================================
function updateHari() {
    const alertRange = document.getElementById('alertCutiBersamaRange');
    const pesanRange = document.getElementById('pesanCutiBersamaRange');

    alertRange.classList.add('d-none');

    if (!tanggalMulai.value || !tanggalSelesai.value) return;

    const mulai  = new Date(tanggalMulai.value + 'T00:00:00');
    const selesai = new Date(tanggalSelesai.value + 'T00:00:00');

    if (selesai < mulai) {
        tanggalSelesai.classList.add('is-invalid');
        document.getElementById('errorSelesai').textContent = 'Tanggal selesai tidak boleh sebelum tanggal mulai.';
        return;
    }

    // Cek cuti bersama dalam range
    const cutiBersamaDalamRange = getCutiBersamaDalamRange(mulai, selesai);

    if (cutiBersamaDalamRange.length > 0) {
        alertRange.classList.remove('d-none');
        pesanRange.innerHTML = 'Rentang tanggal yang dipilih mengandung hari cuti bersama berikut (otomatis dikecualikan dari hitungan):<br>'
            + cutiBersamaDalamRange.map(cb =>
                `&bull; <strong>${cb.nama}</strong> — ${formatTanggal(cb.tanggal)}`
            ).join('<br>');
    }

    const totalHari = hitungHariKerja(mulai, selesai);

    document.getElementById('jumlahHari').textContent = totalHari;

    if (jenisCuti.value === 'Cuti Tahunan') {
        document.getElementById('sisaCuti').textContent = hakCutiAwal - totalHari;
    } else {
        document.getElementById('sisaCuti').textContent = hakCutiAwal;
    }

    infoHari.classList.remove('d-none');

    validasiJenisCuti(totalHari);
}


// =============================================
// VALIDASI JENIS CUTI
// =============================================
function validasiJenisCuti(totalHari = 0) {
    const jenis = jenisCuti.value;

    dynamicInfo.innerHTML = '';
    dynamicFile.innerHTML = '';
    btnSubmit.disabled = false;

    // CUTI TAHUNAN
    if (jenis === 'Cuti Tahunan') {
        dynamicInfo.innerHTML = `
            <div class="alert alert-primary">
                <h6 class="mb-2">Informasi Cuti Tahunan</h6>
                <ul class="mb-0">
                    <li>Sisa hak cuti: <strong>${hakCutiAwal} hari</strong></li>
                    <li>Maksimal 12 hari per tahun</li>
                    <li>Minimal pengambilan 3 hari</li>
                </ul>
            </div>`;

        if (totalHari > 12) {
            alert('Cuti tahunan maksimal 12 hari');
            btnSubmit.disabled = true;
        } else if (totalHari < 3 && totalHari > 0) {
            alert('Minimal cuti tahunan 3 hari');
            btnSubmit.disabled = true;
        } else if (hakCutiAwal <= 0) {
            alert('Hak cuti tahunan Anda sudah habis');
            btnSubmit.disabled = true;
        } else if (totalHari > hakCutiAwal) {
            alert('Pengajuan melebihi sisa hak cuti');
            btnSubmit.disabled = true;
        }
    }

    // CUTI SAKIT
    else if (jenis === 'Cuti Sakit') {
        dynamicInfo.innerHTML = `
            <div class="alert alert-warning">
                <h6 class="mb-2">Informasi Cuti Sakit</h6>
                <ul class="mb-0">
                    <li>Tidak mengurangi hak cuti tahunan</li>
                    <li>Maksimal 14 hari untuk sakit ringan</li>
                    <li>Wajib upload surat dokter</li>
                </ul>
            </div>`;
        dynamicFile.innerHTML = `
            <div class="mb-3">
                <label>Surat Dokter *</label>
                <input type="file" class="form-control" name="surat_dokter" required>
            </div>`;
    }

    // CUTI MELAHIRKAN
    else if (jenis === 'Cuti Melahirkan') {
        dynamicInfo.innerHTML = `
            <div class="alert alert-success">
                <h6 class="mb-2">Informasi Cuti Melahirkan</h6>
                <ul class="mb-0">
                    <li>Maksimal 3 bulan</li>
                    <li>Maksimal 3 kali selama menjadi PNS</li>
                    <li>Tidak mengurangi cuti tahunan</li>
                </ul>
            </div>`;
        dynamicFile.innerHTML = `
            <div class="mb-3">
                <label>Surat Keterangan Melahirkan *</label>
                <input type="file" class="form-control" name="surat_melahirkan" required>
            </div>`;
    }

    // CUTI HAJI
    else if (jenis === 'Cuti Ibadah Haji') {
        dynamicInfo.innerHTML = `
            <div class="alert alert-info">
                <h6 class="mb-2">Informasi Cuti Haji</h6>
                <ul class="mb-0">
                    <li>Hanya diberikan 1 kali selama menjadi PNS</li>
                    <li>Rata-rata sekitar 40 hari</li>
                    <li>Tidak mengurangi hak cuti tahunan</li>
                </ul>
            </div>`;
        dynamicFile.innerHTML = `
            <div class="mb-3">
                <label>Surat Keberangkatan Haji *</label>
                <input type="file" class="form-control" name="surat_haji" required>
            </div>`;
    }

    // CUTI PENTING
    else if (jenis === 'Cuti Penting') {
        dynamicInfo.innerHTML = `
            <div class="alert alert-secondary">
                <h6 class="mb-2">Informasi Cuti Alasan Penting</h6>
                <ul class="mb-0">
                    <li>Maksimal 1 bulan</li>
                    <li>Pernikahan / keluarga meninggal maksimal 3 hari</li>
                    <li>Wajib upload dokumen pendukung</li>
                </ul>
            </div>`;
        dynamicFile.innerHTML = `
            <div class="mb-3">
                <label>Dokumen Pendukung *</label>
                <input type="file" class="form-control" name="dokumen_penting" required>
            </div>`;
    }
}


// =============================================
// EVENT JENIS CUTI
// =============================================
jenisCuti.addEventListener('change', function () {
    validasiJenisCuti();
    updateHari();
});


// =============================================
// VALIDASI SUBMIT
// =============================================
document.getElementById('formPengajuan').addEventListener('submit', function(e) {

    // Cek alasan
    const alasan = document.querySelector('textarea[name="alasan"]').value;
    if (alasan.length < 10) {
        e.preventDefault();
        alert('Alasan minimal 10 karakter');
        return;
    }

    // Cek ulang cuti bersama di range sebelum submit
    if (tanggalMulai.value && tanggalSelesai.value) {
        const mulai   = new Date(tanggalMulai.value + 'T00:00:00');
        const selesai = new Date(tanggalSelesai.value + 'T00:00:00');

        // Pastikan tanggal mulai & selesai bukan cuti bersama
        if (isCutiBersama(tanggalMulai.value)) {
            e.preventDefault();
            alert(`Tanggal mulai adalah Cuti Bersama: "${getNamaCutiBersama(tanggalMulai.value)}". Tidak dapat mengajukan cuti.`);
            return;
        }

        if (isCutiBersama(tanggalSelesai.value)) {
            e.preventDefault();
            alert(`Tanggal selesai adalah Cuti Bersama: "${getNamaCutiBersama(tanggalSelesai.value)}". Tidak dapat mengajukan cuti.`);
            return;
        }
    }
});


// =============================================
// INIT: LOAD CUTI BERSAMA SAAT PAGE LOAD
// =============================================
fetchCutiBersama(tahunIni);

// Jika user ganti tahun di tanggal mulai, reload cuti bersama
tanggalMulai.addEventListener('change', function () {
    if (this.value) {
        const tahunDipilih = parseInt(this.value.substring(0, 4));
        if (tahunDipilih !== tahunIni) {
            fetchCutiBersama(tahunDipilih);
        }
    }
});

</script>
@endpush
