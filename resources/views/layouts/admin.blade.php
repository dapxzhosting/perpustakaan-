<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/book-logo.png') }}">
    <title>@yield('judul', 'Panel Petugas') · {{ config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    {{-- Tema panel: harus setelah tabler.min.css --}}
    <link href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="cx-shell">

    {{-- SIDEBAR --}}
    <aside class="cx-sidebar" id="cxSidebar" aria-label="Menu utama">
        <a class="cx-brand" href="{{ route('admin.peminjaman.index') }}">
            <img src="{{ asset('images/book-logo.png') }}" alt="" width="30" height="30">
            <span>Ruang<span class="fw-light">Baca</span></span>
        </a>

        <nav class="cx-menu">
            <a class="cx-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}" href="{{ route('admin.peminjaman.index') }}">
                <i class="bi bi-grid-1x2-fill"></i><span>Peminjaman</span>
                @if (($menuHitung['terlambat'] ?? 0) > 0)
                    <span class="cx-n is-alert" title="Peminjaman terlambat">{{ $menuHitung['terlambat'] }}</span>
                @endif
            </a>
            <a class="cx-link" href="{{ route('katalog') }}">
                <i class="bi bi-box-arrow-up-right"></i><span>Katalog publik</span>
            </a>

            <div class="cx-menu-gap"></div>

            <a class="cx-link is-plain {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                <span>Kategori</span>
            </a>
            <a class="cx-link is-plain {{ request()->routeIs('admin.buku.*') ? 'active' : '' }}" href="{{ route('admin.buku.index') }}">
                <span>Buku</span><span class="cx-n">{{ $menuHitung['buku'] ?? 0 }}</span>
            </a>
            <a class="cx-link is-plain {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}" href="{{ route('admin.anggota.index') }}">
                <span>Anggota</span><span class="cx-n">{{ $menuHitung['anggota'] ?? 0 }}</span>
            </a>
        </nav>

        <div class="cx-side-foot">
            © {{ now()->year }} Ruang Baca<br>Panel petugas perpustakaan
        </div>
    </aside>
    <div class="cx-scrim" id="cxScrim"></div>

    {{-- KONTEN --}}
    <div class="cx-main">
        <header class="cx-topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="cx-burger" id="cxBurger" type="button" aria-label="Buka menu" aria-controls="cxSidebar" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>
                <h2 class="cx-topbar-title">@yield('judul', 'Panel Petugas')</h2>
            </div>

            <div class="cx-topbar-right">
                <a class="cx-toplink d-none d-sm-inline" href="{{ route('katalog') }}">Buka situs</a>
                <div class="dropdown">
                    <button class="cx-user" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="cx-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                        <i class="bi bi-chevron-down" style="font-size:9px"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="cx-content">
            @include('partials.flash')
            @yield('konten')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('partials.scripts')
<script src="{{ asset('js/admin.js') }}?v={{ @filemtime(public_path('js/admin.js')) }}"></script>
@stack('scripts')
</body>
</html>
