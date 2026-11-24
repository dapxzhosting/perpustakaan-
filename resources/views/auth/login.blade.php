@extends('layouts.app')

@section('judul', 'Masuk')

@section('konten')
    <div class="card border-0 shadow overflow-hidden mx-auto animate__animated animate__zoomIn" style="max-width:960px">
        <div class="row g-0">
            <div class="col-lg-6 d-none d-lg-block position-relative">
                <img class="w-100 h-100 object-fit-cover" src="{{ asset('images/staff-auditor.jpg') }}" alt="Petugas memeriksa berkas dengan kaca pembesar">
            </div>
            <div class="col-lg-6">
                <div class="card-body p-4 p-lg-5">
                    <a class="small text-decoration-none" href="{{ route('katalog') }}"><i class="bi bi-arrow-left me-1"></i>Kembali ke katalog</a>

                    <div class="text-center my-4">
                        <img src="{{ asset('images/book-logo.png') }}" alt="" width="56" height="56" class="rounded mb-2">
                        <div class="small text-primary fw-semibold">LOGIN PETUGAS</div>
                        <h1 class="h3">Selamat datang.</h1>
                        <p class="text-body-secondary mb-0">Masuk untuk mengelola data perpustakaan.</p>
                    </div>

                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat email</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="nama@sekolah.test" autocomplete="username" required autofocus>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Kata sandi</label>
                            <div class="input-group has-validation">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" id="remember" name="remember" type="checkbox" value="1">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>
                        <button class="btn btn-primary w-100 btn-lg" type="submit">Masuk <i class="bi bi-arrow-right ms-1"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
