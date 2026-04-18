@if ($paginator->hasPages())
    <nav class="flex justify-center mt-12 mb-8" role="navigation" aria-label="Pagination Navigation">
        <ul class="flex items-center gap-1 sm:gap-2">
            {{-- Previous Page Link --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-300 cursor-not-allowed border border-slate-100" aria-disabled="true">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all shadow-sm" aria-label="Previous">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </a>
                @endif
            </li>

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li>
                        <span class="w-10 h-10 flex items-center justify-center text-slate-400 font-bold">...</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 text-white font-bold shadow-md shadow-slate-900/20" aria-current="page">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 font-bold hover:bg-slate-100 border border-transparent transition-all">
                                    {{ $page }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 border border-slate-200 transition-all shadow-sm" aria-label="Next">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-300 cursor-not-allowed border border-slate-100" aria-disabled="true">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </li>
        </ul>
    </nav>
@endif
