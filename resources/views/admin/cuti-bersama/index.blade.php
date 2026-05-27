@extends('layouts.app')

@section('title', 'Kelola Cuti Bersama')
@section('page-title', 'Kelola Cuti Bersama')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <!-- FORM TAMBAH -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-plus me-2"></i>
                    Tambah Cuti Bersama
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cuti-bersama.store') }}">
                    @csrf
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">
                                Nama Cuti Bersama <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   name="nama"
                                   value="{{ old('nama') }}"
                                   placeholder="Contoh: Hari Raya Idul Fitri"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('tanggal') is-invalid @enderror"
                                   name="tanggal"
                                   value="{{ old('tanggal') }}"
                                   required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Keterangan</label>
                            <input type="text"
                                   class="form-control"
                                   name="keterangan"
                                   value="{{ old('keterangan') }}"
                                   placeholder="Opsional">
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>


        <!-- DAFTAR CUTI BERSAMA -->
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="bi bi-calendar3 me-2"></i>
                    Daftar Cuti Bersama
                </h5>

                <!-- DROPDOWN TAHUN -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                            type="button"
                            id="dropdownTahun"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="true"
                            aria-expanded="false">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $tahun }}
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-2"
                         aria-labelledby="dropdownTahun"
                         style="max-height: 250px; overflow-y: auto; min-width: 120px;">

                        <div class="px-1 mb-2">
                            <input type="text"
                                   class="form-control form-control-sm"
                                   id="cariTahun"
                                   placeholder="Cari tahun..."
                                   autocomplete="off">
                        </div>

                        <div id="listTahun">
                            @foreach($tahunList as $t)
                                <a href="{{ route('admin.cuti-bersama.index', ['tahun' => $t]) }}"
                                   class="dropdown-item rounded {{ $tahun == $t ? 'active' : '' }} item-tahun"
                                   data-tahun="{{ $t }}">
                                    {{ $t }}
                                </a>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

            <div class="card-body">

                @if($cutiBersama->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-1"></i>
                        <p class="mt-2">Belum ada cuti bersama untuk tahun {{ $tahun }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="22%">Tanggal</th>
                                    <th width="30%">Nama</th>
                                    <th>Keterangan</th>
                                    <th width="12%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cutiBersama as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $item->tanggal->isoFormat('dddd, D MMMM Y') }}
                                            </span>
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td class="text-muted">{{ $item->keterangan ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="d-flex gap-1 justify-content-center">

                                                <!-- TOMBOL EDIT -->
                                                <button type="button"
                                                        class="btn btn-sm btn-warning"
                                                        title="Edit"
                                                        onclick="bukaModalEdit(
                                                            {{ $item->id }},
                                                            '{{ addslashes($item->nama) }}',
                                                            '{{ $item->tanggal->format('Y-m-d') }}',
                                                            '{{ addslashes($item->keterangan ?? '') }}'
                                                        )">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <!-- TOMBOL HAPUS -->
                                                <form method="POST"
                                                      action="{{ route('admin.cuti-bersama.destroy', $item) }}"
                                                      onsubmit="return confirm('Hapus cuti bersama ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>


<!-- ================================================ -->
<!-- MODAL EDIT -->
<!-- ================================================ -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">
                    <i class="bi bi-pencil-square me-2"></i>
                    Edit Cuti Bersama
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" id="formEdit">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">
                            Nama Cuti Bersama <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               name="nama"
                               id="editNama"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Tanggal <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               class="form-control"
                               name="tanggal"
                               id="editTanggal"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text"
                               class="form-control"
                               name="keterangan"
                               id="editKeterangan"
                               placeholder="Opsional">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>

    // =============================================
    // DROPDOWN TAHUN
    // =============================================
    document.getElementById('cariTahun').addEventListener('click', function (e) {
        e.stopPropagation();
    });

    document.getElementById('cariTahun').addEventListener('input', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.item-tahun').forEach(function (item) {
            item.style.display = item.getAttribute('data-tahun').includes(keyword) ? 'block' : 'none';
        });
    });

    document.getElementById('dropdownTahun').addEventListener('shown.bs.dropdown', function () {
        const aktif = document.querySelector('.item-tahun.active');
        if (aktif) aktif.scrollIntoView({ block: 'center' });
        document.getElementById('cariTahun').focus();
    });


    // =============================================
    // MODAL EDIT
    // =============================================
    function bukaModalEdit(id, nama, tanggal, keterangan) {

        // Set action form ke route update
        document.getElementById('formEdit').action = '/admin/cuti-bersama/' + id;

        // Isi field dengan data yang ada
        document.getElementById('editNama').value       = nama;
        document.getElementById('editTanggal').value    = tanggal;
        document.getElementById('editKeterangan').value = keterangan;

        // Tampilkan modal
        new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

</script>
@endpush
