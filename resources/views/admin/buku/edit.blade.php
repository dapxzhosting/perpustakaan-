@extends('layouts.app')
@section('judul', 'Ubah Buku')
@section('konten')
    <div class="page-heading"><h1>Ubah data buku</h1><a class="btn btn-outline-secondary" href="{{ route('admin.buku.index') }}">Kembali</a></div>
    <div class="card p-4">@include('admin.buku._form', ['route' => route('admin.buku.update', $buku), 'method' => 'PUT'])</div>
@endsection
