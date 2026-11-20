@extends('layouts.app')
@section('judul', 'Tambah Anggota')
@section('konten')
    <div class="page-heading"><h1>Tambah anggota</h1><a class="btn btn-outline-secondary" href="{{ route('admin.anggota.index') }}">Kembali</a></div>
    <div class="card p-4">@include('admin.anggota._form', ['route' => route('admin.anggota.store'), 'method' => 'POST'])</div>
@endsection
