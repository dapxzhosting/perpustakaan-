@extends('layouts.admin')

@section('judul', 'Buku')

@section('konten')
    <div class="cx-card">
        <div class="cx-card-head">
            <div>
                <h1 class="cx-title">Buku <span class="cx-count">{{ $buku->total() }}</span></h1>
                <p class="cx-sub">Kelola koleksi buku perpustakaan.</p>
            </div>

            <form class="cx-toolbar" method="GET" action="{{ route('admin.buku.index') }}">
                @if (request('per_page'))
                    <input type="hidden" name="per_page" value="{{ (int) request('per_page') }}">
                @endif
                <div class="cx-search">
                    <select name="by" class="cx-sel" aria-label="Cari berdasarkan" data-autosubmit>
                        <option value="semua" @selected($by === 'semua')>Semua</option>
                        <option value="judul" @selected($by === 'judul')>Judul</option>
                        <option value="penulis" @selected($by === 'penulis')>Penulis</option>
                        <option value="kode_buku" @selected($by === 'kode_buku')>Kode</option>
                    </select>
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" name="q" value="{{ $keyword }}" placeholder="Cari buku" aria-label="Cari buku">
                </div>

                @include('partials.columns-menu', ['columns' => [
                    'sampul' => 'Sampul', 'judul' => 'Kode / Judul', 'kategori' => 'Kategori',
                    'penulis' => 'Penulis', 'stok' => 'Stok',
                ]])

                <a class="cx-btn-primary" href="{{ route('admin.buku.create') }}"><i class="bi bi-plus-lg"></i>Tambah buku</a>
            </form>
        </div>

        <div class="cx-table-wrap">
            <table class="cx-table" data-cx-table="buku">
                <thead>
                    <tr>
                        <th class="cx-num">No</th>
                        <th data-col="sampul">Sampul</th>
                        <th data-col="judul">Kode / Judul</th>
                        <th data-col="kategori">Kategori</th>
                        <th data-col="penulis">Penulis</th>
                        <th data-col="stok">Stok</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($buku as $item)
                        <tr>
                            <td class="cx-num">{{ $buku->firstItem() + $loop->index }}</td>
                            <td data-col="sampul">
                                @if ($item->sampul)
                                    <img class="cx-thumb" src="{{ asset('storage/'.$item->sampul) }}" alt="Sampul {{ $item->judul }}">
                                @else
                                    <span class="cx-thumb cx-thumb--empty" aria-hidden="true">{{ mb_strtoupper(mb_substr($item->judul, 0, 1)) }}</span>
                                @endif
                            </td>
                            <td data-col="judul">
                                <span class="cx-strong">{{ $item->judul }}</span>
                                <span class="cx-small">{{ $item->kode_buku }}</span>
                            </td>
                            <td data-col="kategori">{{ $item->kategori->nama }}</td>
                            <td data-col="penulis">{{ $item->penulis }}</td>
                            <td data-col="stok">
                                <span class="cx-pill {{ $item->stok > 0 ? 'cx-pill--ok' : 'cx-pill--mute' }}">{{ $item->stok > 0 ? $item->stok.' tersedia' : 'Habis' }}</span>
                            </td>
                            <td>
                                <div class="cx-actions">
                                    <a class="cx-act cx-act--edit" href="{{ route('admin.buku.edit', $item) }}" title="Ubah" aria-label="Ubah {{ $item->judul }}"><i class="bi bi-pencil-square"></i></a>
                                    <a class="cx-act cx-act--view" href="{{ route('admin.buku.show', $item) }}" title="Lihat detail" aria-label="Lihat detail {{ $item->judul }}"><i class="bi bi-eye"></i></a>
                                    <form action="{{ route('admin.buku.destroy', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="cx-act cx-act--del" type="submit" title="Hapus" aria-label="Hapus {{ $item->judul }}"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="cx-empty"><i class="bi bi-inbox"></i>{{ $keyword !== '' ? 'Tidak ada buku yang cocok dengan pencarian.' : 'Belum ada buku. Klik "Tambah buku" untuk mulai.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.table-footer', ['paginator' => $buku])
    </div>
@endsection
