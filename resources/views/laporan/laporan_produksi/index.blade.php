<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8" x-data="produksiLaporanController()" x-init="fetchData()">
        {{-- Header --}}
        <div
            class="mb-6 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/70 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-indigo-600 h-9 w-1.5 rounded-full">
                        </div>
                        Laporan Produksi
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400 max-w-3xl">
                        Laporan produksi barang lengkap dengan filter dan ekspor. Gunakan fitur filter untuk melihat
                        data yang spesifik.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button @click="exportExcel()"
                        class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <path d="M12 18v-6"></path>
                            <path d="M8 15l4 4 4-4"></path>
                        </svg>
                        Unduh Excel
                    </button>
                    <button @click="exportPdf()"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <path d="M12 18v-6"></path>
                            <path d="M8 15l4 4 4-4"></path>
                        </svg>
                        Unduh PDF
                    </button>
                </div>
            </div>
        </div>

        {{-- Filter Pencarian --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
            x-data="{ isFilterOpen: false }">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60 cursor-pointer"
                @click="isFilterOpen = !isFilterOpen">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                clip-rule="evenodd" />
                        </svg>
                        Filter Laporan
                    </h3>
                    <div class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg x-show="!isFilterOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        <svg x-show="isFilterOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 pl-7">Gunakan filter untuk menyaring data
                    laporan produksi</p>
            </div>
            <div x-show="isFilterOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0" x-transition:enter-end="transform opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform opacity-100"
                x-transition:leave-end="transform opacity-0" class="p-5">
                <form @submit.prevent="fetchData()" class="space-y-6">
                    <!-- Filter Section -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            <!-- Date Filter Card -->
                            <div class="lg:col-span-12">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Periode Laporan
                                    </label>

                                    <div x-data="{ dateType: 'today' }">
                                        <!-- Date Quick Selects -->
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2 mb-4">
                                            <template
                                                x-for="(option, index) in [
                                                { value: 'today', label: 'Hari Ini', icon: 'M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' },
                                                { value: 'this-week', label: 'Minggu Ini', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
                                                { value: 'this-month', label: 'Bulan Ini', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
                                                { value: 'last-month', label: 'Bulan Lalu', icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z' },
                                                { value: 'custom', label: 'Kustom', icon: 'M4 6h16M4 10h16M4 14h16M4 18h16' }
                                            ]">
                                                <button
                                                    @click="
                                                        dateType = option.value;
                                                        if(option.value === 'today') {
                                                            filter.tanggal_awal = getToday();
                                                            filter.tanggal_akhir = getToday();
                                                        } else if(option.value === 'this-week') {
                                                            filter.tanggal_awal = getThisWeekStart();
                                                            filter.tanggal_akhir = getToday();
                                                        } else if(option.value === 'this-month') {
                                                            filter.tanggal_awal = getThisMonthStart();
                                                            filter.tanggal_akhir = getToday();
                                                        } else if(option.value === 'last-month') {
                                                            filter.tanggal_awal = getLastMonthStart();
                                                            filter.tanggal_akhir = getLastMonthEnd();
                                                        }"
                                                    :class="dateType === option.value ?
                                                        'bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 border-primary-200 dark:border-primary-700 ring-1 ring-primary-500 dark:ring-primary-400' :
                                                        'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                                    class="w-full inline-flex items-center justify-center px-3 py-2 rounded-lg border transition-all duration-200 ease-in-out text-sm font-medium">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                                        :class="{
                                                            'text-primary-500 dark:text-primary-400': dateType ===
                                                                option
                                                                .value
                                                        }"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path :d="option.icon" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" />
                                                    </svg>
                                                    <span x-text="option.label"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search Box -->
                        <div class="relative">
                            <div class="flex rounded-md shadow-sm">
                                <div
                                    class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" x-model="filter.search" @keydown.enter="fetchData()"
                                    class="focus:ring-primary-500 focus:border-primary-500 flex-1 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white placeholder-gray-400 rounded-r-md transition-colors duration-200"
                                    placeholder="Cari nomor produksi, nama produk, atau kode produk...">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="resetFilter()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Reset
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-transparent rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Dashboard Cards Summary --}}
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Produksi</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white"
                            x-text="formatNumber(totals.total_produksi || 0)"></h3>
                    </div>
                    <div
                        class="h-12 w-12 bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Produksi Selesai</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white"
                            x-text="calculateStatus('selesai')"></h3>
                    </div>
                    <div
                        class="h-12 w-12 bg-green-50 dark:bg-green-900/30 text-green-500 dark:text-green-400 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Dalam Proses</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white"
                            x-text="calculateStatus('proses')"></h3>
                    </div>
                    <div
                        class="h-12 w-12 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-500 dark:text-yellow-400 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Header --}}
        <div
            class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Data Produksi</h3>
                    <span
                        class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded-md"
                        x-text="totalItems + ' data'"></span>
                    <span x-show="isLoading">
                        <svg class="animate-spin ml-2 h-4 w-4 text-primary-600 dark:text-primary-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                </div>
                <div>
                    <select x-model="perPage" @change="fetchData(1)"
                        class="form-select rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:focus:border-primary-500 dark:text-gray-300 text-sm">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Loading Skeleton --}}
        <div x-show="isLoading" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-x-auto">
            <div class="animate-pulse">
                <div class="h-16 bg-gray-200 dark:bg-gray-700"></div>
                <div class="h-12 bg-gray-100 dark:bg-gray-800"></div>
                <div class="h-12 bg-gray-200 dark:bg-gray-700"></div>
                <div class="h-12 bg-gray-100 dark:bg-gray-800"></div>
                <div class="h-12 bg-gray-200 dark:bg-gray-700"></div>
                <div class="h-12 bg-gray-100 dark:bg-gray-800"></div>
                <div class="h-16 bg-gray-200 dark:bg-gray-700"></div>
            </div>
        </div>

        {{-- Table Content --}}
        <div x-show="!isLoading">
            @include('laporan.laporan_produksi._table')
        </div>
    </div>

    @push('scripts')
        <script>
            function produksiLaporanController() {
                return {
                    isLoading: true,
                    items: [],
                    filter: {
                        tanggal_awal: '{{ $tanggalAwal->format('Y-m-d') }}',
                        tanggal_akhir: '{{ $tanggalAkhir->format('Y-m-d') }}',
                        search: '',
                    },
                    perPage: 10,
                    currentPage: 1,
                    lastPage: 1,
                    totalItems: 0,
                    totals: {
                        total_produksi: 0
                    },
                    get startNumber() {
                        return (this.currentPage - 1) * this.perPage + 1;
                    },
                    get endNumber() {
                        return Math.min(this.startNumber + this.perPage - 1, this.totalItems);
                    },
                    get pages() {
                        const pages = [];
                        const maxPages = 5;
                        const halfMaxPages = Math.floor(maxPages / 2);

                        let startPage = this.currentPage - halfMaxPages;
                        if (startPage < 1) {
                            startPage = 1;
                        }

                        let endPage = startPage + maxPages - 1;
                        if (endPage > this.lastPage) {
                            endPage = this.lastPage;
                            startPage = Math.max(1, endPage - maxPages + 1);
                        }

                        for (let i = startPage; i <= endPage; i++) {
                            pages.push(i);
                        }

                        return pages;
                    },
                    fetchData(page = null) {
                        if (page) {
                            this.currentPage = page;
                        }

                        this.isLoading = true;
                        const params = new URLSearchParams({
                            ...this.filter,
                            page: this.currentPage,
                            per_page: this.perPage
                        });

                        fetch(`{{ route('laporan.produksi.data') }}?${params.toString()}`)
                            .then(response => response.json())
                            .then(data => {
                                this.items = data.data;
                                this.totalItems = data.total;
                                this.currentPage = data.current_page;
                                this.lastPage = data.last_page;
                                this.totals = data.totals;
                                this.isLoading = false;

                                // Initialize sorting on table after data is loaded
                                this.$nextTick(() => {
                                    if (typeof initTableSorting === 'function') {
                                        initTableSorting();
                                    }
                                });
                            })
                            .catch(error => {
                                console.error('Error fetching data:', error);
                                this.isLoading = false;
                            });
                    },
                    changePage(page) {
                        if (page >= 1 && page <= this.lastPage) {
                            this.fetchData(page);
                        }
                    },
                    resetFilter() {
                        this.filter = {
                            tanggal_awal: '{{ $tanggalAwal->format('Y-m-d') }}',
                            tanggal_akhir: '{{ $tanggalAkhir->format('Y-m-d') }}',
                            search: '',
                        };
                    },
                    exportExcel() {
                        const params = new URLSearchParams({
                            ...this.filter
                        });
                        window.location.href = `{{ route('laporan.produksi.export.excel') }}?${params.toString()}`;
                    },
                    exportPdf() {
                        const params = new URLSearchParams({
                            ...this.filter
                        });
                        window.location.href = `{{ route('laporan.produksi.export.pdf') }}?${params.toString()}`;
                    },
                    formatDate(dateString) {
                        if (!dateString) return '-';
                        const options = {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        };
                        return new Date(dateString).toLocaleDateString('id-ID', options);
                    },
                    formatNumber(number) {
                        if (number === null || number === undefined) return '0';
                        return Number(number).toLocaleString('id-ID');
                    },
                    calculateStatus(status) {
                        // Check if items is undefined or not an array
                        if (!this.items || !Array.isArray(this.items)) {
                            return 0;
                        }
                        // Map work_order status values to expected UI status values
                        return this.items.filter(item => {
                            if (status === 'selesai' && ['selesai', 'qc_passed'].includes(item.status)) {
                                return true;
                            } else if (status === 'proses' && ['berjalan', 'direncanakan', 'selesai_produksi'].includes(
                                    item.status)) {
                                return true;
                            }
                            return item.status === status;
                        }).length;
                    },
                    // Date utility functions
                    getToday() {
                        return new Date().toISOString().split('T')[0];
                    },
                    getThisWeekStart() {
                        const now = new Date();
                        const dayOfWeek = now.getDay(); // 0 = Sunday, 1 = Monday, etc.
                        const diff = now.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Adjust when day is Sunday
                        const monday = new Date(now.setDate(diff));
                        return monday.toISOString().split('T')[0];
                    },
                    getThisMonthStart() {
                        const now = new Date();
                        return new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
                    },
                    getLastMonthStart() {
                        const now = new Date();
                        return new Date(now.getFullYear(), now.getMonth() - 1, 1).toISOString().split('T')[0];
                    },
                    getLastMonthEnd() {
                        const now = new Date();
                        return new Date(now.getFullYear(), now.getMonth(), 0).toISOString().split('T')[0];
                    }
                };
            }

            document.addEventListener('DOMContentLoaded', () => {
                Alpine.data('produksiLaporanController', produksiLaporanController);
            });
        </script>
    @endpush
</x-app-layout>
