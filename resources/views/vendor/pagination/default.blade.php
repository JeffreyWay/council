@if ($paginator->hasPages())
    <div class="text-center">
        <ul class="inline-flex w-48 list-reset border py-2 mt-4 mb-8 rounded">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled flex-1 border-r"><span>&laquo;</span></li>
            @else
                <li class="flex-1 border-r"><a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="text-blue font-bold">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled flex-1 border-r"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active flex-1 font-bold border-r"><span>{{ $page }}</span></li>
                        @else
                            <li class="flex-1 font-bold border-r"><a href="{{ $url }}" class="text-blue">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="flex-1"><a href="{{ $paginator->nextPageUrl() }}" rel="next" class="text-blue font-bold">&raquo;</a></li>
            @else
                <li class="disabled flex-1"><span>&raquo;</span></li>
            @endif
    </ul>
    </div>
@endif
