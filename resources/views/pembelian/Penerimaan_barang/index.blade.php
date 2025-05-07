@php
    // Helper function to get status color for Goods Receipt
    function grStatusColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'gray';
            case 'parsial':
                return 'amber';
            case 'selesai':
                return 'emerald';
            case 'dibatalkan':
                return 'red';
            default:
                return 'primary';
        }
    }
@endphp

<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Stats --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-sky-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Penerimaan Barang
                                (Goods Receipt)
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola penerimaan barang dari supplier berdasarkan Purchase Order.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('pembelian.penerimaan-barang.create') }}"
                        class="group inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-sky-600 to-sky-700 rounded-lg font-semibold text-sm text-white shadow-sm hover:from-sky-700 hover:to-sky-800 active:from-sky-800 active:to-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-all duration-200">
                        <span
                            class="relative flex h-5 w-5 items-center justify-center rounded-md bg-white/20 group-hover:bg-white/30 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </span>
                        <span>Buat Penerimaan Barang</span>
                    </a>
                    <button type="button"
                        class="flex items-center justify-center h-10 w-10 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-400 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Goods Receipt Overview --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                @php $grStatuses = ['draft', 'parsial', 'selesai', 'dibatalkan']; @endphp
                @foreach ($grStatuses as $status)
                    <div
                        class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                        <div
                            class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-{{ grStatusColor($status) }}-50 dark:bg-{{ grStatusColor($status) }}-900/20 opacity-70">
                        </div>
                        <div class="p-4">
                            <p class="text-sm font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                {{ ucfirst($status) }}</p>
                            <div class="mt-2 flex items-baseline">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $statusCounts[$status] ?? 0 }}
                                </p>
                                <p
                                    class="ml-2 text-sm font-medium text-{{ grStatusColor($status) }}-500 dark:text-{{ grStatusColor($status) }}-400">
                                    Dokumen
                                </p>
                            </div>
                            <div
                                class="absolute bottom-0 left-0 right-0 h-1 bg-{{ grStatusColor($status) }}-500 dark:bg-{{ grStatusColor($status) }}-600 opacity-80">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Main Content --}}
        <div x-data="goodsReceiptTableManager()"
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
            {{-- Modern Elegant Tabs --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 pt-6 pb-2 flex overflow-x-auto scrollbar-hide">
                    <div
                        class="relative flex space-x-4 after:absolute after:bottom-0 after:left-0 after:right-0 after:h-px after:bg-gray-200 dark:after:bg-gray-700">
                        @php
                            // Ensure the tabs are always in this specific order
                            $grStatuses = ['draft', 'parsial', 'selesai', 'dibatalkan'];
                        @endphp
                        @foreach ($grStatuses as $status)
                            <button type="button"
                                :class="tab === '{{ $status }}' ?
                                    'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' :
                                    'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border-transparent hover:border-gray-300 dark:hover:border-gray-600'"
                                class="pb-4 px-1 border-b-2 font-medium text-sm transition-all duration-200 focus:outline-none whitespace-nowrap relative"
                                @click="changeTab('{{ $status }}')">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-{{ grStatusColor($status) }}-500"></span>
                                    {{ ucfirst($status) }}
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
                                placeholder="Cari No GR, No PO, Supplier..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-sky-500 focus:border-sky-500 transition-colors">
                        </div>
                    </div>

                    <div class="w-full sm:w-auto space-y-1">
                        <label for="date-filter"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Periode Terima</label>
                        <select id="date-filter" x-model="dateFilter"
                            class="pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-sky-500 focus:border-sky-500 transition-colors">
                            <option value="">Semua Tanggal</option>
                            <option value="today">Hari Ini</option>
                            <option value="this_week">Minggu Ini</option>
                            <option value="this_month">Bulan Ini</option>
                            <option value="range">Range Tanggal</option>
                        </select>
                    </div>

                    <template x-if="dateFilter === 'range'">
                        <div class="flex items-end gap-2 w-full sm:w-auto">
                            <div class="space-y-1">
                                <label for="date-start"
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">Mulai</label>
                                <input type="date" id="date-start" x-model="dateStart"
                                    class="pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-sky-500 focus:border-sky-500 transition-colors">
                            </div>
                            <div class="self-center pt-5">—</div>
                            <div class="space-y-1">
                                <label for="date-end"
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">Hingga</label>
                                <input type="date" id="date-end" x-model="dateEnd"
                                    class="pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-sky-500 focus:border-sky-500 transition-colors">
                            </div>
                        </div>
                    </template>

                    <div class="w-full sm:w-auto space-y-1">
                        <label for="supplier-filter"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Supplier</label>
                        <select id="supplier-filter" x-model="supplierId"
                            class="pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-sky-500 focus:border-sky-500 transition-colors">
                            <option value="">Semua Supplier</option>
                            @if (isset($suppliers))
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="w-full sm:w-auto space-y-1">
                        <label for="po-filter"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Purchase Order</label>
                        <input type="text" id="po-filter" x-model="purchaseOrderId" placeholder="Nomor PO"
                            class="block w-full pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-sky-500 focus:border-sky-500 transition-colors">
                    </div>

                    <div class="flex gap-2 items-end">
                        <button type="submit"
                            class="px-4 py-2 bg-sky-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-sky-700 focus:ring-2 focus:ring-sky-500 focus:ring-offset-1 transition-colors duration-200 h-[38px]">
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
                            class="inline-flex items-center justify-center p-2 border border-gray-200 dark:border-gray-700 rounded-lg text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-sky-600 dark:hover:text-sky-400 hover:border-sky-500 dark:hover:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-1 transition-colors h-[38px] w-[38px]">
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
                            class="flex items-center justify-center h-14 w-14 rounded-full bg-sky-50 dark:bg-sky-900/30">
                            <svg class="animate-spin h-7 w-7 text-sky-600 dark:text-sky-400"
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

                <!-- Missing Table Container Added Here -->
                <div id="gr-table-container" class="overflow-x-auto mt-4" x-html="tableHtml">
                    @if (isset($penerimaanBarangs) && !request()->ajax())
                        @include('pembelian.Penerimaan_barang._table', [
                            'penerimaanBarangs' => $penerimaanBarangs,
                        ])
                    @endif
                </div>

                <div id="gr-pagination-container" x-html="paginationHtml" class="mt-6 px-5">
                    {{-- @if (!request()->ajax())
                        {{ $goodsReceipts->appends(request()->except('page'))->links('vendor.pagination.tailwind-custom') }}
                    @endif --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Keep the JS version of the function for other JS usage
        function grStatusColor(status) {
            switch (status) {
                case 'draft':
                    return 'gray';
                case 'parsial':
                    return 'amber';
                case 'selesai':
                    return 'emerald';
                case 'dibatalkan':
                    return 'red';
                default:
                    return 'primary';
            }
        }

        function goodsReceiptTableManager() {
            return {
                tab: 'draft', // Always start with draft tab
                search: @json($search ?? ''), // Initialized from controller
                dateFilter: @json($date_filter ?? ''), // Initialized from controller
                dateStart: @json($date_start ?? ''), // Initialized from controller
                dateEnd: @json($date_end ?? ''), // Initialized from controller
                supplierId: @json($supplier_id ?? ''), // Initialized from controller
                purchaseOrderId: @json($purchase_order_id ?? ''), // Initialized from controller
                tableHtml: '',
                paginationHtml: '',
                loading: false,
                init() {
                    // Force tab to be 'draft' on initialization
                    this.tab = 'draft';
                    this.fetchTable();
                    // console.log("Alpine component initialized with:", JSON.parse(JSON.stringify(this)));
                },
                changeTab(status) {
                    this.tab = status;
                    this.fetchTable();
                },
                applyFilters() {
                    this.fetchTable();
                },
                resetFilters() {
                    this.search = '';
                    this.dateFilter = '';
                    this.dateStart = '';
                    this.dateEnd = '';
                    this.supplierId = '';
                    this.purchaseOrderId = '';
                    // this.tab = 'draft'; // Optionally reset tab to default or keep current
                    this.fetchTable();
                },
                buildQueryString() {
                    const params = new URLSearchParams();
                    if (this.tab) params.append('status', this.tab);
                    if (this.search) params.append('search', this.search);
                    if (this.dateFilter) params.append('date_filter', this.dateFilter);
                    if (this.dateFilter === 'range') {
                        if (this.dateStart) params.append('date_start', this.dateStart);
                        if (this.dateEnd) params.append('date_end', this.dateEnd);
                    }
                    if (this.supplierId) params.append('supplier_id', this.supplierId);
                    if (this.purchaseOrderId) params.append('purchase_order_id', this.purchaseOrderId);
                    return params.toString();
                },
                fetchTable() {
                    this.loading = true;
                    const queryString = this.buildQueryString();
                    const url = `{{ route('pembelian.penerimaan-barang.index') }}?${queryString}`;
                    // console.log("Fetching table with URL:", url);

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.text().then(text => {
                                    throw new Error(
                                        `HTTP error ${response.status}: ${text || response.statusText}`);
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // console.log("Data received:", data);
                            if (data && data.table_html !== undefined) {
                                this.tableHtml = data.table_html;
                            } else {
                                this.tableHtml =
                                    '<div class="p-4 text-center text-red-500">Format data tabel tidak sesuai.</div>';
                                console.error('Invalid table_html in response:', data);
                            }
                            if (data && data.pagination_html !== undefined) {
                                this.paginationHtml = data.pagination_html;
                            } else {
                                this.paginationHtml = ''; // Clear or set error for pagination
                            }
                            this.loading = false;
                            this.attachPaginationListener();
                        })
                        .catch(error => {
                            console.error('Error fetching table data:', error);
                            this.loading = false;
                            this.tableHtml =
                                `<div class="p-4 text-center text-red-500">Gagal memuat data tabel: ${error.message}</div>`;
                            this.paginationHtml = '';
                        });
                },
                attachPaginationListener() {
                    this.$nextTick(() => {
                        const paginationContainer = document.getElementById('gr-pagination-container');
                        if (!paginationContainer) return;

                        paginationContainer.querySelectorAll('a').forEach(link => {
                            // Clone and replace to remove old listeners effectively
                            const newLink = link.cloneNode(true);
                            link.parentNode.replaceChild(newLink, link);

                            newLink.addEventListener('click', e => {
                                e.preventDefault();
                                const fetchUrl = newLink.href;
                                if (!fetchUrl) return;

                                this.loading = true;
                                // console.log("Fetching pagination URL:", fetchUrl);
                                fetch(fetchUrl, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json',
                                        }
                                    })
                                    .then(response => {
                                        if (!response.ok) {
                                            return response.text().then(text => {
                                                throw new Error(
                                                    `HTTP error ${response.status}: ${text || response.statusText}`
                                                );
                                            });
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        // console.log("Paginated data received:", data);
                                        if (data && data.table_html !== undefined) {
                                            this.tableHtml = data.table_html;
                                        } else {
                                            this.tableHtml =
                                                '<div class="p-4 text-center text-red-500">Format data tabel (halaman) tidak sesuai.</div>';
                                            console.error(
                                                'Invalid table_html in paginated response:',
                                                data);
                                        }
                                        if (data && data.pagination_html !== undefined) {
                                            this.paginationHtml = data.pagination_html;
                                        } else {
                                            this.paginationHtml = '';
                                        }
                                        this.loading = false;
                                        this
                                            .attachPaginationListener(); // Re-attach for new pagination links
                                    })
                                    .catch(error => {
                                        console.error('Error fetching paginated data:', error);
                                        this.loading = false;
                                        this.tableHtml =
                                            `<div class="p-4 text-center text-red-500">Gagal memuat halaman: ${error.message}</div>`;
                                        this.paginationHtml = '';
                                    });
                            });
                        });
                    });
                }
            }
        }
    </script>
</x-app-layout>
