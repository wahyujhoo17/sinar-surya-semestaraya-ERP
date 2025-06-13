<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8" x-data="stokLaporanController()">
        {{-- Header --}}
        <div
            class="mb-6 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/70 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-indigo-600 h-9 w-1.5 rounded-full">
                        </div>
                        Laporan Stok Barang
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400 max-w-3xl">
                        Laporan stok barang lengkap dengan filter, ekspor, dan riwayat pergerakan barang. Gunakan fitur
                        filter untuk melihat data yang spesifik.
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
                </div>
            </div>
        </div>

        {{-- Filter Pencarian --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden"
            x-data="{ showFilter: false }">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60 cursor-pointer hover:bg-gray-100/70 dark:hover:bg-gray-800/80 transition-colors duration-200"
                @click="showFilter = !showFilter">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Filter Laporan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 pl-7">Gunakan filter untuk menyaring
                            data
                            laporan stok</p>
                    </div>
                    <div class="text-gray-500 dark:text-gray-400 transition-transform duration-200"
                        :class="{ 'transform rotate-180': showFilter }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div x-show="showFilter" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2" class="p-5">
                <form @submit.prevent="fetchData()" class="space-y-6">
                    <!-- Filter Section -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            <!-- Date Filter Card -->
                            <div class="lg:col-span-12">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                                    <label
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
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
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path :d="option.icon" />
                                                    </svg>
                                                    <span x-text="option.label"></span>
                                                </button>
                                            </template>
                                        </div>

                                        <!-- Custom Date Range -->
                                        <div x-show="dateType === 'custom'" x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                                            x-transition:enter-end="opacity-100 transform translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 transform translate-y-0"
                                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                                            class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                            <div class="space-y-2">
                                                <label
                                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400">Tanggal
                                                    Awal</label>
                                                <div class="relative">
                                                    <input type="date" x-model="filter.tanggal_awal"
                                                        class="form-input w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 focus:border-primary-500 focus:ring focus:ring-primary-500/20 transition-all duration-200" />
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label
                                                    class="block text-xs font-medium text-gray-600 dark:text-gray-400">Tanggal
                                                    Akhir</label>
                                                <div class="relative">
                                                    <input type="date" x-model="filter.tanggal_akhir"
                                                        class="form-input w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 focus:border-primary-500 focus:ring focus:ring-primary-500/20 transition-all duration-200" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Selected Date Range Display -->
                                        <div
                                            class="mt-4 flex items-center text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/30 rounded-lg p-3">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 mr-2 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span
                                                x-text="formatReadableDate(filter.tanggal_awal) + ' - ' + formatReadableDate(filter.tanggal_akhir)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Filters Card -->
                            <div class="lg:col-span-12">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Gudang Filter -->
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 mr-2 text-primary-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    Gudang
                                                </div>
                                            </label>
                                            <select x-model="filter.gudang_id"
                                                class="form-select w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 focus:border-primary-500 focus:ring focus:ring-primary-500/20 transition-all duration-200">
                                                <option value="">Semua Gudang</option>
                                                @foreach ($gudangs as $gudang)
                                                    <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Search Filter -->
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 mr-2 text-primary-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    Cari Barang
                                                </div>
                                            </label>
                                            <div class="relative">
                                                <input type="text" x-model="filter.search"
                                                    placeholder="Cari kode atau nama barang..."
                                                    class="form-input w-full rounded-lg shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 focus:border-primary-500 focus:ring focus:ring-primary-500/20 pl-10 transition-all duration-200" />
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="resetFilter()"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 shadow-sm focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                        clip-rule="evenodd" />
                                </svg>
                                Reset Filter
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Laporan --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-800/50 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">

                        Data Laporan Stok
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-1"
                            x-text="'('+ totalItems + ' item)'"></span>
                    </h3>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Tampilkan</span>
                    <select x-model="perPage" @change="page = 1"
                        class="form-select w-20 py-1 rounded-md text-sm border-gray-300 shadow-sm dark:bg-gray-800 dark:border-gray-700">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-gray-600 dark:text-gray-400">entri</span>
                </div>
            </div>

            {{-- Include tabel dari _table.blade.php --}}
            @include('laporan.laporan_stok._table')

            <!-- Pagination -->
            <div
                class="p-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-wrap items-center justify-between gap-4">
                <div class="text-sm text-gray-700 dark:text-gray-400">
                    Menampilkan <span class="font-medium" x-text="startItem"></span> - <span class="font-medium"
                        x-text="endItem"></span> dari <span class="font-medium" x-text="totalItems"></span> data
                </div>
                <div>
                    <div class="flex items-center justify-center">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            <button @click="page = 1" :disabled="page <= 1"
                                :class="{ 'opacity-50 cursor-not-allowed': page <= 1 }"
                                class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-150">
                                <span class="sr-only">First</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button @click="page--" :disabled="page <= 1"
                                :class="{ 'opacity-50 cursor-not-allowed': page <= 1 }"
                                class="relative inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-150">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <template x-for="pgNum in pageNumbers" :key="pgNum">
                                <button @click="page = pgNum"
                                    :class="page === pgNum ?
                                        'z-10 bg-primary-50 dark:bg-primary-900/30 border-primary-500 dark:border-primary-700 text-primary-600 dark:text-primary-400' :
                                        'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-all duration-150"
                                    x-text="pgNum">
                                </button>
                            </template>
                            <button @click="page++" :disabled="page >= totalPages"
                                :class="{ 'opacity-50 cursor-not-allowed': page >= totalPages }"
                                class="relative inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-150">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button @click="page = totalPages" :disabled="page >= totalPages"
                                :class="{ 'opacity-50 cursor-not-allowed': page >= totalPages }"
                                class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-150">
                                <span class="sr-only">Last</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 6.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0zm6 0a1 1 0 010-1.414L14.586 10l-4.293-3.293a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function stokLaporanController() {
            return {
                filter: {
                    tanggal_awal: '{{ $tanggalAwal->format('Y-m-d') }}',
                    tanggal_akhir: '{{ $tanggalAkhir->format('Y-m-d') }}',
                    gudang_id: '',
                    search: ''
                },
                data: [],
                loading: true,
                page: 1,
                perPage: 25,
                totalItems: 0,

                init() {
                    this.fetchData();
                    console.log('Initializing stok laporan controller');
                },

                // Helper functions for date filters
                getToday() {
                    return new Date().toISOString().split('T')[0]; // Returns YYYY-MM-DD
                },

                getThisWeekStart() {
                    const now = new Date();
                    const dayOfWeek = now.getDay(); // 0 = Sunday, 1 = Monday, etc.
                    const diff = dayOfWeek === 0 ? 6 : dayOfWeek - 1; // Adjust to get Monday
                    const monday = new Date(now);
                    monday.setDate(now.getDate() - diff);
                    return monday.toISOString().split('T')[0];
                },

                getThisMonthStart() {
                    const now = new Date();
                    return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-01`;
                },

                getLastMonthStart() {
                    const now = new Date();
                    // Set to first day of current month, then go back one month
                    now.setDate(1);
                    now.setMonth(now.getMonth() - 1);
                    return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-01`;
                },

                getLastMonthEnd() {
                    const now = new Date();
                    // Set to first day of current month, then subtract one day
                    now.setDate(0);
                    return now.toISOString().split('T')[0];
                },

                formatReadableDate(dateStr) {
                    if (!dateStr) return '-';
                    const date = new Date(dateStr);
                    return date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                },

                fetchData() {
                    this.loading = true;
                    console.log('Fetching data with params:', this.filter);

                    // Membangun URL dengan parameter filter
                    const params = new URLSearchParams({
                        ...this.filter,
                        page: this.page,
                        per_page: this.perPage
                    });

                    fetch(`{{ route('laporan.stok.data') }}?${params.toString()}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => {
                            if (!res.ok) {
                                throw new Error(`HTTP error! Status: ${res.status}`);
                            }
                            return res.json();
                        })
                        .then(res => {
                            console.log('Data received:', res);
                            this.data = res.data;
                            this.totalItems = res.total;
                            this.loading = false;
                        })
                        .catch(err => {
                            console.error('Error fetching data:', err);
                            this.loading = false;
                            alert('Gagal memuat data. Silakan coba lagi.');
                        });
                },

                resetFilter() {
                    this.filter = {
                        tanggal_awal: '{{ $tanggalAwal->format('Y-m-d') }}',
                        tanggal_akhir: '{{ $tanggalAkhir->format('Y-m-d') }}',
                        gudang_id: '',
                        search: ''
                    };
                    this.page = 1;
                    this.fetchData();
                },

                exportExcel() {
                    const params = new URLSearchParams(this.filter).toString();
                    window.open(`{{ route('laporan.stok.export.excel') }}?${params}`, '_blank');
                },





                viewRiwayat(produkId, gudangId = null) {
                    const url = gudangId ?
                        `{{ url('laporan/stok/detail') }}/${produkId}/${gudangId}` :
                        `{{ url('laporan/stok/detail') }}/${produkId}`;
                    window.location.href = url;
                },

                formatRupiah(val) {
                    return val ? 'Rp ' + Number(val).toLocaleString('id-ID') : '-';
                },

                formatDate(val) {
                    if (!val) return '-';
                    const date = new Date(val);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },

                get pageNumbers() {
                    const pages = [];
                    const totalPages = this.totalPages;
                    const currentPage = this.page;

                    // Selalu tampilkan halaman pertama
                    if (totalPages > 1) {
                        // Tampilkan 5 halaman dengan halaman saat ini di tengah jika bisa
                        let startPage = Math.max(1, currentPage - 2);
                        let endPage = Math.min(totalPages, startPage + 4);

                        // Jika ada kurang dari 5 halaman tersisa dari posisi awal, sesuaikan
                        if (endPage - startPage < 4) {
                            startPage = Math.max(1, endPage - 4);
                        }

                        for (let i = startPage; i <= endPage; i++) {
                            pages.push(i);
                        }
                    } else {
                        pages.push(1);
                    }

                    return pages;
                },

                get totalPages() {
                    return Math.ceil(this.totalItems / this.perPage) || 1;
                },

                get startItem() {
                    return this.totalItems === 0 ? 0 : ((this.page - 1) * this.perPage) + 1;
                },

                get endItem() {
                    return Math.min(this.page * this.perPage, this.totalItems);
                }
            };
        }
    </script>
</x-app-layout>
