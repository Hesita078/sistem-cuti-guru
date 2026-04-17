@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')
@section('page-title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body p-4">
                <h4 class="mb-2">Dashboard Kepala Sekolah 👨‍🏫</h4>
                <p class="mb-0 opacity-75">Monitor dan setujui pengajuan cuti guru.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Total Guru</h6>
                        <h2 class="mb-0">{{ $totalGuru }}</h2>
                        <small class="text-muted">guru</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-people text-primary" style="font-size: 24px;"></i>
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
                        <h6 class="text-muted mb-2">Menunggu Persetujuan</h6>
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

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-2">Ditolak</h6>
                        <h2 class="mb-0">{{ $pengajuanDitolak }}</h2>
                        <small class="text-muted">pengajuan</small>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                        <i class="bi bi-x-circle text-danger" style="font-size: 24px;"></i>
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
                <a href="{{ route('persetujuan.index') }}" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-clipboard-check me-2"></i>
                    Proses Persetujuan
                    @if($pengajuanMenunggu > 0)
                        <span class="badge bg-warning text-dark ms-2">{{ $pengajuanMenunggu }}</span>
                    @endif
                </a>

                <a href="{{ route('laporan.index') }}" class="btn btn-outline-primary w-100 mb-3">
                    <i class="bi bi-bar-chart me-2"></i>
                    Lihat Laporan
                </a>

                <a href="{{ route('laporan.hak-cuti') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-calendar-check me-2"></i>
                    Data Hak Cuti Guru
                </a>
            </div>
        </div>
    </div>

    <!-- Pengajuan Menunggu Persetujuan -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-hourglass-split me-2"></i>Menunggu Persetujuan</h6>
            </div>
            <div class="card-body">
                @if($pengajuanTerbaru->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Guru</th>
                                    <th>Jenis Cuti</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Hari</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuanTerbaru as $item)
                                <tr>
                                    <td><strong>{{ $item->kode_pengajuan }}</strong></td>
                                    <td>{{ $item->user->nama }}</td>
                                    <td>{{ $item->jenis_cuti }}</td>
                                    <td>
                                        <small>
                                            {{ $item->tanggal_mulai->format('d/m/Y') }} -
                                            {{ $item->tanggal_selesai->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>{{ $item->jumlah_hari }} hari</td>
                                    <td>
                                        <a href="{{ route('persetujuan.index') }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-clipboard-check"></i> Proses
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-check-circle" style="font-size: 48px;"></i>
                        <p class="mt-3">Tidak ada pengajuan yang menunggu persetujuan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
