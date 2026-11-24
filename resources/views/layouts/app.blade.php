<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/book-logo.png') }}">
    <title>@yield('judul', 'Perpustakaan Sekolah') · {{ config('app.name') }}</title>

    {{-- Tema Bootswatch "Zephyr". Alternatif: lux, minty, sandstone, journal, cosmo --}}
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/zephyr/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100 bg-body-tertiary">
    @include('partials.navbar')

    <main class="container py-4 flex-grow-1">
        @include('partials.flash')
        @yield('konten')
    </main>

    <footer class="bg-body border-top py-3 mt-4">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2 small text-body-secondary">
            <span><i class="bi bi-book-half me-1"></i>© {{ now()->year }} Ruang Baca · Perpustakaan Sekolah</span>
            <a class="link-secondary text-decoration-none" href="{{ route('katalog') }}">
                Kembali ke katalog <i class="bi bi-arrow-up-short"></i>
            </a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init({ duration: 900, once: true, offset: 70 });</script>
    @include('partials.scripts')
    @stack('scripts')
</body>
</html>
