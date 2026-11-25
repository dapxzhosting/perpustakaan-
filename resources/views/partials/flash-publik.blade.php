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
