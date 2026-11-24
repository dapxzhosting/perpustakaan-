@extends('layouts.admin')

@section('judul', 'Anggota')

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div><span class="page-pretitle d-block">DATA MASTER</span><h1 class="page-title">Anggota perpustakaan</h1></div>
        <a class="btn btn-primary" href="{{ route('admin.anggota.create') }}"><i class="bi bi-plus-lg me-1"></i>Tambah anggota</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter card-table table-hover">
                <thead class="table-light">
                    <tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>No. HP</th><th>Status</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($anggota as $item)
                        <tr>
                            <td>{{ $item->nis }}</td>
                            <td class="fw-semibold">{{ $item->nama }}</td>
                            <td>{{ $item->kelas }}</td>
                            <td>{{ $item->no_hp }}</td>
                            <td><span class="badge text-bg-{{ $item->status === 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($item->status) }}</span></td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.anggota.edit', $item) }}"><i class="bi bi-pencil"></i> Ubah</a>
                                <form class="d-inline" action="{{ route('admin.anggota.destroy', $item) }}" method="POST">
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
        <div class="card-footer bg-body border-0 d-flex justify-content-center pt-3">{{ $anggota->links() }}</div>
    </div>
@endsection
