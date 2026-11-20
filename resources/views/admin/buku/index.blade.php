@extends('layouts.app')

@section('judul', 'Buku')

@section('konten')
    <div class="page-heading">
        <div><p class="eyebrow mb-2">DATA MASTER</p><h1>Koleksi buku</h1></div>
        <a class="btn btn-primary" href="{{ route('admin.buku.create') }}">+ Tambah buku</a>
    </div>
    <div class="card p-3 p-md-4">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Sampul</th><th>Kode / Judul</th><th>Kategori</th><th>Penulis</th><th>Stok</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($buku as $item)
                        <tr>
                            <td>@if ($item->sampul)<img class="cover-thumb" src="{{ asset('storage/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}">@else<span class="text-secondary">-</span>@endif</td>
                            <td><span class="small muted">{{ $item->kode_buku }}</span><br><span class="fw-semibold">{{ $item->judul }}</span></td>
                            <td>{{ $item->kategori->nama }}</td>
                            <td>{{ $item->penulis }}</td>
                            <td>{{ $item->stok }}</td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.buku.show', $item) }}">Detail</a>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.buku.edit', $item) }}">Ubah</a>
                                <form class="d-inline" action="{{ route('admin.buku.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center muted py-4">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $buku->links() }}
    </div>
@endsection
