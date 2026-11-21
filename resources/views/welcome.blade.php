@extends('layouts.app')

@section('judul', 'Katalog')

@section('konten')
    <section class="home-hero">
        <div class="hero-copy">
            <div class="hero-kicker"><span class="kicker-dot"></span> PERPUSTAKAAN SEKOLAH</div>
            <h1>Setiap halaman punya <span>cerita.</span></h1>
            <p class="hero-description">Tempat yang nyaman untuk menemukan pengetahuan, merawat rasa ingin tahu, dan tumbuh bersama lewat membaca.</p>
            <div class="hero-actions">
                @guest
                    <a class="button button-primary" href="{{ route('login') }}">Login akun <svg class="icon-inline icon-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17 17 7M7.5 7H17v9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    <a class="button button-member" href="{{ route('register') }}">Daftar anggota <svg class="icon-inline icon-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17 17 7M7.5 7H17v9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                @else
                    @if (auth()->user()->role === 'petugas')
                        <a class="button button-primary" href="{{ route('admin.peminjaman.index') }}">Buka panel petugas <svg class="icon-inline icon-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17 17 7M7.5 7H17v9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    @else
                        <a class="button button-primary" href="{{ route('akun.peminjaman') }}">Peminjaman saya <svg class="icon-inline icon-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17 17 7M7.5 7H17v9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                    @endif
                @endguest
            </div>
            <div class="hero-note">
                <span>Ruang kecil untuk gagasan besar.</span>
            </div>
        </div>
        <div class="hero-art">
            <img class="hero-library-image" src="{{ asset('images/library-illustration.jpg') }}" alt="Ilustrasi perpustakaan sekolah dengan siswa membaca">
            <div class="art-caption"><span>Temukan halaman<br>favoritmu.</span></div>
            <div class="art-pill"><span class="pill-dot"></span> Buka wawasan baru</div>
        </div>
    </section>

    <section class="catalog-section" id="katalog">
        <div class="catalog-heading">
            <div>
                <p class="section-kicker">KOLEKSI PILIHAN</p>
                <h2>Jelajahi katalog buku</h2>
                <p>Cari judul, penulis, atau kode buku, lalu temukan bacaan yang kamu suka.</p>
            </div>
        </div>
        <form class="catalog-filters" action="{{ route('katalog') }}" method="GET">
            <div class="catalog-search">
                <label class="visually-hidden" for="catalog-q">Cari buku</label>
                <input id="catalog-q" name="q" value="{{ $keyword }}" class="form-control" placeholder="Cari judul, penulis, atau kode buku">
            </div>
            <div>
                <label class="visually-hidden" for="catalog-kategori">Pilih kategori</label>
                <select id="catalog-kategori" name="kategori" class="form-select">
                    <option value="">Semua kategori</option>
                    @foreach ($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}" @selected($kategoriTerpilih === $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button class="button button-primary" type="submit">Cari buku <svg class="icon-inline" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.3" stroke="currentColor" stroke-width="1.8"/><path d="m15.4 15.4 4.3 4.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>
            @if ($keyword !== '' || $kategoriTerpilih)
                <a class="button button-quiet" href="{{ route('katalog') }}">Reset</a>
            @endif
        </form>

        @if ($kategoriList->isNotEmpty())
            <section class="category-shortcuts" aria-labelledby="category-shortcuts-title">
                <div class="category-shortcuts-heading">
                    <div>
                        <p class="section-kicker">JELAJAHI KOLEKSI</p>
                        <h3 id="category-shortcuts-title">Pilih kategori bacaan</h3>
                    </div>
                    <p>Temukan buku sesuai minatmu.</p>
                </div>
                <div class="category-shortcut-grid">
                    @foreach ($kategoriList as $kategori)
                        <a class="category-shortcut" href="{{ route('kategori.show', $kategori) }}">
                            <span class="category-shortcut-count">{{ number_format($kategori->buku_count, 0, ',', '.') }} judul</span>
                            <strong>{{ $kategori->nama }}</strong>
                            <span class="category-shortcut-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M7 17 17 7M7.5 7H17v9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

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
                <span class="catalog-empty-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 7.2C9.6 5.7 6.4 5.3 3.5 6.2v12c3-.9 6.1-.5 8.5 1m0-12c2.4-1.5 5.6-1.9 8.5-1v12c-3-.9-6.1-.5-8.5 1m0-12v12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.4 9.6h2.4m6.4 0h2.4M6.4 12.6h2.4m6.4 0h2.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </span>
                <h3>Belum ada data</h3>
                <p>Buku yang cocok belum ditemukan. Coba kata kunci atau kategori lain.</p>
            </div>
        @endif
    </section>

    <section class="welcome-grid" id="tentang">
        <div class="welcome-intro">
            <p class="section-kicker">LEBIH DARI SEKADAR BUKU</p>
            <h2>Belajar terasa lebih dekat saat semuanya tertata.</h2>
            <p>Perpustakaan sekolah menjadi rumah bagi koleksi dan komunitas pembaca. Temukan pengalaman membaca yang lebih teratur dan menyenangkan.</p>
        </div>
        <article class="welcome-card">
            <span class="feature-icon icon-sage" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"><path d="M12 7.2C9.6 5.7 6.4 5.3 3.5 6.2v12c3-.9 6.1-.5 8.5 1m0-12c2.4-1.5 5.6-1.9 8.5-1v12c-3-.9-6.1-.5-8.5 1m0-12v12" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.4 9.6h2.4m6.4 0h2.4M6.4 12.6h2.4m6.4 0h2.4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            </span>
            <h3>Koleksi tertata</h3>
            <p>Data buku dan kategori dikelola dalam satu ruang yang mudah digunakan.</p>
        </article>
        <article class="welcome-card">
            <span class="feature-icon icon-peach" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3.2" stroke="currentColor" stroke-width="1.7"/><path d="M3.7 19v-1.4c0-2.1 1.7-3.8 3.8-3.8h3c2.1 0 3.8 1.7 3.8 3.8V19m1.1-13.6a3.2 3.2 0 0 1 0 6.2m1.2 2.3h.7c1.6 0 2.9 1.3 2.9 2.9V19" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3>Komunitas pembaca</h3>
            <p>Data anggota tersimpan rapi agar layanan perpustakaan lebih teratur.</p>
        </article>
        <article class="welcome-card">
            <span class="feature-icon icon-lilac" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none"><path d="m12 3.5 7 2.8v4.9c0 4.3-2.7 7.3-7 9.4-4.3-2.1-7-5.1-7-9.4V6.3l7-2.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m9 12.2 2 2 4.2-4.4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3>Akses petugas</h3>
            <p>Panel khusus membantu petugas mengelola informasi dengan aman.</p>
        </article>
    </section>

    <section class="library-showcase" aria-label="Suasana perpustakaan">
        <img src="{{ asset('images/library-shelves.jpg') }}" alt="Rak buku di dalam perpustakaan">
        <div class="showcase-copy">
            <p class="section-kicker">RUANG UNTUK BERTUMBUH</p>
            <h2>Temukan ruang untuk cerita baru.</h2>
            <p>Di antara deretan buku, selalu ada pengetahuan dan inspirasi yang menunggu untuk ditemukan.</p>
        </div>
        <span class="showcase-tag">Nyaman untuk belajar</span>
    </section>

    <section class="home-cta">
        <div>
            <p class="section-kicker">UNTUK PETUGAS PERPUSTAKAAN</p>
            <h2>Siap merapikan koleksi hari ini?</h2>
        </div>
        <span class="home-cta-illustration">
            <img src="{{ asset('images/books-learning.jpg') }}" alt="Ilustrasi buku, pensil, dan lampu belajar">
        </span>
        <a class="button button-light" href="{{ route('login') }}">Buka panel petugas <svg class="icon-inline icon-arrow" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 17 17 7M7.5 7H17v9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </section>
@endsection
