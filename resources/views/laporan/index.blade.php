@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm" style="transition: all 0.3s;">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-file-text text-primary" style="font-size: 64px;"></i>
                </div>
                <h5 class="card-title">Laporan Pengajuan Cuti dan Izin</h5>
                <p class="card-text text-muted">Lihat semua data pengajuan cuti dengan filter tanggal, status, dan jenis cuti.</p>
                <a href="{{ route('laporan.pengajuan') }}" class="btn btn-primary">
                    <i class="bi bi-eye me-2"></i>Lihat Pengajuan On Progres
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm" style="transition: all 0.3s;">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-journal-text text-success" style="font-size: 64px;"></i>
                </div>
                <h5 class="card-title">Histori Cuti</h5>
                <p class="card-text text-muted">Lihat histori cuti yang sudah disetujui dengan detail pengurangan hak cuti.</p>
                <a href="{{ route('laporan.histori') }}" class="btn btn-success">
                    <i class="bi bi-eye me-2"></i>Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm" style="transition: all 0.3s;">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-calendar-check text-warning" style="font-size: 64px;"></i>
                </div>
                <h5 class="card-title">Sisa Hak Cuti Guru</h5>
                <p class="card-text text-muted">Lihat data sisa hak cuti semua guru secara lengkap dan detail.</p>
                <a href="{{ route('laporan.hak-cuti') }}" class="btn btn-warning">
                    <i class="bi bi-eye me-2"></i>Lihat Data
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Laporan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6><strong>Laporan Pengajuan Cuti</strong></h6>
                <ul>
                    <li>Filter berdasarkan tanggal</li>
                    <li>Filter berdasarkan status</li>
                    <li>Filter berdasarkan jenis cuti</li>
                    <li>Filter berdasarkan guru</li>
                    <li>Export ke Excel/PDF (Coming Soon)</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6><strong>Histori Cuti</strong></h6>
                <ul>
                    <li>Data cuti yang sudah disetujui</li>
                    <li>Detail pengurangan hak cuti</li>
                    <li>Filter berdasarkan tahun dan bulan</li>
                    <li>Filter berdasarkan guru</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
</style>
@endpush
