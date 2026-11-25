@extends('layouts.admin')

@section('judul', 'Anggota')

@section('konten')
    <div class="cx-card">
        <div class="cx-card-head">
            <div>
                <h1 class="cx-title">Anggota <span class="cx-count">{{ $anggota->total() }}</span></h1>
                <p class="cx-sub">Siswa yang terdaftar sebagai anggota perpustakaan.</p>
            </div>

            <form class="cx-toolbar" method="GET" action="{{ route('admin.anggota.index') }}">
                @if (request('per_page'))
                    <input type="hidden" name="per_page" value="{{ (int) request('per_page') }}">
                @endif
                <div class="cx-search">
                    <select name="by" class="cx-sel" aria-label="Cari berdasarkan" data-autosubmit>
                        <option value="semua" @selected($by === 'semua')>Semua</option>
                        <option value="nama" @selected($by === 'nama')>Nama</option>
                        <option value="nis" @selected($by === 'nis')>NIS</option>
                        <option value="kelas" @selected($by === 'kelas')>Kelas</option>
                    </select>
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" name="q" value="{{ $keyword }}" placeholder="Cari anggota" aria-label="Cari anggota">
                </div>

                @include('partials.columns-menu', ['columns' => [
                    'nis' => 'NIS', 'nama' => 'Nama', 'kelas' => 'Kelas', 'hp' => 'No. HP', 'status' => 'Status',
                ]])

                <a class="cx-btn-primary" href="{{ route('admin.anggota.create') }}"><i class="bi bi-plus-lg"></i>Tambah anggota</a>
            </form>
        </div>

        <div class="cx-table-wrap">
            <table class="cx-table" data-cx-table="anggota">
                <thead>
                    <tr>
                        <th class="cx-num">No</th>
                        <th data-col="nis">NIS</th>
                        <th data-col="nama">Nama</th>
                        <th data-col="kelas">Kelas</th>
                        <th data-col="hp">No. HP</th>
                        <th data-col="status">Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($anggota as $item)
                        <tr>
                            <td class="cx-num">{{ $anggota->firstItem() + $loop->index }}</td>
                            <td data-col="nis">{{ $item->nis }}</td>
                            <td data-col="nama"><span class="cx-strong">{{ $item->nama }}</span></td>
                            <td data-col="kelas">{{ $item->kelas }}</td>
                            <td data-col="hp">{{ $item->no_hp ?: '-' }}</td>
                            <td data-col="status">
                                <span class="cx-pill {{ $item->status === 'aktif' ? 'cx-pill--ok' : 'cx-pill--mute' }}">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td>
                                <div class="cx-actions">
                                    <a class="cx-act cx-act--edit" href="{{ route('admin.anggota.edit', $item) }}" title="Ubah" aria-label="Ubah {{ $item->nama }}"><i class="bi bi-pencil-square"></i></a>
                                    <form action="{{ route('admin.anggota.destroy', $item) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="cx-act cx-act--del" type="submit" title="Hapus" aria-label="Hapus {{ $item->nama }}"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="cx-empty"><i class="bi bi-inbox"></i>{{ $keyword !== '' ? 'Tidak ada anggota yang cocok dengan pencarian.' : 'Belum ada anggota. Klik "Tambah anggota" untuk mulai.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.table-footer', ['paginator' => $anggota])
    </div>
@endsection
