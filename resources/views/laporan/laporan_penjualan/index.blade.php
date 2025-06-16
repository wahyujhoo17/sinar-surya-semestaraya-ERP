<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8" x-data="penjualanLaporanController()">
        {{-- Header --}}
        <div
            class="mb-6 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/70 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-indigo-600 h-9 w-1.5 rounded-full">
                        </div>
                        Laporan Penjualan
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400 max-w-3xl">
                        Laporan penjualan barang lengkap dengan filter dan ekspor. Gunakan fitur filter untuk melihat
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
                    laporan penjualan</p>
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

                                        <!-- Custom Date Range -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="tanggal_awal"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                                    Awal</label>
                                                <input type="date" id="tanggal_awal" x-model="filter.tanggal_awal"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white" />
                                            </div>
                                            <div>
                                                <label for="tanggal_akhir"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                                    Akhir</label>
                                                <input type="date" id="tanggal_akhir"
                                                    x-model="filter.tanggal_akhir"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Customer Filter -->
                            <div class="lg:col-span-6">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 h-full">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
                                        </svg>
                                        Customer
                                    </label>
                                    <select id="customer_filter" x-model="filter.customer_id"
                                        class="select2-search block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white">
                                        <option value="">Semua Customer</option>
                                        <template x-for="customer in customerList" :key="customer.id">
                                            <option :value="customer.id"
                                                x-text="customer.nama + ' (' + customer.kode + ')'"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="lg:col-span-6">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 h-full">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Status Pembayaran
                                    </label>
                                    <select x-model="filter.status_pembayaran"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white">
                                        <option value="">Semua Status</option>
                                        <option value="lunas">Lunas</option>
                                        <option value="sebagian">Dibayar Sebagian</option>
                                        <option value="belum_bayar">Belum Dibayar</option>
                                        <option value="kelebihan_bayar">Kelebihan Bayar</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Search Filter -->
                            <div class="lg:col-span-12">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Pencarian
                                    </label>
                                    <div class="flex">
                                        <input type="text" x-model="filter.search"
                                            placeholder="Cari nomor faktur atau customer..."
                                            class="block w-full rounded-l-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white">
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-r-md transition-all duration-200 shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="resetFilter"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-gray-400 dark:text-gray-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                    clip-rule="evenodd" />
                            </svg>
                            Reset
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Cards Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-full bg-primary-500/10 text-primary-500 dark:bg-primary-800/20 dark:text-primary-400 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                            <path fill-rule="evenodd"
                                d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Nilai Penjualan</p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1"
                            x-text="formatCurrency(totalPenjualan)"></h3>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div
                        class="p-3 rounded-full bg-green-500/10 text-green-500 dark:bg-green-800/20 dark:text-green-400 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sudah Dibayar</p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1"
                            x-text="formatCurrency(totalDibayar)"></h3>
                    </div>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-500/10 text-red-500 dark:bg-red-800/20 dark:text-red-400 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L8.414 9H10a3 3 0 013 3v1a1 1 0 102 0v-1a5 5 0 00-5-5H8.414l1.293-1.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Pembayaran</p>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mt-1"
                            x-text="formatCurrency(sisaPembayaran)"></h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"
                                    clip-rule="evenodd" />
                            </svg>
                            Data Penjualan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 pl-7">
                            Daftar transaksi penjualan berdasarkan filter yang dipilih
                        </p>
                    </div>
                    <div class="flex items-center mt-3 md:mt-0">
                        <span class="mr-2 text-sm text-gray-700 dark:text-gray-300">Tampilkan:</span>
                        <select x-model="perPage" @change="changePage(1)"
                            class="rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white text-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-5">
                @include('laporan.laporan_penjualan._table')
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <style>
            /* Select2 customization for dark mode */
            .select2-container--default .select2-selection--single {
                height: 38px;
                padding: 5px;
                border-color: #d1d5db;
                border-radius: 0.375rem;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: #111827;
                border-color: #374151;
                color: #fff;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #fff;
            }

            .dark .select2-dropdown {
                background-color: #1f2937;
                border-color: #374151;
                color: #fff;
            }

            .dark .select2-container--default .select2-results__option {
                color: #fff;
            }

            .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #4f46e5;
            }
        </style>
        <script>
            function penjualanLaporanController() {
                return {
                    loading: true,
                    penjualanData: [],
                    customerList: [],
                    filter: {
                        tanggal_awal: '',
                        tanggal_akhir: '',
                        customer_id: '',
                        status_pembayaran: '',
                        search: '',
                    },
                    totalPenjualan: 0,
                    totalDibayar: 0,
                    sisaPembayaran: 0,
                    totalItems: 0,
                    currentPage: 1,
                    perPage: 10,
                    totalPages: 1,

                    async init() {
                        // Initialize date range filter
                        this.filter.tanggal_awal = this.getThisMonthStart();
                        this.filter.tanggal_akhir = this.getToday();

                        await this.loadCustomerData();
                        await this.fetchData();

                        // Initialize Select2 after data is loaded
                        this.$nextTick(() => {
                            $('#customer_filter').select2({
                                placeholder: 'Pilih customer...',
                                allowClear: true,
                                width: '100%'
                            }).on('change', (e) => {
                                this.filter.customer_id = $('#customer_filter').val();
                            });
                        });
                    },

                    async loadCustomerData() {
                        try {
                            // Using direct controller access for customers instead of API
                            this.customerList = @json($customers);
                        } catch (error) {
                            console.error('Error loading customer data:', error);
                            this.customerList = [];
                        }
                    },

                    async fetchData() {
                        this.loading = true;
                        try {
                            const response = await fetch('/laporan/penjualan/data?' + new URLSearchParams({
                                ...this.filter,
                                page: this.currentPage || 1,
                                per_page: this.perPage || 10
                            }));

                            const data = await response.json();
                            this.penjualanData = data.data || [];
                            this.totalItems = data.total || 0;
                            this.totalPages = data.last_page || 1;
                            this.totalPenjualan = data.totals?.total_penjualan || 0;
                            this.totalDibayar = data.totals?.total_dibayar || 0;
                            this.sisaPembayaran = data.totals?.sisa_pembayaran || 0;
                        } catch (error) {
                            console.error('Error fetching data:', error);
                            this.penjualanData = [];
                            this.totalItems = 0;
                            this.totalPages = 1;
                            this.totalPenjualan = 0;
                            this.totalDibayar = 0;
                            this.sisaPembayaran = 0;
                        } finally {
                            this.loading = false;
                        }
                    },

                    async exportExcel() {
                        window.location.href = `/laporan/penjualan/export/excel?` + new URLSearchParams({
                            tanggal_awal: this.filter.tanggal_awal,
                            tanggal_akhir: this.filter.tanggal_akhir,
                            customer_id: this.filter.customer_id,
                            status_pembayaran: this.filter.status_pembayaran,
                            search: this.filter.search
                        });
                    },

                    async exportPdf() {
                        window.location.href = `/laporan/penjualan/export/pdf?` + new URLSearchParams({
                            tanggal_awal: this.filter.tanggal_awal,
                            tanggal_akhir: this.filter.tanggal_akhir,
                            customer_id: this.filter.customer_id,
                            status_pembayaran: this.filter.status_pembayaran,
                            search: this.filter.search
                        });
                    },

                    resetFilter() {
                        this.filter = {
                            tanggal_awal: this.getThisMonthStart(),
                            tanggal_akhir: this.getToday(),
                            customer_id: '',
                            status_pembayaran: '',
                            search: '',
                        };

                        // Update Select2 to reflect the reset
                        this.$nextTick(() => {
                            $('#customer_filter').val('').trigger('change');
                        });

                        // Fetch data with reset filters
                        this.currentPage = 1;
                        this.fetchData();
                    },

                    changePage(page) {
                        this.currentPage = page;
                        this.fetchData();
                    },

                    prevPage() {
                        if (this.currentPage > 1) {
                            this.currentPage--;
                            this.fetchData();
                        }
                    },

                    nextPage() {
                        if (this.currentPage < this.totalPages) {
                            this.currentPage++;
                            this.fetchData();
                        }
                    },

                    get paginationPages() {
                        let pages = [];
                        let startPage = Math.max(1, this.currentPage - 2);
                        let endPage = Math.min(this.totalPages, startPage + 4);

                        if (endPage - startPage < 4) {
                            startPage = Math.max(1, endPage - 4);
                        }

                        for (let i = startPage; i <= endPage; i++) {
                            pages.push(i);
                        }
                        return pages;
                    },

                    formatCurrency(amount) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(amount);
                    },

                    formatDate(dateString) {
                        const options = {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        };
                        return new Date(dateString).toLocaleDateString('id-ID', options);
                    },

                    getToday() {
                        return new Date().toISOString().split('T')[0];
                    },

                    getThisWeekStart() {
                        const now = new Date();
                        const dayOfWeek = now.getDay(); // 0-6, where 0 is Sunday
                        const diff = now.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Adjust to Monday
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
                }
            }
        </script>
    @endpush
</x-app-layout>
