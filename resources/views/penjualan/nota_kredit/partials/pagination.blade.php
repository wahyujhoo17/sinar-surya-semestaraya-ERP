@if ($notaKredits->hasPages())
    <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $notaKredits->firstItem() }} hingga {{ $notaKredits->lastItem() }} dari
            {{ $notaKredits->total() }} data
        </div>
        <div>
            <ul class="flex space-x-1">
                {{-- Previous Page Link --}}
                @if ($notaKredits->onFirstPage())
                    <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                        <span
                            class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-700 dark:text-gray-500 rounded-md cursor-not-allowed"
                            aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $notaKredits->previousPageUrl() }}"
                            class="pagination-link relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-400"
                            rel="prev" aria-label="@lang('pagination.previous')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($notaKredits->getUrlRange(max($notaKredits->currentPage() - 2, 1), min($notaKredits->currentPage() + 2, $notaKredits->lastPage())) as $page => $url)
                    @if ($page == $notaKredits->currentPage())
                        <li class="active">
                            <span
                                class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-primary-600 rounded-md">
                                {{ $page }}
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}"
                                class="pagination-link relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-400">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($notaKredits->hasMorePages())
                    <li>
                        <a href="{{ $notaKredits->nextPageUrl() }}"
                            class="pagination-link relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-400"
                            rel="next" aria-label="@lang('pagination.next')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <span
                            class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 dark:bg-gray-700 dark:text-gray-500 rounded-md cursor-not-allowed"
                            aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </div>
@endif
