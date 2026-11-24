@extends('layouts.admin')
@section('judul', 'Tambah Buku')
@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><span class="page-pretitle d-block">DATA MASTER</span><h1 class="page-title">Tambah buku</h1></div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.buku.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card"><div class="card-body p-4">@include('admin.buku._form', ['route' => route('admin.buku.store'), 'method' => 'POST'])</div></div>
@endsection
