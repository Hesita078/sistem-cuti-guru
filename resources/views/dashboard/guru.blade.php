@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body p-4">
                <h4 class="mb-2">Selamat Datang, {{ auth()->user()->nama }}! 👋</h4>
                <p class="mb-0 opacity-75">Kelola pengajuan cuti Anda dengan mudah dan efisien.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Sisa Hak Cuti</h6>
                        <h2 class="mb-0">{{ $hakCuti }}</h2>
                        <small class="text-muted">hari</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Pengajuan</h6>
                        <h2 class="mb-0">{{ $totalPengajuan }}</h2>
                        <small class="text-muted">pengajuan</small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-file-text text-info" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Menunggu</h6>
                        <h2 class="mb-0">{{ $pengajuanMenunggu }}</h2>
                        <small class="text-muted">pengajuan</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-clock text-warning" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Disetujui</h6>
                        <h2 class="mb-0">{{ $pengajuanDisetujui }}</h2>
                        <small class="text-muted">pengajuan</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-check-circle text-success" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body">
                @if($hakCuti > 0)
                    <a href="{{ route('pengajuan.create') }}" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-plus-circle me-2"></i>
                        Ajukan Cuti Baru
                    </a>
                @else
                    <button class="btn btn-secondary w-100 mb-3" disabled>
                        <i class="bi bi-x-circle me-2"></i>
                        Hak Cuti Habis
                    </button>
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small>Hak cuti Anda sudah habis. Tidak dapat mengajukan cuti.</small>
                    </div>
                @endif

                <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-primary w-100 mb-3">
                    <i class="bi bi-list-ul me-2"></i>
                    Lihat Semua Pengajuan
                </a>

                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-person me-2"></i>
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Pengajuan Terbaru -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Pengajuan Terbaru</h6>
            </div>
            <div class="card-body">
                @if($pengajuanTerbaru->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Jenis Cuti</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuanTerbaru as $item)
                                <tr>
                                    <td><strong>{{ $item->kode_pengajuan }}</strong></td>
                                    <td>{{ $item->jenis_cuti }}</td>
                                    <td>
                                        <small>
                                            {{ $item->tanggal_mulai->format('d/m/Y') }} -
                                            {{ $item->tanggal_selesai->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        @php
                                            $status = strtolower(trim($item->status));
                                        @endphp
                                        @if($status == 'menunggu verifikasi')
                                            <span class="badge bg-warning">Menunggu Verifikasi Admin</span>
                                        @elseif($status == 'menunggu persetujuan')
                                            <span class="badge bg-primary">Menunggu Persetujuan Kepsek</span>
                                        @elseif($status == 'verifikasi')
                                            <span class="badge bg-info">Verifikasi Admin</span>
                                        @elseif($status == 'disetujui')
                                            <span class="badge bg-success">Disetujui Kepsek</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak Admin</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pengajuan.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 48px;"></i>
                        <p class="mt-3">Belum ada pengajuan cuti.</p>
                        @if($hakCuti > 0)
                            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
                                Ajukan Cuti Sekarang
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Histori Cuti -->
@if($historiCuti->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-journal-text me-2"></i>Histori Cuti Disetujui</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Jenis Cuti</th>
                                <th>Tanggal</th>
                                <th>Jumlah Hari</th>
                                <th>Hak Cuti</th>
                                <th>Tanggal Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historiCuti as $item)
                            <tr>
                                <td><strong>{{ $item->kode_pengajuan }}</strong></td>
                                <td>{{ $item->jenis_cuti }}</td>
                                <td>
                                    <small>
                                        {{ $item->tanggal_mulai->format('d/m/Y') }} -
                                        {{ $item->tanggal_selesai->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td>{{ $item->jumlah_hari }} hari</td>
                                <td>
                                    <small class="text-muted">
                                        {{ $item->hak_cuti_sebelum }} → {{ $item->hak_cuti_sesudah }} hari
                                    </small>
                                </td>
                                <td>
                                    <small>{{ $item->tanggal_persetujuan->format('d/m/Y H:i') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
