@extends('layouts.app')

@section('judul', 'Buku')

@section('konten')
    <div class="page-heading">
        <div><p class="section-kicker">DATA MASTER</p><h1>Koleksi buku</h1></div>
        <a class="button button-primary" href="{{ route('admin.buku.create') }}"><span aria-hidden="true">＋</span> Tambah buku</a>
    </div>
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-stack">
                <thead><tr><th>Sampul</th><th>Kode / Judul</th><th>Kategori</th><th>Penulis</th><th>Stok tersedia</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($buku as $item)
                        <tr>
                            <td data-label="Sampul" class="cell-cover">@if ($item->sampul)<img class="cover-thumb" src="{{ asset('storage/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}">@else<span class="text-secondary">-</span>@endif</td>
                            <td data-label="Judul"><div class="cell-main"><span class="small muted">{{ $item->kode_buku }}</span><br><span class="fw-semibold">{{ $item->judul }}</span></div></td>
                            <td data-label="Kategori">{{ $item->kategori->nama }}</td>
                            <td data-label="Penulis">{{ $item->penulis }}</td>
                            <td data-label="Stok tersedia">{{ $item->stok }}</td>
                            <td class="text-end text-nowrap cell-actions">
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
