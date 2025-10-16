@if ($paginator->hasPages())
    <div class="mbp_pagination mt30 text-center">
        <ul class="page_navigation">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><span class="fas fa-angle-left"></span></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                        <span class="fas fa-angle-left"></span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a class="page-link" href="#">{{ $page }} <span
                                        class="sr-only">(current)</span></a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                        <span class="fas fa-angle-right"></span>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><span class="fas fa-angle-right"></span></span>
                </li>
            @endif
        </ul>
        <p class="mt10 mb-0 pagination_page_count text-center">
            Showing {{ $paginator->firstItem() }} – {{ $paginator->lastItem() }} of {{ $paginator->total() }} properties
            available
        </p>
    </div>
@endif
