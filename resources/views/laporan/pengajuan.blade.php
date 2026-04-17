@extends('layouts.app')

@section('title', 'Laporan Pengajuan Cuti')
@section('page-title', 'Laporan Pengajuan Cuti')

@section('content')
<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.pengajuan') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date"
                           class="form-control"
                           name="tanggal_mulai"
                           value="{{ request('tanggal_mulai') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date"
                           class="form-control"
                           name="tanggal_selesai"
                           value="{{ request('tanggal_selesai') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">-- Semua Status --</option>
                        <option value="Menunggu Verifikasi Admin" {{ request('status') == 'Menunggu Verifikasi Admin' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="Menunggu Persetujuan Kepala Sekolah" {{ request('status') == 'Menunggu Persetujuan Kepala Sekolah' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak Admin" {{ request('status') == 'Ditolak Admin' ? 'selected' : '' }}>Ditolak Admin</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Jenis Cuti</label>
                    <select class="form-select" name="jenis_cuti">
                        <option value="">-- Semua Jenis --</option>
                        <option value="Cuti Tahunan" {{ request('jenis_cuti') == 'Cuti Tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                        <option value="Cuti Sakit" {{ request('jenis_cuti') == 'Cuti Sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                        <option value="Cuti Melahirkan" {{ request('jenis_cuti') == 'Cuti Melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                        <option value="Cuti Bersama" {{ request('jenis_cuti') == 'Cuti Bersama' ? 'selected' : '' }}>Cuti Bersama</option>
                        <option value="Cuti Penting" {{ request('jenis_cuti') == 'Cuti Penting' ? 'selected' : '' }}>Cuti Penting</option>
                    </select>
                </div>

                @if(!auth()->user()->isGuru())
                <div class="col-md-3 mb-3">
                    <label class="form-label">Guru</label>
                    <select class="form-select" name="user_id">
                        <option value="">-- Semua Guru --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-2"></i>Filter
                    </button>
                    <a href="{{ route('laporan.pengajuan') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Data Pengajuan Cuti</h5>
            <button class="btn btn-success btn-sm" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Cetak
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($pengajuan->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Guru</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal</th>
                            <th>Jumlah Hari</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengajuan as $key => $item)
                        <tr>
                            <td>{{ $pengajuan->firstItem() + $key }}</td>
                            <td><strong>{{ $item->kode_pengajuan }}</strong></td>
                            <td>{{ $item->user->nama }}</td>
                            <td>{{ $item->jenis_cuti }}</td>
                            <td>
                                <small>
                                    {{ $item->tanggal_mulai->format('d/m/Y') }} -
                                    {{ $item->tanggal_selesai->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>{{ $item->jumlah_hari }} hari</td>
                            <td>
                                @if($item->status == 'Menunggu Verifikasi Admin')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @elseif($item->status == 'Menunggu Persetujuan Kepala Sekolah')
                                    <span class="badge bg-primary">Menunggu Persetujuan</span>
                                @elseif($item->status == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td><small>{{ $item->created_at->format('d/m/Y H:i') }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end"><strong>Total Hari Cuti:</strong></td>
                            <td><strong class="text-primary">{{ $pengajuan->sum('jumlah_hari') }} hari</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $pengajuan->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 64px;"></i>
                <h5 class="mt-3 text-muted">Tidak Ada Data</h5>
                <p class="text-muted">Tidak ada data pengajuan sesuai filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>
@endsection
