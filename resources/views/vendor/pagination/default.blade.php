 <div class="theme-pagination thme-pagination-mt text-center mt-18">
     @if ($paginator->hasPages())
         <ul>
             {{-- Previous Page Link --}}
             @if ($paginator->onFirstPage())
                 <li><a class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')"><i
                             class="fa-solid fa-angle-left"></i></a></li>
             @else
                 <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><i
                             class="fa-solid fa-angle-left"></i></a></li>
             @endif

             {{-- Pagination Elements --}}
             @foreach ($paginator->links()->elements as $element)
                 {{-- "Three Dots" Separator --}}
                 @if (is_string($element))
                     <li><a class="disabled" aria-disabled="true">{{ $element }}</a></li>
                 @endif

                 {{-- Array Of Links --}}
                 @if (is_array($element))
                     @foreach ($element as $page => $url)
                         @if ($page == $paginator->currentPage())
                             <li><a class="active" aria-current="page">{{ $page }}</a></li>
                         @else
                             <li><a href="{{ $url }}">{{ $page }}</a></li>
                         @endif
                     @endforeach
                 @endif
             @endforeach

             {{-- Next Page Link --}}
             @if ($paginator->hasMorePages())
                 <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"><i
                             class="fa-solid fa-angle-right"></i></a></li>
             @else
                 <li><a class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')"><i
                             class="fa-solid fa-angle-right"></i></a></li>
             @endif
         </ul>
     @endif
 </div>
