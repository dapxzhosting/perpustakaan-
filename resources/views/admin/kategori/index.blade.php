@extends('layouts.admin')

@section('judul', 'Kategori')

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><span class="page-pretitle d-block">DATA MASTER</span><h1 class="page-title">Kategori buku</h1></div>
        <a class="btn btn-primary" href="{{ route('admin.kategori.create') }}"><i class="bi bi-plus-lg me-1"></i>Tambah kategori</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-hover">
                <thead class="table-light">
                    <tr><th>Nama kategori</th><th>Jumlah buku</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $item)
                        <tr>
                            <td class="fw-semibold"><i class="bi bi-tag text-primary me-2"></i>{{ $item->nama }}</td>
                            <td><span class="badge text-bg-light border">{{ $item->buku_count }} buku</span></td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.kategori.edit', $item) }}"><i class="bi bi-pencil"></i> Ubah</a>
                                <form class="d-inline" action="{{ route('admin.kategori.destroy', $item) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-body-secondary py-5"><i class="bi bi-inbox display-6 d-block mb-2"></i>Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-body border-0 d-flex justify-content-center pt-3">{{ $kategori->links() }}</div>
    </div>
@endsection
