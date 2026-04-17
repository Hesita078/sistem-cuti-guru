@extends('layouts.app')

@section('title', 'Verifikasi Pengajuan Cuti')
@section('page-title', 'Verifikasi Pengajuan Cuti')

@section('content')

<div class="card shadow-sm border-0">

    <!-- HEADER -->
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-list-check me-2 text-primary"></i>
            Daftar Pengajuan Menunggu Verifikasi
        </h5>
    </div>

    <div class="card-body">

        @if($pengajuan->count() > 0)

        <div class="table-responsive">
            <table class="table align-middle table-hover">

                <!-- TABLE HEAD -->
                <thead class="table-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>Nama Guru</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Diajukan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <!-- TABLE BODY -->
                <tbody>
                    @foreach($pengajuan as $key => $item)
                    <tr>

                        <td>{{ $pengajuan->firstItem() + $key }}</td>

                        <td>
                            <span class="fw-semibold text-primary">
                                {{ $item->kode_pengajuan }}
                            </span>
                        </td>

                        <td>
                            <div class="fw-semibold">{{ $item->user->nama }}</div>
                            <small class="text-muted">{{ $item->user->nip }}</small>
                        </td>

                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $item->jenis_cuti }}
                            </span>
                        </td>

                        <td>
                            <small>
                                {{ $item->tanggal_mulai->format('d/m/Y') }} <br>
                                <span class="text-muted">s/d</span>
                                {{ $item->tanggal_selesai->format('d/m/Y') }}
                            </small>
                        </td>

                        <td>
                            <span class="fw-semibold">{{ $item->jumlah_hari }}</span> hari
                        </td>

                        <td>
                            <small class="text-muted">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </small>
                        </td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#modalDetail{{ $item->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>

                    </tr>

                    <!-- ================= MODAL DETAIL ================= -->
                    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content border-0 shadow">

                                <div class="modal-header">
                                    <h5 class="modal-title fw-semibold">
                                        Detail Pengajuan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <p><strong>Nama:</strong><br>{{ $item->user->nama }}</p>
                                            <p><strong>NIP:</strong><br>{{ $item->user->nip }}</p>
                                            <p><strong>Jenis Cuti:</strong><br>{{ $item->jenis_cuti }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <p><strong>Tanggal:</strong><br>
                                                {{ $item->tanggal_mulai->format('d M Y') }} -
                                                {{ $item->tanggal_selesai->format('d M Y') }}
                                            </p>

                                            <p><strong>Jumlah Hari:</strong><br>
                                                <span class="fw-bold text-primary">
                                                    {{ $item->jumlah_hari }} hari
                                                </span>
                                            </p>

                                            <p><strong>Sisa Hak Cuti:</strong><br>
                                                {{ $item->user->hak_cuti }} hari
                                            </p>
                                        </div>
                                    </div>

                                    <hr>

                                    <h6 class="fw-semibold">Alasan</h6>
                                    <p class="text-muted">{{ $item->alasan }}</p>

                                    @if($item->file_pendukung)
                                        <a href="{{ Storage::url($item->file_pendukung) }}"
                                           target="_blank"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-file-earmark-text"></i> Lihat File
                                        </a>
                                    @endif

                                </div>

                                <div class="modal-footer">

                                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                        Tutup
                                    </button>

                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTolak{{ $item->id }}">
                                        Tolak
                                    </button>

                                    <form action="{{ route('verifikasi.setuju', $item->id) }}"
                                          method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm">
                                            Setujui
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- ================= MODAL TOLAK ================= -->
                    <div class="modal fade" id="modalTolak{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form action="{{ route('verifikasi.tolak', $item->id) }}" method="POST">
                                    @csrf

                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Tolak Pengajuan</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label class="form-label">Alasan</label>
                                            <textarea name="catatan_admin"
                                                class="form-control"
                                                rows="4"
                                                required></textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-danger">Tolak</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="mt-3">
            {{ $pengajuan->links() }}
        </div>

        @else
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-success" style="font-size: 60px;"></i>
                <h5 class="mt-3">Tidak ada pengajuan</h5>
                <p class="text-muted">Semua pengajuan sudah diproses.</p>
            </div>
        @endif

    </div>
</div>

@endsection
