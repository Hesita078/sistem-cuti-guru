@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@push('styles')
<style>
    /* ── Layout ── */
    .lp-wrap {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ── Page intro ── */
    .lp-intro {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .lp-intro-text h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a1f3d;
        margin: 0 0 4px;
    }
    .lp-intro-text p {
        font-size: .825rem;
        color: #7a80a8;
        margin: 0;
    }

    /* ── Cards grid ── */
    .lp-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    @media (max-width: 900px) { .lp-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 560px) { .lp-grid { grid-template-columns: 1fr; } }

    /* ── Report card ── */
    .lp-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e4e8f5;
        box-shadow: 0 2px 12px rgba(30,40,90,.06);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .22s, box-shadow .22s;
    }
    .lp-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 32px rgba(30,40,90,.12);
    }

    /* Coloured top strip */
    .lp-card-strip {
        height: 5px;
        width: 100%;
    }
    .lp-card--blue   .lp-card-strip { background: linear-gradient(90deg, #4a5fc1, #6c8aff); }
    .lp-card--green  .lp-card-strip { background: linear-gradient(90deg, #10b981, #34d399); }
    .lp-card--amber  .lp-card-strip { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

    .lp-card-inner {
        padding: 24px 22px 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 14px;
    }

    /* Icon badge */
    .lp-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }
    .lp-card--blue  .lp-icon { background: #eef1fb; color: #4a5fc1; }
    .lp-card--green .lp-icon { background: #d1fae5; color: #059669; }
    .lp-card--amber .lp-icon { background: #fef3c7; color: #d97706; }

    .lp-card-title {
        font-size: .975rem;
        font-weight: 700;
        color: #1a1f3d;
        line-height: 1.35;
        margin: 0;
    }
    .lp-card-desc {
        font-size: .8rem;
        color: #7a80a8;
        line-height: 1.55;
        margin: 0;
        flex: 1;
    }

    /* Feature chips */
    .lp-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .lp-chip {
        font-size: .68rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        letter-spacing: .02em;
    }
    .lp-card--blue  .lp-chip { background: #eef1fb; color: #4a5fc1; }
    .lp-card--green .lp-chip { background: #d1fae5; color: #059669; }
    .lp-card--amber .lp-chip { background: #fef3c7; color: #d97706; }

    /* CTA button */
    .lp-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: .825rem;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: filter .18s, transform .12s;
        width: 100%;
    }
    .lp-btn:active { transform: scale(.97); }
    .lp-btn--blue  { background: linear-gradient(135deg, #4a5fc1, #6c8aff); color: #fff; box-shadow: 0 4px 14px rgba(74,95,193,.3); }
    .lp-btn--green { background: linear-gradient(135deg, #10b981, #34d399); color: #fff; box-shadow: 0 4px 14px rgba(16,185,129,.28); }
    .lp-btn--amber { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #fff; box-shadow: 0 4px 14px rgba(245,158,11,.3); }
    .lp-btn:hover  { filter: brightness(1.08); color: #fff; }

    /* ── Info section ── */
    .lp-info {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e4e8f5;
        box-shadow: 0 2px 12px rgba(30,40,90,.06);
        overflow: hidden;
    }
    .lp-info-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 22px;
        border-bottom: 1px solid #eef0f8;
        background: #fafbff;
    }
    .lp-info-header-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: #eef1fb;
        color: #4a5fc1;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .lp-info-header-title {
        font-size: .9rem;
        font-weight: 700;
        color: #1a1f3d;
    }
    .lp-info-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    @media (max-width: 640px) { .lp-info-body { grid-template-columns: 1fr; } }

    .lp-info-col {
        padding: 20px 24px;
    }
    .lp-info-col:first-child {
        border-right: 1px solid #eef0f8;
    }
    @media (max-width: 640px) {
        .lp-info-col:first-child { border-right: none; border-bottom: 1px solid #eef0f8; }
    }

    .lp-info-col-title {
        font-size: .775rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #9ba3cc;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .lp-info-col-title::before {
        content: '';
        display: inline-block;
        width: 8px; height: 8px;
        border-radius: 50%;
    }
    .lp-info-col:first-child .lp-info-col-title::before { background: #4a5fc1; }
    .lp-info-col:last-child  .lp-info-col-title::before { background: #10b981; }

    .lp-info-list {
        list-style: none;
        margin: 0; padding: 0;
        display: flex;
        flex-direction: column;
        gap: 9px;
    }
    .lp-info-list li {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: .825rem;
        color: #424770;
    }
    .lp-info-list li::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .lp-info-col:first-child .lp-info-list li::before { background: #c5d7fa; }
    .lp-info-col:last-child  .lp-info-list li::before { background: #6ee7b7; }

    /* export badge */
    .lp-export-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #fef3c7;
        color: #d97706;
        font-size: .68rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        margin-left: 4px;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')

<div class="lp-wrap">

    {{-- ── Cards ── --}}
    <div class="lp-grid">

        {{-- Histori Pengajuan --}}
        <div class="lp-card lp-card--blue">
            <div class="lp-card-strip"></div>
            <div class="lp-card-inner">
                <div class="lp-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <p class="lp-card-title">Histori Pengajuan Cuti &amp; Izin</p>
                </div>
                <p class="lp-card-desc">Lihat semua data pengajuan cuti dengan filter tanggal, status, dan jenis cuti.</p>
                <div class="lp-chips">
                    <span class="lp-chip">Filter Tanggal</span>
                    <span class="lp-chip">Filter Status</span>
                    <span class="lp-chip">Jenis Cuti</span>
                </div>
                <a href="{{ route('laporan.pengajuan') }}" class="lp-btn lp-btn--blue">
                    <i class="bi bi-eye"></i> Lihat Pengajuan
                </a>
            </div>
        </div>

        {{-- Laporan Cuti --}}
        <div class="lp-card lp-card--green">
            <div class="lp-card-strip"></div>
            <div class="lp-card-inner">
                <div class="lp-icon">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div>
                    <p class="lp-card-title">Laporan Cuti</p>
                </div>
                <p class="lp-card-desc">Lihat laporan cuti yang sudah disetujui dengan detail pengurangan hak cuti.</p>
                <div class="lp-chips">
                    <span class="lp-chip">Disetujui</span>
                    <span class="lp-chip">Filter Bulan</span>
                    <span class="lp-chip">Export PDF</span>
                </div>
                <a href="{{ route('laporan.histori') }}" class="lp-btn lp-btn--green">
                    <i class="bi bi-eye"></i> Lihat Laporan
                </a>
            </div>
        </div>

        {{-- Sisa Hak Cuti --}}
        <div class="lp-card lp-card--amber">
            <div class="lp-card-strip"></div>
            <div class="lp-card-inner">
                <div class="lp-icon">
                    <i class="bi bi-calendar2-check"></i>
                </div>
                <div>
                    <p class="lp-card-title">Sisa Hak Cuti Guru</p>
                </div>
                <p class="lp-card-desc">Lihat data sisa hak cuti semua guru secara lengkap dan detail.</p>
                <div class="lp-chips">
                    <span class="lp-chip">Semua Guru</span>
                    <span class="lp-chip">Sisa Cuti</span>
                    <span class="lp-chip">Detail</span>
                </div>
                <a href="{{ route('laporan.hak-cuti') }}" class="lp-btn lp-btn--amber">
                    <i class="bi bi-eye"></i> Lihat Data
                </a>
            </div>
        </div>

    </div>

    {{-- ── Info section ── --}}
    <div class="lp-info">
        <div class="lp-info-header">
            <div class="lp-info-header-icon">
                <i class="bi bi-info-circle"></i>
            </div>
            <div class="lp-info-header-title">Informasi Laporan</div>
        </div>
        <div class="lp-info-body">
            <div class="lp-info-col">
                <div class="lp-info-col-title">Laporan Pengajuan Cuti</div>
                <ul class="lp-info-list">
                    <li>Filter berdasarkan tanggal</li>
                    <li>Filter berdasarkan status</li>
                    <li>Filter berdasarkan jenis cuti</li>
                    <li>Filter berdasarkan guru</li>
                </ul>
            </div>
            <div class="lp-info-col">
                <div class="lp-info-col-title">Histori Cuti</div>
                <ul class="lp-info-list">
                    <li>Data cuti yang sudah disetujui</li>
                    <li>Detail pengurangan hak cuti</li>
                    <li>Filter berdasarkan tahun dan bulan</li>
                    <li>Filter berdasarkan guru</li>
                    <li>Export ke PDF <span class="lp-export-badge"><i class="bi bi-filetype-pdf"></i> PDF</span></li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection
