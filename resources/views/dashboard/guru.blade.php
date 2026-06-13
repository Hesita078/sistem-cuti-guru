@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard')

@section('content')

<style>
    /* ===== PALET WARNA SINKRON LOGIN ===== */
    :root {
        --blue-main:   #2d6fe0;
        --blue-dark:   #1a55c4;
        --blue-deep:   #0f2d72;
        --blue-mid:    #3a5699;
        --blue-light:  #dce8fa;
        --blue-xlight: #eaf1fd;
        --blue-icon:   #6a90d0;
        --blue-focus:  rgba(45,111,224,.15);
        --blue-shadow: rgba(45,111,224,.18);
    }

    /* ===== WELCOME CARD ===== */
    .welcome-card {
        background: linear-gradient(160deg, var(--blue-main) 0%, var(--blue-dark) 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px var(--blue-shadow);
    }
    .welcome-card h4 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .welcome-card p {
        font-size: 13px;
        color: rgba(255,255,255,.75);
        margin: 0;
    }

    /* ===== STAT CARDS ===== */
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 4px 20px var(--blue-shadow);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: .25s;
        height: 100%;
        border: 1px solid var(--blue-light);
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(45,111,224,.20);
    }
    .stat-card .stat-label {
        font-size: 12px;
        color: var(--blue-icon);
        font-weight: 600;
        margin-bottom: 6px;
    }
    .stat-card .stat-value {
        font-size: 30px;
        font-weight: 700;
        color: var(--blue-deep);
        line-height: 1;
        margin-bottom: 4px;
    }
    .stat-card .stat-sub {
        font-size: 11px;
        color: var(--blue-icon);
    }
    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .stat-icon-blue   { background: var(--blue-xlight); color: var(--blue-main); }
    .stat-icon-cyan   { background: rgba(13,202,240,.10); color: #0dcaf0; }
    .stat-icon-yellow { background: rgba(255,193,7,.10);  color: #e6a800; }
    .stat-icon-green  { background: rgba(47,143,91,.10);  color: #2f8f5b; }

    /* ===== SECTION CARDS ===== */
    .dash-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px var(--blue-shadow);
        border: 1px solid var(--blue-light);
        overflow: hidden;
        transition: .25s;
        height: 100%;
    }
    .dash-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(45,111,224,.20);
    }
    .dash-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--blue-xlight);
        font-size: 14px;
        font-weight: 600;
        color: var(--blue-deep);
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--blue-xlight);
    }
    .dash-card-header i { color: var(--blue-main); }
    .dash-card-body { padding: 20px; }

    /* ===== BUTTONS ===== */
    .dash-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 11px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: background .2s, transform .15s, box-shadow .2s;
        margin-bottom: 10px;
    }
    .dash-btn:hover { transform: translateY(-1px); }
    .dash-btn:last-child { margin-bottom: 0; }

    .dash-btn-primary {
        background: var(--blue-main);
        color: #fff;
        box-shadow: 0 4px 14px rgba(45,111,224,.35);
    }
    .dash-btn-primary:hover {
        background: var(--blue-dark);
        color: #fff;
        box-shadow: 0 6px 18px rgba(26,85,196,.40);
    }

    .dash-btn-outline {
        background: var(--blue-xlight);
        color: var(--blue-main);
        border: 1.5px solid var(--blue-light);
    }
    .dash-btn-outline:hover {
        background: #ddeaf9;
        color: var(--blue-dark);
    }

    .dash-btn-disabled {
        background: var(--blue-xlight);
        color: var(--blue-icon);
        cursor: not-allowed;
        opacity: .7;
    }

    /* ===== ALERT WARNING ===== */
    .dash-alert {
        background: #fff8e6;
        border: 1px solid #ffd97a;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 12px;
        color: #a07800;
        display: flex;
        gap: 8px;
        align-items: flex-start;
        margin-top: 10px;
    }

    /* ===== TABLE ===== */
    .dash-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .dash-table thead tr {
        background: var(--blue-xlight);
    }
    .dash-table th {
        padding: 10px 12px;
        font-size: 11px;
        font-weight: 700;
        color: var(--blue-mid);
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 1px solid var(--blue-light);
    }
    .dash-table td {
        padding: 12px 12px;
        color: var(--blue-deep);
        border-bottom: 1px solid var(--blue-xlight);
        vertical-align: middle;
    }
    .dash-table tbody tr:last-child td { border-bottom: none; }
    .dash-table tbody tr:hover td { background: var(--blue-xlight); }

    /* ===== BADGES ===== */
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-warning   { background: #fff3cd; color: #997404; }
    .badge-primary   { background: var(--blue-xlight); color: var(--blue-main); }
    .badge-success   { background: #d4edda; color: #2f8f5b; }
    .badge-danger    { background: #ffe5e8; color: #c45f6e; }
    .badge-secondary { background: var(--blue-xlight); color: var(--blue-icon); }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--blue-icon);
    }
    .empty-state i { font-size: 44px; margin-bottom: 12px; display: block; }
    .empty-state p { font-size: 13px; margin-bottom: 16px; }

    /* ===== ACTION BTN TABLE ===== */
    .btn-view {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--blue-xlight);
        border: 1.5px solid var(--blue-light);
        color: var(--blue-main);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
        transition: background .2s;
    }
    .btn-view:hover { background: #ddeaf9; color: var(--blue-dark); }

    /* ===== HISTORI WARNA TEKS ===== */
    .hak-cuti-flow {
        color: var(--blue-icon);
        font-size: 12px;
    }
</style>

<!-- WELCOME -->
<div class="welcome-card mb-4">
    <h4>Selamat Datang, {{ auth()->user()->nama }}!</h4>
    <p>Kelola pengajuan cuti Anda dengan mudah dan efisien.</p>
</div>

<!-- STAT CARDS -->
<div class="row mb-4">
    <div class="col-md-3 col-6 mb-3 mb-md-0">
        <div class="stat-card">
            <div>
                <div class="stat-label">Sisa Hak Cuti</div>
                <div class="stat-value">{{ $hakCuti }}</div>
                <div class="stat-sub">hari</div>
            </div>
            <div class="stat-icon stat-icon-blue">
                <i class="bi bi-calendar-check"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3 mb-md-0">
        <div class="stat-card">
            <div>
                <div class="stat-label">Total Pengajuan</div>
                <div class="stat-value">{{ $totalPengajuan }}</div>
                <div class="stat-sub">pengajuan</div>
            </div>
            <div class="stat-icon stat-icon-cyan">
                <i class="bi bi-file-text"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div>
                <div class="stat-label">Menunggu</div>
                <div class="stat-value">{{ $pengajuanMenunggu }}</div>
                <div class="stat-sub">pengajuan</div>
            </div>
            <div class="stat-icon stat-icon-yellow">
                <i class="bi bi-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card">
            <div>
                <div class="stat-label">Disetujui Kepala Sekolah</div>
                <div class="stat-value">{{ $pengajuanDisetujui }}</div>
                <div class="stat-sub">pengajuan</div>
            </div>
            <div class="stat-icon stat-icon-green">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- AKSI CEPAT + PENGAJUAN TERBARU -->
<div class="row mb-4">

    <!-- Aksi Cepat -->
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="bi bi-lightning"></i> Aksi Cepat
            </div>
            <div class="dash-card-body">

                @if($hakCuti > 0)
                    <a href="{{ route('pengajuan.create') }}" class="dash-btn dash-btn-primary">
                        <i class="bi bi-plus-circle"></i> Ajukan Cuti Baru
                    </a>
                @else
                    <button class="dash-btn dash-btn-disabled" disabled>
                        <i class="bi bi-x-circle"></i> Hak Cuti Habis
                    </button>
                    <div class="dash-alert">
                        <i class="bi bi-exclamation-triangle"></i>
                        <span>Hak cuti Anda sudah habis. Tidak dapat mengajukan cuti.</span>
                    </div>
                @endif

                <a href="{{ route('pengajuan.index') }}" class="dash-btn dash-btn-outline mt-3">
                    <i class="bi bi-list-ul"></i> Lihat Semua Pengajuan
                </a>

                <a href="{{ route('profile.show') }}" class="dash-btn dash-btn-outline">
                    <i class="bi bi-person"></i> Edit Profil
                </a>

            </div>
        </div>
    </div>

    <!-- Pengajuan Terbaru -->
    <div class="col-md-8">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="bi bi-clock-history"></i> Pengajuan Terbaru
            </div>
            <div class="dash-card-body" style="padding:0;">

                @if($pengajuanTerbaru->count() > 0)
                    <div class="table-responsive">
                        <table class="dash-table">
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
                                        {{ $item->tanggal_mulai->format('d/m/Y') }} –
                                        {{ $item->tanggal_selesai->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @php $status = strtolower(trim($item->status)); @endphp
                                        @if($status == 'menunggu verifikasi admin')
                                            <span class="status-badge badge-warning">Menunggu Admin</span>
                                        @elseif($status == 'menunggu persetujuan kepala sekolah')
                                            <span class="status-badge badge-primary">Menunggu Kepsek</span>
                                        @elseif($status == 'disetujui kepala sekolah')
                                            <span class="status-badge badge-success">Disetujui</span>
                                        @elseif($status == 'ditolak admin')
                                            <span class="status-badge badge-danger">Ditolak Admin</span>
                                        @elseif($status == 'ditolak kepala sekolah')
                                            <span class="status-badge badge-danger">Ditolak Kepsek</span>
                                        @else
                                            <span class="status-badge badge-secondary">{{ $item->status ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pengajuan.show', $item->id) }}" class="btn-view">
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
                        <i class="bi bi-inbox"></i>
                        <p>Belum ada pengajuan cuti.</p>
                        @if($hakCuti > 0)
                            <a href="{{ route('pengajuan.create') }}" class="dash-btn dash-btn-primary" style="width:auto;display:inline-flex;">
                                Ajukan Cuti Sekarang
                            </a>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>

<!-- HISTORI CUTI -->
@if($historiCuti->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="dash-card">
            <div class="dash-card-header">
                <i class="bi bi-journal-text"></i> Histori Cuti Disetujui
            </div>
            <div class="dash-card-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="dash-table">
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
                                    {{ $item->tanggal_mulai->format('d/m/Y') }} –
                                    {{ $item->tanggal_selesai->format('d/m/Y') }}
                                </td>
                                <td>{{ $item->jumlah_hari }} hari</td>
                                <td>
                                    <span class="hak-cuti-flow">
                                        {{ $item->hak_cuti_sebelum }} → {{ $item->hak_cuti_sesudah }} hari
                                    </span>
                                </td>
                                <td style="font-size:12px; color: var(--blue-mid);">
                                    {{ $item->tanggal_persetujuan->format('d/m/Y H:i') }}
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
