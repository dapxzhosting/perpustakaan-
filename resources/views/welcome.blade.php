@extends('layouts.app')

@section('judul', 'Katalog')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
@endpush

@section('konten')
    {{-- HERO --}}
    <section class="card border-0 shadow overflow-hidden mb-5">
        <div class="row g-0 align-items-center">
            <div class="col-lg-6 p-4 p-lg-5" data-aos="fade-right">
                <span class="badge text-bg-primary-subtle text-primary-emphasis mb-3 animate__animated animate__fadeInDown"><i class="bi bi-book-half me-1"></i>PERPUSTAKAAN SEKOLAH</span>
                <h1 class="display-5 fw-bold animate__animated animate__fadeInUp">Setiap halaman punya <span class="text-primary">cerita.</span></h1>
                <p class="lead text-body-secondary animate__animated animate__fadeInUp animate__delay-1s">Tempat yang nyaman untuk menemukan pengetahuan, merawat rasa ingin tahu, dan tumbuh bersama lewat membaca.</p>
                <div class="d-flex flex-wrap gap-2 mt-4 animate__animated animate__fadeInUp animate__delay-1s">
                    <a class="btn btn-primary btn-lg animate__animated animate__pulse animate__delay-2s" href="{{ route('katalog') }}#katalog"><i class="bi bi-search me-1"></i>Jelajahi katalog</a>
                    @auth
                        <a class="btn btn-outline-primary btn-lg" href="{{ route('admin.peminjaman.index') }}"><i class="bi bi-speedometer2 me-1"></i>Panel petugas</a>
                    @else
                        <a class="btn btn-outline-primary btn-lg" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Login petugas</a>
                    @endauth
                </div>
            </div>
            <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
                <img class="img-fluid w-100 object-fit-cover" style="max-height:420px" src="{{ asset('images/library-illustration.jpg') }}" alt="Ilustrasi perpustakaan sekolah dengan siswa membaca">
            </div>
        </div>
    </section>

    {{-- BUKU TERBARU (carousel Swiper; disembunyikan saat sedang mencari/memfilter) --}}
    @if ($bukuTerbaru->isNotEmpty() && $keyword === '' && ! $kategoriTerpilih)
        <section class="mb-5" data-aos="fade-up">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div>
                    <span class="text-primary fw-semibold small">BARU DITAMBAHKAN</span>
                    <h2 class="h3 mb-0">Buku terbaru</h2>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm buku-prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                    <button type="button" class="btn btn-outline-primary btn-sm buku-next" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div class="swiper buku-swiper pb-5">
                <div class="swiper-wrapper">
                    @foreach ($bukuTerbaru as $item)
                        <div class="swiper-slide h-auto">
                            <a class="card h-100 border-0 shadow-sm text-decoration-none text-body" href="{{ route('buku.show', $item) }}">
                                @if ($item->sampul)
                                    <img class="card-img-top object-fit-cover" style="aspect-ratio:3/4" src="{{ asset('storage/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}">
                                @else
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-primary-subtle text-primary-emphasis display-3 fw-bold" style="aspect-ratio:3/4" aria-hidden="true">{{ mb_strtoupper(mb_substr($item->judul, 0, 1)) }}</div>
                                @endif
                                <div class="card-body">
                                    <span class="badge text-bg-light border mb-2">{{ $item->kategori->nama }}</span>
                                    <h3 class="h6 card-title mb-1">{{ $item->judul }}</h3>
                                    <p class="small text-body-secondary mb-0">{{ $item->penulis }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
    @endif

    {{-- KATALOG --}}
    <section id="katalog" class="mb-5">
        <div class="mb-3" data-aos="fade-up">
            <span class="text-primary fw-semibold small">KOLEKSI PILIHAN</span>
            <h2 class="h3 mb-1">Jelajahi katalog buku</h2>
            <p class="text-body-secondary mb-0">Cari judul, penulis, atau kode buku, lalu temukan bacaan yang kamu suka.</p>
        </div>

        <form class="card card-body border-0 shadow-sm mb-4" action="{{ route('katalog') }}" method="GET" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="visually-hidden" for="catalog-q">Cari buku</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input id="catalog-q" name="q" value="{{ $keyword }}" class="form-control" placeholder="Cari judul, penulis, atau kode buku">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="visually-hidden" for="catalog-kategori">Pilih kategori</label>
                    <select id="catalog-kategori" name="kategori" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}" @selected($kategoriTerpilih === $kategori->id)>{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary flex-fill" type="submit">Cari buku</button>
                    @if ($keyword !== '' || $kategoriTerpilih)
                        <a class="btn btn-outline-secondary" href="{{ route('katalog') }}#katalog">Reset</a>
                    @endif
                </div>
            </div>
        </form>

        @if ($buku->isNotEmpty())
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                @foreach ($buku as $item)
                    <div class="col" data-aos="zoom-in-up" data-aos-delay="{{ ($loop->index % 4) * 100 }}">
                        <a class="card h-100 border-0 shadow-sm text-decoration-none text-body" href="{{ route('buku.show', $item) }}">
                            @if ($item->sampul)
                                <img class="card-img-top object-fit-cover" style="aspect-ratio:3/4" src="{{ asset('storage/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}">
                            @else
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-primary-subtle text-primary-emphasis display-3 fw-bold" style="aspect-ratio:3/4" aria-hidden="true">
                                    {{ mb_strtoupper(mb_substr($item->judul, 0, 1)) }}
                                </div>
                            @endif
                            <div class="card-body">
                                <span class="badge text-bg-light border mb-2">{{ $item->kategori->nama }}</span>
                                <h3 class="h6 card-title mb-1">{{ $item->judul }}</h3>
                                <p class="small text-body-secondary mb-2">{{ $item->penulis }}</p>
                                <span class="badge {{ $item->stok > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $item->stok > 0 ? 'Tersedia '.$item->stok : 'Sedang dipinjam semua' }}
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 d-flex justify-content-center">{{ $buku->links() }}</div>
        @else
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="bi bi-journal-x display-4 text-body-tertiary"></i>
                    <h3 class="h5 mt-3">Belum ada data</h3>
                    <p class="text-body-secondary mb-0">Buku yang cocok belum ditemukan. Coba kata kunci atau kategori lain.</p>
                </div>
            </div>
        @endif
    </section>

    {{-- TENTANG --}}
    <section id="tentang" class="mb-5">
        <div class="text-center mb-4" data-aos="fade-up">
            <span class="text-primary fw-semibold small">LEBIH DARI SEKADAR BUKU</span>
            <h2 class="h3">Belajar terasa lebih dekat saat semuanya tertata.</h2>
        </div>
        <div class="row g-3">
            <div class="col-md-4" data-aos="flip-up">
                <div class="card h-100 border-0 shadow-sm text-center p-3">
                    <div class="card-body">
                        <i class="bi bi-journal-bookmark-fill display-5 text-primary"></i>
                        <h3 class="h5 mt-3">Koleksi tertata</h3>
                        <p class="text-body-secondary mb-0">Data buku dan kategori dikelola dalam satu ruang yang mudah digunakan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="flip-up" data-aos-delay="150">
                <div class="card h-100 border-0 shadow-sm text-center p-3">
                    <div class="card-body">
                        <i class="bi bi-people-fill display-5 text-success"></i>
                        <h3 class="h5 mt-3">Komunitas pembaca</h3>
                        <p class="text-body-secondary mb-0">Data anggota tersimpan rapi agar layanan perpustakaan lebih teratur.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="flip-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm text-center p-3">
                    <div class="card-body">
                        <i class="bi bi-shield-check display-5 text-warning"></i>
                        <h3 class="h5 mt-3">Akses petugas</h3>
                        <p class="text-body-secondary mb-0">Panel khusus membantu petugas mengelola informasi dengan aman.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SHOWCASE --}}
    <section class="card text-bg-dark border-0 shadow-sm overflow-hidden mb-5" aria-label="Suasana perpustakaan" data-aos="zoom-in">
        <img src="{{ asset('images/library-shelves.jpg') }}" class="card-img object-fit-cover opacity-50" style="min-height:300px" alt="Rak buku di dalam perpustakaan">
        <div class="card-img-overlay d-flex flex-column justify-content-center p-4 p-lg-5">
            <span class="small fw-semibold opacity-75">RUANG UNTUK BERTUMBUH</span>
            <h2 class="h2" style="max-width:480px">Temukan ruang untuk cerita baru.</h2>
            <p class="mb-3" style="max-width:480px">Di antara deretan buku, selalu ada pengetahuan dan inspirasi yang menunggu untuk ditemukan.</p>
            <span class="badge text-bg-light align-self-start">Nyaman untuk belajar</span>
        </div>
    </section>

    {{-- CTA --}}
    <section class="card border-0 bg-primary text-white shadow-sm" data-aos="fade-up">
        <div class="card-body p-4 p-lg-5 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="small opacity-75">UNTUK PETUGAS PERPUSTAKAAN</span>
                <h2 class="h3 mb-0">Siap merapikan koleksi hari ini?</h2>
            </div>
            <img class="rounded d-none d-md-block" style="height:96px" src="{{ asset('images/books-learning.jpg') }}" alt="Ilustrasi buku, pensil, dan lampu belajar">
            <a class="btn btn-light btn-lg" href="{{ route('login') }}">Buka panel petugas <i class="bi bi-arrow-up-right"></i></a>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        if (document.querySelector('.buku-swiper') && typeof Swiper !== 'undefined') {
            new Swiper('.buku-swiper', {
                loop: true,
                spaceBetween: 16,
                slidesPerView: 2,
                autoplay: { delay: 2800, disableOnInteraction: false, pauseOnMouseEnter: true },
                pagination: { el: '.buku-swiper .swiper-pagination', clickable: true },
                navigation: { prevEl: '.buku-prev', nextEl: '.buku-next' },
                breakpoints: { 576: { slidesPerView: 3 }, 992: { slidesPerView: 4 }, 1200: { slidesPerView: 5 } }
            });
        }
    </script>
@endpush
