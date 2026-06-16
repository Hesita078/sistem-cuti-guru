@extends('layouts.app')

@section('title', 'Data Guru')
@section('page-title', 'Data Guru')

@section('content')

<div class="pci-card">

    <div class="pci-header">
        <div class="pci-header-left">
            <div class="pci-header-icon"><i class="bi bi-person-lines-fill"></i></div>
            <div>
                <div class="pci-header-title">Data Guru</div>
                <div class="pci-header-sub">Kelola hak cuti dan status aktif guru</div>
            </div>
        </div>
    </div>

    <div class="pci-search-wrap">
        <form method="GET" action="{{ route('admin.data-guru.index') }}">
            <div class="pci-search-row">
                <div class="pci-input-wrap" style="flex:1; min-width:200px;">
                    <i class="bi bi-search pci-input-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau NIP…" class="pci-input">
                </div>
                <button type="submit" class="pci-btn-primary"><i class="bi bi-search"></i> Cari</button>
                @if(request('search'))
                <a href="{{ route('admin.data-guru.index') }}" class="pci-btn-reset"><i class="bi bi-x-lg"></i> Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="pci-table-wrap">
        <table class="pci-table">
            <thead>
                <tr>
                    <th>Nama / NIP</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="text-align:center;">Hak Cuti</th>
                    <th style="text-align:center;">Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guru as $g)
                <tr>
                    <td>
                        <div class="pci-nama">{{ $g->nama }}</div>
                        <div class="pci-nip">{{ $g->nip ?? '-' }}</div>
                    </td>
                    <td style="font-size:13px; color:#5a6090;">{{ $g->email }}</td>
                    <td>
                        @if($g->role == 'admin')
                            <span class="pci-badge pci-badge-danger">Admin</span>
                        @elseif($g->role == 'kepala_sekolah')
                            <span class="pci-badge pci-badge-secondary">Kepala Sekolah</span>
                        @else
                            <span class="pci-badge pci-badge-blue">Guru</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span style="font-weight:700; color:#4a5fc1;">{{ $g->hak_cuti_tahunan ?? 0 }} hari</span>
                    </td>
                    <td style="text-align:center;">
                        @if($g->is_active)
                            <span class="pci-badge pci-badge-success">Aktif</span>
                        @else
                            <span class="pci-badge pci-badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>

                    <div class="pci-action-group">

                        {{-- Detail --}}
                        <a href="{{ route('admin.data-guru.show', $g->id) }}"
                        class="pci-action-btn pci-action-view" title="Detail Guru">
                            <i class="bi bi-eye"></i>
                        </a>

                        {{-- Reset Hak Cuti (hanya guru) --}}
                        @if($g->role == 'guru')
                        <form method="POST" action="{{ route('admin.guru.reset-hak-cuti', $g->id) }}" style="display:inline;"
                            onsubmit="return confirm('Reset hak cuti {{ addslashes($g->nama) }} ke 12 hari?')">
                            @csrf
                            <button type="submit" class="pci-action-btn pci-action-reset" title="Reset Hak Cuti">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </form>
                        @endif

                        {{-- Hapus --}}
                        <button type="button"
                            class="pci-action-btn pci-action-delete"
                            title="Hapus Data"
                            onclick="confirmDelete('{{ route('admin.data-guru.destroy', $g->id) }}', '{{ addslashes($g->nama) }}')">
                            <i class="bi bi-trash"></i>
                        </button>

                    </div>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="pci-empty">
                            <div class="pci-empty-icon"><i class="bi bi-inbox"></i></div>
                            <div class="pci-empty-title">Belum Ada Data Guru</div>
                            <div class="pci-empty-sub">Belum ada data guru yang terdaftar.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pci-pagination">{{ $guru->links() }}</div>

</div>

<style>
.pci-card { background:#fff; border-radius:20px; box-shadow:0 4px 24px rgba(30,40,90,.08); border:1px solid #dce0f0; overflow:hidden; }
.pci-header { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:20px 28px; border-bottom:1px solid #eef0f8; flex-wrap:wrap; }
.pci-header-left { display:flex; align-items:center; gap:14px; }
.pci-header-icon { width:42px; height:42px; border-radius:12px; background:#eef1fb; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pci-header-icon i { font-size:18px; color:#4a5fc1; }
.pci-header-title { font-size:15px; font-weight:700; color:#1a1f3d; }
.pci-header-sub { font-size:12px; color:#7a80a8; margin-top:1px; }
.pci-btn-primary { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:#4a5fc1; color:#fff; border:none; border-radius:10px; font-size:13.5px; font-weight:600; cursor:pointer; text-decoration:none; transition:background .2s,transform .15s; white-space:nowrap; }
.pci-btn-primary:hover { background:#5a6fd6; transform:translateY(-1px); color:#fff; }
.pci-btn-reset { display:inline-flex; align-items:center; gap:7px; padding:9px 16px; background:#f7f9ff; color:#5a6090; border:1px solid #dce0f0; border-radius:10px; font-size:13.5px; font-weight:600; cursor:pointer; text-decoration:none; transition:background .2s; white-space:nowrap; }
.pci-btn-reset:hover { background:#eef0f8; color:#1a1f3d; }
.pci-search-wrap { padding:18px 28px; border-bottom:1px solid #eef0f8; background:#fafbff; }
.pci-search-row { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
.pci-input-wrap { position:relative; display:flex; align-items:center; }
.pci-input-icon { position:absolute; left:12px; font-size:14px; color:#9ba3cc; pointer-events:none; }
.pci-input { width:100%; background:#f7f9ff; border:1px solid #dce0f0; border-radius:10px; padding:9px 12px 9px 36px; font-size:13px; color:#1a1f3d; outline:none; font-family:inherit; transition:border-color .2s,box-shadow .2s; }
.pci-input:focus { border-color:#4a5fc1; box-shadow:0 0 0 3px rgba(74,95,193,.10); }
.pci-input::placeholder { color:#c0c5de; }
.pci-select { cursor:pointer; }
.pci-table-wrap { overflow-x:auto; }
.pci-table { width:100%; border-collapse:collapse; font-size:13.5px; }
.pci-table thead th { background:#f7f9ff; padding:11px 16px; font-size:11px; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:#9ba3cc; border-bottom:1.5px solid #eef0f8; white-space:nowrap; text-align:left; }
.pci-table tbody td { padding:13px 16px; border-bottom:1px solid #f0f2fa; color:#424770; vertical-align:middle; }
.pci-table tbody tr:last-child td { border-bottom:none; }
.pci-table tbody tr:hover td { background:#f7f9ff; }
.pci-nama { font-weight:600; color:#1a1f3d; font-size:13.5px; }
.pci-nip { font-size:11.5px; color:#9ba3cc; margin-top:2px; }
.pci-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:999px; font-size:11.5px; font-weight:600; white-space:nowrap; }
.pci-badge-blue { background:#eef1fb; color:#4a5fc1; }
.pci-badge-success { background:#d4f0e2; color:#4a9e72; }
.pci-badge-danger { background:#fad5da; color:#c45f6e; }
.pci-badge-secondary { background:#edeff8; color:#7a80a8; }
.pci-action-group { display:flex; gap:6px; align-items:center; }
.pci-action-btn { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; border:1px solid; cursor:pointer; background:transparent; font-size:14px; transition:all .18s; text-decoration:none; }
.pci-action-edit { border-color:#fce3ce; color:#c97a50; }
.pci-action-edit:hover { background:#c97a50; color:#fff; border-color:#c97a50; }
.pci-action-activate { border-color:#d4f0e2; color:#4a9e72; }
.pci-action-activate:hover { background:#4a9e72; color:#fff; border-color:#4a9e72; }
.pci-action-deactivate { border-color:#fce3ce; color:#c97a50; }
.pci-action-deactivate:hover { background:#c97a50; color:#fff; border-color:#c97a50; }
.pci-action-reset { border-color:#c5d7fa; color:#4a5fc1; }
.pci-action-reset:hover { background:#4a5fc1; color:#fff; border-color:#4a5fc1; }
.pci-pagination { padding:16px 28px; border-top:1px solid #eef0f8; }
.pci-empty { display:flex; flex-direction:column; align-items:center; padding:3rem 2rem; text-align:center; }
.pci-empty-icon { width:64px; height:64px; border-radius:16px; background:#eef1fb; display:flex; align-items:center; justify-content:center; margin-bottom:1rem; }
.pci-empty-icon i { font-size:28px; color:#9ba3cc; }
.pci-empty-title { font-size:15px; font-weight:700; color:#1a1f3d; margin-bottom:4px; }
.pci-empty-sub { font-size:13px; color:#7a80a8; }
.pci-action-delete { border-color:#fad5da; color:#c45f6e; }
.pci-action-delete:hover { background:#c45f6e; color:#fff; border-color:#c45f6e; }
.pci-action-view { border-color:#c5d7fa; color:#4a5fc1; }
.pci-action-view:hover { background:#4a5fc1; color:#fff; border-color:#4a5fc1; }
</style>

{{-- Modal Konfirmasi Hapus --}}
<div id="deleteModal" style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center;">
    <div onclick="closeDelete()" style="position:absolute; inset:0; background:rgba(20,25,60,.45); backdrop-filter:blur(3px);"></div>
    <div style="position:relative; background:#fff; border-radius:18px; padding:32px 28px; width:100%; max-width:380px; margin:16px; box-shadow:0 8px 40px rgba(20,25,60,.18); text-align:center;">

        <div style="width:56px; height:56px; border-radius:16px; background:#fce8ea; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
            <i class="bi bi-trash" style="font-size:24px; color:#c45f6e;"></i>
        </div>

        <div style="font-size:16px; font-weight:700; color:#1a1f3d; margin-bottom:8px;">Hapus Data Guru?</div>
        <div style="font-size:13px; color:#7a80a8; margin-bottom:24px;">
            Data <strong id="deleteNama"></strong> akan dihapus permanen dan tidak dapat dikembalikan.
        </div>

        <div style="display:flex; gap:10px; justify-content:center;">
            <button onclick="closeDelete()"
                style="flex:1; padding:10px; border-radius:10px; border:1px solid #dce0f0; background:#f7f9ff; color:#5a6090; font-size:13.5px; font-weight:600; cursor:pointer;">
                Batal
            </button>
            <form id="deleteForm" method="POST" style="flex:1;">
                @csrf
                @method('DELETE')
                <button type="submit"
                    style="width:100%; padding:10px; border-radius:10px; border:none; background:#c45f6e; color:#fff; font-size:13.5px; font-weight:600; cursor:pointer;">
                    Ya, Hapus
                </button>
            </form>
        </div>

    </div>
</div>

<script>
function confirmDelete(url, nama) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeDelete() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>

@endsection
