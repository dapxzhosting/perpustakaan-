@extends('layouts.admin')

@section('judul', 'Peminjaman')

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <span class="page-pretitle d-block">PANEL PERPUSTAKAAN</span>
            <h1 class="page-title">Selamat datang, {{ auth()->user()->name }}</h1>
            <p class="text-body-secondary mb-0">Kelola peminjaman, pengembalian, dan koleksi perpustakaan.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.peminjaman.create') }}"><i class="bi bi-plus-lg me-1"></i>Catat peminjaman</a>
    </div>

    {{-- STATISTIK --}}
    <div class="row row-deck row-cards mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm"><div class="card-body"><div class="row align-items-center">
                <div class="col-auto"><span class="bg-primary text-white avatar"><i class="bi bi-journal-bookmark fs-3"></i></span></div>
                <div class="col"><div class="fw-bold fs-3">{{ $statistik['buku'] }}</div><div class="text-secondary">Judul buku</div></div>
            </div></div></div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm"><div class="card-body"><div class="row align-items-center">
                <div class="col-auto"><span class="bg-green text-white avatar"><i class="bi bi-people fs-3"></i></span></div>
                <div class="col"><div class="fw-bold fs-3">{{ $statistik['anggota'] }}</div><div class="text-secondary">Anggota aktif</div></div>
            </div></div></div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm"><div class="card-body"><div class="row align-items-center">
                <div class="col-auto"><span class="bg-azure text-white avatar"><i class="bi bi-arrow-left-right fs-3"></i></span></div>
                <div class="col"><div class="fw-bold fs-3">{{ $statistik['dipinjam'] }}</div><div class="text-secondary">Sedang dipinjam</div></div>
            </div></div></div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm"><div class="card-body"><div class="row align-items-center">
                <div class="col-auto"><span class="bg-red text-white avatar"><i class="bi bi-alarm fs-3"></i></span></div>
                <div class="col"><div class="fw-bold fs-3">{{ $statistik['terlambat'] }}</div><div class="text-secondary">Terlambat</div></div>
            </div></div></div>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="card mb-4">
        <div class="card-header"><h2 class="card-title"><i class="bi bi-bar-chart-line me-1"></i>Peminjaman 6 bulan terakhir</h2></div>
        <div class="card-body"><div style="height:240px"><canvas id="chartPeminjaman" aria-label="Grafik peminjaman per bulan" role="img"></canvas></div></div>
    </div>

    <div class="card">
        <div class="card-header bg-body border-0 pt-3">
            <h2 class="h5 mb-3"><i class="bi bi-clock-history me-1"></i>Riwayat peminjaman</h2>
            <form action="{{ route('admin.peminjaman.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="visually-hidden" for="loan-q">Cari nama anggota</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input id="loan-q" name="q" value="{{ $keyword }}" class="form-control" placeholder="Cari nama anggota">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="visually-hidden" for="loan-status">Filter status</label>
                        <select id="loan-status" name="status" class="form-select">
                            <option value="">Semua status</option>
                            <option value="dipinjam" @selected($statusTerpilih === 'dipinjam')>Dipinjam</option>
                            <option value="dikembalikan" @selected($statusTerpilih === 'dikembalikan')>Dikembalikan</option>
                            <option value="terlambat" @selected($statusTerpilih === 'terlambat')>Terlambat</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-funnel me-1"></i>Terapkan filter</button>
                        @if ($keyword !== '' || $statusTerpilih !== '')
                            <a class="btn btn-outline-secondary" href="{{ route('admin.peminjaman.index') }}">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-vcenter card-table table-hover">
                <thead class="table-light">
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
                        @php $terlambat = $item->status === 'dipinjam' && $item->tgl_jatuh_tempo->lt(today()); @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->anggota->nama }}</div>
                                <small class="text-body-secondary">{{ $item->anggota->nis }} · {{ $item->anggota->kelas }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $item->buku->judul }}</div>
                                <small class="text-body-secondary">{{ $item->buku->kode_buku }}</small>
                            </td>
                            <td class="text-nowrap">{{ $item->tgl_pinjam->format('d M Y') }}</td>
                            <td class="text-nowrap">{{ $item->tgl_jatuh_tempo->format('d M Y') }}</td>
                            <td>
                                @if ($item->status === 'dikembalikan')
                                    <span class="badge text-bg-success">Dikembalikan</span>
                                @elseif ($terlambat)
                                    <span class="badge text-bg-danger">Terlambat</span>
                                @else
                                    <span class="badge text-bg-warning">Dipinjam</span>
                                @endif
                            </td>
                            <td class="text-nowrap">Rp{{ number_format($item->denda, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap">
                                @if ($item->status === 'dipinjam')
                                    <form class="d-inline" action="{{ route('admin.peminjaman.kembalikan', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-outline-success" type="submit"><i class="bi bi-box-arrow-in-down me-1"></i>Kembalikan</button>
                                    </form>
                                @else
                                    <span class="text-body-secondary small">{{ $item->tgl_kembali?->format('d M Y') ?? '-' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-body-secondary py-5"><i class="bi bi-inbox display-6 d-block mb-2"></i>Belum ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-body border-0 d-flex justify-content-center pt-3">{{ $peminjaman->links() }}</div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            var data = @json($grafik);
            var el = document.getElementById('chartPeminjaman');
            if (!el || typeof Chart === 'undefined') return;
            var warna = getComputedStyle(document.documentElement).getPropertyValue('--tblr-primary').trim() || '#066fd1';
            new Chart(el, {
                type: 'bar',
                data: { labels: data.label, datasets: [{ label: 'Peminjaman', data: data.data, backgroundColor: warna, borderRadius: 6 }] },
                options: {
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        })();
    </script>
@endpush
