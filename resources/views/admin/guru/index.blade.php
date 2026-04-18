@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Kelola Data User')
@section('page-subtitle', 'Tambah, Edit, dan Hapus Data User')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Daftar User</h5>
            </div>
            <a href="{{ route('admin.guru.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah User
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th> <!-- ✅ TAMBAHAN -->
                                <th>Telepon</th>
                                <th>Hak Cuti</th>
                                <th>Sisa Cuti</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guru as $index => $g)
                            <tr>
                                <td>{{ $guru->firstItem() + $index }}</td>
                                <td><strong>{{ $g->nip }}</strong></td>
                                <td>{{ $g->nama }}</td>
                                <td>{{ $g->email }}</td>

                                <!-- ✅ ROLE -->
                                <td>
                                    @if($g->role == 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @elseif($g->role == 'kepala_sekolah')
                                        <span class="badge bg-dark">Kepala Sekolah</span>
                                    @else
                                        <span class="badge bg-primary">Guru</span>
                                    @endif
                                </td>

                                <td>{{ $g->no_telp ?? '-' }}</td>

                                <!-- ✅ HAK CUTI -->
                                <td>
                                    @if($g->role == 'guru')
                                        <span class="badge bg-info">{{ $g->hak_cuti_tahunan }} hari</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- ✅ SISA CUTI -->
                                <td>
                                    @if($g->role == 'guru')
                                        @if($g->hak_cuti == 0)
                                            <span class="badge bg-danger">{{ $g->sisa_hak_cuti }} hari</span>
                                        @elseif($g->hak_cuti <= 3)
                                            <span class="badge bg-warning">{{ $g->sisa_hak_cuti }} hari</span>
                                        @else
                                            <span class="badge bg-success">{{ $g->sisa_hak_cuti }} hari</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- STATUS -->
                                <td>
                                    @if($g->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>

                                <!-- AKSI -->
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.guru.show', $g->id) }}"
                                           class="btn btn-sm btn-info"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.guru.edit', $g->id) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        @if($g->is_active)
                                        <form action="{{ route('admin.guru.destroy', $g->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Nonaktifkan akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Nonaktifkan">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.guru.activate', $g->id) }}"
                                              method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-success"
                                                    title="Aktifkan">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted">Belum ada data user</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $guru->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
