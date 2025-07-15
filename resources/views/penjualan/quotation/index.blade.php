<x-app-layout :breadcrumbs="[['label' => 'Penjualan', 'url' => route('penjualan.quotation.index')], ['label' => 'Quotation']]" :currentPage="'Quotation'">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="quotationTableManager()" x-init="init()">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2a4 4 0 014-4h4m0 0V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10a4 4 0 004 4h4" />
                            </svg>
                            Quotation
                        </h1>
                        @if (auth()->user()->hasPermission('quotation.create'))
                            <a href="{{ route('penjualan.quotation.create') }}"
                                class="ml-4 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Quotation
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
                        <span x-show="search || periode || status" x-cloak>Filter aktif</span>
                        <span x-show="!search && !periode && !status">Tidak ada filter</span>
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
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
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
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search"
                                    placeholder="Cari nama, perusahaan, atau no. quotation" x-model="search"
                                    class="pl-10 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <span x-show="status"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <select name="status" x-model="status"
                                    class="pl-10 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    <option value="">Semua Status</option>
                                    <option value="draft">Draft</option>
                                    <option value="dikirim">Dikirim</option>
                                    <option value="disetujui">Disetujui</option>
                                    <option value="ditolak">Ditolak</option>
                                    <option value="kedaluwarsa">Kedaluwarsa</option>
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
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <select name="periode" x-model="periode" @change="setPeriode()"
                                    class="pl-10 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
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
                            x-show="search || periode || status">
                            <span x-show="search"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 mr-1">
                                Pencarian
                            </span>
                            <span x-show="periode"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 mr-1">
                                Periode
                            </span>
                            <span x-show="status"
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 mr-1">
                                Status
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
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center gap-1 group"
                                    @click="sortBy('no_quotation')">
                                    No Quotation
                                    <template x-if="sortField === 'no_quotation'">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
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
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
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
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
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
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center gap-1 group"
                                    @click="sortBy('status')">
                                    Status
                                    <template x-if="sortField === 'status'">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                <button type="button" class="flex items-center justify-end gap-1 group ml-auto"
                                    @click="sortBy('total')">
                                    Total
                                    <template x-if="sortField === 'total'">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                :d="sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'" />
                                        </svg>
                                    </template>
                                </button>
                            </th>
                            <th
                                class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700"
                        x-html="tableHtml">
                        {{-- Tabel diisi via AJAX --}}
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-100 dark:border-gray-700 min-w-full align-middle overflow-hidden"
                    x-html="paginationHtml" @click="handlePaginationClick($event)">
                    {{-- Pagination diisi via AJAX --}}
                </div>
            </div>
        </div>
    </div>
    <script>
        function quotationTableManager() {
            return {
                search: '',
                periode: '',
                tanggal_awal: '',
                tanggal_akhir: '',
                status: '',
                sortField: 'tanggal',
                sortDirection: 'desc',
                tableHtml: '',
                paginationHtml: '',
                isLoading: false,
                init() {
                    this.search = @json(request('search', ''));
                    this.periode = @json(request('periode', ''));
                    this.tanggal_awal = @json(request('tanggal_awal', ''));
                    this.tanggal_akhir = @json(request('tanggal_akhir', ''));
                    this.status = @json(request('status', ''));
                    this.sortField = @json(request('sort', 'tanggal'));
                    this.sortDirection = @json(request('direction', 'desc'));

                    // If we have start and end dates but no period, set to custom
                    if (!this.periode && (this.tanggal_awal || this.tanggal_akhir)) {
                        this.periode = 'custom';
                    }

                    this.fetchTable();
                },
                formatDate(date) {
                    return date.toISOString().split('T')[0];
                },
                setPeriode() {
                    const today = new Date();
                    let startDate, endDate;

                    switch (this.periode) {
                        case 'today':
                            startDate = new Date(today);
                            endDate = new Date(today);
                            break;
                        case 'yesterday':
                            startDate = new Date(today);
                            startDate.setDate(today.getDate() - 1);
                            endDate = new Date(startDate);
                            break;
                        case 'this_week':
                            startDate = new Date(today);
                            startDate.setDate(today.getDate() - today.getDay()); // Start of week (Sunday)
                            endDate = new Date(today);
                            break;
                        case 'last_week':
                            startDate = new Date(today);
                            startDate.setDate(today.getDate() - today.getDay() - 7); // Start of last week
                            endDate = new Date(startDate);
                            endDate.setDate(startDate.getDate() + 6); // End of last week (Saturday)
                            break;
                        case 'this_month':
                            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                            endDate = new Date(today);
                            break;
                        case 'last_month':
                            startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                            endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                            break;
                        case 'this_year':
                            startDate = new Date(today.getFullYear(), 0, 1);
                            endDate = new Date(today);
                            break;
                        case 'custom':
                            // Don't change existing dates if custom is selected
                            return;
                        default:
                            // Clear dates if "Semua Tanggal" is selected
                            this.tanggal_awal = '';
                            this.tanggal_akhir = '';
                            return;
                    }

                    this.tanggal_awal = this.formatDate(startDate);
                    this.tanggal_akhir = this.formatDate(endDate);
                },
                applyFilters() {
                    this.fetchTable();
                },
                resetFilters() {
                    this.search = '';
                    this.periode = '';
                    this.tanggal_awal = '';
                    this.tanggal_akhir = '';
                    this.status = '';
                    this.sortField = 'tanggal';
                    this.sortDirection = 'desc';
                    this.fetchTable();
                },
                handlePaginationClick(event) {
                    // Check if the clicked element is a pagination link
                    const link = event.target.closest('a[href]');
                    if (link && link.getAttribute('href')) {
                        event.preventDefault();
                        const url = link.getAttribute('href');

                        // Only handle pagination URLs, not other links
                        if (url.includes('page=') || url.includes('{{ route('penjualan.quotation.index') }}')) {
                            this.fetchTable(url);
                        }
                    }
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
                    if (this.status) params.append('status', this.status);

                    if (!(this.sortField === 'tanggal' && this.sortDirection === 'desc')) {
                        if (this.sortField) params.append('sort', this.sortField);
                        if (this.sortDirection) params.append('direction', this.sortDirection);
                    }

                    let finalUrl;

                    if (pageUrl) {
                        // If pageUrl is provided (from pagination), use it as base and add our filters
                        finalUrl = new URL(pageUrl, window.location.origin);

                        // Remove existing filter params and add current ones
                        const filterParams = ['search', 'periode', 'tanggal_awal', 'tanggal_akhir', 'status', 'sort',
                            'direction'
                        ];
                        filterParams.forEach(param => finalUrl.searchParams.delete(param));

                        params.forEach((value, key) => {
                            finalUrl.searchParams.set(key, value);
                        });
                    } else {
                        // Normal case - build URL from scratch
                        finalUrl = new URL(`{{ route('penjualan.quotation.index') }}`, window.location.origin);
                        params.forEach((value, key) => {
                            finalUrl.searchParams.append(key, value);
                        });
                    }

                    // Add cache-busting parameter
                    finalUrl.searchParams.set('_', new Date().getTime());

                    let urlToFetch = finalUrl.toString();
                    // console.log('Fetching URL:', urlToFetch); // Debug log

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
                            params.forEach((value, key) => historyUrl.searchParams.set(key, value));
                            if (!(this.sortField === 'tanggal' && this.sortDirection === 'desc')) {
                                if (this.sortField) historyUrl.searchParams.set('sort', this.sortField);
                                if (this.sortDirection) historyUrl.searchParams.set('direction', this.sortDirection);
                            }

                            // Add current page parameter from the fetched URL if it exists
                            const currentPage = finalUrl.searchParams.get('page');
                            if (currentPage && currentPage !== '1') {
                                historyUrl.searchParams.set('page', currentPage);
                            }

                            window.history.replaceState({}, '', historyUrl.toString());

                            // console.log('Table updated successfully'); // Debug log
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
