@extends('layouts.app')
@section('judul', 'Ubah Anggota')
@section('konten')
    <div class="page-heading"><h1>Ubah data anggota</h1><a class="btn btn-outline-secondary" href="{{ route('admin.anggota.index') }}">Kembali</a></div>
    <div class="card p-4">@include('admin.anggota._form', ['route' => route('admin.anggota.update', $anggota), 'method' => 'PUT'])</div>
@endsection
