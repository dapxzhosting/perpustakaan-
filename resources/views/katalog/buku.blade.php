@extends('layouts.app')

@section('judul', $buku->judul)

@section('konten')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('katalog') }}">Katalog</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail buku</li>
        </ol>
    </nav>

    <div class="card border-0 shadow-sm" data-aos="fade-up">
        <div class="row g-0">
            <div class="col-md-4 col-lg-3">
                @if ($buku->sampul)
                    <img class="img-fluid w-100 object-fit-cover rounded-start" style="aspect-ratio:3/4" src="{{ asset('storage/'.$buku->sampul) }}" alt="Sampul {{ $buku->judul }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary-emphasis display-1 fw-bold h-100 rounded-start" style="min-height:260px" aria-hidden="true">
                        {{ mb_strtoupper(mb_substr($buku->judul, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="col-md-8 col-lg-9">
                <div class="card-body p-4">
                    <span class="badge text-bg-primary">{{ $buku->kategori->nama }}</span>
                    <p class="text-body-secondary small mt-2 mb-1"><i class="bi bi-upc me-1"></i>{{ $buku->kode_buku }}</p>
                    <h1 class="h2">{{ $buku->judul }}</h1>
                    <p class="text-body-secondary">Ditulis oleh <strong>{{ $buku->penulis }}</strong></p>

                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Penerbit</span><span>{{ $buku->penerbit ?: '-' }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Tahun terbit</span><span>{{ $buku->tahun_terbit }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Kategori</span><span>{{ $buku->kategori->nama }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Stok tersedia</span><span>{{ $buku->stok > 0 ? $buku->stok.' eksemplar' : 'Sedang dipinjam semua' }}</span></li>
                    </ul>

                    <span class="badge fs-6 {{ $buku->stok > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">
                        <i class="bi {{ $buku->stok > 0 ? 'bi-check-circle' : 'bi-x-circle' }} me-1"></i>
                        {{ $buku->stok > 0 ? 'Tersedia untuk dipinjam' : 'Sedang dipinjam semua' }}
                    </span>
                    <div class="mt-4">
                        <a class="btn btn-outline-secondary" href="{{ route('katalog') }}"><i class="bi bi-arrow-left me-1"></i>Kembali ke katalog</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
