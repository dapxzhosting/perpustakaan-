@extends('layouts.admin')

@section('judul', 'Kategori')

@section('konten')
    <div class="cx-card">
        <div class="cx-card-head">
            <div>
                <h1 class="cx-title">Kategori <span class="cx-count">{{ $kategori->total() }}</span></h1>
                <p class="cx-sub">Kelompok untuk mengatur koleksi buku.</p>
            </div>

            <form class="cx-toolbar" method="GET" action="{{ route('admin.kategori.index') }}">
                @if (request('per_page'))
                    <input type="hidden" name="per_page" value="{{ (int) request('per_page') }}">
                @endif
                <div class="cx-search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" name="q" value="{{ $keyword }}" placeholder="Cari kategori" aria-label="Cari kategori" style="padding-left:8px">
                </div>
                <a class="cx-btn-primary" href="{{ route('admin.kategori.create') }}"><i class="bi bi-plus-lg"></i>Tambah kategori</a>
            </form>
        </div>

        <div class="cx-table-wrap">
            <table class="cx-table">
                <thead>
                    <tr>
                        <th class="cx-num">No</th>
                        <th>Nama kategori</th>
                        <th>Jumlah buku</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $item)
                        <tr>
                            <td class="cx-num">{{ $kategori->firstItem() + $loop->index }}</td>
                            <td><span class="cx-strong">{{ $item->nama }}</span></td>
                            <td><span class="cx-pill cx-pill--mute">{{ $item->buku_count }} buku</span></td>
                            <td>
                                <div class="cx-actions">
                                    <a class="cx-act cx-act--edit" href="{{ route('admin.kategori.edit', $item) }}" title="Ubah" aria-label="Ubah {{ $item->nama }}"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('admin.kategori.destroy', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="cx-act cx-act--del" type="submit" title="Hapus" aria-label="Hapus {{ $item->nama }}"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="cx-empty"><i class="bi bi-inbox"></i>{{ $keyword !== '' ? 'Tidak ada kategori yang cocok dengan pencarian.' : 'Belum ada kategori. Klik "Tambah kategori" untuk mulai.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.table-footer', ['paginator' => $kategori])
    </div>
@endsection
