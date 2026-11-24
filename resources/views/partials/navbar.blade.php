<nav class="navbar navbar-expand-lg bg-primary navbar-dark shadow-sm sticky-top" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('katalog') }}">
            <img src="{{ asset('images/book-logo.png') }}" alt="" width="30" height="30" class="rounded">
            <span>Ruang<span class="fw-light">Baca</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('katalog', 'buku.show') ? 'active' : '' }}" href="{{ route('katalog') }}">
                        <i class="bi bi-grid me-1"></i>Katalog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('katalog') }}#tentang"><i class="bi bi-info-circle me-1"></i>Tentang</a>
                </li>
            </ul>
            @auth
                <a class="btn btn-sm btn-light" href="{{ route('admin.peminjaman.index') }}"><i class="bi bi-speedometer2 me-1"></i>Panel petugas</a>
            @else
                <a class="btn btn-sm btn-light" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i>Login petugas</a>
            @endauth
        </div>
    </div>
</nav>
