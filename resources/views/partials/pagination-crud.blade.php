@if ($paginator->hasPages())
    <nav aria-label="Navigasi halaman">
        <ul class="cx-pager">
            @if ($paginator->onFirstPage())
                <li class="is-disabled" aria-disabled="true"><span aria-hidden="true"><i class="bi bi-chevron-left"></i></span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya"><i class="bi bi-chevron-left"></i></a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="is-disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="is-active" aria-current="page"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" aria-label="Ke halaman {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya"><i class="bi bi-chevron-right"></i></a></li>
            @else
                <li class="is-disabled" aria-disabled="true"><span aria-hidden="true"><i class="bi bi-chevron-right"></i></span></li>
            @endif
        </ul>
    </nav>
@endif
