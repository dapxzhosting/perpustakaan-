@if (session('sukses') || session('galat'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false,
                timer: 4000, timerProgressBar: true
            });
            @if (session('sukses'))
                Toast.fire({ icon: 'success', title: @json(session('sukses')) });
            @endif
            @if (session('galat'))
                Toast.fire({ icon: 'error', title: @json(session('galat')) });
            @endif
        });
    </script>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <div class="fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Periksa kembali data yang dimasukkan.</div>
        <ul class="mb-0 mt-2 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif
