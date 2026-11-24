@extends('layouts.admin')

@section('judul', 'Catat Peminjaman')

@section('konten')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <span class="page-pretitle d-block">TRANSAKSI BARU</span>
            <h1 class="page-title">Catat peminjaman</h1>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('admin.peminjaman.index') }}"><i class="bi bi-arrow-left me-1"></i>Kembali ke riwayat</a>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <p class="text-body-secondary mb-4">Tanggal pinjam diisi hari ini dan jatuh tempo otomatis tujuh hari setelahnya.</p>
            <form action="{{ route('admin.peminjaman.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="anggota_id" class="form-label fw-semibold">Anggota aktif</label>
                        <select id="anggota_id" name="anggota_id" class="form-select select2 @error('anggota_id') is-invalid @enderror" required>
                            <option value="">Pilih anggota</option>
                            @foreach ($anggota as $item)
                                <option value="{{ $item->id }}" @selected(old('anggota_id') == $item->id)>{{ $item->nama }} · {{ $item->nis }} · {{ $item->kelas }}</option>
                            @endforeach
                        </select>
                        @error('anggota_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        @if ($anggota->isEmpty())
                            <small class="text-danger">Belum ada anggota aktif yang dapat meminjam.</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="buku_id" class="form-label fw-semibold">Buku</label>
                        <select id="buku_id" name="buku_id" class="form-select select2 @error('buku_id') is-invalid @enderror" required>
                            <option value="">Pilih buku</option>
                            @foreach ($buku as $item)
                                <option value="{{ $item->id }}" @selected(old('buku_id') == $item->id) @disabled($item->stok < 1)>
                                    {{ $item->judul }} · {{ $item->kategori->nama }} · {{ $item->stok > 0 ? 'Stok '.$item->stok : 'Stok habis' }}
                                </option>
                            @endforeach
                        </select>
                        @error('buku_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="alert alert-info mt-4 mb-0">
                    <div class="fw-semibold mb-1"><i class="bi bi-info-circle me-1"></i>Ketentuan peminjaman</div>
                    <ul class="mb-0 ps-3">
                        <li>Anggota harus aktif dan dapat memiliki maksimal 3 pinjaman aktif.</li>
                        <li>Buku harus tersedia dan judul yang sama tidak boleh dipinjam dua kali sebelum dikembalikan.</li>
                        <li>Pengembalian terlambat dikenai denda Rp1.000 per hari.</li>
                    </ul>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button class="btn btn-primary" type="submit" @disabled($anggota->isEmpty() || $buku->where('stok', '>', 0)->isEmpty())><i class="bi bi-check2-circle me-1"></i>Simpan peminjaman</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.peminjaman.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
