@extends('layouts.app')

@section('judul', 'Masuk')

@section('konten')
    <div class="flex min-h-[calc(100vh-18rem)] items-center justify-center">
        <div class="grid w-full max-w-4xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5 lg:grid-cols-2">
            <div class="hidden lg:block">
                <img class="h-full w-full object-cover" src="{{ asset('images/staff-auditor.jpg') }}" alt="Petugas memeriksa berkas dengan kaca pembesar">
            </div>
            <div class="p-8 sm:p-10">
            <img class="mx-auto h-14 w-14 rounded-2xl" src="{{ asset('images/book-logo.png') }}" alt="" width="56" height="56">
            <div class="mt-4 text-center">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-brand-600"><i class="bi bi-shield-lock"></i>Login petugas</span>
                <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Selamat datang</h1>
                <p class="mt-1 text-sm text-slate-500">Masuk untuk mengelola data perpustakaan.</p>
            </div>

            <form class="mt-8 space-y-5" action="{{ route('login.store') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat email</label>
                    <div class="relative">
                        <i class="bi bi-envelope pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@sekolah.test" autocomplete="username" required autofocus
                               class="h-12 w-full rounded-xl border bg-white pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-4 @error('email') border-rose-400 focus:border-rose-500 focus:ring-rose-500/15 @else border-slate-200 focus:border-brand-500 focus:ring-brand-500/15 @enderror">
                    </div>
                    @error('email')<p class="mt-1.5 inline-flex items-center gap-1.5 text-sm text-rose-600"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Kata sandi</label>
                    <div class="relative">
                        <i class="bi bi-lock pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input id="password" name="password" type="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required
                               class="h-12 w-full rounded-xl border bg-white pl-11 pr-12 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:ring-4 @error('password') border-rose-400 focus:border-rose-500 focus:ring-rose-500/15 @else border-slate-200 focus:border-brand-500 focus:ring-brand-500/15 @enderror">
                        <button id="togglePassword" type="button" aria-label="Tampilkan kata sandi" class="absolute right-3 top-1/2 inline-flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"><i class="bi bi-eye"></i></button>
                    </div>
                    @error('password')<p class="mt-1.5 inline-flex items-center gap-1.5 text-sm text-rose-600"><i class="bi bi-exclamation-circle"></i>{{ $message }}</p>@enderror
                </div>

                <label class="flex items-center gap-2.5 text-sm text-slate-600">
                    <input id="remember" name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    Ingat saya
                </label>

                <button type="submit" class="inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-brand-600 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    <i class="bi bi-box-arrow-in-right"></i>Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <a class="inline-flex items-center gap-1.5 text-sm text-slate-500 transition hover:text-brand-600" href="{{ route('katalog') }}"><i class="bi bi-arrow-left"></i>Kembali ke katalog</a>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var btn = document.getElementById('togglePassword');
            var input = document.getElementById('password');
            if (!btn || !input) return;
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
                btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            });
        })();
    </script>
@endpush
