@php
    // Helper function to get status color
    function returStatusColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'gray';
            case 'diproses':
                return 'blue';
            case 'menunggu_barang_pengganti':
                return 'purple';
            case 'selesai':
                return 'emerald';
            default:
                return 'primary';
        }
    }

    // Helper function for status icon
    function returStatusIcon($status)
    {
        switch ($status) {
            case 'draft':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';
            case 'diproses':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
            case 'menunggu_barang_pengganti':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            case 'selesai':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            default:
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        }
    }

    // Helper function to format status text to display version (without underscores)
    function formatStatusText($status)
    {
        switch ($status) {
            case 'draft':
                return 'Draft';
            case 'diproses':
                return 'Diproses';
            case 'menunggu_barang_pengganti':
                return 'Menunggu Barang Pengganti';
            case 'selesai':
                return 'Selesai';
            case 'semua':
                return 'Semua';
            default:
                return ucfirst($status);
        }
    }
@endphp

<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Stats and Summary --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Retur Pembelian</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola pengembalian barang ke supplier
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if (auth()->user()->hasPermission('retur_pembelian.create'))
                        <a href="{{ route('pembelian.retur-pembelian.create') }}"
                            class="group inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg font-semibold text-sm text-white shadow-sm hover:from-primary-700 hover:to-primary-800 active:from-primary-800 active:to-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200">
                            <span
                                class="relative flex h-5 w-5 items-center justify-center rounded-md bg-white/20 group-hover:bg-white/30 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            <span>Tambah Retur</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Status Cards Summary --}}
        <div class="mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach ($validStatuses as $status)
                <div
                    class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    <div
                        class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-{{ returStatusColor($status) }}-50 dark:bg-{{ returStatusColor($status) }}-900/20 opacity-70">
                    </div>
                    <div class="p-4">
                        <p class="text-sm font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            {{ formatStatusText($status) }}</p>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $statusCounts[$status] ?? 0 }}
                            </p>
                            <p
                                class="ml-2 text-sm font-medium text-{{ returStatusColor($status) }}-500 dark:text-{{ returStatusColor($status) }}-400">
                                Dokumen
                            </p>
                        </div>
                        <div
                            class="absolute bottom-0 left-0 right-0 h-1 bg-{{ returStatusColor($status) }}-500 dark:bg-{{ returStatusColor($status) }}-600 opacity-80">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Main Content --}}
        <div x-data="returPembelianTableManager()"
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
            {{-- Modern Elegant Tabs --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 pt-6 pb-2 flex overflow-x-auto scrollbar-hide">
                    <div
                        class="relative flex space-x-4 after:absolute after:bottom-0 after:left-0 after:right-0 after:h-px after:bg-gray-200 dark:after:bg-gray-700">
                        @php
                            // Ensure the tabs are always in this specific order
                            // Adding 'semua' as the first tab to show all statuses
                            $returStatuses = ['semua', ...$validStatuses];
                        @endphp
                        @foreach ($returStatuses as $status)
                            <button type="button"
                                :class="tab === '{{ $status }}' ?
                                    'text-primary-600 dark:text-primary-400 border-primary-600 dark:border-primary-400' :
                                    'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-transparent hover:border-gray-300 dark:hover:border-gray-600'"
                                class="pb-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 focus:outline-none whitespace-nowrap relative"
                                @click="changeTab('{{ $status }}')">
                                <span class="flex items-center gap-2">
                                    @if ($status != 'semua')
                                        <span
                                            class="w-2 h-2 rounded-full bg-{{ returStatusColor($status) }}-500"></span>
                                    @endif
                                    {{ formatStatusText($status) }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Enhanced Filter Section --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                <form @submit.prevent="applyFilters" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-grow max-w-md space-y-1">
                        <label for="search-input"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Pencarian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="search-input" x-model="search"
                                placeholder="Cari nomor, supplier, PO..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        </div>
                    </div>

                    <div class="w-full sm:w-auto space-y-1">
                        <label for="date-filter"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Periode</label>
                        <select id="date-filter" x-model="dateFilter"
                            class="pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">Semua Tanggal</option>
                            <option value="today">Hari Ini</option>
                            <option value="yesterday">Kemarin</option>
                            <option value="this_week">Minggu Ini</option>
                            <option value="last_week">Minggu Lalu</option>
                            <option value="this_month">Bulan Ini</option>
                            <option value="last_month">Bulan Lalu</option>
                            <option value="range">Range Tanggal</option>
                        </select>
                    </div>

                    <template x-if="dateFilter === 'range'">
                        <div class="flex items-end gap-2 w-full sm:w-auto">
                            <div class="space-y-1">
                                <label for="date-start"
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">Mulai</label>
                                <input type="date" id="date-start" x-model="dateStart"
                                    class="pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            </div>
                            <div class="self-center pt-5">—</div>
                            <div class="space-y-1">
                                <label for="date-end"
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">Hingga</label>
                                <input type="date" id="date-end" x-model="dateEnd"
                                    class="pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            </div>
                        </div>
                    </template>

                    <div class="w-full sm:w-auto space-y-1">
                        <label for="supplier-filter"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                        <select id="supplier-filter" x-model="supplierId"
                            class="pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">Semua Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 items-end">
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-colors duration-200 h-[38px]">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <span>Filter</span>
                            </span>
                        </button>

                        <button type="button" @click="resetFilters" title="Reset filter"
                            class="inline-flex items-center justify-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-500 dark:hover:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-colors h-[38px] w-[38px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table Container with Loading State --}}
            <div class="relative px-1 sm:px-3 pb-6">
                <div x-show="loading"
                    class="absolute inset-0 bg-white/70 dark:bg-gray-800/70 flex items-center justify-center z-30 backdrop-blur-sm">
                    <div class="flex flex-col items-center">
                        <div
                            class="flex items-center justify-center h-14 w-14 rounded-full bg-primary-50 dark:bg-primary-900/30">
                            <svg class="animate-spin h-7 w-7 text-primary-600 dark:text-primary-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <span class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300">Memuat data...</span>
                    </div>
                </div>

                <div class="min-w-full align-middle">
                    <div id="table-content">
                        <div x-show="!tableHtml">
                            @if (!request()->ajax())
                                @include('pembelian.retur_pembelian._table')
                            @endif
                        </div>
                        <div x-html="tableHtml" x-show="tableHtml"></div>
                    </div>

                    <div id="retur-pagination-container" class="mt-6 px-5">
                        <div x-show="!paginationHtml">
                            @if (!request()->ajax())
                                {{ $returPembelian->appends(request()->except('page'))->links('vendor.pagination.tailwind-custom') }}
                            @endif
                        </div>
                        <div x-html="paginationHtml" x-show="paginationHtml"></div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function returPembelianTableManager() {
                    return {
                        tab: '{{ $currentStatus }}',
                        search: @json($search ?? ''),
                        dateFilter: @json($dateFilter ?? ''),
                        dateStart: @json($dateStart ?? ''),
                        dateEnd: @json($dateEnd ?? ''),
                        supplierId: @json($supplierId ?? ''),
                        loading: false,
                        tableHtml: '',
                        paginationHtml: '',
                        init() {
                            // Initial table HTML already rendered server-side
                            this.attachPaginationListener();
                        },
                        changeTab(status) {
                            this.tab = status;
                            this.fetchTable();
                        },
                        applyFilters() {
                            // Validate date range if selected
                            if (this.dateFilter === 'range') {
                                if (!this.dateStart || !this.dateEnd) {
                                    alert('Silakan isi tanggal mulai dan tanggal akhir.');
                                    return;
                                }

                                if (this.dateStart > this.dateEnd) {
                                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
                                    return;
                                }
                            }

                            this.fetchTable();
                        },
                        resetFilters() {
                            this.search = '';
                            this.dateFilter = '';
                            this.dateStart = '';
                            this.dateEnd = '';
                            this.supplierId = '';
                            // Keep current tab selection when resetting other filters
                            const currentTab = this.tab;
                            this.fetchTable();
                        },
                        buildQueryString() {
                            const params = new URLSearchParams();
                            if (this.tab && this.tab !== 'semua') params.append('status', this.tab);
                            if (this.search) params.append('search', this.search);
                            if (this.dateFilter) params.append('date_filter', this.dateFilter);
                            if (this.dateFilter === 'range') {
                                if (this.dateStart) params.append('date_start', this.dateStart);
                                if (this.dateEnd) params.append('date_end', this.dateEnd);
                            }
                            if (this.supplierId) params.append('supplier_id', this.supplierId);
                            return params.toString();
                        },
                        fetchTable() {
                            this.loading = true;
                            // Clear existing content first to avoid duplication
                            this.tableHtml = '';
                            this.paginationHtml = '';

                            const queryString = this.buildQueryString();
                            const url = `{{ route('pembelian.retur-pembelian.index') }}?${queryString}`;

                            // For debugging, log the query
                            console.log('Fetching with query:', queryString);

                            fetch(url, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(r => r.json())
                                .then(data => {
                                    this.tableHtml = data.table_html;
                                    this.paginationHtml = data.pagination_html;
                                    this.loading = false;
                                    this.attachPaginationListener();
                                })
                                .catch(error => {
                                    console.error('Error fetching data:', error);
                                    this.loading = false;
                                });
                        },
                        attachPaginationListener() {
                            this.$nextTick(() => {
                                document.querySelectorAll('#retur-pagination-container a').forEach(link => {
                                    link.addEventListener('click', e => {
                                        e.preventDefault();
                                        const url = new URL(link.href);
                                        const page = url.searchParams.get('page');
                                        if (page) {
                                            const params = this.buildQueryString() + `&page=${page}`;
                                            this.loading = true;
                                            // Clear existing content first to avoid duplication
                                            this.tableHtml = '';
                                            this.paginationHtml = '';

                                            fetch(`{{ route('pembelian.retur-pembelian.index') }}?${params}`, {
                                                    headers: {
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    }
                                                }).then(r => r.json())
                                                .then(data => {
                                                    this.tableHtml = data.table_html;
                                                    this.paginationHtml = data.pagination_html;
                                                    this.loading = false;
                                                    this.attachPaginationListener();
                                                })
                                                .catch(error => {
                                                    console.error('Error fetching page:', error);
                                                    this.loading = false;
                                                });
                                        }
                                    });
                                });
                            });
                        }
                    };
                }
            </script>
        @endpush
</x-app-layout>
