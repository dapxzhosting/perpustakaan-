@php
    $linkAktif = 'bg-brand-50 text-brand-700';
    $linkBiasa = 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';
@endphp
<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/80 backdrop-blur-md">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4">
        <a class="inline-flex items-center gap-2.5 text-lg font-bold tracking-tight text-slate-900" href="{{ route('katalog') }}">
            <img src="{{ asset('images/book-logo.png') }}" alt="" width="32" height="32" class="h-8 w-8 rounded-lg">
            <span>Ruang<span class="font-normal text-brand-600">Baca</span></span>
        </a>

        <nav class="hidden items-center gap-1 md:flex" aria-label="Menu utama">
            <a class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition {{ request()->routeIs('katalog', 'buku.show') ? $linkAktif : $linkBiasa }}" href="{{ route('katalog') }}">
                <i class="bi bi-grid-1x2"></i>Katalog
            </a>
            <a class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition {{ $linkBiasa }}" href="{{ route('katalog') }}#tentang">
                <i class="bi bi-info-circle"></i>Tentang
            </a>
        </nav>

        <div class="hidden md:block">
            @auth
                <a class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700" href="{{ route('admin.peminjaman.index') }}">
                    <i class="bi bi-speedometer2"></i>Panel petugas
                </a>
            @else
                <a class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700" href="{{ route('login') }}">
                    <i class="bi bi-box-arrow-in-right"></i>Login petugas
                </a>
            @endauth
        </div>

        <button id="navToggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-700 md:hidden" aria-controls="navMenu" aria-expanded="false" aria-label="Buka navigasi">
            <i class="bi bi-list text-2xl"></i>
        </button>
    </div>

    <div id="navMenu" class="hidden border-t border-slate-200 bg-white px-4 py-3 md:hidden">
        <div class="flex flex-col gap-1">
            <a class="inline-flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('katalog', 'buku.show') ? $linkAktif : $linkBiasa }}" href="{{ route('katalog') }}"><i class="bi bi-grid-1x2"></i>Katalog</a>
            <a class="inline-flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium {{ $linkBiasa }}" href="{{ route('katalog') }}#tentang"><i class="bi bi-info-circle"></i>Tentang</a>
            @auth
                <a class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white" href="{{ route('admin.peminjaman.index') }}"><i class="bi bi-speedometer2"></i>Panel petugas</a>
            @else
                <a class="mt-2 inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i>Login petugas</a>
            @endauth
        </div>
    </div>
</header>
