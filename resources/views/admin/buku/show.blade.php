@extends('layouts.app')
@section('judul', 'Detail Buku')
@section('konten')
    <div class="page-heading">
        <div><p class="section-kicker">DATA MASTER</p><h1>Detail buku</h1></div>
        <a class="button button-quiet" href="{{ route('admin.buku.index') }}"><span aria-hidden="true">←</span> Kembali</a>
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
                <div><dt>Stok tersedia</dt><dd>{{ $buku->stok }} eksemplar</dd></div>
            </dl>
            <div class="admin-book-actions">
                <a class="button button-primary" href="{{ route('admin.buku.edit', $buku) }}">Ubah buku</a>
                <a class="button button-quiet" href="{{ route('admin.buku.index') }}">Kembali ke daftar</a>
            </div>
        </div>
    </section>
@endsection
