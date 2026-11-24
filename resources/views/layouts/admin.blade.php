<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/book-logo.png') }}">
    <title>@yield('judul', 'Panel Petugas') · {{ config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="page">
    {{-- SIDEBAR --}}
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                    aria-controls="sidebar-menu" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark">
                <a href="{{ route('admin.peminjaman.index') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                    <img src="{{ asset('images/book-logo.png') }}" alt="" width="34" height="34" class="rounded">
                    <span>Ruang<span class="fw-light">Baca</span></span>
                </a>
            </h1>

            <div class="collapse navbar-collapse" id="sidebar-menu">
                <ul class="navbar-nav pt-lg-3">
                    <li class="nav-item {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.peminjaman.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="bi bi-arrow-left-right"></i></span>
                            <span class="nav-link-title">Peminjaman</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.kategori.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="bi bi-tags"></i></span>
                            <span class="nav-link-title">Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.buku.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="bi bi-journal-bookmark"></i></span>
                            <span class="nav-link-title">Buku</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.anggota.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="bi bi-people"></i></span>
                            <span class="nav-link-title">Anggota</span>
                        </a>
                    </li>

                    <li class="nav-item mt-lg-4">
                        <a class="nav-link" href="{{ route('katalog') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="bi bi-grid"></i></span>
                            <span class="nav-link-title">Katalog publik</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="bi bi-person-circle"></i></span>
                            <span class="nav-link-title">{{ auth()->user()->name }}</span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="bi bi-box-arrow-right"></i></span>
                                <span class="nav-link-title">Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    {{-- KONTEN --}}
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">
                @include('partials.flash')
                @yield('konten')
            </div>
        </div>
        <footer class="footer footer-transparent d-print-none">
            <div class="container-xl text-secondary small">
                © {{ now()->year }} Ruang Baca · Panel Petugas Perpustakaan Sekolah
            </div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@include('partials.scripts')
@stack('scripts')
</body>
</html>
