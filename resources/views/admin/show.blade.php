@extends('layouts.app')

@section('title', 'Detail Guru')
@section('page-title', 'Detail Data Guru')
@section('page-subtitle', 'Informasi Lengkap Guru')

@section('content')
<div class="row">
    <!-- Profil Guru -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-person-badge"></i> Profil Guru</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width: 100px; height: 100px;">
                        <i class="bi bi-person-circle fs-1 text-muted"></i>
                    </div>
                </div>

                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted">NIP</td>
                        <td><strong>{{ $guru->nip }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td><strong>{{ $guru->nama }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $guru->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Telepon</td>
                        <td>{{ $guru->telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            @if($guru->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <hr>

                <div class="mb-2">
                    <small class="text-muted">Alamat</small>
                    <p>{{ $guru->alamat ?? '-' }}</p>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.guru.edit', $guru->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Data
                    </a>
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistik Hak Cuti -->
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Hak Cuti</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Hak Cuti Tahunan</span>
                        <strong>{{ $guru->hak_cuti_tahunan }} hari</strong>
                    </div>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-info" style="width: 100%;">
                            {{ $guru->hak_cuti_tahunan }} hari
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Sisa Hak Cuti</span>
                        <strong>{{ $guru->sisa_hak_cuti }} hari</strong>
                    </div>
                    <div class="progress" style="height: 25px;">
                        @php
                            $persentase = ($guru->sisa_hak_cuti / $guru->hak_cuti_tahunan) * 100;
                            $color = $persentase > 50 ? 'success' : ($persentase > 25 ? 'warning' : 'danger');
                        @endphp
                        <div class="progress-bar bg-{{ $color }}" style="width: {{ $persentase }}%;">
                            {{ $guru->sisa_hak_cuti }} hari
                        </div>
                    </div>
                </div>

                <div class="mb-0">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Cuti Terpakai</span>
                        <strong>{{ $guru->hak_cuti_tahunan - $guru->sisa_hak_cuti }} hari</strong>
                    </div>
                </div>

                <hr>

                <form action="{{ route('admin.guru.reset-hak-cuti', $guru->id) }}"
                      method="POST"
                      onsubmit="return confirm('Reset hak cuti guru ini?')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Reset Hak Cuti
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Pengajuan Cuti -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Pengajuan Cuti</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
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
                                <td><strong>{{ $p->kode_pengajuan }}</strong></td>
                                <td>{{ $p->jenis_cuti }}</td>
                                <td>{{ $p->tanggal_mulai->format('d/m/Y') }} - {{ $p->tanggal_selesai->format('d/m/Y') }}</td>
                                <td>{{ $p->lama_cuti }} hari</td>
                                <td>
                                    <span class="badge bg-{{ $p->getStatusBadgeClass() }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Belum ada riwayat pengajuan cuti
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $pengajuanCuti->links() }}
            </div>
        </div>

        <!-- Histori Penggunaan Cuti -->
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-journal-text"></i> Histori Penggunaan Cuti</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Pengajuan</th>
                                <th>Hak Sebelum</th>
                                <th>Digunakan</th>
                                <th>Hak Sesudah</th>
                                <th>Tahun Ajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historiCuti as $h)
                            <tr>
                                <td>{{ $h->created_at->format('d/m/Y') }}</td>
                                <td><strong>{{ $h->pengajuanCuti->kode_pengajuan }}</strong></td>
                                <td>{{ $h->hak_cuti_sebelum }} hari</td>
                                <td><span class="badge bg-danger">-{{ $h->hak_cuti_digunakan }} hari</span></td>
                                <td>{{ $h->hak_cuti_sesudah }} hari</td>
                                <td>{{ $h->tahun_ajaran }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    Belum ada histori penggunaan cuti
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $historiCuti->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
