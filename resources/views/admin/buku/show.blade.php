@extends('layouts.admin')
@section('judul', 'Detail Buku')
@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><span class="page-pretitle d-block">DATA MASTER</span><h1 class="page-title">Detail buku</h1></div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.buku.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card">
        <div class="row g-0">
            <div class="col-md-4 col-lg-3">
                @if ($buku->sampul)
                    <img class="img-fluid w-100 object-fit-cover rounded-start" style="aspect-ratio:3/4" src="{{ asset('storage/'.$buku->sampul) }}" alt="Sampul {{ $buku->judul }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary-emphasis display-1 fw-bold h-100 rounded-start" style="min-height:260px" aria-hidden="true">{{ mb_strtoupper(mb_substr($buku->judul, 0, 1)) }}</div>
                @endif
            </div>
            <div class="col-md-8 col-lg-9">
                <div class="card-body p-4">
                    <span class="badge text-bg-primary">{{ $buku->kategori->nama }}</span>
                    <p class="text-body-secondary small mt-2 mb-1"><i class="bi bi-upc me-1"></i>{{ $buku->kode_buku }}</p>
                    <h2 class="h3">{{ $buku->judul }}</h2>
                    <p class="text-body-secondary">Ditulis oleh <strong>{{ $buku->penulis }}</strong></p>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Penerbit</span><span>{{ $buku->penerbit ?: '-' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Tahun terbit</span><span>{{ $buku->tahun_terbit }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Kategori</span><span>{{ $buku->kategori->nama }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Stok tersedia</span><span>{{ $buku->stok }} eksemplar</span></li>
                    </ul>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-primary" href="{{ route('admin.buku.edit', $buku) }}"><i class="bi bi-pencil me-1"></i>Ubah buku</a>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.buku.index') }}">Kembali ke daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
