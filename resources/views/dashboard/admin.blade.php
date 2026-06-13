@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

<style>
/* ========================
   WELCOME BANNER
======================== */
.db-welcome {
    background: linear-gradient(135deg, #4a5fc1 0%, #7b8fe8 100%);
    border-radius: 16px;
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
    color: #fff;
    box-shadow: 0 8px 24px rgba(74,95,193,.25);
}
.db-welcome h2 {
    font-size: 22px;
    font-weight: 700;
    margin: 0 0 6px;
    color: #fff;
}
.db-welcome p {
    font-size: 13.5px;
    margin: 0;
    opacity: .75;
    color: #fff;
}

/* ========================
   STAT GRID
======================== */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 1.5rem;
}
.stat-card {
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
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(74,95,193,.14);
}
.stat-info .s-label {
    font-size: 12px;
    color: #7a80a8;
    font-weight: 600;
    margin-bottom: 6px;
}
.stat-info .s-value {
    font-size: 30px;
    font-weight: 700;
    color: #1a1f3d;
    line-height: 1;
    margin-bottom: 3px;
    letter-spacing: -.02em;
}
.stat-info .s-sub {
    font-size: 11px;
    color: #9ba3cc;
}
.stat-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.stat-icon-box i { font-size: 22px; }
.sib-blue  { background: rgba(74,95,193,.12);  color: #4a5fc1; }
.sib-amber { background: rgba(255,193,7,.12);  color: #ffc107; }
.sib-green { background: rgba(47,143,91,.12);  color: #2f8f5b; }
.sib-red   { background: rgba(196,95,110,.12); color: #c45f6e; }

/* ========================
   LOWER GRID
======================== */
.lower-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 14px;
    align-items: start;
}
.db-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.375rem 1.5rem;
    box-shadow: 0 4px 20px rgba(74,95,193,.08);
    transition: .25s;
}
.db-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(74,95,193,.14);
}
.db-card-head {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}
.db-card-head i {
    font-size: 16px;
    color: #4a5fc1;
}
.db-card-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1f3d;
    margin: 0;
}

/* ========================
   QUICK ACTIONS
======================== */
.qa-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 10px;
    border: 1px solid #dce0f0;
    cursor: pointer;
    transition: all .18s;
    color: #4a5fc1;
    background: #f7f9ff;
}
.qa-btn:last-child { margin-bottom: 0; }
.qa-btn:hover {
    background: #edf0fc;
    border-color: #4a5fc1;
    text-decoration: none;
    color: #4a5fc1;
    transform: translateY(-1px);
}
.qa-btn-primary {
    background: #4a5fc1;
    border-color: #4a5fc1;
    color: #fff;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(74,95,193,.25);
}
.qa-btn-primary:hover {
    background: #5a6fd6;
    border-color: #5a6fd6;
    color: #fff;
}
.qa-badge {
    background: #fff3cd;
    color: #b45309;
    border-radius: 20px;
    padding: 2px 9px;
    font-size: 11px;
    font-weight: 700;
}

/* ========================
   TABLE
======================== */
.db-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.db-table thead tr { background: #f7f9ff; }
.db-table th {
    color: #7a80a8;
    font-weight: 700;
    padding: 10px 12px;
    text-align: left;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 1px solid #f0f2fa;
}
.db-table td {
    padding: 12px 12px;
    color: #1a1f3d;
    border-bottom: 1px solid #f7f9ff;
    vertical-align: middle;
}
.db-table tbody tr:last-child td { border-bottom: none; }
.db-table tbody tr:hover td { background: #f7f9ff; }

.jenis-pill {
    border-radius: 20px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}
.pill-blue  { background: #e8ecfa; color: #4a5fc1; }
.pill-green { background: #dff6ea; color: #2f8f5b; }
.pill-amber { background: #fff3cd; color: #b45309; }
.pill-red   { background: #ffe5e8; color: #c45f6e; }

.detail-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #dce0f0;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #4a5fc1;
    text-decoration: none;
    transition: all .15s;
    background: #f7f9ff;
}
.detail-btn:hover {
    background: #e8ecfa;
    border-color: #4a5fc1;
    color: #4a5fc1;
    text-decoration: none;
}

.empty-state   { text-align: center; padding: 3rem 1rem; color: #9ba3cc; }
.empty-state i { font-size: 44px; display: block; margin-bottom: .75rem; opacity: .4; }
.empty-state p { font-size: 13px; margin: 0; }

/* ========================
   RESPONSIVE
======================== */
@media (max-width: 992px) {
    .stat-grid  { grid-template-columns: repeat(2, 1fr); }
    .lower-grid { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .stat-grid  { grid-template-columns: 1fr; }
}
</style>

<!-- Welcome Banner -->
<div class="db-welcome">
    <h2>Selamat Datang..</h2>
    <p>Monitor dan verifikasi pengajuan cuti guru SD Negeri Kincang 01.</p>
</div>

<!-- Stat Cards -->
<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-info">
            <div class="s-label">Total Guru</div>
            <div class="s-value">{{ $totalGuru }}</div>
            <div class="s-sub">guru terdaftar</div>
        </div>
        <div class="stat-icon-box sib-blue">
            <i class="bi bi-people-fill"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="s-label">Menunggu</div>
            <div class="s-value">{{ $pengajuanMenunggu }}</div>
            <div class="s-sub">perlu diverifikasi</div>
        </div>
        <div class="stat-icon-box sib-amber">
            <i class="bi bi-clock-fill"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="s-label">Diverifikasi</div>
            <div class="s-value">{{ $pengajuanDiverifikasi }}</div>
            <div class="s-sub">disetujui</div>
        </div>
        <div class="stat-icon-box sib-green">
            <i class="bi bi-check-circle-fill"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <div class="s-label">Ditolak</div>
            <div class="s-value">{{ $pengajuanDitolak }}</div>
            <div class="s-sub">tidak disetujui</div>
        </div>
        <div class="stat-icon-box sib-red">
            <i class="bi bi-x-circle-fill"></i>
        </div>
    </div>

</div>

<!-- Lower Row -->
<div class="lower-grid">

    <!-- Quick Actions -->
    <div class="db-card">
        <div class="db-card-head">
            <i class="bi bi-lightning-fill"></i>
            <h6 class="db-card-title">Aksi Cepat</h6>
        </div>

        <a href="{{ route('verifikasi.index') }}" class="qa-btn qa-btn-primary">
            <span style="display:flex;align-items:center;gap:8px;">
                <i class="bi bi-patch-check-fill"></i> Verifikasi Pengajuan
            </span>
            @if($pengajuanMenunggu > 0)
                <span class="qa-badge">{{ $pengajuanMenunggu }}</span>
            @endif
        </a>

        <a href="{{ route('laporan.index') }}" class="qa-btn">
            <i class="bi bi-bar-chart-fill"></i> Lihat Laporan
        </a>

        <a href="{{ route('laporan.hak-cuti') }}" class="qa-btn">
            <i class="bi bi-calendar-check-fill"></i> Hak Cuti Guru
        </a>

        <a href="{{ route('admin.data-guru.index') }}" class="qa-btn">
            <i class="bi bi-people-fill"></i> Manajemen User
        </a>
    </div>

    <!-- Waiting Table -->
    <div class="db-card">
        <div class="db-card-head">
            <i class="bi bi-clock-history"></i>
            <h6 class="db-card-title">Menunggu Verifikasi</h6>
        </div>

        @if($pengajuanTerbaru->count() > 0)
        <div class="table-responsive">
            <table class="db-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Guru</th>
                        <th>Jenis Cuti</th>
                        <th>Tanggal</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $pillMap = [
                            'Sakit'   => 'pill-blue',
                            'Tahunan' => 'pill-green',
                            'Darurat' => 'pill-amber',
                            'Izin'    => 'pill-red',
                        ];
                    @endphp
                    @foreach($pengajuanTerbaru as $item)
                    <tr>
                        <td><strong>{{ $item->kode_pengajuan }}</strong></td>
                        <td>{{ $item->user->nama }}</td>
                        <td>
                            <span class="jenis-pill {{ $pillMap[$item->jenis_cuti] ?? 'pill-blue' }}">
                                {{ $item->jenis_cuti }}
                            </span>
                        </td>
                        <td style="color:#9ba3cc;font-size:12px;">
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} –
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('verifikasi.index') }}" class="detail-btn" title="Tinjau">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="bi bi-check-circle"></i>
            <p>Tidak ada pengajuan menunggu verifikasi.</p>
        </div>
        @endif
    </div>

</div>

@endsection
