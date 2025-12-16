@if ($paginator->hasPages())
    <div class="flex flex-col items-end ">
        <!-- Pagination Container -->
        <div class="inline-flex items-center gap-1 bg-white p-1.5 rounded-full   shadow-sm">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-9 h-9 flex items-center justify-center rounded-full text-gray-300 cursor-default"
                    aria-disabled="true">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-[#1D1D1F] hover:bg-[#F5F5F7] transition duration-200"
                    rel="prev">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span
                        class="w-9 h-9 flex items-center justify-center text-gray-400 text-xs font-medium cursor-default">...</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="w-9 h-9 flex items-center justify-center rounded-full bg-black text-white text-[13px] font-semibold shadow-md cursor-default">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="w-9 h-9 flex items-center justify-center rounded-full text-[#1D1D1F] hover:bg-[#F5F5F7] text-[13px] font-medium transition duration-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="w-9 h-9 flex items-center justify-center rounded-full text-[#1D1D1F] hover:bg-[#F5F5F7] transition duration-200"
                    rel="next">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            @else
                <span class="w-9 h-9 flex items-center justify-center rounded-full text-gray-300 cursor-default"
                    aria-disabled="true">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </span>
            @endif
        </div>

        <!-- Help Text -->
        <div class="mt-3 text-[11px] font-medium text-[#86868B] tracking-wide uppercase">
            Showing {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </div>
    </div>
@endif
