@extends('layouts.app')

@section('judul', $buku->judul)

@section('konten')
    <div class="page-heading">
        <div>
            <p class="section-kicker">DETAIL KOLEKSI</p>
            <h1>Detail buku</h1>
        </div>
        <a class="button button-quiet" href="{{ route('katalog') }}">← Kembali ke katalog</a>
    </div>

    <section class="panel-card public-book-detail">
        <div class="public-book-cover">
            @if ($buku->sampul)
                <img src="{{ asset('storage/'.$buku->sampul) }}" alt="Sampul {{ $buku->judul }}">
            @else
                <span class="catalog-cover-placeholder" aria-hidden="true">{{ mb_strtoupper(mb_substr($buku->judul, 0, 1)) }}</span>
            @endif
        </div>
        <div class="public-book-info">
            <span class="catalog-category">{{ $buku->kategori->nama }}</span>
            <p class="book-code">{{ $buku->kode_buku }}</p>
            <h2>{{ $buku->judul }}</h2>
            <p class="book-byline">Ditulis oleh <strong>{{ $buku->penulis }}</strong></p>
            <dl class="book-metadata">
                <div><dt>Penerbit</dt><dd>{{ $buku->penerbit ?: '-' }}</dd></div>
                <div><dt>Tahun terbit</dt><dd>{{ $buku->tahun_terbit }}</dd></div>
                <div><dt>Kategori</dt><dd>{{ $buku->kategori->nama }}</dd></div>
                <div><dt>Stok tersedia</dt><dd>{{ $buku->stok > 0 ? $buku->stok.' eksemplar' : 'Sedang dipinjam semua' }}</dd></div>
            </dl>
            <span class="stock-chip {{ $buku->stok > 0 ? 'stock-available' : 'stock-empty' }}">
                {{ $buku->stok > 0 ? 'Tersedia untuk dipinjam' : 'Sedang dipinjam semua' }}
            </span>
        </div>
    </section>

@endsection
