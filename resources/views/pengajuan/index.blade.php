@extends('layouts.app')

@section('title', 'Daftar Pengajuan Cuti')
@section('page-title', 'Daftar Pengajuan Cuti')

@section('content')

<style>
/* ========================
   CARD WRAPPER
======================== */
.pci-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e6f0;
    overflow: hidden;
}

/* ========================
   HEADER
======================== */
.pci-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #eef0f8;
    flex-wrap: wrap;
}
.pci-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.pci-header-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #eef1fb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.pci-header-icon i { font-size: 17px; color: #4a5fc1; }
.pci-header-title  { font-size: 14px; font-weight: 700; color: #1a1f3d; }

/* ========================
   BUTTONS
======================== */
.pci-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 16px;
    background: #4a5fc1;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s, transform .15s;
    white-space: nowrap;
}
.pci-btn-primary:hover {
    background: #5a6fd6;
    transform: translateY(-1px);
    color: #fff;
    text-decoration: none;
}
.pci-btn-reset {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    background: #fff;
    color: #5a6090;
    border: 1px solid #dce0f0;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s;
    white-space: nowrap;
}
.pci-btn-reset:hover {
    background: #f0f2fa;
    color: #1a1f3d;
    text-decoration: none;
}

/* ========================
   SEARCH BAR
======================== */
.pci-search-wrap {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #eef0f8;
    background: #f7f9ff;
}
.pci-search-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}
.pci-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.pci-input-icon {
    position: absolute;
    left: 11px;
    font-size: 13px;
    color: #b0b8d8;
    pointer-events: none;
}
.pci-input {
    width: 100%;
    background: #fff;
    border: 1px solid #dce0f0;
    border-radius: 10px;
    padding: 8px 12px 8px 34px;
    font-size: 13px;
    color: #1a1f3d;
    outline: none;
    font-family: inherit;
    transition: border-color .2s, box-shadow .2s;
}
.pci-input:focus {
    border-color: #4a5fc1;
    box-shadow: 0 0 0 3px rgba(74,95,193,.10);
}
.pci-input::placeholder { color: #c0c5de; }
.pci-select { cursor: pointer; }
.pci-result-info { font-size: 12px; color: #7a80a8; margin-top: 8px; }
.pci-result-info strong { color: #1a1f3d; }

/* ========================
   TABLE
======================== */
.pci-table-wrap { overflow-x: auto; }
.pci-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.pci-table thead th {
    background: #f7f9ff;
    padding: 10px 14px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #7a80a8;
    border-bottom: 1px solid #eef0f8;
    white-space: nowrap;
    text-align: left;
}
.pci-table tbody td {
    padding: 12px 14px;
    border-bottom: 1px solid #f3f5fc;
    color: #1a1f3d;
    vertical-align: middle;
}
.pci-table tbody tr:last-child td { border-bottom: none; }
.pci-table tbody tr:hover td { background: #f7f9ff; }

.td-no   { color: #9ba3cc; font-size: 12px; }
.pci-kode { font-weight: 700; color: #4a5fc1; font-size: 13px; }
.pci-date { font-size: 12px; color: #5a6090; }
.pci-date-sep { color: #b0b8d8; }
.pci-hari {
    display: inline-block;
    background: #eef1fb;
    color: #4a5fc1;
    border-radius: 8px;
    padding: 3px 10px;
    font-size: 11px;
    font-weight: 600;
}

/* ========================
   BADGES
======================== */
.pci-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
.pci-badge-warning   { background: #fff3cd; color: #b45309; }
.pci-badge-info      { background: #eef1fb; color: #4a5fc1; }
.pci-badge-blue      { background: #eef1fb; color: #4a5fc1; }
.pci-badge-success   { background: #dff6ea; color: #2f8f5b; }
.pci-badge-danger    { background: #ffe5e8; color: #c45f6e; }
.pci-badge-secondary { background: #f0f2fa; color: #7a80a8; }

/* ========================
   ACTION BUTTONS
======================== */
.pci-action-group { display: flex; gap: 6px; align-items: center; }
.pci-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dce0f0;
    cursor: pointer;
    background: #f7f9ff;
    font-size: 14px;
    transition: all .18s;
    text-decoration: none;
}
.pci-action-view   { color: #4a5fc1; }
.pci-action-view:hover   { background: #4a5fc1; color: #fff; border-color: #4a5fc1; }
.pci-action-delete { color: #c45f6e; }
.pci-action-delete:hover { background: #c45f6e; color: #fff; border-color: #c45f6e; }

/* ========================
   PAGINATION
======================== */
.pci-pagination { padding: 1rem 1.5rem; border-top: 1px solid #eef0f8; }

/* ========================
   EMPTY STATE
======================== */
.pci-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    text-align: center;
}
.pci-empty-icon {
    width: 68px;
    height: 68px;
    border-radius: 18px;
    background: #eef1fb;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}
.pci-empty-icon i  { font-size: 30px; color: #9ba3cc; }
.pci-empty-title   { font-size: 15px; font-weight: 700; color: #1a1f3d; margin-bottom: 6px; }
.pci-empty-sub     { font-size: 13px; color: #7a80a8; margin-bottom: 4px; }

/* ========================
   DELETE MODAL
======================== */
@keyframes modalIn {
    from { opacity: 0; transform: scale(.96) translateY(8px); }
    to   { opacity: 1; transform: scale(1)  translateY(0); }
}
.del-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(26,31,61,.4);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.del-box {
    background: #fff;
    border-radius: 16px;
    padding: 1.75rem;
    width: 370px;
    max-width: 90%;
    border: 1px solid #e2e6f0;
    animation: modalIn .2s ease;
}
.del-head  { display: flex; align-items: center; gap: 14px; margin-bottom: 1rem; }
.del-ico   {
    width: 46px; height: 46px; border-radius: 12px;
    background: #ffe5e8;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.del-ico i { font-size: 20px; color: #c45f6e; }
.del-title { font-size: 15px; font-weight: 700; color: #1a1f3d; margin: 0 0 2px; }
.del-sub   { font-size: 12px; color: #7a80a8; margin: 0; }
.del-kode  {
    background: #ffe5e8; color: #c45f6e;
    border-radius: 8px; padding: 8px 14px;
    font-size: 13px; font-weight: 700;
    margin: .75rem 0 .75rem;
}
.del-note  { font-size: 12px; color: #9ba3cc; margin-bottom: 1.5rem; line-height: 1.6; }
.del-note strong { color: #c45f6e; }
.del-actions { display: flex; gap: 10px; }
.del-btn-cancel {
    flex: 1; padding: 9px; border-radius: 10px;
    border: 1px solid #dce0f0; background: #f7f9ff;
    color: #5a6090; font-family: inherit; font-weight: 600;
    font-size: 13px; cursor: pointer; transition: background .2s;
}
.del-btn-cancel:hover { background: #edf0fc; }
.del-btn-confirm {
    flex: 1; padding: 9px; border-radius: 10px;
    border: none; background: #c45f6e; color: #fff;
    font-family: inherit; font-weight: 600;
    font-size: 13px; cursor: pointer; transition: background .2s;
}
.del-btn-confirm:hover { background: #b0505e; }

/* ========================
   RESPONSIVE
======================== */
@media (max-width: 768px) {
    .pci-header, .pci-search-wrap, .pci-pagination { padding: 1rem; }
    .pci-table thead th, .pci-table tbody td { padding: 9px 10px; }
}
</style>

<div class="pci-card">

    {{-- HEADER --}}
    <div class="pci-header">
        <div class="pci-header-left">
            <div class="pci-header-icon">
                <i class="bi bi-list-ul"></i>
            </div>
            <div class="pci-header-title">Kelola Pengajuan Cuti Anda</div>
        </div>
        <a href="{{ route('pengajuan.create') }}" class="pci-btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Ajukan Cuti Baru
        </a>
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="pci-search-wrap">
        <form method="GET" action="{{ route('pengajuan.index') }}">
            <div class="pci-search-row">

                <div class="pci-input-wrap" style="flex:1;min-width:200px;">
                    <i class="bi bi-search pci-input-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari kode, jenis cuti, atau status…" class="pci-input">
                </div>

                <div class="pci-input-wrap" style="min-width:220px;">
                    <i class="bi bi-funnel pci-input-icon"></i>
                    <select name="status" class="pci-input pci-select">
                        <option value="">Semua Status</option>
                        <option value="Menunggu Verifikasi Admin"
                            {{ request('status') == 'Menunggu Verifikasi Admin' ? 'selected' : '' }}>
                            Menunggu Verifikasi Admin</option>
                        <option value="Menunggu Persetujuan Kepala Sekolah"
                            {{ request('status') == 'Menunggu Persetujuan Kepala Sekolah' ? 'selected' : '' }}>
                            Menunggu Persetujuan Kepsek</option>
                        <option value="Disetujui Kepala Sekolah"
                            {{ request('status') == 'Disetujui Kepala Sekolah' ? 'selected' : '' }}>
                            Disetujui Kepala Sekolah</option>
                        <option value="Ditolak Admin"
                            {{ request('status') == 'Ditolak Admin' ? 'selected' : '' }}>
                            Ditolak Admin</option>
                        <option value="Ditolak Kepala Sekolah"
                            {{ request('status') == 'Ditolak Kepala Sekolah' ? 'selected' : '' }}>
                            Ditolak Kepala Sekolah</option>
                    </select>
                </div>

                <button type="submit" class="pci-btn-primary">
                    <i class="bi bi-search"></i> Cari
                </button>

                @if(request('search') || request('status'))
                <a href="{{ route('pengajuan.index') }}" class="pci-btn-reset">
                    <i class="bi bi-x-lg"></i> Reset
                </a>
                @endif

            </div>
        </form>

        @if(request('search') || request('status'))
        <div class="pci-result-info">
            Menampilkan <strong>{{ $pengajuan->total() }}</strong> hasil
            @if(request('search')) untuk "<strong>{{ request('search') }}</strong>"@endif
            @if(request('status')) &mdash; status "<strong>{{ request('status') }}</strong>"@endif
        </div>
        @endif
    </div>

    {{-- TABLE / EMPTY --}}
    @if($pengajuan->count() > 0)

    <div class="pci-table-wrap">
        <table class="pci-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Pengajuan</th>
                    <th>Jenis Cuti</th>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>Status</th>
                    <th>Tgl Ajukan</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan as $key => $item)
                <tr>
                    <td class="td-no">{{ $pengajuan->firstItem() + $key }}</td>
                    <td><span class="pci-kode">{{ $item->kode_pengajuan }}</span></td>
                    <td>{{ $item->jenis_cuti }}</td>
                    <td>
                        <span class="pci-date">
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                            <span class="pci-date-sep"> – </span>
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                        </span>
                    </td>
                    <td><span class="pci-hari">{{ $item->jumlah_hari }} hari</span></td>
                    <td>
                        @if($item->status == 'Menunggu Verifikasi Admin')
                            <span class="pci-badge pci-badge-warning">
                                <i class="bi bi-clock"></i> Menunggu Verifikasi
                            </span>
                        @elseif($item->status == 'Diverifikasi Admin')
                            <span class="pci-badge pci-badge-info">
                                <i class="bi bi-patch-check"></i> Diverifikasi Admin
                            </span>
                        @elseif($item->status == 'Menunggu Persetujuan Kepala Sekolah')
                            <span class="pci-badge pci-badge-blue">
                                <i class="bi bi-hourglass-split"></i> Menunggu Kepsek
                            </span>
                        @elseif($item->status == 'Disetujui Kepala Sekolah')
                            <span class="pci-badge pci-badge-success">
                                <i class="bi bi-check-circle"></i> Disetujui
                            </span>
                        @elseif($item->status == 'Ditolak Admin')
                            <span class="pci-badge pci-badge-danger">
                                <i class="bi bi-x-circle"></i> Ditolak Admin
                            </span>
                        @elseif($item->status == 'Ditolak Kepala Sekolah')
                            <span class="pci-badge pci-badge-secondary">
                                <i class="bi bi-x-circle"></i> Ditolak Kepsek
                            </span>
                        @else
                            <span class="pci-badge pci-badge-secondary">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="pci-date">
                            {{ $item->created_at->format('d/m/Y') }}
                            <span class="pci-date-sep"> {{ $item->created_at->format('H:i') }}</span>
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <div class="pci-action-group" style="justify-content:flex-end;">
                            <a href="{{ route('pengajuan.show', $item->id) }}"
                               class="pci-action-btn pci-action-view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button type="button"
                                    class="pci-action-btn pci-action-delete"
                                    onclick="confirmDelete({{ $item->id }}, '{{ $item->kode_pengajuan }}')"
                                    title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pci-pagination">
        {{ $pengajuan->appends(request()->query())->links() }}
    </div>

    @else

    <div class="pci-empty">
        <div class="pci-empty-icon">
            <i class="bi bi-inbox"></i>
        </div>
        @if(request('search') || request('status'))
            <div class="pci-empty-title">Tidak Ada Hasil</div>
            <div class="pci-empty-sub">Tidak ditemukan pengajuan yang sesuai pencarian Anda.</div>
            <a href="{{ route('pengajuan.index') }}" class="pci-btn-reset" style="margin-top:1rem;">
                <i class="bi bi-arrow-left"></i> Kembali ke semua pengajuan
            </a>
        @else
            <div class="pci-empty-title">Belum Ada Pengajuan</div>
            <div class="pci-empty-sub">Anda belum pernah mengajukan cuti.</div>
            <a href="{{ route('pengajuan.create') }}" class="pci-btn-primary" style="margin-top:1rem;">
                <i class="bi bi-plus-circle-fill"></i> Ajukan Cuti Sekarang
            </a>
        @endif
    </div>

    @endif

</div>

{{-- FORM DELETE --}}
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

{{-- MODAL HAPUS --}}
<div id="deleteModal" class="del-backdrop">
    <div class="del-box">
        <div class="del-head">
            <div class="del-ico"><i class="bi bi-trash3"></i></div>
            <div>
                <p class="del-title">Hapus Pengajuan</p>
                <p class="del-sub">Tindakan ini tidak dapat dibatalkan</p>
            </div>
        </div>
        <p style="font-size:13px;color:#5a6090;margin:0 0 2px;">Apakah Anda yakin ingin menghapus:</p>
        <div id="deleteModalKode" class="del-kode"></div>
        <p class="del-note">Data yang sudah dihapus <strong>tidak dapat dipulihkan</strong>.</p>
        <div class="del-actions">
            <button class="del-btn-cancel" onclick="closeDeleteModal()">
                <i class="bi bi-x-lg"></i> Batal
            </button>
            <button id="btnConfirmDelete" class="del-btn-confirm">
                <i class="bi bi-trash"></i> Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(id, kode) {
    document.getElementById('deleteModalKode').textContent = kode;
    document.getElementById('btnConfirmDelete').onclick = function () {
        const form = document.getElementById('deleteForm');
        form.action = '/pengajuan/' + id;
        form.submit();
    };
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
document.getElementById('deleteModal').addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});
</script>
@endpush
