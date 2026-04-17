@extends('layouts.app')

@section('title', 'Ajukan Cuti Baru')
@section('page-title', 'Ajukan Cuti Baru')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Info Hak Cuti -->
        <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Sisa Hak Cuti Anda</h5>
                        <p class="mb-0 opacity-75">Pastikan mengajukan sesuai kebutuhan</p>
                    </div>
                    <div>
                        <h1 class="mb-0">{{ auth()->user()->hak_cuti }}</h1>
                        <p class="mb-0 text-center">hari</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pengajuan -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-file-earmark-plus me-2"></i>Form Pengajuan Cuti</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data" id="formPengajuan">
                    @csrf

                    <!-- Jenis Cuti -->
                    <div class="mb-3">
                        <label class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                        <select class="form-select @error('jenis_cuti') is-invalid @enderror" name="jenis_cuti" required>
                            <option value="">-- Pilih Jenis Cuti --</option>
                            <option value="Cuti Tahunan" {{ old('jenis_cuti') == 'Cuti Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                            <option value="Cuti Sakit" {{ old('jenis_cuti') == 'Cuti Sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                            <option value="Cuti Melahirkan" {{ old('jenis_cuti') == 'Cuti Melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                            <option value="Cuti Bersama" {{ old('jenis_cuti') == 'Cuti Bersama' ? 'selected' : '' }}>Cuti Bersama</option>
                            <option value="Cuti Penting" {{ old('jenis_cuti') == 'Cuti Penting' ? 'selected' : '' }}>Cuti Penting</option>
                        </select>
                        @error('jenis_cuti')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date"
                               class="form-control @error('tanggal_mulai') is-invalid @enderror"
                               name="tanggal_mulai"
                               id="tanggalMulai"
                               value="{{ old('tanggal_mulai') }}"
                               min="{{ date('Y-m-d') }}"
                               required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date"
                               class="form-control @error('tanggal_selesai') is-invalid @enderror"
                               name="tanggal_selesai"
                               id="tanggalSelesai"
                               value="{{ old('tanggal_selesai') }}"
                               min="{{ date('Y-m-d') }}"
                               required>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Info Jumlah Hari -->
                    <div class="mb-3">
                        <div class="alert alert-info d-none" id="infoHari">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Jumlah hari cuti: <span id="jumlahHari">0</span> hari</strong>
                            <br>
                            <small>Sisa hak cuti setelah pengajuan: <span id="sisaCuti">{{ auth()->user()->hak_cuti }}</span> hari</small>
                        </div>
                        <div class="alert alert-danger d-none" id="warningCuti">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Hak cuti tidak mencukupi!</strong>
                            <br>
                            <small>Anda hanya memiliki {{ auth()->user()->hak_cuti }} hari cuti.</small>
                        </div>
                    </div>

                    <!-- Alasan -->
                    <div class="mb-3">
                        <label class="form-label">Alasan Cuti <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('alasan') is-invalid @enderror"
                                  name="alasan"
                                  rows="4"
                                  placeholder="Jelaskan alasan pengajuan cuti Anda..."
                                  required>{{ old('alasan') }}</textarea>
                        <small class="text-muted">Minimal 10 karakter</small>
                        @error('alasan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Pendukung -->
                    <div class="mb-3">
                        <label class="form-label">File Pendukung (Opsional)</label>
                        <input type="file"
                               class="form-control @error('file_pendukung') is-invalid @enderror"
                               name="file_pendukung"
                               accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 2MB</small>
                        @error('file_pendukung')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
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
    const hakCutiAwal = {{ auth()->user()->hak_cuti }};
    const tanggalMulai = document.getElementById('tanggalMulai');
    const tanggalSelesai = document.getElementById('tanggalSelesai');
    const infoHari = document.getElementById('infoHari');
    const warningCuti = document.getElementById('warningCuti');
    const btnSubmit = document.getElementById('btnSubmit');

    function hitungHari() {
        if (tanggalMulai.value && tanggalSelesai.value) {
            const mulai = new Date(tanggalMulai.value);
            const selesai = new Date(tanggalSelesai.value);

            if (selesai >= mulai) {
                const diffTime = Math.abs(selesai - mulai);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                document.getElementById('jumlahHari').textContent = diffDays;
                const sisaCuti = hakCutiAwal - diffDays;
                document.getElementById('sisaCuti').textContent = sisaCuti;

                infoHari.classList.remove('d-none');

                if (sisaCuti < 0) {
                    warningCuti.classList.remove('d-none');
                    btnSubmit.disabled = true;
                } else {
                    warningCuti.classList.add('d-none');
                    btnSubmit.disabled = false;
                }
            }
        }
    }

    tanggalMulai.addEventListener('change', function() {
        tanggalSelesai.min = this.value;
        hitungHari();
    });

    tanggalSelesai.addEventListener('change', hitungHari);

    // Validasi form sebelum submit
    document.getElementById('formPengajuan').addEventListener('submit', function(e) {
        const alasan = document.querySelector('textarea[name="alasan"]').value;
        if (alasan.length < 10) {
            e.preventDefault();
            alert('Alasan minimal 10 karakter!');
            return false;
        }
    });
</script>
@endpush
