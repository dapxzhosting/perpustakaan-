@extends('layouts.app')

@section('judul', 'Masuk')

@section('konten')
    <section class="login-layout">
        <div class="login-story">
          
            <div class="story-illustration">
                <img class="story-image" src="{{ asset('images/staff-auditor.jpg') }}" alt="Petugas memeriksa berkas dengan kaca pembesar">
            </div>
            <div class="story-caption">
                <p class="section-kicker">SELAMAT DATANG KEMBALI</p>
                <h1>Rawat ruang tumbuhnya banyak cerita.</h1>
                <p>Kelola koleksi perpustakaan dengan lebih mudah dan nyaman.</p>
            </div>
        </div>
        <div class="login-panel">
            <a class="back-link" href="{{ route('katalog') }}"><span aria-hidden="true">←</span> Kembali ke katalog</a>
            <div class="login-heading">
                <img class="login-mark" src="{{ asset('images/book-logo.png') }}" alt="">
                <p class="section-kicker">AKUN PERPUSTAKAAN</p>
                <h2>Selamat datang.</h2>
                <p>Masuk untuk mengajukan pinjaman atau mengelola perpustakaan.</p>
            </div>
            <form action="{{ route('login.store') }}" method="POST" class="login-form">
                @csrf
                @if ($bukuId)
                    <input type="hidden" name="buku_id" value="{{ $bukuId }}">
                @endif
                <div class="field-group">
                    <label for="email" class="form-label">Alamat email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="nama@sekolah.test" autocomplete="username" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="field-group">
                    <label for="password" class="form-label">Kata sandi</label>
                    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <label class="remember-option">
                    <input name="remember" type="checkbox" value="1">
                    <span>Ingat saya</span>
                </label>
                <button class="button button-primary login-submit" type="submit">Masuk <span aria-hidden="true">→</span></button>
            </form>
            <p class="login-footnote">Belum punya akun anggota?
                <a href="{{ route('register', $bukuId ? ['buku_id' => $bukuId] : []) }}">Daftar sekarang</a>
            </p>
        </div>
    </section>
@endsection
