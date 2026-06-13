@extends('layouts.app')

@section('title', 'Detail Pengajuan Cuti')
@section('page-title', 'Detail Pengajuan Cuti')

@section('content')

@push('styles')
<style>
    .detail-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(99, 102, 241, 0.08);
        border: 1px solid #e8e8f0;
        overflow: hidden;
    }

    .detail-card-header {
        background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
        padding: 24px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .detail-card-header h5 {
        color: #ffffff;
        font-weight: 600;
        font-size: 1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-card-header h5 i {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        opacity: 0.7;
    }
    .status-menunggu-verifikasi  { background: #fef3c7; color: #92400e; }
    .status-menunggu-persetujuan { background: #dbeafe; color: #1e3a8a; }
    .status-disetujui            { background: #d1fae5; color: #065f46; }
    .status-ditangguhkan         { background: #f3f4f6; color: #374151; }
    .status-ditolak              { background: #fee2e2; color: #991b1b; }

    /* Info Table */
    .info-table {
        width: 100%;
        border-collapse: collapse;
    }
    .info-table tr {
        border-bottom: 1px solid #f1f1f5;
    }
    .info-table tr:last-child {
        border-bottom: none;
    }
    .info-table td {
        padding: 12px 0;
        font-size: 0.9rem;
        color: #374151;
        vertical-align: top;
    }
    .info-table td:first-child {
        width: 180px;
        color: #6b7280;
        font-weight: 500;
    }
    .info-table td:nth-child(2) {
        width: 12px;
        color: #9ca3af;
        padding-right: 8px;
    }
    .kode-pengajuan {
        font-weight: 700;
        color: #6366f1;
        font-family: monospace;
        font-size: 0.92rem;
        background: #eef2ff;
        padding: 3px 10px;
        border-radius: 6px;
        letter-spacing: 0.5px;
    }
    .jumlah-hari-badge {
        background: #eef2ff;
        color: #4338ca;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    /* Section Title */
    .section-title {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #9ca3af;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f1f5;
    }

    /* Alasan Box */
    .alasan-box {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-left: 4px solid #6366f1;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.9rem;
        color: #374151;
        line-height: 1.6;
    }

    /* File Button */
    .btn-lihat-file {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1.5px solid #6366f1;
        color: #6366f1;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-lihat-file:hover {
        background: #eef2ff;
        color: #4338ca;
        border-color: #4338ca;
        text-decoration: none;
    }

    /* Alert Catatan */
    .catatan-box {
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.9rem;
    }
    .catatan-box.info  { background: #eff6ff; border: 1px solid #bfdbfe; border-left: 4px solid #3b82f6; }
    .catatan-box.success { background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #22c55e; }
    .catatan-box.danger  { background: #fef2f2; border: 1px solid #fecaca; border-left: 4px solid #ef4444; }
    .catatan-box .catatan-label {
        font-weight: 700;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .catatan-box.info    .catatan-label { color: #1d4ed8; }
    .catatan-box.success .catatan-label { color: #15803d; }
    .catatan-box.danger  .catatan-label { color: #b91c1c; }
    .catatan-box .catatan-time {
        font-size: 0.78rem;
        color: #9ca3af;
        margin-top: 8px;
    }

    /* Timeline */
    .timeline-wrapper {
        position: relative;
        padding-left: 28px;
    }
    .timeline-wrapper::before {
        content: '';
        position: absolute;
        left: 7px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: linear-gradient(to bottom, #6366f1, #e5e7eb);
        border-radius: 2px;
    }
    .timeline-step {
        position: relative;
        margin-bottom: 20px;
    }
    .timeline-step:last-child {
        margin-bottom: 0;
    }
    .timeline-dot {
        position: absolute;
        left: -24px;
        top: 2px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid #ffffff;
        box-shadow: 0 0 0 2px currentColor;
    }
    .timeline-dot.success { color: #22c55e; background: #22c55e; }
    .timeline-dot.info    { color: #3b82f6; background: #3b82f6; }
    .timeline-dot.danger  { color: #ef4444; background: #ef4444; }
    .timeline-dot.warning { color: #f59e0b; background: #f59e0b; }
    .timeline-dot.secondary { color: #9ca3af; background: #9ca3af; }
    .timeline-step strong {
        font-size: 0.9rem;
        color: #1f2937;
        font-weight: 600;
    }
    .timeline-step small {
        font-size: 0.78rem;
        color: #9ca3af;
        display: block;
        margin-top: 2px;
    }

    /* Card Footer */
    .detail-card-footer {
        background: #f9fafb;
        border-top: 1px solid #f1f1f5;
        padding: 18px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 0 0 16px 16px;
    }
    .btn-kembali {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1.5px solid #d1d5db;
        color: #374151;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 9px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-kembali:hover {
        background: #f3f4f6;
        color: #111827;
        text-decoration: none;
    }
    .btn-cetak {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
        border: none;
        color: #ffffff;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 9px 20px;
        border-radius: 9px;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
    }
    .btn-cetak:hover {
        opacity: 0.92;
        color: #ffffff;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
    }

    /* Divider */
    .section-divider {
        border: none;
        border-top: 1px solid #f1f1f5;
        margin: 20px 0;
    }
</style>
@endpush

<div class="row">
    <div class="col-lg-8 mx-auto">

        @php $status = $pengajuan->status; @endphp

        <div class="detail-card">

            {{-- Header --}}
            <div class="detail-card-header">
                <h5>
                    <i class="bi bi-file-text-fill"></i>
                    Detail Pengajuan
                </h5>

                @if(str_contains($status, 'Menunggu Verifikasi') || $status === 'Menunggu Verifikasi Admin')
                    <span class="status-badge status-menunggu-verifikasi">Menunggu Verifikasi</span>
                @elseif(str_contains($status, 'Menunggu Persetujuan') || $status === 'Menunggu Persetujuan Kepala Sekolah')
                    <span class="status-badge status-menunggu-persetujuan">Menunggu Persetujuan</span>
                @elseif($status === 'Disetujui' || str_contains($status, 'Disetujui'))
                    <span class="status-badge status-disetujui">Disetujui</span>
                @elseif($status === 'Ditangguhkan')
                    <span class="status-badge status-ditangguhkan">Ditangguhkan</span>
                @elseif($status === 'Ditolak Admin' || str_contains($status, 'Ditolak'))
                    <span class="status-badge status-ditolak">Ditolak</span>
                @else
                    <span class="status-badge status-ditangguhkan">{{ $status }}</span>
                @endif
            </div>

            {{-- Body --}}
            <div class="px-4 pt-4 pb-2">

                {{-- Info Table --}}
                <p class="section-title"><i class="bi bi-info-circle"></i> Informasi Pengajuan</p>
                <table class="info-table mb-2">
                    <tr>
                        <td>Kode Pengajuan</td>
                        <td>:</td>
                        <td><span class="kode-pengajuan">{{ $pengajuan->kode_pengajuan }}</span></td>
                    </tr>
                    <tr>
                        <td>Nama Guru</td>
                        <td>:</td>
                        <td>{{ $pengajuan->user->nama }}</td>
                    </tr>
                    <tr>
                        <td>NIP</td>
                        <td>:</td>
                        <td>{{ $pengajuan->user->nip }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Cuti</td>
                        <td>:</td>
                        <td>{{ $pengajuan->jenis_cuti }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Mulai</td>
                        <td>:</td>
                        <td>{{ $pengajuan->tanggal_mulai->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Selesai</td>
                        <td>:</td>
                        <td>{{ $pengajuan->tanggal_selesai->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Hari</td>
                        <td>:</td>
                        <td><span class="jumlah-hari-badge">{{ $pengajuan->jumlah_hari }} hari</span></td>
                    </tr>
                    <tr>
                        <td>Tanggal Pengajuan</td>
                        <td>:</td>
                        <td>{{ $pengajuan->created_at->format('d F Y H:i') }} WIB</td>
                    </tr>
                </table>

                <hr class="section-divider">

                {{-- Alasan Cuti --}}
                <p class="section-title"><i class="bi bi-chat-square-text"></i> Alasan Cuti</p>
                <div class="alasan-box mb-3">
                    {{ $pengajuan->alasan }}
                </div>

                {{-- File Pendukung --}}
                @if($pengajuan->file_pendukung)
                    <hr class="section-divider">
                    <p class="section-title"><i class="bi bi-paperclip"></i> File Pendukung</p>
                    <a href="{{ Storage::url($pengajuan->file_pendukung) }}"
                       target="_blank"
                       class="btn-lihat-file mb-3">
                        <i class="bi bi-file-earmark-pdf"></i> Lihat File
                    </a>
                @endif

                {{-- Catatan Admin --}}
                @if($pengajuan->catatan_admin)
                    <hr class="section-divider">
                    <p class="section-title"><i class="bi bi-person-badge"></i> Catatan Admin</p>
                    @php
                        $boxTypeAdmin = str_contains($pengajuan->status, 'Ditolak Admin') ? 'danger' : 'info';
                    @endphp
                    <div class="catatan-box {{ $boxTypeAdmin }} mb-3">
                        <div class="catatan-label">
                            <i class="bi bi-chat-left-dots"></i> Catatan Admin
                        </div>
                        <div>{{ $pengajuan->catatan_admin }}</div>
                        @if($pengajuan->tanggal_verifikasi_admin)
                            <div class="catatan-time">
                                <i class="bi bi-clock me-1"></i>
                                {{ $pengajuan->tanggal_verifikasi_admin->format('d F Y H:i') }} WIB
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Catatan Kepala Sekolah --}}
                @if($pengajuan->catatan_kepala_sekolah)
                    <hr class="section-divider">
                    <p class="section-title"><i class="bi bi-person-check"></i> Catatan Kepala Sekolah</p>
                    @php
                        $boxTypeKepala = (str_contains($pengajuan->status, 'Ditolak') && !str_contains($pengajuan->status, 'Admin')) ? 'danger' : 'success';
                    @endphp
                    <div class="catatan-box {{ $boxTypeKepala }} mb-3">
                        <div class="catatan-label">
                            <i class="bi bi-chat-left-dots"></i> Catatan Kepala Sekolah
                        </div>
                        <div>{{ $pengajuan->catatan_kepala_sekolah }}</div>
                        @if($pengajuan->tanggal_persetujuan)
                            <div class="catatan-time">
                                <i class="bi bi-clock me-1"></i>
                                {{ $pengajuan->tanggal_persetujuan->format('d F Y H:i') }} WIB
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Timeline --}}
                <hr class="section-divider">
                <p class="section-title"><i class="bi bi-clock-history"></i> Timeline Status</p>
                <div class="timeline-wrapper mb-3">

                    {{-- Step 1: Dibuat --}}
                    <div class="timeline-step">
                        <div class="timeline-dot success"></div>
                        <strong>Pengajuan Dibuat</strong>
                        <small>{{ $pengajuan->created_at->format('d F Y H:i') }} WIB</small>
                    </div>

                    {{-- Step 2: Verifikasi Admin --}}
                    @if($pengajuan->tanggal_verifikasi_admin)
                        <div class="timeline-step">
                            <div class="timeline-dot {{ str_contains($pengajuan->status, 'Ditolak Admin') ? 'danger' : 'info' }}"></div>
                            <strong>{{ str_contains($pengajuan->status, 'Ditolak Admin') ? 'Ditolak Admin' : 'Diverifikasi Admin' }}</strong>
                            <small>{{ $pengajuan->tanggal_verifikasi_admin->format('d F Y H:i') }} WIB</small>
                        </div>
                    @endif

                    {{-- Step 3: Keputusan Kepala Sekolah --}}
                    @if($pengajuan->tanggal_persetujuan)
                        <div class="timeline-step">
                            @php
                                $dotColor = 'success';
                                if ($pengajuan->status === 'Ditangguhkan') $dotColor = 'warning';
                                elseif (str_contains($pengajuan->status, 'Ditolak') && !str_contains($pengajuan->status, 'Admin')) $dotColor = 'danger';
                            @endphp
                            <div class="timeline-dot {{ $dotColor }}"></div>
                            <strong>
                                @if(str_contains($pengajuan->status, 'Disetujui'))
                                    Disetujui Kepala Sekolah
                                @elseif($pengajuan->status === 'Ditangguhkan')
                                    Ditangguhkan
                                @else
                                    Ditolak Kepala Sekolah
                                @endif
                            </strong>
                            <small>{{ $pengajuan->tanggal_persetujuan->format('d F Y H:i') }} WIB</small>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Footer --}}
            <div class="detail-card-footer">
                <a href="{{ route('pengajuan.index') }}" class="btn-kembali">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>

                @if(str_contains($pengajuan->status, 'Disetujui'))
                    <a href="{{ route('pengajuan.cetak-pdf', $pengajuan->id) }}"
                       class="btn-cetak"
                       target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> Cetak Formulir Cuti
                    </a>
                @endif
            </div>

        </div>{{-- end .detail-card --}}

    </div>
</div>

@endsection
