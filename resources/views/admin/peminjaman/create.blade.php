@extends('layouts.app')

@section('judul', 'Catat Peminjaman')

@section('konten')
    <div class="page-heading">
        <div>
            <p class="section-kicker">TRANSAKSI BARU</p>
            <h1>Catat peminjaman</h1>
        </div>
        <a class="button button-quiet" href="{{ route('admin.peminjaman.index') }}">Kembali ke riwayat</a>
    </div>

    <div class="panel-card loan-form-card">
        <p class="text-secondary mb-4">Tanggal pinjam diisi hari ini dan jatuh tempo otomatis tujuh hari setelahnya.</p>
        <form action="{{ route('admin.peminjaman.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label for="anggota_id" class="form-label fw-semibold">Anggota aktif</label>
                    <select id="anggota_id" name="anggota_id" class="form-select @error('anggota_id') is-invalid @enderror" required>
                        <option value="">Pilih anggota</option>
                        @foreach ($anggota as $item)
                            <option value="{{ $item->id }}" @selected(old('anggota_id') == $item->id)>{{ $item->nama }} · {{ $item->nis }} · {{ $item->kelas }}</option>
                        @endforeach
                    </select>
                    @error('anggota_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if ($anggota->isEmpty())
                        <small class="form-text text-danger">Belum ada anggota aktif yang dapat meminjam.</small>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="buku_id" class="form-label fw-semibold">Buku</label>
                    <select id="buku_id" name="buku_id" class="form-select @error('buku_id') is-invalid @enderror" required>
                        <option value="">Pilih buku</option>
                        @foreach ($buku as $item)
                            <option value="{{ $item->id }}" @selected(old('buku_id') == $item->id) @disabled($item->stok < 1)>
                                {{ $item->judul }} · {{ $item->kategori->nama }} · {{ $item->stok > 0 ? 'Stok '.$item->stok : 'Stok habis' }}
                            </option>
                        @endforeach
                    </select>
                    @error('buku_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="loan-rules mt-4">
                <strong>Ketentuan peminjaman</strong>
                <ul class="mb-0 mt-2">
                    <li>Anggota harus aktif dan dapat memiliki maksimal 3 pinjaman aktif.</li>
                    <li>Buku harus tersedia dan judul yang sama tidak boleh dipinjam dua kali sebelum dikembalikan.</li>
                    <li>Pengembalian terlambat dikenai denda Rp1.000 per hari.</li>
                </ul>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <button class="button button-primary" type="submit" @disabled($anggota->isEmpty() || $buku->where('stok', '>', 0)->isEmpty())>Simpan peminjaman</button>
                <a class="button button-quiet" href="{{ route('admin.peminjaman.index') }}">Batal</a>
            </div>
        </form>
    </div>
@endsection
