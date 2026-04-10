@if ($paginator->hasPages())
    <nav class="trek-pagination" role="navigation" aria-label="Pagination">
        <ul class="trek-pagination__list">
            <li class="trek-pagination__item">
                @if ($paginator->onFirstPage())
                    <span class="trek-pagination__link is-disabled" aria-disabled="true" aria-label="Previous">
                        <span class="trek-pagination__icon" aria-hidden="true">&lsaquo;</span>
                    </span>
                @else
                    <a class="trek-pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                        <span class="trek-pagination__icon" aria-hidden="true">&lsaquo;</span>
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="trek-pagination__item">
                        <span class="trek-pagination__ellipsis">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="trek-pagination__item">
                            @if ($page == $paginator->currentPage())
                                <span class="trek-pagination__link is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="trek-pagination__link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li class="trek-pagination__item">
                @if ($paginator->hasMorePages())
                    <a class="trek-pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                        <span class="trek-pagination__icon" aria-hidden="true">&rsaquo;</span>
                    </a>
                @else
                    <span class="trek-pagination__link is-disabled" aria-disabled="true" aria-label="Next">
                        <span class="trek-pagination__icon" aria-hidden="true">&rsaquo;</span>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
