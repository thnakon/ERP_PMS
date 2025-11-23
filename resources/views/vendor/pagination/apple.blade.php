@if ($paginator->hasPages())
    <div class="people-pagination">
        <span class="pagination-text">
            {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </span>

        <div class="pagination-controls">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="pagination-btn disabled" disabled aria-label="@lang('pagination.previous')">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" aria-label="@lang('pagination.previous')">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" aria-label="@lang('pagination.next')">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            @else
                <button class="pagination-btn disabled" disabled aria-label="@lang('pagination.next')">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            @endif
        </div>
    </div>
@endif
