@extends('layouts.app')

@section('title', 'Daftar Pengajuan Cuti')
@section('page-title', 'Daftar Pengajuan Cuti')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Daftar Pengajuan Cuti Saya</h5>
            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Ajukan Cuti Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($pengajuan->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pengajuan</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal</th>
                            <th>Jumlah Hari</th>
                            <th>Status</th>
                            <th>Tanggal Ajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengajuan as $key => $item)
                        <tr>
                            <td>{{ $pengajuan->firstItem() + $key }}</td>
                            <td><strong>{{ $item->kode_pengajuan }}</strong></td>
                            <td>{{ $item->jenis_cuti }}</td>
                            <td>
                                <small>
                                    {{ $item->tanggal_mulai->format('d/m/Y') }}<br>
                                    s/d {{ $item->tanggal_selesai->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>{{ $item->jumlah_hari }} hari</td>
                            <td>
                                @if($item->status == 'Menunggu Verifikasi Admin')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock me-1"></i>Menunggu Verifikasi Admin
                                    </span>

                                @elseif($item->status == 'Diverifikasi Admin')
                                    <span class="badge bg-info">
                                        <i class="bi bi-check me-1"></i>Diverifikasi Admin
                                    </span>

                                @elseif($item->status == 'Menunggu Persetujuan Kepala Sekolah')
                                    <span class="badge bg-primary">
                                        <i class="bi bi-hourglass-split me-1"></i>Menunggu Persetujuan Kepala Sekolah
                                    </span>

                                @elseif($item->status == 'Disetujui Kepala Sekolah')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Disetujui Kepala Sekolah
                                    </span>

                                @elseif($item->status == 'Ditolak Admin')
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Ditolak Admin
                                    </span>

                                @elseif($item->status == 'Ditolak Kepala Sekolah') {{-- INI YANG KURANG --}}
                                    <span class="badge bg-secondary
                                    ">
                                        <i class="bi bi-x-circle me-1"></i>Ditolak Kepala Sekolah
                                    </span>

                                @else
                                    <span class="badge bg-secondary">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $item->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('pengajuan.show', $item->id) }}"
                                       class="btn btn-outline-primary"
                                       title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Tombol Edit (Hanya jika masih Menunggu Verifikasi) -->
                                    @if($item->status == 'Menunggu Verifikasi Admin')
                                        <a href="{{ route('pengajuan.edit', $item->id) }}"
                                            class="btn btn-outline-warning"
                                            title="Edit">
                                            <i class="bi bi-pencil"></i>
                                    </a>
                                    @endif

                                    <!-- Tombol Hapus -->
                                    @if($item->status == 'Menunggu Verifikasi Admin')
                                        <button type="button"
                                                class="btn btn-outline-danger"
                                                onclick="confirmDelete({{ $item->id }})"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $pengajuan->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 64px;"></i>
                <h5 class="mt-3 text-muted">Belum Ada Pengajuan</h5>
                <p class="text-muted">Anda belum pernah mengajukan cuti.</p>
                <a href="{{ route('pengajuan.create') }}" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle me-2"></i>Ajukan Cuti Sekarang
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Form Delete (Hidden) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')) {
            const form = document.getElementById('deleteForm');
            form.action = '/pengajuan/' + id;
            form.submit();
        }
    }
</script>
@endpush
