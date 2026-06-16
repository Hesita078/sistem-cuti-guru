@extends('layouts.app')

@section('title', 'Laporan Hak Cuti Guru')
@section('page-title', 'Laporan Hak Cuti Guru')

@section('content')

<style>
.hc-stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 1.5rem;
}
.hc-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.375rem 1.25rem;
    box-shadow: 0 4px 20px rgba(74,95,193,.08);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    transition: .25s;
}
.hc-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(74,95,193,.14);
}
.hc-stat-info .s-label {
    font-size: 12px;
    color: #7a80a8;
    font-weight: 600;
    margin-bottom: 6px;
}
.hc-stat-info .s-value {
    font-size: 30px;
    font-weight: 700;
    color: #1a1f3d;
    line-height: 1;
    margin-bottom: 3px;
    letter-spacing: -.02em;
}
.hc-stat-info .s-sub {
    font-size: 11px;
    color: #9ba3cc;
}
.hc-icon-box {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.hc-icon-box i { font-size: 22px; }
.hib-blue  { background: rgba(74,95,193,.12);  color: #4a5fc1; }
.hib-green { background: rgba(47,143,91,.12);  color: #2f8f5b; }
.hib-red   { background: rgba(196,95,110,.12); color: #c45f6e; }

.hc-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.375rem 1.5rem;
    box-shadow: 0 4px 20px rgba(74,95,193,.08);
}
.hc-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.hc-card-head-left {
    display: flex;
    align-items: center;
    gap: 8px;
}
.hc-card-head-left i { font-size: 16px; color: #4a5fc1; }
.hc-card-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1f3d;
    margin: 0;
}

.hc-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.hc-table thead tr { background: #f7f9ff; }
.hc-table th {
    color: #7a80a8;
    font-weight: 700;
    padding: 10px 14px;
    text-align: left;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 1px solid #f0f2fa;
    white-space: nowrap;
}
.hc-table td {
    padding: 12px 14px;
    color: #1a1f3d;
    border-bottom: 1px solid #f7f9ff;
    vertical-align: middle;
}
.hc-table tbody tr:last-child td { border-bottom: none; }
.hc-table tbody tr:hover td { background: #f7f9ff; }
.hc-table tfoot td {
    padding: 12px 14px;
    border-top: 2px solid #f0f2fa;
    font-size: 13px;
}

.hc-pill {
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}
.pill-aman     { background: #dff6ea; color: #2f8f5b; }
.pill-terbatas { background: #fff3cd; color: #b45309; }
.pill-habis    { background: #ffe5e8; color: #c45f6e; }

.hc-days-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #eef1fb;
    color: #4a5fc1;
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 700;
}

.legend-wrap {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid #f0f2fa;
    font-size: 12px;
    color: #7a80a8;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.empty-state { text-align: center; padding: 3rem 1rem; color: #9ba3cc; }
.empty-state i { font-size: 44px; display: block; margin-bottom: .75rem; opacity: .4; }
.empty-state p { font-size: 13px; margin: 0; }

@media (max-width: 768px) {
    .hc-stat-grid { grid-template-columns: 1fr; }
}
</style>

{{-- Stat Cards --}}
<div class="hc-stat-grid">
    <div class="hc-stat-card">
        <div class="hc-stat-info">
            <div class="s-label">Total Guru</div>
            <div class="s-value">{{ $guru->count() }}</div>
            <div class="s-sub">guru terdaftar</div>
        </div>
        <div class="hc-icon-box hib-blue">
            <i class="bi bi-people-fill"></i>
        </div>
    </div>
    <div class="hc-stat-card">
        <div class="hc-stat-info">
            <div class="s-label">Hak Cuti Tersedia</div>
            <div class="s-value">{{ $guru->where('hak_cuti', '>', 0)->count() }}</div>
            <div class="s-sub">guru masih memiliki cuti</div>
        </div>
        <div class="hc-icon-box hib-green">
            <i class="bi bi-check-circle-fill"></i>
        </div>
    </div>
    <div class="hc-stat-card">
        <div class="hc-stat-info">
            <div class="s-label">Hak Cuti Habis</div>
            <div class="s-value">{{ $guru->where('hak_cuti', '<=', 0)->count() }}</div>
            <div class="s-sub">guru tidak ada sisa cuti</div>
        </div>
        <div class="hc-icon-box hib-red">
            <i class="bi bi-x-circle-fill"></i>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="hc-card">
    <div class="hc-card-head">
        <div class="hc-card-head-left">
            <i class="bi bi-calendar-check-fill"></i>
            <h6 class="hc-card-title">Data Sisa Hak Cuti Guru</h6>
        </div>
    </div>

    @if($guru->count() > 0)
    <div class="table-responsive">
        <table class="hc-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th style="text-align:center;">Sisa Hak Cuti</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($guru as $key => $item)
                <tr>
                    <td style="color:#9ba3cc;">{{ $key + 1 }}</td>
                    <td style="color:#9ba3cc;font-size:12px;">{{ $item->nip ?? '-' }}</td>
                    <td><strong>{{ $item->nama }}</strong></td>
                    <td style="color:#7a80a8;font-size:12px;">{{ $item->email }}</td>
                    <td style="color:#7a80a8;font-size:12px;">{{ $item->no_telp ?? '-' }}</td>
                    <td style="text-align:center;">
                        <span class="hc-days-badge">
                            <i class="bi bi-calendar2"></i>
                            {{ $item->hak_cuti_tahunan }} hari
                        </span>
                    </td>
                    <td style="text-align:center;">
                        @if($item->hak_cuti_tahunan > 6)
                            <span class="hc-pill pill-aman">Aman</span>
                        @elseif($item->hak_cuti_tahunan > 0)
                            <span class="hc-pill pill-terbatas">Terbatas</span>
                        @else
                            <span class="hc-pill pill-habis">Habis</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right; color:#7a80a8; font-weight:600;">
                        Total Hak Cuti Semua Guru:
                    </td>
                    <td style="text-align:center;">
                        <span class="hc-days-badge" style="background:#4a5fc1; color:#fff;">
                            <i class="bi bi-calendar2"></i>
                            {{ $guru->sum('hak_cuti') }} hari
                        </span>
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="legend-wrap">
        <strong style="color:#1a1f3d;">Keterangan:</strong>
        <div class="legend-item">
            <span class="hc-pill pill-aman">Aman</span>
            <span>Lebih dari 6 hari</span>
        </div>
        <div class="legend-item">
            <span class="hc-pill pill-terbatas">Terbatas</span>
            <span>1–6 hari tersisa</span>
        </div>
        <div class="legend-item">
            <span class="hc-pill pill-habis">Habis</span>
            <span>Tidak ada sisa cuti</span>
        </div>
    </div>

    @else
    <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <p>Belum ada data guru yang terdaftar.</p>
    </div>
    @endif
</div>

@endsection
