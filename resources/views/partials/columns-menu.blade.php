{{-- Dropdown "Pilih kolom". Pakai: @include('partials.columns-menu', ['columns' => ['kunci' => 'Label']])
     Tabel harus punya data-cx-table="nama" dan sel th/td bertanda data-col="kunci". --}}
<div class="dropdown" data-cx-colmenu>
    <button type="button" class="cx-btn-ghost" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        Pilih kolom <i class="bi bi-chevron-down cx-chev"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end cx-colmenu">
        @foreach ($columns as $key => $label)
            <label><input type="checkbox" data-col-toggle="{{ $key }}" checked> {{ $label }}</label>
        @endforeach
    </div>
</div>
