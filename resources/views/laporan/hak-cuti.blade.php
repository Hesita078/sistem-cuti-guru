@extends('layouts.app')

@section('title', 'Laporan Hak Cuti Guru')
@section('page-title', 'Laporan Hak Cuti Guru')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Data Sisa Hak Cuti Guru</h5>
            <button class="btn btn-success btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Cetak
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($guru->count() > 0)
            <!-- Summary Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="mb-2">Total Guru</h6>
                            <h3 class="mb-0">{{ $guru->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="mb-2">Hak Cuti Tersedia</h6>
                            <h3 class="mb-0">{{ $guru->where('hak_cuti', '>', 0)->count() }} Guru</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="mb-2">Hak Cuti Habis</h6>
                            <h3 class="mb-0">{{ $guru->where('hak_cuti', '<=', 0)->count() }} Guru</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama Guru</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th class="text-center">Sisa Hak Cuti</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guru as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nip ?? '-' }}</td>
                            <td><strong>{{ $item->nama }}</strong></td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->no_telp ?? '-' }}</td>
                            <td class="text-center">
                                <h5 class="mb-0">
                                    <span class="badge bg-{{ $item->hak_cuti > 6 ? 'success' : ($item->hak_cuti > 0 ? 'warning' : 'danger') }}">
                                        {{ $item->hak_cuti }} hari
                                    </span>
                                </h5>
                            </td>
                            <td class="text-center">
                                @if($item->hak_cuti > 6)
                                    <span class="badge bg-success">Aman</span>
                                @elseif($item->hak_cuti > 0)
                                    <span class="badge bg-warning text-dark">Terbatas</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end"><strong>Total Hak Cuti Semua Guru:</strong></td>
                            <td class="text-center">
                                <strong class="text-primary">{{ $guru->sum('hak_cuti') }} hari</strong>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Legend -->
            <div class="mt-3">
                <small class="text-muted">
                    <strong>Keterangan Status:</strong><br>
                    <span class="badge bg-success">Aman</span> = Lebih dari 6 hari cuti tersisa<br>
                    <span class="badge bg-warning text-dark">Terbatas</span> = 1-6 hari cuti tersisa<br>
                    <span class="badge bg-danger">Habis</span> = Tidak ada hak cuti tersisa
                </small>
            </div>

        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 64px;"></i>
                <h5 class="mt-3 text-muted">Tidak Ada Data</h5>
                <p class="text-muted">Belum ada data guru yang terdaftar.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        .sidebar, .top-navbar, .btn, .no-print {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush
