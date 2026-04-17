@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti')
@section('page-title', 'Detail Pengajuan Cuti')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Detail Pengajuan</h5>
                    @if($pengajuan->status == 'Menunggu Verifikasi Admin')
                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                    @elseif($pengajuan->status == 'Menunggu Persetujuan Kepala Sekolah')
                        <span class="badge bg-primary">Menunggu Persetujuan</span>
                    @elseif($pengajuan->status == 'Disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Info Pengajuan -->
                <table class="table table-borderless">
                    <tr>
                        <td width="200"><strong>Kode Pengajuan</strong></td>
                        <td>: <strong class="text-primary">{{ $pengajuan->kode_pengajuan }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Nama Guru</strong></td>
                        <td>: {{ $pengajuan->user->nama }}</td>
                    </tr>
                    <tr>
                        <td><strong>NIP</strong></td>
                        <td>: {{ $pengajuan->user->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Cuti</strong></td>
                        <td>: {{ $pengajuan->jenis_cuti }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Mulai</strong></td>
                        <td>: {{ $pengajuan->tanggal_mulai->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Selesai</strong></td>
                        <td>: {{ $pengajuan->tanggal_selesai->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Hari</strong></td>
                        <td>: <strong>{{ $pengajuan->jumlah_hari }} hari</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Pengajuan</strong></td>
                        <td>: {{ $pengajuan->created_at->format('d F Y H:i') }} WIB</td>
                    </tr>
                </table>

                <hr>

                <!-- Alasan -->
                <h6 class="mb-2"><strong>Alasan Cuti:</strong></h6>
                <p class="text-muted">{{ $pengajuan->alasan }}</p>

                <!-- File Pendukung -->
                @if($pengajuan->file_pendukung)
                    <hr>
                    <h6 class="mb-2"><strong>File Pendukung:</strong></h6>
                    <a href="{{ Storage::url($pengajuan->file_pendukung) }}"
                       target="_blank"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-pdf me-2"></i>Lihat File
                    </a>
                @endif

                <!-- Catatan Admin -->
                @if($pengajuan->catatan_admin)
                    <hr>
                    <div class="alert alert-{{ $pengajuan->status == 'Ditolak Admin' ? 'danger' : 'info' }}">
                        <h6 class="mb-2"><strong><i class="bi bi-chat-left-text me-2"></i>Catatan Admin:</strong></h6>
                        <p class="mb-0">{{ $pengajuan->catatan_admin }}</p>
                        @if($pengajuan->tanggal_verifikasi_admin)
                            <small class="text-muted d-block mt-2">
                                {{ $pengajuan->tanggal_verifikasi_admin->format('d F Y H:i') }} WIB
                            </small>
                        @endif
                    </div>
                @endif

                <!-- Catatan Kepala Sekolah -->
                @if($pengajuan->catatan_kepala_sekolah)
                    <hr>
                    <div class="alert alert-{{ $pengajuan->status == 'Ditolak' ? 'danger' : 'success' }}">
                        <h6 class="mb-2"><strong><i class="bi bi-chat-left-text me-2"></i>Catatan Kepala Sekolah:</strong></h6>
                        <p class="mb-0">{{ $pengajuan->catatan_kepala_sekolah }}</p>
                        @if($pengajuan->tanggal_persetujuan)
                            <small class="text-muted d-block mt-2">
                                {{ $pengajuan->tanggal_persetujuan->format('d F Y H:i') }} WIB
                            </small>
                        @endif
                    </div>
                @endif

                <!-- Status Timeline -->
                <hr>
                <h6 class="mb-3"><strong>Timeline Status:</strong></h6>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <strong>Pengajuan Dibuat</strong>
                            <br>
                            <small class="text-muted">{{ $pengajuan->created_at->format('d F Y H:i') }} WIB</small>
                        </div>
                    </div>

                    @if($pengajuan->tanggal_verifikasi_admin)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $pengajuan->status == 'Ditolak Admin' ? 'danger' : 'info' }}"></div>
                            <div class="timeline-content">
                                <strong>{{ $pengajuan->status == 'Ditolak Admin' ? 'Ditolak Admin' : 'Diverifikasi Admin' }}</strong>
                                <br>
                                <small class="text-muted">{{ $pengajuan->tanggal_verifikasi_admin->format('d F Y H:i') }} WIB</small>
                            </div>
                        </div>
                    @endif

                    @if($pengajuan->tanggal_persetujuan)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $pengajuan->status == 'Disetujui' ? 'success' : 'danger' }}"></div>
                            <div class="timeline-content">
                                <strong>{{ $pengajuan->status }}</strong>
                                <br>
                                <small class="text-muted">{{ $pengajuan->tanggal_persetujuan->format('d F Y H:i') }} WIB</small>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
            <div class="card-footer">
                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-marker {
        position: absolute;
        left: -26px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid white;
    }

    .timeline-content {
        padding-left: 10px;
    }
</style>
@endpush
