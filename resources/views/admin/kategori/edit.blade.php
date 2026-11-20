@extends('layouts.app')
@section('judul', 'Ubah Kategori')
@section('konten')
    <div class="page-heading">
        <div><p class="section-kicker">DATA MASTER</p><h1>Ubah kategori</h1></div>
        <a class="button button-quiet" href="{{ route('admin.kategori.index') }}"><span aria-hidden="true">←</span> Kembali</a>
    </div>
    <div class="panel-card form-card">
        <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST">
            @csrf @method('PUT')
            <label for="nama" class="form-label">Nama kategori</label>
            <input id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}" class="form-control @error('nama') is-invalid @enderror" required maxlength="100">
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-actions">
        <button class="button button-primary" type="submit">Simpan perubahan</button>
        <a class="button button-quiet" href="{{ route('admin.kategori.index') }}">Batal</a>
    </div>
        </form>
    </div>
@endsection
