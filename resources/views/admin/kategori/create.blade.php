@extends('layouts.app')
@section('judul', 'Tambah Kategori')
@section('konten')
    <div class="page-heading"><h1>Tambah kategori</h1><a class="btn btn-outline-secondary" href="{{ route('admin.kategori.index') }}">Kembali</a></div>
    <div class="card p-4">
        <form action="{{ route('admin.kategori.store') }}" method="POST">
            @csrf
            <label for="nama" class="form-label">Nama kategori</label>
            <input id="nama" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" required maxlength="100">
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <button class="btn btn-primary mt-4">Simpan kategori</button>
        </form>
    </div>
@endsection
