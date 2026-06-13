@extends('layouts.app')

@section('title', 'Histori Pengajuan Cuti')
@section('page-title', 'Histori Pengajuan Cuti')

@section('content')

<style>
    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(80,89,176,0.08);
    }
    .card-header {
        background: #fff;
        border-bottom: 1px solid #eef0f5;
        border-radius: 14px 14px 0 0 !important;
        padding: 14px 20px;
    }
    .card-header h5, .card-header h6 {
        font-weight: 600;
        color: #1a1a2e;
    }
    .form-select, .form-control {
        border: 1px solid #d8dce8;
        border-radius: 8px;
        background: #f8f9fc;
        font-size: 13px;
        padding: 9px 12px;
        color: #1a1a2e;
        transition: border-color 0.2s;
    }
    .form-select:focus, .form-control:focus {
        border-color: #5059B0;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(80,89,176,0.08);
    }
    .form-label {
        font-size: 12px;
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
    }
    .btn-primary {
        background: #5059B0 !important;
        border-color: #5059B0 !important;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        padding: 8px 18px;
    }
    .btn-primary:hover { background: #404A9C !important; border-color: #404A9C !important; }
    .btn-secondary {
        background: #f1f2f8 !important;
        border-color: #d8dce8 !important;
        color: #5059B0 !important;
        border-radius: 8px;
        font-size: 13px;
        padding: 8px 14px;
    }
    .btn-secondary:hover { background: #e4e6f5 !important; color: #404A9C !important; }
    .btn-danger {
        background: #e74c5e !important;
        border-color: #e74c5e !important;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
    }
    .btn-danger:hover { background: #c73a4d !important; border-color: #c73a4d !important; }
    .table thead th {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #888;
        background: #f8f9fc;
        border-bottom: 1px solid #eef0f5;
        padding: 10px 14px;
    }
    .table tbody td {
        font-size: 13px;
        color: #1a1a2e;
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid #f2f3f8;
    }
    .table tbody tr:last-child td { border-bottom: none; }
    .table tbody tr:hover td { background: #f5f6fb; }
    .table tfoot td {
        font-size: 13px;
        background: #f8f9fc;
        padding: 10px 14px;
        border-top: 1px solid #eef0f5;
    }
    .kode-badge {
        color: #5059B0;
        font-weight: 600;
        font-size: 13px;
    }
    .hari-badge {
        display: inline-block;
        background: #eef0f5;
        color: #5059B0;
        font-weight: 600;
        font-size: 12px;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .date-text { font-size: 12px; color: #888; }
    .total-label { text-align: right; color: #555; font-weight: 500; }
    .total-val { color: #5059B0; font-weight: 700; }
    .empty-state { padding: 3rem 0; text-align: center; }
    .empty-state i { font-size: 56px; color: #d0d4e8; }
    .empty-state h5 { margin-top: 12px; color: #aaa; font-size: 15px; }
    .empty-state p { color: #bbb; font-size: 13px; }

    /* Badge status */
    .status-badge {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .status-disetujui   { background: #e8f7ee; color: #2d6a4a; }
    .status-ditolak     { background: #fdecea; color: #a32d2d; }
    .status-ditangguhkan{ background: #f1f2f8; color: #5059B0; }
    .status-diverifikasi{ background: #e6f1fb; color: #185FA5; }
    .status-menunggu    { background: #faeeda; color: #854F0B; }

    /* Pagination */
    .pagination-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-top: 1px solid #eef0f5;
    }
    .pagination-info { font-size: 12px; color: #888; }
    .pagination { margin: 0; gap: 3px; display: flex; align-items: center; }
    .pagination .page-item .page-link {
        border-radius: 8px !important;
        border: 1px solid #d8dce8;
        color: #5059B0;
        font-size: 13px;
        font-weight: 500;
        padding: 6px 12px;
        margin: 0 2px;
        background: #fff;
        transition: all 0.15s;
        line-height: 1.4;
    }
    .pagination .page-item .page-link:hover {
        background: #eef0f5;
        border-color: #5059B0;
        color: #404A9C;
    }
    .pagination .page-item.active .page-link {
        background: #5059B0 !important;
        border-color: #5059B0 !important;
        color: #fff !important;
        box-shadow: none;
    }
    .pagination .page-item.disabled .page-link {
        color: #ccc;
        background: #f8f9fc;
        border-color: #eef0f5;
        cursor: default;
    }
</style>

{{-- FILTER --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2" style="color:#5059B0;"></i>Filter Laporan
        </h6>
    </div>
    <div class="card-body" style="padding:20px;">
        <form method="GET" action="{{ route('laporan.pengajuan') }}">
            <div class="row g-3">

                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" name="tahun">
                        <option value="">-- Semua --</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <select class="form-select" name="bulan">
                        <option value="">-- Semua --</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $k => $b)
                            <option value="{{ $k+1 }}" {{ request('bulan') == ($k+1) ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">-- Semua --</option>
                        <option value="Menunggu"     {{ request('status') == 'Menunggu'     ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diverifikasi" {{ request('status') == 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                        <option value="Disetujui"    {{ request('status') == 'Disetujui'    ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak"      {{ request('status') == 'Ditolak'      ? 'selected' : '' }}>Ditolak</option>
                        <option value="Ditangguhkan" {{ request('status') == 'Ditangguhkan' ? 'selected' : '' }}>Ditangguhkan</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Guru</label>
                    <select class="form-select" name="user_id">
                        <option value="">-- Semua Guru --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('laporan.pengajuan') }}" class="btn btn-secondary">
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
                <i class="bi bi-journal-text me-2" style="color:#5059B0;"></i>Histori Pengajuan Cuti
            </h5>
            <a href="{{ route('laporan.cetak-pengajuan', [
                    'bulan'   => request('bulan')   ?? date('m'),
                    'tahun'   => request('tahun')   ?? date('Y'),
                    'status'  => request('status'),
                    'user_id' => request('user_id'),
                ]) }}"
               class="btn btn-danger btn-sm"
               target="_blank">
                <i class="bi bi-file-earmark-pdf me-1"></i>Cetak PDF
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        @if($pengajuan->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Guru</th>
                            <th>Jenis Cuti</th>
                            <th>Tgl Mulai</th>
                            <th>Tgl Selesai</th>
                            <th>Jml Hari</th>
                            <th>Status</th>
                            <th>Tgl Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengajuan as $key => $item)
                        <tr>
                            <td>{{ $pengajuan->firstItem() + $key }}</td>
                            <td><span class="kode-badge">{{ $item->kode_pengajuan }}</span></td>
                            <td>{{ $item->user->nama ?? '-' }}</td>
                            <td>{{ $item->jenis_cuti }}</td>
                            <td><span class="date-text">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</span></td>
                            <td><span class="date-text">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</span></td>
                            <td><span class="hari-badge">{{ $item->jumlah_hari }} hari</span></td>
                            <td>
                                @php $s = strtolower($item->status); @endphp
                                @if($s === 'disetujui')
                                    <span class="status-badge status-disetujui">Disetujui</span>
                                @elseif($s === 'ditolak')
                                    <span class="status-badge status-ditolak">Ditolak</span>
                                @elseif($s === 'ditangguhkan')
                                    <span class="status-badge status-ditangguhkan">Ditangguhkan</span>
                                @elseif($s === 'diverifikasi')
                                    <span class="status-badge status-diverifikasi">Diverifikasi</span>
                                @else
                                    <span class="status-badge status-menunggu">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td><span class="date-text">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="total-label">Total Hari Diajukan:</td>
                            <td><span class="total-val">{{ $pengajuan->sum('jumlah_hari') }} hari</span></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($pengajuan->hasPages())
            <div class="pagination-wrap">
                <span class="pagination-info">
                    Menampilkan {{ $pengajuan->firstItem() }}-{{ $pengajuan->lastItem() }}
                    dari {{ $pengajuan->total() }} data
                </span>
                {{ $pengajuan->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
            @endif

        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>Tidak Ada Data</h5>
                <p>Tidak ada histori pengajuan sesuai filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>

@endsection
