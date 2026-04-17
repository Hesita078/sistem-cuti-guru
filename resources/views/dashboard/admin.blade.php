@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0"
             style="background: linear-gradient(135deg, #4e73df 0%, #6f42c1 100%); color: white; border-radius: 12px;">
            <div class="card-body p-4">
                <h4 class="mb-1 fw-semibold">Dashboard Admin 👨‍💼</h4>
                <p class="mb-0 opacity-75">Kelola dan verifikasi pengajuan cuti guru dengan lebih efisien.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @php
        $cards = [
            ['title' => 'Total Guru', 'value' => $totalGuru, 'icon' => 'bi-people', 'color' => 'primary', 'suffix' => 'guru'],
            ['title' => 'Menunggu Verifikasi', 'value' => $pengajuanMenunggu, 'icon' => 'bi-clock', 'color' => 'warning', 'suffix' => 'pengajuan'],
            ['title' => 'Diverifikasi', 'value' => $pengajuanDiverifikasi, 'icon' => 'bi-check-circle', 'color' => 'success', 'suffix' => 'pengajuan'],
            ['title' => 'Ditolak', 'value' => $pengajuanDitolak, 'icon' => 'bi-x-circle', 'color' => 'danger', 'suffix' => 'pengajuan'],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="col-md-3 mb-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">{{ $card['title'] }}</h6>
                        <h2 class="mb-0 fw-bold">{{ $card['value'] }}</h2>
                        <small class="text-muted">{{ $card['suffix'] }}</small>
                    </div>
                    <div class="bg-{{ $card['color'] }} bg-opacity-10 p-3 rounded-circle">
                        <i class="bi {{ $card['icon'] }} text-{{ $card['color'] }}" style="font-size: 22px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-lightning me-2 text-primary"></i>Aksi Cepat
                </h6>
            </div>
            <div class="card-body">

                <a href="{{ route('verifikasi.index') }}"
                   class="btn btn-primary w-100 mb-3 shadow-sm">
                    <i class="bi bi-check-circle me-2"></i>
                    Verifikasi Pengajuan
                    @if($pengajuanMenunggu > 0)
                        <span class="badge bg-warning text-dark ms-2">
                            {{ $pengajuanMenunggu }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('laporan.index') }}"
                   class="btn btn-outline-primary w-100 mb-3">
                    <i class="bi bi-bar-chart me-2"></i>
                    Lihat Laporan
                </a>

                <a href="{{ route('laporan.hak-cuti') }}"
                   class="btn btn-outline-secondary w-100">
                    <i class="bi bi-calendar-check me-2"></i>
                    Data Hak Cuti Guru
                </a>

            </div>
        </div>
    </div>

    <!-- Pengajuan Menunggu -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-hourglass-split me-2 text-primary"></i>
                    Menunggu Verifikasi
                </h6>
            </div>

            <div class="card-body">
                @if($pengajuanTerbaru->count() > 0)

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Guru</th>
                                <th>Jenis</th>
                                <th>Tanggal</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pengajuanTerbaru as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item->kode_pengajuan }}</td>
                                <td>{{ $item->user->nama }}</td>
                                <td>{{ $item->jenis_cuti }}</td>
                                <td class="text-muted small">
                                    {{ $item->tanggal_mulai->format('d M Y') }} -
                                    {{ $item->tanggal_selesai->format('d M Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('verifikasi.index') }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-check-circle"></i>
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
                    <p class="mt-3">Tidak ada pengajuan menunggu verifikasi</p>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
