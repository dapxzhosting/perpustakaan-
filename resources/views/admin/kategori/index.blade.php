@extends('layouts.app')

@section('judul', 'Kategori')

@section('konten')
    <div class="page-heading">
        <div><p class="eyebrow mb-2">DATA MASTER</p><h1>Kategori buku</h1></div>
        <a class="btn btn-primary" href="{{ route('admin.kategori.create') }}">+ Tambah kategori</a>
    </div>
    <div class="card p-3 p-md-4">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Nama kategori</th><th>Jumlah buku</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($kategori as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->nama }}</td>
                            <td>{{ $item->buku_count }}</td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.kategori.edit', $item) }}">Ubah</a>
                                <form class="d-inline" action="{{ route('admin.kategori.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center muted py-4">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $kategori->links() }}
    </div>
@endsection
