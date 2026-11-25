@extends('layouts.admin')

@section('judul', 'Peminjaman')

@section('konten')
    {{-- STATISTIK --}}
    <div class="row row-deck row-cards mb-3">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm cx-stat"><div class="card-body"><div class="row align-items-center">
                <div class="col-auto"><span class="bg-primary text-white avatar"><i class="bi bi-journal-bookmark fs-3"></i></span></div>
                <div class="col"><div class="fw-bold fs-3">{{ $statistik['buku'] }}</div><div class="text-secondary">Judul buku</div></div>
            </div></div></div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm cx-stat"><div class="card-body"><div class="row align-items-center">
                <div class="col-auto"><span class="bg-green text-white avatar"><i class="bi bi-people fs-3"></i></span></div>
                <div class="col"><div class="fw-bold fs-3">{{ $statistik['anggota'] }}</div><div class="text-secondary">Anggota aktif</div></div>
            </div></div></div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm cx-stat"><div class="card-body"><div class="row align-items-center">
                <div class="col-auto"><span class="bg-azure text-white avatar"><i class="bi bi-arrow-left-right fs-3"></i></span></div>
                <div class="col"><div class="fw-bold fs-3">{{ $statistik['dipinjam'] }}</div><div class="text-secondary">Sedang dipinjam</div></div>
            </div></div></div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm cx-stat"><div class="card-body"><div class="row align-items-center">
                <div class="col-auto"><span class="bg-red text-white avatar"><i class="bi bi-alarm fs-3"></i></span></div>
                <div class="col"><div class="fw-bold fs-3">{{ $statistik['terlambat'] }}</div><div class="text-secondary">Terlambat</div></div>
            </div></div></div>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="card mb-3">
        <div class="card-header"><h2 class="card-title"><i class="bi bi-bar-chart-line me-1"></i>Peminjaman 6 bulan terakhir</h2></div>
        <div class="card-body"><div style="height:220px"><canvas id="chartPeminjaman" aria-label="Grafik peminjaman per bulan" role="img"></canvas></div></div>
    </div>

    {{-- RIWAYAT --}}
    <div class="cx-card">
        <div class="cx-card-head">
            <div>
                <h1 class="cx-title">Riwayat peminjaman <span class="cx-count">{{ $peminjaman->total() }}</span></h1>
                <p class="cx-sub">Halo, {{ auth()->user()->name }}. Catat peminjaman dan pengembalian di sini.</p>
            </div>

            <form class="cx-toolbar" action="{{ route('admin.peminjaman.index') }}" method="GET">
                @if (request('per_page'))
                    <input type="hidden" name="per_page" value="{{ (int) request('per_page') }}">
                @endif
                <div class="cx-search">
                    <select name="status" class="cx-sel" aria-label="Filter status" data-autosubmit>
                        <option value="">Semua</option>
                        <option value="dipinjam" @selected($statusTerpilih === 'dipinjam')>Dipinjam</option>
                        <option value="dikembalikan" @selected($statusTerpilih === 'dikembalikan')>Dikembalikan</option>
                        <option value="terlambat" @selected($statusTerpilih === 'terlambat')>Terlambat</option>
                    </select>
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" name="q" value="{{ $keyword }}" placeholder="Cari nama anggota" aria-label="Cari nama anggota">
                </div>

                @if ($keyword !== '' || $statusTerpilih !== '')
                    <a class="cx-btn-ghost" href="{{ route('admin.peminjaman.index') }}"><i class="bi bi-x-lg"></i>Reset</a>
                @endif

                @include('partials.columns-menu', ['columns' => [
                    'anggota' => 'Anggota', 'buku' => 'Buku', 'pinjam' => 'Tanggal pinjam',
                    'tempo' => 'Jatuh tempo', 'status' => 'Status', 'denda' => 'Denda',
                ]])

                <a class="cx-btn-primary" href="{{ route('admin.peminjaman.create') }}"><i class="bi bi-plus-lg"></i>Catat peminjaman</a>
            </form>
        </div>

        <div class="cx-table-wrap">
            <table class="cx-table" data-cx-table="peminjaman">
                <thead>
                    <tr>
                        <th class="cx-num">No</th>
                        <th data-col="anggota">Anggota</th>
                        <th data-col="buku">Buku</th>
                        <th data-col="pinjam">Tanggal pinjam</th>
                        <th data-col="tempo">Jatuh tempo</th>
                        <th data-col="status">Status</th>
                        <th data-col="denda">Denda</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $item)
                        @php $terlambat = $item->status === 'dipinjam' && $item->tgl_jatuh_tempo->lt(today()); @endphp
                        <tr>
                            <td class="cx-num">{{ $peminjaman->firstItem() + $loop->index }}</td>
                            <td data-col="anggota">
                                <span class="cx-strong">{{ $item->anggota->nama }}</span>
                                <span class="cx-small">{{ $item->anggota->nis }} · {{ $item->anggota->kelas }}</span>
                            </td>
                            <td data-col="buku">
                                <span class="cx-strong">{{ $item->buku->judul }}</span>
                                <span class="cx-small">{{ $item->buku->kode_buku }}</span>
                            </td>
                            <td data-col="pinjam" class="cx-nowrap">{{ $item->tgl_pinjam->format('d M Y') }}</td>
                            <td data-col="tempo" class="cx-nowrap">{{ $item->tgl_jatuh_tempo->format('d M Y') }}</td>
                            <td data-col="status">
                                @if ($item->status === 'dikembalikan')
                                    <span class="cx-pill cx-pill--ok">Dikembalikan</span>
                                @elseif ($terlambat)
                                    <span class="cx-pill cx-pill--bad">Terlambat</span>
                                @else
                                    <span class="cx-pill cx-pill--warn">Dipinjam</span>
                                @endif
                            </td>
                            <td data-col="denda" class="cx-nowrap">Rp{{ number_format($item->denda, 0, ',', '.') }}</td>
                            <td>
                                <div class="cx-actions">
                                    @if ($item->status === 'dipinjam')
                                        <form action="{{ route('admin.peminjaman.kembalikan', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="cx-act-text" type="submit"><i class="bi bi-box-arrow-in-down"></i>Kembalikan</button>
                                        </form>
                                    @else
                                        <span class="cx-small">{{ $item->tgl_kembali?->format('d M Y') ?? '-' }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="cx-empty"><i class="bi bi-inbox"></i>{{ ($keyword !== '' || $statusTerpilih !== '') ? 'Tidak ada peminjaman yang cocok dengan filter.' : 'Belum ada peminjaman. Klik "Catat peminjaman" untuk mulai.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('partials.table-footer', ['paginator' => $peminjaman])
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            var data = @json($grafik);
            var el = document.getElementById('chartPeminjaman');
            if (!el || typeof Chart === 'undefined') return;
            var warna = getComputedStyle(document.documentElement).getPropertyValue('--tblr-primary').trim() || '#4a4de6';
            new Chart(el, {
                type: 'bar',
                data: { labels: data.label, datasets: [{ label: 'Peminjaman', data: data.data, backgroundColor: warna, borderRadius: 4, maxBarThickness: 38 }] },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        })();
    </script>
@endpush
