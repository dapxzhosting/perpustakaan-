@extends('layouts.app')

@section('judul', 'Anggota')

@section('konten')
    <div class="page-heading">
        <div><p class="section-kicker">DATA MASTER</p><h1>Anggota perpustakaan</h1></div>
        <a class="button button-primary" href="{{ route('admin.anggota.create') }}"><span aria-hidden="true">＋</span> Tambah anggota</a>
    </div>
    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-stack">
                <thead><tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>No. HP</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($anggota as $item)
                        <tr>
                            <td data-label="NIS">{{ $item->nis }}</td><td class="fw-semibold" data-label="Nama">{{ $item->nama }}</td><td data-label="Kelas">{{ $item->kelas }}</td><td data-label="No. HP">{{ $item->no_hp }}</td>
                            <td data-label="Status"><span class="badge text-bg-{{ $item->status === 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($item->status) }}</span></td>
                            <td class="text-end text-nowrap cell-actions">
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
