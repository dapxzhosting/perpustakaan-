<nav class="navbar navbar-expand-lg site-nav">
    <div class="container page-wrap">
        <a class="navbar-brand brand-lockup" href="{{ route('katalog') }}">
            <img class="brand-mark" src="{{ asset('images/book-logo.png') }}" alt="">
            <span>Ruang<span class="brand-light">Baca</span></span>
        </a>
        <button class="navbar-toggler nav-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            @auth
                <ul class="navbar-nav ms-lg-5 me-auto nav-links">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}" href="{{ route('admin.peminjaman.index') }}">Peminjaman</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.buku.*') ? 'active' : '' }}" href="{{ route('admin.buku.index') }}">Buku</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}" href="{{ route('admin.anggota.index') }}">Anggota</a>
                    </li>
                </ul>
                <div class="nav-account">
                    <span class="account-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="account-name">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn-logout" type="submit">Keluar</button>
                    </form>
                </div>
            @else
                <div class="guest-nav-menu">
                    <a class="guest-nav-link {{ request()->routeIs('katalog') ? 'active' : '' }}" href="{{ route('katalog') }}">Katalog</a>
                    <div class="guest-nav-actions guest-nav-single">
                        <a class="guest-login-button" href="{{ route('login') }}">Login petugas</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>
