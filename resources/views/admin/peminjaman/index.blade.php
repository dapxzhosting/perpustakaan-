@extends('layouts.app')

@section('judul', 'Peminjaman')

@section('konten')
    <section class="dashboard-heading">
        <div>
            <p class="section-kicker">PANEL PERPUSTAKAAN</p>
            <h1>Selamat datang, {{ auth()->user()->name }}</h1>
            <p>Kelola peminjaman, pengembalian, dan koleksi perpustakaan.</p>
        </div>
        <a class="button button-primary" href="{{ route('admin.peminjaman.create') }}"><span aria-hidden="true">＋</span> Catat peminjaman</a>
    </section>

    <section class="panel-card loans-panel">
        <div class="panel-heading">
            <div>
                <p class="section-kicker">TRANSAKSI</p>
                <h2>Riwayat peminjaman</h2>
            </div>
        </div>

        <form class="loan-filters" action="{{ route('admin.peminjaman.index') }}" method="GET">
            <div class="loan-search">
                <label class="visually-hidden" for="loan-q">Cari nama anggota</label>
                <input id="loan-q" name="q" value="{{ $keyword }}" class="form-control" placeholder="Cari nama anggota">
            </div>
            <div>
                <label class="visually-hidden" for="loan-status">Filter status</label>
                <select id="loan-status" name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="dipinjam" @selected($statusTerpilih === 'dipinjam')>Dipinjam</option>
                    <option value="dikembalikan" @selected($statusTerpilih === 'dikembalikan')>Dikembalikan</option>
                    <option value="terlambat" @selected($statusTerpilih === 'terlambat')>Terlambat</option>
                </select>
            </div>
            <button class="button button-primary" type="submit">Terapkan filter</button>
            @if ($keyword !== '' || $statusTerpilih !== '')
                <a class="button button-quiet" href="{{ route('admin.peminjaman.index') }}">Reset</a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table align-middle loan-table table-stack">
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tanggal pinjam</th>
                        <th>Jatuh tempo</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $item)
                        @php
                            $terlambat = $item->status === 'dipinjam' && $item->tgl_jatuh_tempo->lt(today());
                        @endphp
                        <tr>
                            <td data-label="Anggota">
                                <div class="cell-main">
                                    <strong>{{ $item->anggota->nama }}</strong>
                                    <small class="d-block text-secondary">{{ $item->anggota->nis }} · {{ $item->anggota->kelas }}</small>
                                </div>
                            </td>
                            <td data-label="Buku">
                                <div class="cell-main">
                                    <strong>{{ $item->buku->judul }}</strong>
                                    <small class="d-block text-secondary">{{ $item->buku->kode_buku }}</small>
                                </div>
                            </td>
                            <td data-label="Tanggal pinjam">{{ $item->tgl_pinjam->format('d M Y') }}</td>
                            <td data-label="Jatuh tempo">{{ $item->tgl_jatuh_tempo->format('d M Y') }}</td>
                            <td data-label="Status">
                                @if ($item->status === 'dikembalikan')
                                    <span class="badge text-bg-success">Dikembalikan</span>
                                @elseif ($terlambat)
                                    <span class="badge text-bg-danger">Terlambat</span>
                                @else
                                    <span class="badge text-bg-warning">Dipinjam</span>
                                @endif
                            </td>
                            <td data-label="Denda">Rp{{ number_format($item->denda, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap cell-actions">
                                @if ($item->status === 'dipinjam')
                                    <form action="{{ route('admin.peminjaman.kembalikan', $item) }}" method="POST" onsubmit="return confirm('Catat pengembalian buku ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success" type="submit">Kembalikan</button>
                                    </form>
                                @else
                                    <span class="text-secondary small"><span class="d-md-none">Dikembalikan </span>{{ $item->tgl_kembali?->format('d M Y') ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center muted py-4">Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $peminjaman->links() }}
    </section>
@endsection
