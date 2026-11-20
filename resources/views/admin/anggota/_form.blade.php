<form action="{{ $route }}" method="POST">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label for="nis" class="form-label">NIS</label>
            <input id="nis" name="nis" value="{{ old('nis', $anggota->nis) }}" class="form-control @error('nis') is-invalid @enderror" required>
            @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="nama" class="form-label">Nama lengkap</label>
            <input id="nama" name="nama" value="{{ old('nama', $anggota->nama) }}" class="form-control @error('nama') is-invalid @enderror" required>
            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="kelas" class="form-label">Kelas</label>
            <input id="kelas" name="kelas" value="{{ old('kelas', $anggota->kelas) }}" class="form-control @error('kelas') is-invalid @enderror" required>
            @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="no_hp" class="form-label">Nomor HP</label>
            <input id="no_hp" name="no_hp" type="tel" value="{{ old('no_hp', $anggota->no_hp) }}" placeholder="081234567890" class="form-control @error('no_hp') is-invalid @enderror" required>
            @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="aktif" @selected(old('status', $anggota->status ?? 'aktif') === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(old('status', $anggota->status) === 'nonaktif')>Nonaktif</option>
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <button class="btn btn-primary mt-4">Simpan anggota</button>
</form>
