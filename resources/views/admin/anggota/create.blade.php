@extends('layouts.app')
@section('judul', 'Tambah Anggota')
@section('konten')
    <div class="page-heading">
        <div><p class="section-kicker">DATA MASTER</p><h1>Tambah anggota</h1></div>
        <a class="button button-quiet" href="{{ route('admin.anggota.index') }}"><span aria-hidden="true">←</span> Kembali</a>
    </div>
    <div class="panel-card form-card">@include('admin.anggota._form', ['route' => route('admin.anggota.store'), 'method' => 'POST'])</div>
@endsection
