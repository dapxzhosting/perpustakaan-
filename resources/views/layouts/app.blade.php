<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/book-logo.png') }}">
    <title>@yield('judul', 'Perpustakaan Sekolah') · {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#f0f1ff', 100: '#e2e5ff', 200: '#c9cefd', 300: '#a5adfb',
                            400: '#7c83f5', 500: '#5b5ff0', 600: '#4a4de6', 700: '#3b3ed0', 800: '#3032a8', 900: '#2b2e84'
                        }
                    }
                }
            }
        };
    </script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="flex min-h-screen flex-col bg-slate-50 font-sans text-slate-600 antialiased selection:bg-brand-100 selection:text-brand-900">
    @include('partials.navbar')

    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:py-10">
        @yield('konten')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-5 text-sm text-slate-500">
            <span class="inline-flex items-center gap-2"><i class="bi bi-book-half text-brand-600"></i>© {{ now()->year }} Ruang Baca · Perpustakaan Sekolah</span>
            <a class="inline-flex items-center gap-1 transition hover:text-brand-600" href="{{ route('katalog') }}"><i class="bi bi-arrow-up-short text-lg"></i>Kembali ke atas</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.flash-publik')
    <script>
        (function () {
            var toggle = document.getElementById('navToggle');
            var menu = document.getElementById('navMenu');
            if (!toggle || !menu) return;
            toggle.addEventListener('click', function () {
                var hidden = menu.classList.toggle('hidden');
                toggle.setAttribute('aria-expanded', hidden ? 'false' : 'true');
                toggle.querySelector('i').className = hidden ? 'bi bi-list text-2xl' : 'bi bi-x-lg text-xl';
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
