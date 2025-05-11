@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 cursor-default leading-5 rounded-md select-none">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" data-page="{{ $paginator->currentPage() - 1 }}"
                class="pagination-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 leading-5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-primary-500 active:bg-gray-100 dark:active:bg-gray-700 transition ease-in-out duration-150">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        <div class="hidden md:flex items-center space-x-1">
            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true"
                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 cursor-default leading-5 select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-700 cursor-default leading-5 select-none">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" data-page="{{ $page }}"
                                class="pagination-link relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 leading-5 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-primary-500 active:bg-gray-100 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" data-page="{{ $paginator->currentPage() + 1 }}"
                class="pagination-link relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 leading-5 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-1 focus:ring-primary-500 active:bg-gray-100 dark:active:bg-gray-700 transition ease-in-out duration-150">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 cursor-default leading-5 rounded-md select-none">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif
