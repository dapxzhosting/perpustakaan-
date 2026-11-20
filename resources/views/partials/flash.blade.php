@if (session('sukses'))
    <div class="alert alert-success alert-dismissible fade show app-alert" role="alert">
        <span class="alert-icon" aria-hidden="true">✓</span>
        <span>{{ session('sukses') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
@if (session('galat'))
    <div class="alert alert-danger alert-dismissible fade show app-alert" role="alert">
        <span class="alert-icon" aria-hidden="true">!</span>
        <span>{{ session('galat') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger app-alert app-alert-errors" role="alert">
        <strong>Periksa kembali data yang dimasukkan.</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
