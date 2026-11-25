@extends('layouts.app')

@section('judul', 'Katalog')

@section('konten')
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white">
        <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-brand-100/70 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 left-1/3 h-64 w-64 rounded-full bg-violet-100/60 blur-3xl"></div>

        <div class="relative grid gap-8 p-6 sm:p-10 lg:grid-cols-[1.1fr_1fr] lg:items-center">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-brand-700">
                    <i class="bi bi-book-half"></i>Perpustakaan sekolah
                </span>
                <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                    Temukan bacaan <span class="text-brand-600">berikutnya</span>.
                </h1>
                <p class="mt-3 max-w-xl text-base leading-relaxed text-slate-500">
                    Cari judul, penulis, atau kode buku, lalu cek ketersediaannya sebelum datang ke perpustakaan.
                </p>

                <form class="mt-6 flex max-w-xl flex-col gap-2 sm:flex-row" action="{{ route('katalog') }}#katalog" method="GET">
                    @if ($kategoriTerpilih)
                        <input type="hidden" name="kategori" value="{{ $kategoriTerpilih }}">
                    @endif
                    <label class="sr-only" for="catalog-q">Cari buku</label>
                    <div class="relative flex-1">
                        <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input id="catalog-q" name="q" value="{{ $keyword }}" placeholder="Cari judul, penulis, atau kode buku"
                               class="h-12 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15">
                    </div>
                    <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-brand-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                        <i class="bi bi-search"></i>Cari
                    </button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <img class="h-56 w-full object-cover sm:h-72 lg:h-80" src="{{ asset('images/library-illustration.jpg') }}" alt="Ilustrasi perpustakaan sekolah dengan siswa membaca">
            </div>
        </div>
    </section>

    <div class="mt-4 grid gap-3 sm:grid-cols-3">
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-xl text-brand-600"><i class="bi bi-journal-bookmark"></i></span>
            <div>
                <div class="text-2xl font-bold leading-none text-slate-900">{{ number_format($ringkasan['judul'], 0, ',', '.') }}</div>
                <div class="mt-1 text-xs text-slate-500">Judul buku</div>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl text-emerald-600"><i class="bi bi-check2-circle"></i></span>
            <div>
                <div class="text-2xl font-bold leading-none text-slate-900">{{ number_format($ringkasan['tersedia'], 0, ',', '.') }}</div>
                <div class="mt-1 text-xs text-slate-500">Eksemplar tersedia</div>
            </div>
        </div>
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-600"><i class="bi bi-tags"></i></span>
            <div>
                <div class="text-2xl font-bold leading-none text-slate-900">{{ number_format($ringkasan['kategori'], 0, ',', '.') }}</div>
                <div class="mt-1 text-xs text-slate-500">Kategori</div>
            </div>
        </div>
    </div>

    @if ($bukuTerbaru->isNotEmpty() && $keyword === '' && ! $kategoriTerpilih)
        <section class="mt-12">
            <div class="mb-4 flex items-end justify-between gap-3">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">Baru ditambahkan</span>
                    <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900"><i class="bi bi-stars text-brand-600"></i>Buku terbaru</h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" data-rail-prev aria-label="Sebelumnya" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-brand-300 hover:text-brand-600"><i class="bi bi-chevron-left"></i></button>
                    <button type="button" data-rail-next aria-label="Berikutnya" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-brand-300 hover:text-brand-600"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div data-rail class="no-scrollbar -mx-4 flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 pb-3">
                @foreach ($bukuTerbaru as $item)
                    @include('partials.kartu-buku', ['item' => $item, 'kompak' => true])
                @endforeach
            </div>
        </section>
    @endif

    <section id="katalog" class="mt-12 scroll-mt-24">
        <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">Koleksi</span>
                <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900"><i class="bi bi-grid-1x2 text-brand-600"></i>Katalog buku</h2>
            </div>
            <div class="flex items-center gap-3">
                @if ($buku->total() > 0)
                    <span class="text-sm text-slate-500">{{ $buku->total() }} buku ditemukan</span>
                @endif
                @if ($keyword !== '' || $kategoriTerpilih)
                    <a href="{{ route('katalog') }}#katalog" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm font-medium text-slate-600 transition hover:border-brand-300 hover:text-brand-600">
                        <i class="bi bi-x-lg text-xs"></i>Reset filter
                    </a>
                @endif
            </div>
        </div>

        <div class="no-scrollbar -mx-4 mb-6 flex gap-2 overflow-x-auto px-4 pb-1">
            <a class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-medium transition {{ $kategoriTerpilih ? 'border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-600' : 'border-brand-600 bg-brand-600 text-white' }}" href="{{ route('katalog', array_filter(['q' => $keyword])) }}#katalog">
                <i class="bi bi-collection"></i>Semua
            </a>
            @foreach ($kategoriList as $kategori)
                <a class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-medium transition {{ $kategoriTerpilih === $kategori->id ? 'border-brand-600 bg-brand-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-600' }}" href="{{ route('katalog', array_filter(['q' => $keyword, 'kategori' => $kategori->id])) }}#katalog">
                    <i class="bi bi-tag"></i>{{ $kategori->nama }}
                </a>
            @endforeach
        </div>

        @if ($buku->isNotEmpty())
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($buku as $item)
                    @include('partials.kartu-buku', ['item' => $item])
                @endforeach
            </div>
            <div class="mt-8">{{ $buku->links('partials.pagination-publik') }}</div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <span class="mx-auto inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl text-slate-400"><i class="bi bi-journal-x"></i></span>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Belum ada data</h3>
                <p class="mt-1 text-sm text-slate-500">Buku yang cocok belum ditemukan. Coba kata kunci atau kategori lain.</p>
            </div>
        @endif
    </section>

    <section class="relative mt-16 overflow-hidden rounded-3xl">
        <img class="h-72 w-full object-cover sm:h-80" src="{{ asset('images/library-shelves.jpg') }}" alt="Rak buku di dalam perpustakaan" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900/85 via-slate-900/55 to-transparent"></div>
        <div class="absolute inset-0 flex flex-col justify-center p-6 sm:p-12">
            <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-brand-200"><i class="bi bi-stars"></i>Ruang untuk bertumbuh</span>
            <h2 class="mt-2 max-w-md text-2xl font-extrabold leading-tight text-white sm:text-3xl">Temukan ruang untuk cerita baru.</h2>
            <p class="mt-2 max-w-md text-sm leading-relaxed text-slate-200">Di antara deretan buku, selalu ada pengetahuan dan inspirasi yang menunggu untuk ditemukan.</p>
        </div>
    </section>

    <section id="tentang" class="mt-12 scroll-mt-24">
        <div class="mb-5">
            <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">Tentang</span>
            <h2 class="flex items-center gap-2 text-xl font-bold text-slate-900"><i class="bi bi-info-circle text-brand-600"></i>Lebih dari sekadar buku</h2>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-brand-300 hover:shadow-lg hover:shadow-brand-600/5">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-xl text-brand-600"><i class="bi bi-journal-bookmark"></i></span>
                <h3 class="mt-4 font-semibold text-slate-900">Koleksi tertata</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Data buku dan kategori dikelola dalam satu ruang yang mudah digunakan.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-brand-300 hover:shadow-lg hover:shadow-brand-600/5">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-xl text-emerald-600"><i class="bi bi-people"></i></span>
                <h3 class="mt-4 font-semibold text-slate-900">Komunitas pembaca</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Data anggota tersimpan rapi agar layanan perpustakaan lebih teratur.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-brand-300 hover:shadow-lg hover:shadow-brand-600/5">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-600"><i class="bi bi-shield-check"></i></span>
                <h3 class="mt-4 font-semibold text-slate-900">Akses petugas</h3>
                <p class="mt-1 text-sm leading-relaxed text-slate-500">Panel khusus membantu petugas mengelola informasi dengan aman.</p>
            </div>
        </div>
    </section>
    <section class="mt-12 overflow-hidden rounded-3xl bg-brand-600 text-white">
        <div class="flex flex-wrap items-center justify-between gap-6 p-6 sm:p-10">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-brand-200">Untuk petugas perpustakaan</span>
                <h2 class="mt-1 text-xl font-bold sm:text-2xl">Siap merapikan koleksi hari ini?</h2>
            </div>
            <img class="hidden h-24 rounded-xl object-cover md:block" src="{{ asset('images/books-learning.jpg') }}" alt="Ilustrasi buku, pensil, dan lampu belajar" loading="lazy">
            @auth
                <a class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50" href="{{ route('admin.peminjaman.index') }}">Buka panel petugas<i class="bi bi-arrow-up-right"></i></a>
            @else
                <a class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-brand-700 transition hover:bg-brand-50" href="{{ route('login') }}">Buka panel petugas<i class="bi bi-arrow-up-right"></i></a>
            @endauth
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            var rail = document.querySelector('[data-rail]');
            if (!rail) return;
            var step = function () { return Math.max(rail.clientWidth * 0.8, 180); };
            var prev = document.querySelector('[data-rail-prev]');
            var next = document.querySelector('[data-rail-next]');
            if (prev) prev.addEventListener('click', function () { rail.scrollBy({ left: -step(), behavior: 'smooth' }); });
            if (next) next.addEventListener('click', function () { rail.scrollBy({ left: step(), behavior: 'smooth' }); });
        })();
    </script>
@endpush
