@extends('layouts.admin')

@section('judul', 'Buku')

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><span class="page-pretitle d-block">DATA MASTER</span><h1 class="page-title">Koleksi buku</h1></div>
        <a class="btn btn-primary" href="{{ route('admin.buku.create') }}"><i class="bi bi-plus-lg me-1"></i>Tambah buku</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-hover">
                <thead class="table-light">
                    <tr><th>Sampul</th><th>Kode / Judul</th><th>Kategori</th><th>Penulis</th><th>Stok</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($buku as $item)
                        <tr>
                            <td>
                                @if ($item->sampul)
                                    <img class="rounded border object-fit-cover" width="44" height="58" src="{{ asset('storage/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}">
                                @else
                                    <span class="text-body-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-body-secondary">{{ $item->kode_buku }}</small>
                                <div class="fw-semibold">{{ $item->judul }}</div>
                            </td>
                            <td><span class="badge text-bg-light border">{{ $item->kategori->nama }}</span></td>
                            <td>{{ $item->penulis }}</td>
                            <td><span class="badge {{ $item->stok > 0 ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $item->stok }}</span></td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.buku.show', $item) }}"><i class="bi bi-eye"></i> Detail</a>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.buku.edit', $item) }}"><i class="bi bi-pencil"></i> Ubah</a>
                                <form class="d-inline" action="{{ route('admin.buku.destroy', $item) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-body-secondary py-5"><i class="bi bi-inbox display-6 d-block mb-2"></i>Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-body border-0 d-flex justify-content-center pt-3">{{ $buku->links() }}</div>
    </div>
@endsection
