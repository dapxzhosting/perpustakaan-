{{-- Footer tabel: info baris · pagination · baris per halaman. Pakai: @include('partials.table-footer', ['paginator' => $buku]) --}}
<div class="cx-foot">
    <div class="cx-foot-info">
        @if ($paginator->total() > 0)
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }}
        @endif
    </div>

    <div>{{ $paginator->onEachSide(1)->links('partials.pagination-crud') }}</div>

    <form class="cx-perpage" method="GET" action="{{ url()->current() }}">
        @foreach (request()->except(['per_page', 'page']) as $name => $value)
            @if (is_scalar($value))
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endif
        @endforeach
        <label class="visually-hidden" for="per-page">Baris per halaman</label>
        <select id="per-page" name="per_page" class="cx-sel cx-sel--box" data-autosubmit>
            @foreach ([10, 20, 50, 100] as $n)
                <option value="{{ $n }}" @selected($paginator->perPage() === $n)>{{ $n }}</option>
            @endforeach
        </select>
    </form>
</div>
