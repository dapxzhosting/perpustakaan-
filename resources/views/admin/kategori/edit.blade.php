@extends('layouts.admin')
@section('judul', 'Ubah Kategori')
@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><span class="page-pretitle d-block">DATA MASTER</span><h1 class="page-title">Ubah kategori</h1></div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.kategori.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card" style="max-width:640px">
        <div class="card-body p-4">
            <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST">
                @csrf @method('PUT')
                <label for="nama" class="form-label">Nama kategori</label>
                <input id="nama" name="nama" value="{{ old('nama', $kategori->nama) }}" class="form-control @error('nama') is-invalid @enderror" required maxlength="100" autofocus>
                @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Simpan perubahan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.kategori.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
