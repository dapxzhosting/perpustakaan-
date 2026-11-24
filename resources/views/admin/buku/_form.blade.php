<form action="{{ $route }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label for="kategori_id" class="form-label">Kategori</label>
            <select id="kategori_id" name="kategori_id" class="form-select select2 @error('kategori_id') is-invalid @enderror" required>
                <option value="">Pilih kategori</option>
                @foreach ($kategori as $item)
                    <option value="{{ $item->id }}" @selected(old('kategori_id', $buku->kategori_id) == $item->id)>{{ $item->nama }}</option>
                @endforeach
            </select>
            @error('kategori_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="kode_buku" class="form-label">Kode buku</label>
            <input id="kode_buku" name="kode_buku" value="{{ old('kode_buku', $buku->kode_buku) }}" class="form-control @error('kode_buku') is-invalid @enderror" required maxlength="20">
            @error('kode_buku')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label for="judul" class="form-label">Judul</label>
            <input id="judul" name="judul" value="{{ old('judul', $buku->judul) }}" class="form-control @error('judul') is-invalid @enderror" required maxlength="200">
            @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="penulis" class="form-label">Penulis</label>
            <input id="penulis" name="penulis" value="{{ old('penulis', $buku->penulis) }}" class="form-control @error('penulis') is-invalid @enderror" required>
            @error('penulis')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="penerbit" class="form-label">Penerbit</label>
            <input id="penerbit" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" class="form-control @error('penerbit') is-invalid @enderror">
            @error('penerbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="tahun_terbit" class="form-label">Tahun terbit</label>
            <input id="tahun_terbit" name="tahun_terbit" type="number" min="1000" max="{{ date('Y') }}" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" class="form-control @error('tahun_terbit') is-invalid @enderror" required>
            @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="stok" class="form-label">Stok tersedia</label>
            <input id="stok" name="stok" type="number" min="0" value="{{ old('stok', $buku->stok ?? 0) }}" class="form-control @error('stok') is-invalid @enderror" required>
            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label for="sampul" class="form-label">Sampul buku (opsional, JPG/PNG maks. 2 MB)</label>
            <input id="sampul" name="sampul" type="file" accept=".jpg,.jpeg,.png" class="form-control @error('sampul') is-invalid @enderror">
            @error('sampul')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">Pilih gambar untuk menambah atau mengganti sampul. Kosongkan jika ingin mempertahankan sampul saat ini.</div>
            <div id="sampul-preview" class="mt-3" @if (!$buku->sampul) hidden @endif>
                <img id="sampul-preview-image" class="img-thumbnail" style="max-height:200px"
                     data-current-src="{{ $buku->sampul ? asset('storage/'.$buku->sampul) : '' }}"
                     src="{{ $buku->sampul ? asset('storage/'.$buku->sampul) : '' }}"
                     alt="Pratinjau sampul {{ $buku->judul }}">
                <div id="sampul-preview-label" class="small text-body-secondary mt-1">{{ $buku->sampul ? 'Sampul saat ini' : '' }}</div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mt-4">
        <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle me-1"></i>Simpan buku</button>
        <a class="btn btn-outline-secondary" href="{{ route('admin.buku.index') }}">Batal</a>
    </div>
</form>

@push('scripts')
    <script>
        const sampulInput = document.getElementById('sampul');
        const sampulPreview = document.getElementById('sampul-preview');
        const sampulPreviewImage = document.getElementById('sampul-preview-image');
        const sampulPreviewLabel = document.getElementById('sampul-preview-label');
        let sampulPreviewUrl;

        sampulInput.addEventListener('change', () => {
            const file = sampulInput.files[0];

            if (sampulPreviewUrl) {
                URL.revokeObjectURL(sampulPreviewUrl);
                sampulPreviewUrl = undefined;
            }

            if (file) {
                sampulPreviewUrl = URL.createObjectURL(file);
                sampulPreviewImage.src = sampulPreviewUrl;
                sampulPreviewLabel.textContent = 'Pratinjau sampul baru';
                sampulPreview.hidden = false;
                return;
            }

            const currentSrc = sampulPreviewImage.dataset.currentSrc;
            sampulPreview.hidden = !currentSrc;

            if (currentSrc) {
                sampulPreviewImage.src = currentSrc;
                sampulPreviewLabel.textContent = 'Sampul saat ini';
            }
        });
    </script>
@endpush
