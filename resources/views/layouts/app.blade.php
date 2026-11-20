<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f7f2">
    <link rel="icon" type="image/png" href="{{ asset('images/book-logo.png') }}">
    <title>@yield('judul', 'Perpustakaan Sekolah') · {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @include('partials.navbar')
    <main class="container page-wrap page-enter">
        @include('partials.flash')
        @yield('konten')
    </main>
    <footer class="site-footer">
        <div class="container page-wrap">
            <div class="footer-main">
                <div class="footer-about">
                    <a class="brand-lockup footer-brand" href="{{ route('katalog') }}">
                        <img class="brand-mark" src="{{ asset('images/book-logo.png') }}" alt="">
                        <span>Ruang Baca</span>
                    </a>
                    <p>Ruang untuk membaca, belajar, dan bertumbuh. Temukan koleksi yang menginspirasi dan nikmati pengalaman perpustakaan yang lebih mudah.</p>
                    <span class="footer-about-label">PERPUSTAKAAN SEKOLAH</span>
                </div>
                <nav class="footer-column" aria-label="Jelajahi perpustakaan">
                    <h2>Jelajahi</h2>
                    <a href="{{ route('katalog') }}">Katalog buku</a>
                    <a href="{{ route('katalog') }}#tentang">Tentang perpustakaan</a>
                </nav>
                <nav class="footer-column" aria-label="Akses akun">
                    <h2>Akses akun</h2>
                    @auth
                        @if (auth()->user()->role === 'anggota')
                            <a href="{{ route('akun.peminjaman') }}">Peminjaman saya</a>
                        @else
                            <a href="{{ route('admin.peminjaman.index') }}">Panel petugas</a>
                        @endif
                    @else
                        <a href="{{ route('register') }}">Daftar anggota</a>
                        <a href="{{ route('login') }}">Masuk ke akun</a>
                    @endauth
                </nav>
                <div class="footer-service">
                    <p class="footer-service-label">LAYANAN ANGGOTA</p>
                    <h2>Mulai dari buku yang kamu suka.</h2>
                    <p>Jelajahi katalog, lalu masuk dengan akun anggota untuk mengajukan peminjaman dan melihat riwayatnya.</p>
                    <a href="{{ route('katalog') }}">Lihat katalog <span aria-hidden="true">↗</span></a>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© {{ now()->year }} Ruang Baca. Ruang untuk tumbuh bersama.</span>
                <a href="{{ route('katalog') }}">Kembali ke katalog <span aria-hidden="true">↑</span></a>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
