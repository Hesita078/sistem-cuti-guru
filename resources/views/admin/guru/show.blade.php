@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail Data User')

@section('content')

<div class="pcs-wrap">

    {{-- ROW UTAMA --}}
    <div class="pcs-grid">

        {{-- ===================== KOLOM KIRI ===================== --}}
        <div class="pcs-col-left">

            {{-- PROFIL CARD --}}
            <div class="pcs-card">
                <div class="pcs-card-header">
                    <div class="pcs-header-icon">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <div class="pcs-card-title">Profil User</div>
                        <div class="pcs-card-sub">Informasi lengkap akun</div>
                    </div>
                </div>
                <div class="pcs-card-body">

                    {{-- Avatar --}}
                    <div class="pcs-avatar-wrap">
                        <div class="pcs-avatar">
                            {{ collect(explode(' ', $guru->nama))->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('') }}
                        </div>
                        <div class="pcs-avatar-name">{{ $guru->nama }}</div>
                        <div class="pcs-avatar-email">{{ $guru->email }}</div>
                        <div style="margin-top:8px;">
                            @if($guru->role == 'admin')
                                <span class="pcs-badge pcs-badge-danger">Admin</span>
                            @elseif($guru->role == 'kepala_sekolah')
                                <span class="pcs-badge pcs-badge-secondary">Kepala Sekolah</span>
                            @else
                                <span class="pcs-badge pcs-badge-blue">Guru</span>
                            @endif
                            @if($guru->is_active)
                                <span class="pcs-badge pcs-badge-success">Aktif</span>
                            @else
                                <span class="pcs-badge pcs-badge-secondary">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="pcs-divider"></div>

                    {{-- Info rows --}}
                    <div class="pcs-info-list">
                        <div class="pcs-info-row">
                            <div class="pcs-info-icon"><i class="bi bi-card-text"></i></div>
                            <div>
                                <div class="pcs-info-label">NIP</div>
                                <div class="pcs-info-value">{{ $guru->nip }}</div>
                            </div>
                        </div>
                        <div class="pcs-info-row">
                            <div class="pcs-info-icon"><i class="bi bi-telephone"></i></div>
                            <div>
                                <div class="pcs-info-label">No Telepon</div>
                                <div class="pcs-info-value">{{ $guru->no_telp ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="pcs-info-row">
                            <div class="pcs-info-icon"><i class="bi bi-briefcase"></i></div>
                            <div>
                                <div class="pcs-info-label">Jabatan</div>
                                <div class="pcs-info-value">{{ $guru->jabatan ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="pcs-divider"></div>

                    {{-- Action buttons --}}
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <a href="{{ route('admin.guru.edit', $guru->id) }}" class="pcs-btn-primary" style="justify-content:center;">
                            <i class="bi bi-pencil"></i> Edit Data
                        </a>
                        <a href="{{ route('admin.guru.index') }}" class="pcs-btn-secondary" style="justify-content:center;">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>

            {{-- HAK CUTI CARD (hanya guru) --}}
            @if($guru->role == 'guru')
            @php
                $hakTahunan  = $guru->hak_cuti_tahunan ?? 0;
                $sisaCuti    = $guru->hak_cuti ?? 0;
                $terpakai    = $hakTahunan - $sisaCuti;
                $persentase  = $hakTahunan > 0 ? ($sisaCuti / $hakTahunan) * 100 : 0;
                $barColor    = $persentase > 50 ? '#4a9e72' : ($persentase > 25 ? '#c97a50' : '#c45f6e');
            @endphp
            <div class="pcs-card">
                <div class="pcs-card-header">
                    <div class="pcs-header-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <div class="pcs-card-title">Hak Cuti Tahunan</div>
                        <div class="pcs-card-sub">Rekap penggunaan cuti</div>
                    </div>
                </div>
                <div class="pcs-card-body">

                    {{-- Stat row --}}
                    <div class="pcs-stat-row">
                        <div class="pcs-stat-box pcs-stat-blue">
                            <div class="pcs-stat-num">{{ $hakTahunan }}</div>
                            <div class="pcs-stat-lbl">Hak Tahunan</div>
                        </div>
                        <div class="pcs-stat-box pcs-stat-green">
                            <div class="pcs-stat-num">{{ $sisaCuti }}</div>
                            <div class="pcs-stat-lbl">Sisa Cuti</div>
                        </div>
                        <div class="pcs-stat-box pcs-stat-peach">
                            <div class="pcs-stat-num">{{ $terpakai }}</div>
                            <div class="pcs-stat-lbl">Terpakai</div>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <div style="margin:16px 0 8px;">
                        <div style="display:flex; justify-content:space-between; font-size:12px; color:#7a80a8; margin-bottom:6px;">
                            <span>Sisa hak cuti</span>
                            <span>{{ round($persentase) }}%</span>
                        </div>
                        <div style="background:#eef0f8; border-radius:999px; height:10px; overflow:hidden;">
                            <div style="height:100%; width:{{ $persentase }}%; background:{{ $barColor }}; border-radius:999px; transition:width .4s;"></div>
                        </div>
                    </div>

                    <div class="pcs-divider"></div>

                    {{-- Reset --}}
                    <form action="{{ route('admin.guru.reset-hak-cuti', $guru->id) }}"
                          method="POST"
                          onsubmit="return confirm('Reset hak cuti guru ini ke nilai awal?')">
                        @csrf
                        <button type="submit" class="pcs-btn-outline" style="width:100%; justify-content:center;">
                            <i class="bi bi-arrow-clockwise"></i> Reset Hak Cuti
                        </button>
                    </form>

                </div>
            </div>
            @endif

        </div>

        {{-- ===================== KOLOM KANAN ===================== --}}
        <div class="pcs-col-right">

            {{-- RIWAYAT PENGAJUAN --}}
            <div class="pcs-card">
                <div class="pcs-card-header">
                    <div class="pcs-header-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="pcs-card-title">Riwayat Pengajuan Cuti</div>
                        <div class="pcs-card-sub">Semua pengajuan cuti user ini</div>
                    </div>
                </div>
                <div class="pcs-table-wrap">
                    <table class="pcs-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Jenis Cuti</th>
                                <th>Tanggal</th>
                                <th>Lama</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuanCuti as $p)
                            <tr>
                                <td><span class="pcs-kode">{{ $p->kode_pengajuan }}</span></td>
                                <td style="font-size:13px;">{{ $p->jenis_cuti }}</td>
                                <td>
                                    <span class="pcs-date">
                                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') }}
                                        <span class="pcs-date-sep">s/d</span>
                                        {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td><span class="pcs-hari">{{ $p->jumlah_hari }} hari</span></td>
                                <td>
                                    @if($p->status == 'Menunggu Verifikasi Admin')
                                        <span class="pcs-badge pcs-badge-warning">Menunggu Verifikasi</span>
                                    @elseif($p->status == 'Menunggu Persetujuan Kepala Sekolah')
                                        <span class="pcs-badge pcs-badge-blue">Menunggu Kepsek</span>
                                    @elseif($p->status == 'Disetujui Kepala Sekolah')
                                        <span class="pcs-badge pcs-badge-success">Disetujui</span>
                                    @elseif(str_contains($p->status, 'Ditolak'))
                                        <span class="pcs-badge pcs-badge-danger">{{ $p->status }}</span>
                                    @else
                                        <span class="pcs-badge pcs-badge-secondary">{{ $p->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="pcs-empty">
                                        <i class="bi bi-inbox"></i>
                                        <span>Belum ada riwayat pengajuan cuti</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pengajuanCuti->hasPages())
                <div class="pcs-pagination">{{ $pengajuanCuti->links() }}</div>
                @endif
            </div>

            {{-- HISTORI PENGGUNAAN --}}
            <div class="pcs-card">
                <div class="pcs-card-header">
                    <div class="pcs-header-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <div>
                        <div class="pcs-card-title">Histori Penggunaan Cuti</div>
                        <div class="pcs-card-sub">Perubahan saldo hak cuti</div>
                    </div>
                </div>
                <div class="pcs-table-wrap">
                    <table class="pcs-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Pengajuan</th>
                                <th>Sebelum</th>
                                <th>Digunakan</th>
                                <th>Sesudah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historiCuti as $h)
                            <tr>
                                <td style="font-size:12.5px; color:#7a80a8;">{{ $h->tanggal_persetujuan ? \Carbon\Carbon::parse($h->tanggal_persetujuan)->format('d/m/Y') : $h->created_at->format('d/m/Y') }}</td>
                                <td><span class="pcs-kode">{{ $h->kode_pengajuan }}</span></td>
                                <td><span class="pcs-hari">{{ $h->hak_cuti_sebelum }} hari</span></td>
                                <td>
                                    <span class="pcs-badge pcs-badge-danger" style="font-size:11px;">
                                        −{{ $h->jumlah_hari }} hari
                                    </span>
                                </td>
                                <td><span class="pcs-hari" style="background:#d4f0e2; color:#4a9e72;">{{ $h->hak_cuti_sesudah }} hari</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="pcs-empty">
                                        <i class="bi bi-inbox"></i>
                                        <span>Belum ada histori penggunaan cuti</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($historiCuti->hasPages())
                <div class="pcs-pagination">{{ $historiCuti->links() }}</div>
                @endif
            </div>

        </div>
    </div>
</div>

<style>
.pcs-wrap { }

/* GRID */
.pcs-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 20px;
    align-items: start;
}
.pcs-col-left  { display: flex; flex-direction: column; gap: 20px; }
.pcs-col-right { display: flex; flex-direction: column; gap: 20px; }

/* CARD */
.pcs-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #dce0f0;
    box-shadow: 0 4px 24px rgba(30,40,90,.07);
    overflow: hidden;
}
.pcs-card-header {
    display: flex; align-items: center; gap: 14px;
    padding: 18px 22px;
    border-bottom: 1px solid #eef0f8;
    background: #fafbff;
}
.pcs-header-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: #eef1fb;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.pcs-header-icon i { font-size: 17px; color: #4a5fc1; }
.pcs-card-title { font-size: 14px; font-weight: 700; color: #1a1f3d; }
.pcs-card-sub   { font-size: 11.5px; color: #7a80a8; margin-top: 1px; }
.pcs-card-body  { padding: 20px 22px; }

/* AVATAR */
.pcs-avatar-wrap { text-align: center; padding: 8px 0 4px; }
.pcs-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, #4a5fc1, #764ba2);
    color: #fff; font-size: 28px; font-weight: 700;
    display: inline-flex; align-items: center; justify-content: center;
    margin-bottom: 10px;
    box-shadow: 0 4px 16px rgba(74,95,193,.28);
}
.pcs-avatar-name  { font-size: 15px; font-weight: 700; color: #1a1f3d; }
.pcs-avatar-email { font-size: 12px; color: #7a80a8; margin-top: 2px; }

/* DIVIDER */
.pcs-divider { height: 1px; background: #eef0f8; margin: 16px 0; }

/* INFO LIST */
.pcs-info-list { display: flex; flex-direction: column; gap: 12px; }
.pcs-info-row  { display: flex; align-items: flex-start; gap: 12px; }
.pcs-info-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: #eef1fb; color: #4a5fc1;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0; margin-top: 1px;
}
.pcs-info-label { font-size: 11px; color: #9ba3cc; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
.pcs-info-value { font-size: 13.5px; color: #424770; font-weight: 500; margin-top: 1px; }

/* STAT ROW */
.pcs-stat-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; }
.pcs-stat-box { border-radius: 12px; padding: 12px 10px; text-align: center; }
.pcs-stat-num { font-size: 22px; font-weight: 800; line-height: 1; }
.pcs-stat-lbl { font-size: 10.5px; font-weight: 600; margin-top: 4px; letter-spacing: .02em; }
.pcs-stat-blue  { background: #eef1fb; color: #4a5fc1; }
.pcs-stat-green { background: #d4f0e2; color: #4a9e72; }
.pcs-stat-peach { background: #fce3ce; color: #c97a50; }

/* BADGES */
.pcs-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 999px;
    font-size: 11.5px; font-weight: 600; white-space: nowrap;
    margin: 2px;
}
.pcs-badge-blue      { background: #eef1fb; color: #4a5fc1; }
.pcs-badge-success   { background: #d4f0e2; color: #4a9e72; }
.pcs-badge-warning   { background: #fff4e0; color: #c97a50; }
.pcs-badge-danger    { background: #fad5da; color: #c45f6e; }
.pcs-badge-secondary { background: #edeff8; color: #7a80a8; }

/* TABLE */
.pcs-table-wrap { overflow-x: auto; }
.pcs-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
.pcs-table thead th {
    background: #f7f9ff; padding: 10px 16px;
    font-size: 11px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase;
    color: #9ba3cc; border-bottom: 1.5px solid #eef0f8; white-space: nowrap; text-align: left;
}
.pcs-table tbody td {
    padding: 12px 16px; border-bottom: 1px solid #f0f2fa;
    color: #424770; vertical-align: middle;
}
.pcs-table tbody tr:last-child td { border-bottom: none; }
.pcs-table tbody tr:hover td { background: #f7f9ff; }
.pcs-kode { font-weight: 700; color: #4a5fc1; font-size: 13px; }
.pcs-date { display: flex; flex-direction: column; gap: 1px; font-size: 12.5px; color: #5a6090; }
.pcs-date-sep { color: #9ba3cc; font-size: 11px; }
.pcs-hari { display: inline-block; background: #eef1fb; color: #4a5fc1; border-radius: 8px; padding: 3px 10px; font-size: 12px; font-weight: 600; }

/* EMPTY */
.pcs-empty { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 2rem; color: #9ba3cc; font-size: 13px; }
.pcs-empty i { font-size: 22px; }

/* PAGINATION */
.pcs-pagination { padding: 14px 16px; border-top: 1px solid #eef0f8; }

/* BUTTONS */
.pcs-btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; background: #4a5fc1; color: #fff;
    border: none; border-radius: 10px; font-size: 13.5px; font-weight: 600;
    cursor: pointer; text-decoration: none; font-family: inherit;
    transition: background .2s, transform .15s;
}
.pcs-btn-primary:hover { background: #5a6fd6; transform: translateY(-1px); color: #fff; }
.pcs-btn-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 20px; background: #f7f9ff; color: #5a6090;
    border: 1px solid #dce0f0; border-radius: 10px; font-size: 13.5px; font-weight: 600;
    cursor: pointer; text-decoration: none; transition: background .2s;
}
.pcs-btn-secondary:hover { background: #eef0f8; color: #1a1f3d; }
.pcs-btn-outline {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 16px; background: transparent; color: #4a5fc1;
    border: 1.5px solid #c5d7fa; border-radius: 10px; font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none; font-family: inherit; transition: all .18s;
}
.pcs-btn-outline:hover { background: #4a5fc1; color: #fff; border-color: #4a5fc1; }

/* RESPONSIVE */
@media (max-width: 900px) {
    .pcs-grid { grid-template-columns: 1fr; }
}
</style>

@endsection
