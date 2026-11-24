<script>
    // Select2 untuk <select class="select2"> (hanya jika jQuery + Select2 dimuat, yaitu di layout admin)
    if (window.jQuery && jQuery.fn.select2) {
        jQuery(function ($) {
            $('select.select2').select2({ theme: 'bootstrap-5', width: '100%' });
        });
    }

    // Konfirmasi SweetAlert2 otomatis untuk form hapus (DELETE) dan pengembalian (/kembali)
    document.querySelectorAll('form').forEach(function (form) {
        var method = form.querySelector('input[name="_method"]');
        var isDelete = method && method.value.toUpperCase() === 'DELETE';
        var isReturn = /\/kembali\/?$/.test(form.getAttribute('action') || '');
        if (!isDelete && !isReturn) return;

        form.removeAttribute('onsubmit'); // buang confirm() bawaan browser

        form.addEventListener('submit', function (e) {
            if (form.dataset.confirmed) return;
            e.preventDefault();
            Swal.fire({
                title: isDelete ? 'Hapus data ini?' : 'Catat pengembalian?',
                text: isDelete
                    ? 'Data yang dihapus tidak bisa dikembalikan.'
                    : 'Stok buku bertambah dan denda (jika telat) dihitung otomatis.',
                icon: isDelete ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonText: isDelete ? 'Ya, hapus' : 'Ya, catat',
                cancelButtonText: 'Batal',
                confirmButtonColor: isDelete ? '#d63939' : '#2fb344',
                reverseButtons: true
            }).then(function (r) {
                if (r.isConfirmed) { form.dataset.confirmed = '1'; form.submit(); }
            });
        });
    });
</script>
