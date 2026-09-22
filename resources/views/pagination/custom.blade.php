@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="pt-10">
        <div class="flex items-center justify-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" aria-label="Previous page" class="w-10 h-10 inline-flex items-center justify-center border border-ink/10 text-graphite opacity-40">
                    <span aria-hidden="true">&larr;</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page"
                   class="w-10 h-10 inline-flex items-center justify-center border border-ink/20 hover:border-ink hover:bg-ink hover:text-bone transition-colors">
                    <span aria-hidden="true">&larr;</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="w-10 h-10 inline-flex items-center justify-center text-graphite">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="w-10 h-10 inline-flex items-center justify-center bg-ink text-bone text-sm font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" aria-label="Go to page {{ $page }}"
                               class="w-10 h-10 inline-flex items-center justify-center border border-ink/15 hover:border-ink text-sm transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page"
                   class="w-10 h-10 inline-flex items-center justify-center border border-ink/20 hover:border-ink hover:bg-ink hover:text-bone transition-colors">
                    <span aria-hidden="true">&rarr;</span>
                </a>
            @else
                <span aria-disabled="true" aria-label="Next page" class="w-10 h-10 inline-flex items-center justify-center border border-ink/10 text-graphite opacity-40">
                    <span aria-hidden="true">&rarr;</span>
                </span>
            @endif
        </div>
    </nav>
@endif