@extends('layouts.app')

@section('title', 'Detail Data Guru')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Detail Data Guru</h4>
            <small class="text-muted">Informasi lengkap & riwayat cuti guru</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.data-guru.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Profil Guru --}}
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <div class="mb-3">
                        <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width:80px;height:80px;font-size:2rem;">
                            {{ strtoupper(substr($guru->nama, 0, 1)) }}
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $guru->nama }}</h5>
                    <span class="badge bg-{{ $guru->is_active ? 'success' : 'danger' }} mb-2">
                        {{ $guru->is_active ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                    <p class="text-muted mb-0">{{ $guru->jabatan ?? '-' }}</p>
                </div>
                <hr class="my-0">
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <span class="text-muted">NIP</span><br>
                            <strong>{{ $guru->nip }}</strong>
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">Email</span><br>
                            <strong>{{ $guru->email }}</strong>
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">No. Telepon</span><br>
                            <strong>{{ $guru->no_telp ?? '-' }}</strong>
                        </li>
                        <li class="mb-2">
                            <span class="text-muted">Alamat</span><br>
                            <strong>{{ $guru->alamat ?? '-' }}</strong>
                        </li>
                        <li>
                            <span class="text-muted">Hak Cuti</span><br>
                            <strong class="text-primary">{{ $guru->hak_cuti }} hari / tahun</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">

            {{-- Pengajuan Cuti --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-file-earmark-text me-1 text-primary"></i> Pengajuan Cuti
                </div>
                <div class="card-body p-0">
                    @if($pengajuanCuti->isEmpty())
                        <p class="text-muted text-center py-3 mb-0">Belum ada pengajuan cuti.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Cuti</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuanCuti as $i => $cuti)
                                    <tr>
                                        <td>{{ $pengajuanCuti->firstItem() + $i }}</td>
                                        <td>{{ $cuti->jenis_cuti ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') }}</td>
                                        <td>{{ $cuti->durasi ?? '-' }} hari</td>
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'pending'  => 'warning',
                                                    'disetujui'=> 'success',
                                                    'ditolak'  => 'danger',
                                                ];
                                                $color = $statusMap[$cuti->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ ucfirst($cuti->status) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-3 py-2">
                            {{ $pengajuanCuti->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Histori Cuti --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-clock-history me-1 text-success"></i> Histori Cuti
                </div>
                <div class="card-body p-0">
                    @if($historiCuti->isEmpty())
                        <p class="text-muted text-center py-3 mb-0">Belum ada histori cuti.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Cuti</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Durasi</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historiCuti as $i => $histori)
                                    <tr>
                                        <td>{{ $historiCuti->firstItem() + $i }}</td>
                                        <td>{{ $histori->jenis_cuti ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($histori->tanggal_mulai)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($histori->tanggal_selesai)->format('d/m/Y') }}</td>
                                        <td>{{ $histori->durasi ?? '-' }} hari</td>
                                        <td>{{ $histori->keterangan ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-3 py-2">
                            {{ $historiCuti->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
