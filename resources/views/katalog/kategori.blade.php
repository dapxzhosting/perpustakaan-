@extends('layouts.app')

@section('judul', 'Kategori '.$kategori->nama)

@section('konten')
    <div class="catalog-heading category-heading">
        <div>
            <p class="section-kicker">JELAJAHI KATEGORI</p>
            <h1>{{ $kategori->nama }}</h1>
            <p>{{ $kategori->buku_count }} buku dalam kategori ini.</p>
        </div>
        <a class="button button-quiet" href="{{ route('katalog') }}">← Semua kategori</a>
    </div>
    <form class="catalog-filters category-search" action="{{ route('kategori.show', $kategori) }}" method="GET">
        <div class="catalog-search">
            <label class="visually-hidden" for="category-q">Cari buku di kategori ini</label>
            <input id="category-q" name="q" value="{{ $keyword }}" class="form-control" placeholder="Cari judul, penulis, atau kode buku">
        </div>
        <button class="button button-primary" type="submit">Cari buku</button>
    </form>

    @if ($buku->isNotEmpty())
        <div class="catalog-grid">
            @foreach ($buku as $item)
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
                        <span class="stock-chip {{ $item->stok > 0 ? 'stock-available' : 'stock-empty' }}">
                            {{ $item->stok > 0 ? 'Tersedia '.$item->stok : 'Sedang dipinjam semua' }}
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
        <div class="catalog-pagination">{{ $buku->links() }}</div>
    @else
        <div class="catalog-empty">
            <span aria-hidden="true">▤</span>
            <h3>Belum ada data</h3>
            <p>Tidak ada buku yang cocok dengan pencarian di kategori ini.</p>
        </div>
    @endif
@endsection
