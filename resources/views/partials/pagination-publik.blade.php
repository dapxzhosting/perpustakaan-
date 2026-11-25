@if ($paginator->hasPages())
    @php
        $dasar = 'inline-flex h-10 min-w-10 items-center justify-center rounded-lg border px-3 text-sm font-medium transition';
    @endphp
    <nav class="flex flex-wrap items-center justify-center gap-1.5" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="{{ $dasar }} cursor-not-allowed border-slate-200 bg-slate-50 text-slate-300" aria-disabled="true"><i class="bi bi-chevron-left"></i></span>
        @else
            <a class="{{ $dasar }} border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-600" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="{{ $dasar }} border-transparent text-slate-400">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $halaman => $url)
                    @if ($halaman == $paginator->currentPage())
                        <span class="{{ $dasar }} border-brand-600 bg-brand-600 text-white" aria-current="page">{{ $halaman }}</span>
                    @else
                        <a class="{{ $dasar }} border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-600" href="{{ $url }}">{{ $halaman }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="{{ $dasar }} border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-600" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></a>
        @else
            <span class="{{ $dasar }} cursor-not-allowed border-slate-200 bg-slate-50 text-slate-300" aria-disabled="true"><i class="bi bi-chevron-right"></i></span>
        @endif
    </nav>
@endif
