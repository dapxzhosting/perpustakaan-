@extends('layouts.app')

@section('judul', $buku->judul)

@section('konten')
    <div class="page-heading">
        <div>
            <p class="section-kicker">DETAIL KOLEKSI</p>
            <h1>{{ $buku->judul }}</h1>
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
            <a class="catalog-category" href="{{ route('kategori.show', $buku->kategori) }}">{{ $buku->kategori->nama }}</a>
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
            @if ($buku->stok > 0)
                <div class="borrow-actions">
                    @guest
                        <a class="button button-primary" href="{{ route('login', ['buku_id' => $buku->id]) }}">Masuk untuk meminjam</a>
                        <p>Belum punya akun anggota? <a href="{{ route('register', ['buku_id' => $buku->id]) }}">Daftar sekarang</a></p>
                    @else
                        @if (auth()->user()->role === 'anggota')
                            <form action="{{ route('buku.pinjam', $buku) }}" method="POST">
                                @csrf
                                <button class="button button-primary" type="submit">Pinjam buku</button>
                            </form>
                            <a class="text-link" href="{{ route('akun.peminjaman') }}">Lihat peminjaman saya <span aria-hidden="true">↗</span></a>
                        @else
                            <p>Masuk dengan akun anggota untuk meminjam buku.</p>
                        @endif
                    @endguest
                </div>
            @endif
            @error('peminjaman')<p class="borrow-error">{{ $message }}</p>@enderror
        </div>
    </section>

    @if ($bukuTerkait->isNotEmpty())
        <section class="related-books">
            <div class="panel-heading">
                <div><p class="section-kicker">DARI KATEGORI YANG SAMA</p><h2>Buku lainnya</h2></div>
            </div>
            <div class="catalog-grid related-grid">
                @foreach ($bukuTerkait as $item)
                    <a class="catalog-card" href="{{ route('buku.show', $item) }}">
                        @if ($item->sampul)
                            <img class="catalog-cover" src="{{ asset('storage/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}">
                        @else
                            <span class="catalog-cover catalog-cover-placeholder" aria-hidden="true">{{ mb_strtoupper(mb_substr($item->judul, 0, 1)) }}</span>
                        @endif
                        <span class="catalog-card-body">
                            <span class="catalog-category">{{ $item->kategori->nama }}</span>
                            <strong>{{ $item->judul }}</strong>
                            <span class="catalog-author">{{ $item->penulis }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
