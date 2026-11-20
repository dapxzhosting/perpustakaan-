@extends('layouts.app')
@section('judul', 'Tambah Buku')
@section('konten')
    <div class="page-heading">
        <div><p class="section-kicker">DATA MASTER</p><h1>Tambah buku</h1></div>
        <a class="button button-quiet" href="{{ route('admin.buku.index') }}"><span aria-hidden="true">←</span> Kembali</a>
    </div>
    <div class="panel-card form-card">@include('admin.buku._form', ['route' => route('admin.buku.store'), 'method' => 'POST'])</div>
@endsection
