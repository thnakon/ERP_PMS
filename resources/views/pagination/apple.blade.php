{{-- Apple Style Pagination --}}
@if ($paginator->hasPages())
    @if ($paginator->onFirstPage())
        <button class="table-pagination-btn" disabled>
            <i class="ph-bold ph-caret-left"></i>
        </button>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="table-pagination-btn">
            <i class="ph-bold ph-caret-left"></i>
        </a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="table-pagination-btn text-gray-400">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="table-pagination-btn table-pagination-btn-active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="table-pagination-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="table-pagination-btn">
            <i class="ph-bold ph-caret-right"></i>
        </a>
    @else
        <button class="table-pagination-btn" disabled>
            <i class="ph-bold ph-caret-right"></i>
        </button>
    @endif
@endif
