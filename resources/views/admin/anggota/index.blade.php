@extends('layouts.app')

@section('judul', 'Anggota')

@section('konten')
    <div class="page-heading">
        <div><p class="eyebrow mb-2">DATA MASTER</p><h1>Anggota perpustakaan</h1></div>
        <a class="btn btn-primary" href="{{ route('admin.anggota.create') }}">+ Tambah anggota</a>
    </div>
    <div class="card p-3 p-md-4">
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>No. HP</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($anggota as $item)
                        <tr>
                            <td>{{ $item->nis }}</td><td class="fw-semibold">{{ $item->nama }}</td><td>{{ $item->kelas }}</td><td>{{ $item->no_hp }}</td>
                            <td><span class="badge text-bg-{{ $item->status === 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($item->status) }}</span></td>
                            <td class="text-end text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.anggota.edit', $item) }}">Ubah</a>
                                <form class="d-inline" action="{{ route('admin.anggota.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
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
        {{ $anggota->links() }}
    </div>
@endsection
