@extends('layouts.app')

@section('judul', 'Peminjaman Saya')

@section('konten')
    <div class="page-heading">
        <div>
            <p class="section-kicker">AKUN ANGGOTA</p>
            <h1>Peminjaman saya</h1>
        </div>
        <a class="button button-quiet" href="{{ route('katalog') }}">Jelajahi katalog</a>
    </div>

    <section class="panel-card member-loans">
        <div class="panel-heading">
            <div>
                <p class="section-kicker">RIWAYAT BUKU</p>
                <h2>Daftar peminjaman</h2>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Tanggal pinjam</th>
                        <th>Jatuh tempo</th>
                        <th>Tanggal kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $item)
                        <tr>
                            <td>
                                <a class="member-loan-title" href="{{ route('buku.show', $item->buku) }}">{{ $item->buku->judul }}</a>
                                <small class="d-block text-secondary">{{ $item->buku->kode_buku }}</small>
                            </td>
                            <td>{{ $item->tgl_pinjam->format('d M Y') }}</td>
                            <td>{{ $item->tgl_jatuh_tempo->format('d M Y') }}</td>
                            <td>{{ $item->tgl_kembali?->format('d M Y') ?? 'Belum dikembalikan' }}</td>
                            <td>
                                @if ($item->status === 'dikembalikan')
                                    <span class="badge text-bg-success">Dikembalikan</span>
                                @elseif ($item->tgl_jatuh_tempo->lt(today()))
                                    <span class="badge text-bg-danger">Terlambat</span>
                                @else
                                    <span class="badge text-bg-warning">Dipinjam</span>
                                @endif
                            </td>
                            <td>Rp{{ number_format($item->dendaSaatIni(), 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <h3>Belum ada peminjaman</h3>
                                    <p>Buku yang kamu pinjam akan muncul di halaman ini.</p>
                                    <a class="button button-primary" href="{{ route('katalog') }}">Cari buku</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $peminjaman->links() }}
    </section>
@endsection
