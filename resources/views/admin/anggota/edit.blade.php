@extends('layouts.admin')
@section('judul', 'Ubah Anggota')
@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><span class="page-pretitle d-block">DATA MASTER</span><h1 class="page-title">Ubah data anggota</h1></div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.anggota.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    </div>
    <div class="card"><div class="card-body p-4">@include('admin.anggota._form', ['route' => route('admin.anggota.update', $anggota), 'method' => 'PUT'])</div></div>
@endsection
