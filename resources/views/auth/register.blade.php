@extends('layouts.app')

@section('judul', 'Daftar Anggota')

@section('konten')
    <section class="login-layout register-layout">
        <div class="login-story">
            <div class="story-illustration">
                <img class="story-image" src="{{ asset('images/bookshelf-isometric.jpg') }}" alt="Rak buku berisi koleksi buku berwarna">
            </div>
            <div class="story-caption">
                <p class="section-kicker">MULAI MEMBACA</p>
                <h1>Buat akun perpustakaanmu.</h1>
                <p>Gunakan akun anggota untuk mengajukan pinjaman dan melihat riwayat buku.</p>
            </div>
        </div>
        <div class="login-panel register-panel">
            <a class="back-link" href="{{ route('katalog') }}"><span aria-hidden="true">←</span> Kembali ke katalog</a>
            <div class="login-heading">
                <img class="login-mark" src="{{ asset('images/book-logo.png') }}" alt="">
                <p class="section-kicker">PENDAFTARAN ANGGOTA</p>
                <h2>Daftar akun.</h2>
                <p>Isi data sesuai informasi keanggotaan perpustakaan.</p>
            </div>
            <form action="{{ route('register.store') }}" method="POST" class="login-form">
                @csrf
                @if ($bukuId)
                    <input type="hidden" name="buku_id" value="{{ $bukuId }}">
                @endif
                <div class="field-group">
                    <label for="nama" class="form-label">Nama lengkap</label>
                    <input id="nama" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" autocomplete="name" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="register-fields">
                    <div class="field-group">
                        <label for="nis" class="form-label">NIS</label>
                        <input id="nis" name="nis" value="{{ old('nis') }}" class="form-control @error('nis') is-invalid @enderror" required>
                        @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="field-group">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input id="kelas" name="kelas" value="{{ old('kelas') }}" class="form-control @error('kelas') is-invalid @enderror" required>
                        @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="field-group">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input id="no_hp" name="no_hp" type="tel" value="{{ old('no_hp') }}" class="form-control @error('no_hp') is-invalid @enderror" placeholder="08xxxxxxxxxx" autocomplete="tel" required>
                    @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="field-group">
                    <label for="email" class="form-label">Alamat email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="register-fields">
                    <div class="field-group">
                        <label for="password" class="form-label">Kata sandi</label>
                        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="field-group">
                        <label for="password_confirmation" class="form-label">Ulangi kata sandi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
                    </div>
                </div>
                <button class="button button-primary login-submit" type="submit">Buat akun</button>
            </form>
            <p class="login-footnote">Sudah punya akun?
                <a href="{{ route('login', $bukuId ? ['buku_id' => $bukuId] : []) }}">Masuk di sini</a>
            </p>
        </div>
    </section>
@endsection
