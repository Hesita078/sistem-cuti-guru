@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Pengajuan Cuti</h3>

    <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai"
                value="{{ $pengajuan->tanggal_mulai }}"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai"
                value="{{ $pengajuan->tanggal_selesai }}"
                class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Alasan</label>
            <textarea name="alasan" class="form-control" required>{{ $pengajuan->alasan }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
