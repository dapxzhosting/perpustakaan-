@extends('layouts.app')
@section('judul', 'Detail Buku')
@section('konten')
    <div class="page-heading">
        <div><p class="section-kicker">DATA MASTER</p><h1>Detail buku</h1></div>
        <a class="button button-quiet" href="{{ route('admin.buku.index') }}"><span aria-hidden="true">←</span> Kembali</a>
    </div>
    <div class="panel-card">
        <div class="row g-4 align-items-center">
            <div class="col-sm-3">
                @if ($buku->sampul)<img class="img-fluid rounded-3" src="{{ asset('storage/'.$buku->sampul) }}" alt="Sampul {{ $buku->judul }}">@else<div class="bg-light rounded-3 p-5 text-center">Tanpa sampul</div>@endif
            </div>
            <div class="col-sm-9">
                <p class="section-kicker">{{ $buku->kategori->nama }} · {{ $buku->kode_buku }}</p>
                <h2 class="h3 fw-bold">{{ $buku->judul }}</h2>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Penulis</dt><dd class="col-sm-8">{{ $buku->penulis }}</dd>
                    <dt class="col-sm-4">Penerbit</dt><dd class="col-sm-8">{{ $buku->penerbit ?: '-' }}</dd>
                    <dt class="col-sm-4">Tahun terbit</dt><dd class="col-sm-8">{{ $buku->tahun_terbit }}</dd>
                    <dt class="col-sm-4">Stok tersedia</dt><dd class="col-sm-8">{{ $buku->stok }}</dd>
                </dl>
                <a class="button button-primary mt-3" href="{{ route('admin.buku.edit', $buku) }}">Ubah buku</a>
            </div>
        </div>
    </div>
@endsection
