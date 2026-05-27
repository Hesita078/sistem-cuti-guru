@extends('layouts.app')

@section('title', 'Laporan Cuti Disetujui')
@section('page-title', 'Laporan Cuti Disetujui')

@section('content')

{{-- FILTER --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.histori') }}">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" name="tahun">
                        <option value="">-- Semua Tahun --</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Bulan</label>
                    <select class="form-select" name="bulan">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $k => $b)
                            <option value="{{ $k+1 }}" {{ request('bulan') == ($k+1) ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                @if(!auth()->user()->isGuru())
                <div class="col-md-3 mb-3">
                    <label class="form-label">Guru</label>
                    <select class="form-select" name="user_id">
                        <option value="">-- Semua Guru --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3 mb-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('laporan.histori') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- TABEL --}}
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-journal-text me-2"></i>Laporan Cuti Disetujui
            </h5>
            <div class="d-flex gap-2">
                <a href="{{ route('laporan.cetak-bulanan', [
                        'bulan' => request('bulan') ?? date('m'),
                        'tahun' => request('tahun') ?? date('Y'),
                    ]) }}"
                   class="btn btn-danger btn-sm" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-1"></i>PDF Bulanan
                </a>
                <a href="{{ route('laporan.cetak-tahunan', [
                        'tahun' => request('tahun') ?? date('Y'),
                    ]) }}"
                   class="btn btn-success btn-sm" target="_blank">
                    <i class="bi bi-file-earmark-pdf me-1"></i>PDF Tahunan
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($histori->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Guru</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal Cuti</th>
                            <th>Jumlah Hari</th>
                            <th>Hak Cuti</th>
                            <th>Tgl Persetujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histori as $key => $item)
                        <tr>
                            <td>{{ $histori->firstItem() + $key }}</td>
                            <td><strong class="text-primary">{{ $item->kode_pengajuan }}</strong></td>
                            <td>{{ $item->user->nama ?? '-' }}</td>
                            <td>{{ $item->jenis_cuti }}</td>
                            <td>
                                <small>
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                </small>
                            </td>
                            <td><strong>{{ $item->jumlah_hari }} hari</strong></td>
                            <td>
                                <small class="text-muted">
                                    {{ $item->hak_cuti_sebelum }}
                                    <i class="bi bi-arrow-right"></i>
                                    <strong class="text-danger">{{ $item->hak_cuti_sesudah }}</strong>
                                </small>
                            </td>
                            <td><small>{{ \Carbon\Carbon::parse($item->tanggal_persetujuan)->format('d/m/Y H:i') }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end"><strong>Total Hari Cuti Disetujui:</strong></td>
                            <td><strong class="text-success">{{ $histori->sum('jumlah_hari') }} hari</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-3">{{ $histori->links() }}</div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size:64px;"></i>
                <h5 class="mt-3 text-muted">Tidak Ada Data</h5>
                <p class="text-muted">Tidak ada laporan cuti sesuai filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>

@endsection
