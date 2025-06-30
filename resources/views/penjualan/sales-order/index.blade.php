<x-app-layout :breadcrumbs="[['label' => 'Penjualan', 'url' => route('penjualan.sales-order.index')], ['label' => 'Sales Order']]" :currentPage="'Sales Order'">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="salesOrderTableManager()" x-init="init()">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Sales Order
                        </h1>
                        @if (auth()->user()->hasPermission('sales_order.create'))
                            <a href="{{ route('penjualan.sales-order.create') }}"
                                class="ml-4 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Sales Order
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div x-data="{ filterPanelOpen: true }" class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center mb-2 sm:mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter Data
                </h2>

                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        <span x-show="search || periode || status_pembayaran || status_pengiriman" x-cloak>Filter
                            aktif</span>
                        <span x-show="!search && !periode && !status_pembayaran && !status_pengiriman">Tidak ada
                            filter</span>
                    </span>
                    <button @click="filterPanelOpen = !filterPanelOpen" type="button"
                        class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">
                        <span x-text="filterPanelOpen ? 'Sembunyikan' : 'Tampilkan'"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 transition-transform duration-200"
                            :class="filterPanelOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="filterPanelOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 overflow-hidden">
                <form @submit.prevent="applyFilters" class="space-y-3">
                    <!-- All Controls in One Row for larger screens -->
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3">
                        <!-- Search -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Pencarian</label>
                                <span x-show="search"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search"
                                    placeholder="Cari nama, perusahaan, atau no. sales order" x-model="search"
                                    class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                            </div>
                        </div>

                        <!-- Status Pembayaran -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Status
                                    Pembayaran</label>
                                <span x-show="status_pembayaran"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <select name="status_pembayaran" x-model="status_pembayaran"
                                    class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white appearance-none">
                                    <option value="">Semua Status</option>
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="sebagian">Sebagian</option>
                                    <option value="lunas">Lunas</option>
                                </select>

                            </div>
                        </div>

                        <!-- Status Pengiriman -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Status
                                    Pengiriman</label>
                                <span x-show="status_pengiriman"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <select name="status_pengiriman" x-model="status_pengiriman"
                                    class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white appearance-none">
                                    <option value="">Semua Status</option>
                                    <option value="belum_dikirim">Belum Dikirim</option>
                                    <option value="sebagian">Sebagian</option>
                                    <option value="dikirim">Dikirim</option>
                                </select>

                            </div>
                        </div>

                        <!-- Periode -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Periode</label>
                                <span x-show="periode"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <select name="periode" x-model="periode" @change="setPeriode()"
                                    class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white appearance-none">
                                    <option value="">Semua Tanggal</option>
                                    <option value="today">Hari Ini</option>
                                    <option value="yesterday">Kemarin</option>
                                    <option value="this_week">Minggu Ini</option>
                                    <option value="last_week">Minggu Lalu</option>
                                    <option value="this_month">Bulan Ini</option>
                                    <option value="last_month">Bulan Lalu</option>
                                    <option value="this_year">Tahun Ini</option>
                                    <option value="custom">Kustom</option>
                                </select>

                            </div>
                        </div>
                    </div>

                    <!-- Date Range (Only show when custom period selected) -->
                    <div x-show="periode === 'custom'" x-transition class="flex flex-wrap gap-3 items-center">
                        <div class="flex-grow min-w-[140px] max-w-xs">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="date" name="tanggal_awal" x-model="tanggal_awal"
                                    class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="text-gray-500 dark:text-gray-400 text-sm">hingga</span>
                        </div>
                        <div class="flex-grow min-w-[140px] max-w-xs">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="date" name="tanggal_akhir" x-model="tanggal_akhir"
                                    class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons and Active Filters -->
                    <div
                        class="flex flex-wrap items-center justify-between gap-3 pt-1 border-t border-gray-100 dark:border-gray-700">
                        <!-- Active Filters Indicator (will only show on mobile) -->
                        <div class="lg:hidden text-xs text-gray-600 dark:text-gray-400 order-2 w-full sm:w-auto sm:order-1"
                            x-show="search || periode || status_pembayaran || status_pengiriman">
                            <span x-show="search"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 mr-1">
                                Pencarian
                            </span>
                            <span x-show="periode"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 mr-1">
                                Periode
                            </span>
                            <span x-show="status_pembayaran"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 mr-1">
                                Status Pembayaran
                            </span>
                            <span x-show="status_pengiriman"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 mr-1">
                                Status Pengiriman
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2 order-1 sm:ml-auto sm:order-2">
                            <button type="button" @click.prevent="resetFilters"
                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                        clip-rule="evenodd" />
                                </svg>
                                Reset
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Terapkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if (isset($error))
            <div class="bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-700 rounded-lg shadow-sm p-4 mb-6"
                role="alert">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-600 dark:text-red-400">Terjadi Kesalahan</h3>
                        <div class="mt-1 text-sm text-red-600 dark:text-red-400">
                            <p>{{ $error }}</p>
                        </div>
                        <div class="mt-3">
                            <div class="-mx-2 -my-1.5">
                                <button type="button" @click="window.location.reload()"
                                    class="inline-flex items-center px-2 py-1.5 border border-transparent text-xs rounded-md font-medium text-red-600 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:text-red-400 dark:hover:bg-red-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Muat Ulang Halaman
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div
            class="overflow-hidden rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm relative">
            <!-- Loading Overlay -->
            <div x-show="isLoading"
                class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 z-10 flex items-center justify-center"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="flex flex-col items-center">
                    <div
                        class="rounded-full border-4 border-t-4 border-gray-200 dark:border-gray-700 border-t-primary-600 dark:border-t-primary-500 h-12 w-12 animate-spin">
                    </div>
                    {{-- <span class="mt-2 text-sm text-gray-700 dark:text-gray-300 font-medium">Memuat data...</span> --}}
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center gap-1 group" @click="sortBy('no')">
                                    No
                                    <template x-if="sortField === 'no'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                            :class="{
                                                'rotate-180': sortDirection === 'desc',
                                                'text-primary-500': true
                                            }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </template>
                                    <template x-if="sortField !== 'no'">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center gap-1 group"
                                    @click="sortBy('no_sales_order')">
                                    No. Sales Order
                                    <template x-if="sortField === 'no_sales_order'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                            :class="{
                                                'rotate-180': sortDirection === 'desc',
                                                'text-primary-500': true
                                            }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </template>
                                    <template x-if="sortField !== 'no_sales_order'">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center gap-1 group"
                                    @click="sortBy('tanggal')">
                                    Tanggal
                                    <template x-if="sortField === 'tanggal'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                            :class="{
                                                'rotate-180': sortDirection === 'desc',
                                                'text-primary-500': true
                                            }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </template>
                                    <template x-if="sortField !== 'tanggal'">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center gap-1 group"
                                    @click="sortBy('customer')">
                                    Customer
                                    <template x-if="sortField === 'customer'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                            :class="{
                                                'rotate-180': sortDirection === 'desc',
                                                'text-primary-500': true
                                            }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </template>
                                    <template x-if="sortField !== 'customer'">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center gap-1 group"
                                    @click="sortBy('kontak')">
                                    Kontak
                                    <template x-if="sortField === 'kontak'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                            :class="{
                                                'rotate-180': sortDirection === 'desc',
                                                'text-primary-500': true
                                            }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </template>
                                    <template x-if="sortField !== 'kontak'">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center gap-1">
                                    Status
                                </div>
                            </th>
                            <th
                                class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center gap-1 group ml-auto"
                                    @click="sortBy('total')">
                                    Total
                                    <template x-if="sortField === 'total'">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500"
                                            :class="{
                                                'rotate-180': sortDirection === 'desc',
                                                'text-primary-500': true
                                            }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                    </template>
                                    <template x-if="sortField !== 'total'">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700" x-html="tableHtml">
                        @include('penjualan.sales-order._table')
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3" x-html="paginationHtml">
                {{ $salesOrders->links() }}
            </div>
        </div>
    </div>

    <script>
        function salesOrderTableManager() {
            return {
                isLoading: false,
                search: new URLSearchParams(window.location.search).get('search') || '',
                status_pembayaran: new URLSearchParams(window.location.search).get('status_pembayaran') || '',
                status_pengiriman: new URLSearchParams(window.location.search).get('status_pengiriman') || '',
                periode: new URLSearchParams(window.location.search).get('periode') || '',
                tanggal_awal: new URLSearchParams(window.location.search).get('tanggal_awal') || '',
                tanggal_akhir: new URLSearchParams(window.location.search).get('tanggal_akhir') || '',
                sortField: new URLSearchParams(window.location.search).get('sort') || 'tanggal',
                sortDirection: new URLSearchParams(window.location.search).get('direction') || 'desc',
                tableHtml: '',
                paginationHtml: '',
                init() {
                    // Set initial table content
                    this.tableHtml = document.querySelector('tbody').innerHTML;
                    this.paginationHtml = document.querySelector('.px-5.py-3').innerHTML;

                    // Listen for popstate (browser back/forward buttons)
                    window.addEventListener('popstate', () => {
                        const params = new URLSearchParams(window.location.search);
                        this.search = params.get('search') || '';
                        this.status_pembayaran = params.get('status_pembayaran') || '';
                        this.status_pengiriman = params.get('status_pengiriman') || '';
                        this.periode = params.get('periode') || '';
                        this.tanggal_awal = params.get('tanggal_awal') || '';
                        this.tanggal_akhir = params.get('tanggal_akhir') || '';
                        this.sortField = params.get('sort') || 'tanggal';
                        this.sortDirection = params.get('direction') || 'desc';
                        this.fetchTable();
                    });
                },
                setPeriode() {
                    if (this.periode !== 'custom') {
                        this.tanggal_awal = '';
                        this.tanggal_akhir = '';
                    }
                },
                resetFilters() {
                    this.search = '';
                    this.status_pembayaran = '';
                    this.status_pengiriman = '';
                    this.periode = '';
                    this.tanggal_awal = '';
                    this.tanggal_akhir = '';
                    this.sortField = 'tanggal';
                    this.sortDirection = 'desc';
                    this.fetchTable();
                },
                applyFilters() {
                    this.fetchTable();
                },
                resetSort() {
                    this.sortField = 'tanggal';
                    this.sortDirection = 'desc';
                    this.fetchTable();
                },
                sortBy(field) {
                    if (this.sortField === field) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDirection = 'asc';
                    }
                    this.fetchTable();
                },
                fetchTable(pageUrl = null) {
                    // Set loading state to true
                    this.isLoading = true;

                    let params = new URLSearchParams();
                    if (this.search) params.append('search', this.search);
                    if (this.periode) params.append('periode', this.periode);
                    if (this.tanggal_awal) params.append('tanggal_awal', this.tanggal_awal);
                    if (this.tanggal_akhir) params.append('tanggal_akhir', this.tanggal_akhir);
                    if (this.status_pembayaran) params.append('status_pembayaran', this.status_pembayaran);
                    if (this.status_pengiriman) params.append('status_pengiriman', this.status_pengiriman);

                    if (!(this.sortField === 'tanggal' && this.sortDirection === 'desc')) {
                        if (this.sortField) params.append('sort', this.sortField);
                        if (this.sortDirection) params.append('direction', this.sortDirection);
                    }

                    let baseUrl = pageUrl || `{{ route('penjualan.sales-order.index') }}`;
                    let finalUrl = new URL(baseUrl, window.location.origin);

                    params.forEach((value, key) => {
                        finalUrl.searchParams.append(key, value);
                    });

                    // Add cache-busting parameter
                    finalUrl.searchParams.append('_', new Date().getTime());

                    let urlToFetch = finalUrl.toString();

                    fetch(urlToFetch, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Set loading state to false after receiving data
                            this.isLoading = false;

                            if (data.error) {
                                this.tableHtml =
                                    `<tr><td colspan="8" class="px-5 py-10 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-red-50 dark:bg-red-900/20">
                                                <svg class="h-12 w-12 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-base font-medium text-red-500">Error: ${data.error}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan coba muat ulang halaman.</p>
                                            </div>
                                            <button type="button" @click="fetchTable()" 
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Muat Ulang
                                            </button>
                                        </div>
                                    </td></tr>`;
                                this.paginationHtml = '';
                                return;
                            }

                            this.tableHtml = data.table_html;
                            this.paginationHtml = data.pagination_html;

                            // Ensure sorting state is correctly updated from the response
                            if (data.sort_field) this.sortField = data.sort_field;
                            if (data.sort_direction) this.sortDirection = data.sort_direction;

                            // Update URL without reload, preserving the base path and only changing search params
                            const historyUrl = new URL(window.location.pathname, window.location.origin);
                            params.forEach((value, key) => historyUrl.searchParams.append(key, value));
                            if (!(this.sortField === 'tanggal' && this.sortDirection === 'desc')) {
                                if (this.sortField) historyUrl.searchParams.append('sort', this.sortField);
                                if (this.sortDirection) historyUrl.searchParams.append('direction', this.sortDirection);
                            }
                            window.history.replaceState({}, '', historyUrl.toString());
                        })
                        .catch(error => {
                            // Also set loading state to false on error
                            this.isLoading = false;

                            console.error('Error fetching table data:', error);
                            this.tableHtml =
                                `<tr><td colspan="8" class="px-5 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-red-50 dark:bg-red-900/20">
                                            <svg class="h-12 w-12 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-base font-medium text-red-500">Gagal memuat data</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan coba lagi.</p>
                                        </div>
                                        <button type="button" @click="fetchTable()" 
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Coba Lagi
                                        </button>
                                    </div>
                                </td></tr>`;
                            this.paginationHtml = '';
                        });
                }
            }
        }
    </script>
</x-app-layout>
