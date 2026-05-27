@extends('layouts.app')

@section('title', 'Histori Pengajuan Cuti')
@section('page-title', 'Histori Pengajuan Cuti')

@section('content')

{{-- FILTER --}}
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.pengajuan') }}">
            <div class="row">

                <div class="col-md-2 mb-3">
                    <label class="form-label">Tahun</label>
                    <select class="form-select" name="tahun">
                        <option value="">-- Semua --</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Bulan</label>
                    <select class="form-select" name="bulan">
                        <option value="">-- Semua --</option>
                        @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $k => $b)
                            <option value="{{ $k+1 }}" {{ request('bulan') == ($k+1) ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">-- Semua --</option>
                        <option value="Menunggu"      {{ request('status') == 'Menunggu'      ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diverifikasi"  {{ request('status') == 'Diverifikasi'  ? 'selected' : '' }}>Diverifikasi</option>
                        <option value="Disetujui"     {{ request('status') == 'Disetujui'     ? 'selected' : '' }}>Disetujui</option>
                        <option value="Ditolak"       {{ request('status') == 'Ditolak'       ? 'selected' : '' }}>Ditolak</option>
                        <option value="Ditangguhkan"  {{ request('status') == 'Ditangguhkan'  ? 'selected' : '' }}>Ditangguhkan</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Guru</label>
                    <select class="form-select" name="user_id">
                        <option value="">-- Semua Guru --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('laporan.pengajuan') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- TABEL --}}
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-journal-text me-2"></i>Histori Pengajuan Cuti
            </h5>
            <a href="{{ route('laporan.cetak-pengajuan', [
                    'bulan'   => request('bulan')   ?? date('m'),
                    'tahun'   => request('tahun')   ?? date('Y'),
                    'status'  => request('status'),
                    'user_id' => request('user_id'),
                ]) }}"
               class="btn btn-danger btn-sm"
               target="_blank">
                <i class="bi bi-file-earmark-pdf me-2"></i>Cetak PDF
            </a>
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
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jumlah Hari</th>
                            <th>Status</th>
                            <th>Tgl Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengajuan as $key => $item)
                        <tr>
                            <td>{{ $pengajuan->firstItem() + $key }}</td>
                            <td><strong class="text-primary">{{ $item->kode_pengajuan }}</strong></td>
                            <td>{{ $item->user->nama ?? '-' }}</td>
                            <td>{{ $item->jenis_cuti }}</td>
                            <td><small>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</small></td>
                            <td><strong>{{ $item->jumlah_hari }} hari</strong></td>
                            <td>
                                @php $s = strtolower($item->status); @endphp
                                @if($s === 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($s === 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($s === 'ditangguhkan')
                                    <span class="badge bg-secondary">Ditangguhkan</span>
                                @elseif($s === 'diverifikasi')
                                    <span class="badge bg-info text-dark">Diverifikasi</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td><small>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="6" class="text-end"><strong>Total Hari Diajukan:</strong></td>
                            <td><strong class="text-primary">{{ $pengajuan->sum('jumlah_hari') }} hari</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-3">{{ $pengajuan->links() }}</div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size:64px;"></i>
                <h5 class="mt-3 text-muted">Tidak Ada Data</h5>
                <p class="text-muted">Tidak ada histori pengajuan sesuai filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>

@endsection
