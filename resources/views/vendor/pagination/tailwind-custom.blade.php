@if ($paginator->hasPages())
    <div class="flex items-center justify-between bg-white dark:bg-gray-800">
        <div class="text-sm text-gray-700 dark:text-gray-300">
            <p>
                Menampilkan
                <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span>
                sampai
                <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span>
                dari
                <span class="font-medium">{{ $paginator->total() }}</span>
                hasil
            </p>
        </div>

        <div class="flex items-center">
            <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                {{-- Tombol Previous --}}
                @if ($paginator->onFirstPage())
                    <span
                        class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-not-allowed rounded-l-md">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                        @click.prevent="handlePaginationClick('{{ $paginator->previousPageUrl() }}')"
                        class="pagination-link relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-l-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Angka Halaman --}}
                @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-primary-600 dark:bg-primary-700 border border-primary-600 dark:border-primary-700 z-10">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" @click.prevent="handlePaginationClick('{{ $url }}')"
                            class="pagination-link relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Tombol Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                        @click.prevent="handlePaginationClick('{{ $paginator->nextPageUrl() }}')"
                        class="pagination-link relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-r-md hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span
                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-not-allowed rounded-r-md">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </nav>
        </div>
    </div>
@endif
