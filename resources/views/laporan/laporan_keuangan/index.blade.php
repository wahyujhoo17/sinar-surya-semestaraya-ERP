<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8" x-data="keuanganLaporanController()">
        {{-- Header --}}
        <div
            class="mb-6 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/70 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 h-9 w-1.5 rounded-full">
                        </div>
                        Laporan Keuangan
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400 max-w-3xl">
                        Laporan keuangan komprehensif meliputi Neraca, Laba Rugi, dan Arus Kas. Gunakan fitur filter
                        untuk melihat
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
                    laporan keuangan</p>
            </div>
            <div x-show="isFilterOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0" x-transition:enter-end="transform opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform opacity-100"
                x-transition:leave-end="transform opacity-0" class="p-5">
                <form @submit.prevent="fetchData()" class="space-y-6">
                    <!-- Filter Section -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                            <!-- Report Type Filter -->
                            <div class="lg:col-span-6">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                                    <label
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Jenis Laporan
                                    </label>
                                    <select id="report_type" x-model="filter.report_type" @change="fetchData()"
                                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white">
                                        <option value="balance_sheet">Neraca (Balance Sheet)</option>
                                        <option value="income_statement">Laba Rugi (Income Statement)</option>
                                        <option value="cash_flow">Arus Kas (Cash Flow)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Date Filter Card -->
                            <div class="lg:col-span-6">
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
                                        <span
                                            x-text="filter.report_type === 'balance_sheet' ? 'Tanggal Neraca' : 'Periode Laporan'"></span>
                                    </label>

                                    <div x-data="{ dateType: 'this-month' }">
                                        <!-- Date Quick Selects -->
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-4">
                                            <template
                                                x-for="(option, index) in [
                                                { value: 'today', label: 'Hari Ini', icon: 'M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z' },
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
                                                                option.value
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
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                            x-show="filter.report_type !== 'balance_sheet'">
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

                                        <!-- Single Date for Balance Sheet -->
                                        <div x-show="filter.report_type === 'balance_sheet'">
                                            <label for="tanggal_neraca"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                                Neraca</label>
                                            <input type="date" id="tanggal_neraca" x-model="filter.tanggal_akhir"
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                                Tampilkan Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Report Content --}}
        <div class="space-y-6">
            <!-- Balance Sheet Report -->
            <div x-show="filter.report_type === 'balance_sheet'" class="space-y-6">
                <!-- Charts Section for Balance Sheet -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Assets Composition Chart -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                                </svg>
                                Komposisi Aset
                            </h3>
                        </div>
                        <div class="p-6">
                            <canvas id="assetsChart" class="w-full h-64"></canvas>
                        </div>
                    </div>

                    <!-- Liabilities vs Equity Chart -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                                </svg>
                                Kewajiban vs Ekuitas
                            </h3>
                        </div>
                        <div class="p-6">
                            <canvas id="liabilitiesEquityChart" class="w-full h-64"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Data Tables -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Assets -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                                </svg>
                                Aset (Assets)
                            </h3>
                        </div>
                        <div class="p-6">
                            <div x-show="loading" class="flex justify-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                            </div>
                            <div x-show="!loading && balanceSheetData.assets?.length > 0">
                                <template x-for="asset in balanceSheetData.assets" :key="asset.id">
                                    <div
                                        class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white"
                                                x-text="asset.nama"></span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400" x-text="asset.kode">
                                            </div>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white"
                                            x-text="formatCurrency(asset.balance)"></span>
                                    </div>
                                </template>
                                <div class="pt-4 mt-4 border-t-2 border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">Total Aset</span>
                                        <span class="text-lg font-bold text-blue-600 dark:text-blue-400"
                                            x-text="formatCurrency(balanceSheetData.totals?.total_assets || 0)"></span>
                                    </div>
                                </div>
                            </div>
                            <div x-show="!loading && (!balanceSheetData.assets || balanceSheetData.assets.length === 0)"
                                class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">Tidak ada data aset</p>
                            </div>
                        </div>
                    </div>

                    <!-- Liabilities & Equity -->
                    <div class="space-y-6">
                        <!-- Liabilities -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Kewajiban (Liabilities)
                                </h3>
                            </div>
                            <div class="p-6">
                                <div x-show="!loading && balanceSheetData.liabilities?.length > 0">
                                    <template x-for="liability in balanceSheetData.liabilities" :key="liability.id">
                                        <div
                                            class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white"
                                                    x-text="liability.nama"></span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400"
                                                    x-text="liability.kode"></div>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white"
                                                x-text="formatCurrency(liability.balance)"></span>
                                        </div>
                                    </template>
                                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-600">
                                        <div class="flex justify-between items-center">
                                            <span class="text-base font-bold text-gray-900 dark:text-white">Total
                                                Kewajiban</span>
                                            <span class="text-base font-bold text-red-600 dark:text-red-400"
                                                x-text="formatCurrency(balanceSheetData.totals?.total_liabilities || 0)"></span>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="!loading && (!balanceSheetData.liabilities || balanceSheetData.liabilities.length === 0)"
                                    class="text-center py-4">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada data kewajiban</p>
                                </div>
                            </div>
                        </div>

                        <!-- Equity -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Ekuitas (Equity)
                                </h3>
                            </div>
                            <div class="p-6">
                                <div x-show="!loading && balanceSheetData.equity?.length > 0">
                                    <template x-for="equity in balanceSheetData.equity" :key="equity.id">
                                        <div
                                            class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white"
                                                    x-text="equity.nama"></span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400"
                                                    x-text="equity.kode"></div>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white"
                                                x-text="formatCurrency(equity.balance)"></span>
                                        </div>
                                    </template>
                                    <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-600">
                                        <div class="flex justify-between items-center">
                                            <span class="text-base font-bold text-gray-900 dark:text-white">Total
                                                Ekuitas</span>
                                            <span class="text-base font-bold text-green-600 dark:text-green-400"
                                                x-text="formatCurrency(balanceSheetData.totals?.total_equity || 0)"></span>
                                        </div>
                                    </div>
                                </div>
                                <div x-show="!loading && (!balanceSheetData.equity || balanceSheetData.equity.length === 0)"
                                    class="text-center py-4">
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada data ekuitas</p>
                                </div>
                            </div>
                        </div>

                        <!-- Balance Check -->
                        <div
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">Total Kewajiban +
                                    Ekuitas</span>
                                <span class="text-lg font-bold text-primary-600 dark:text-primary-400"
                                    x-text="formatCurrency(balanceSheetData.totals?.total_liab_equity || 0)"></span>
                            </div>
                            <div class="mt-2 text-sm"
                                :class="Math.abs((balanceSheetData.totals?.total_assets || 0) - (balanceSheetData.totals
                                        ?.total_liab_equity || 0)) < 1 ? 'text-green-600 dark:text-green-400' :
                                    'text-red-600 dark:text-red-400'">
                                <span
                                    x-text="Math.abs((balanceSheetData.totals?.total_assets || 0) - (balanceSheetData.totals?.total_liab_equity || 0)) < 1 ? '✓ Neraca seimbang' : '⚠ Neraca tidak seimbang'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income Statement Report -->
            <div x-show="filter.report_type === 'income_statement'" class="space-y-6">
                <!-- Charts Section for Income Statement -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Revenue Breakdown Chart -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Pendapatan
                            </h3>
                        </div>
                        <div class="p-6">
                            <canvas id="revenueChart" class="w-full h-64"></canvas>
                        </div>
                    </div>

                    <!-- Expense Breakdown Chart -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Pengeluaran
                            </h3>
                        </div>
                        <div class="p-6">
                            <canvas id="expenseChart" class="w-full h-64"></canvas>
                        </div>
                    </div>

                    <!-- Profitability Chart -->
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                        clip-rule="evenodd" />
                                </svg>
                                Profitabilitas
                            </h3>
                        </div>
                        <div class="p-6">
                            <canvas id="profitabilityChart" class="w-full h-64"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Income Statement Details -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 0a1 1 0 100 2h.01a1 1 0 100-2H9zm2 0a1 1 0 100 2h.01a1 1 0 100-2H11z"
                                    clip-rule="evenodd" />
                            </svg>
                            Laporan Laba Rugi Detail
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Loading State -->
                        <div x-show="loading" class="flex justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                        </div>

                        <!-- Revenue Section -->
                        <div class="mb-8" x-show="!loading">
                            <h4
                                class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4 border-b border-green-200 dark:border-green-800 pb-2">
                                PENDAPATAN</h4>
                            <div class="space-y-3">
                                <!-- Sales Revenue from Journal -->
                                <template x-if="incomeStatementData.revenue?.sales_accounts?.length > 0">
                                    <div
                                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">Pendapatan
                                                    Penjualan</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Dari akun
                                                    penjualan</div>
                                            </div>
                                            <span class="text-sm font-bold text-green-600 dark:text-green-400"
                                                x-text="formatCurrency(incomeStatementData.revenue?.sales_revenue || 0)"></span>
                                        </div>
                                        <div class="space-y-1 pl-4 border-l-2 border-green-300 dark:border-green-700">
                                            <template x-for="account in incomeStatementData.revenue.sales_accounts"
                                                :key="account.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="account.nama + ' (' + account.kode + ')'"></span>
                                                    <span
                                                        class="text-xs font-medium text-green-500 dark:text-green-400"
                                                        x-text="formatCurrency(account.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Other Income -->
                                <template x-if="incomeStatementData.revenue?.other_income?.length > 0">
                                    <div
                                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">Pendapatan
                                                    Lain-lain</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Pendapatan di
                                                    luar penjualan utama</div>
                                            </div>
                                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400"
                                                x-text="formatCurrency(incomeStatementData.revenue?.other_income?.reduce((sum, item) => sum + item.balance, 0) || 0)"></span>
                                        </div>
                                        <div class="space-y-1 pl-4 border-l-2 border-blue-300 dark:border-blue-700">
                                            <template x-for="income in incomeStatementData.revenue.other_income"
                                                :key="income.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="income.nama + ' (' + income.kode + ')'"></span>
                                                    <span class="text-xs font-medium text-blue-500 dark:text-blue-400"
                                                        x-text="formatCurrency(income.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Penjualan</span>
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400"
                                        x-text="formatCurrency(incomeStatementData.revenue?.sales_revenue || 0)"></span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Pendapatan
                                        Lain</span>
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400"
                                        x-text="formatCurrency(incomeStatementData.revenue?.other_income?.reduce((sum, item) => sum + item.balance, 0) || 0)"></span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900 dark:text-white">Total
                                            Pendapatan</span>
                                        <span class="text-base font-bold text-green-600 dark:text-green-400"
                                            x-text="formatCurrency(incomeStatementData.revenue?.total_revenue || 0)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- COGS Section -->
                        <div class="mb-8" x-show="!loading">
                            <h4
                                class="text-lg font-semibold text-orange-600 dark:text-orange-400 mb-4 border-b border-orange-200 dark:border-orange-800 pb-2">
                                HARGA POKOK PENJUALAN</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Harga Pokok
                                        Penjualan (dari jurnal)</span>
                                    <span class="text-sm font-semibold text-orange-600 dark:text-orange-400"
                                        x-text="formatCurrency(incomeStatementData.cogs?.total_cogs || 0)"></span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">Pembelian</span>
                                    <span class="text-sm font-semibold text-orange-600 dark:text-orange-400"
                                        x-text="formatCurrency(incomeStatementData.cogs?.purchase_costs || 0)"></span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">HPP
                                        Lainnya</span>
                                    <span class="text-sm font-semibold text-orange-600 dark:text-orange-400"
                                        x-text="formatCurrency(incomeStatementData.cogs?.additional_cogs || 0)"></span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900 dark:text-white">Total
                                            HPP</span>
                                        <span class="text-base font-bold text-orange-600 dark:text-orange-400"
                                            x-text="formatCurrency(incomeStatementData.cogs?.total_cogs || 0)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gross Profit -->
                        <div x-show="!loading"
                            class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">LABA KOTOR</span>
                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400"
                                    x-text="formatCurrency(incomeStatementData.totals?.gross_profit || 0)"></span>
                            </div>
                        </div>

                        <!-- Operating Expenses Section -->
                        <div class="mb-8" x-show="!loading">
                            <h4
                                class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4 border-b border-red-200 dark:border-red-800 pb-2">
                                BEBAN OPERASIONAL</h4>
                            <div class="space-y-4">
                                <!-- Salary from Journal ONLY -->
                                <template
                                    x-if="incomeStatementData.operating_expenses?.salary_from_journal?.length > 0">
                                    <div
                                        class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Biaya
                                                    Gaji & Tunjangan</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Dari jurnal beban
                                                    gaji</div>
                                            </div>
                                            <span class="text-sm font-bold text-red-600 dark:text-red-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.salary_from_journal_total || 0)"></span>
                                        </div>
                                        <div class="space-y-1 pl-4 border-l-2 border-red-300 dark:border-red-700">
                                            <template
                                                x-for="salary in incomeStatementData.operating_expenses.salary_from_journal"
                                                :key="salary.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="salary.nama"></span>
                                                    <span class="text-xs font-medium text-red-500 dark:text-red-400"
                                                        x-text="formatCurrency(salary.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Utility Expenses -->
                                <template x-if="incomeStatementData.operating_expenses?.utility_expenses?.length > 0">
                                    <div
                                        class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Biaya
                                                    Utilitas</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Listrik, air,
                                                    telepon, internet</div>
                                            </div>
                                            <span class="text-sm font-bold text-orange-600 dark:text-orange-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.utility_expenses_total || 0)"></span>
                                        </div>
                                        <div
                                            class="space-y-1 pl-4 border-l-2 border-orange-300 dark:border-orange-700">
                                            <template
                                                x-for="utility in incomeStatementData.operating_expenses.utility_expenses"
                                                :key="utility.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="utility.nama"></span>
                                                    <span
                                                        class="text-xs font-medium text-orange-500 dark:text-orange-400"
                                                        x-text="formatCurrency(utility.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Rent Expenses -->
                                <template x-if="incomeStatementData.operating_expenses?.rent_expenses?.length > 0">
                                    <div
                                        class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Biaya
                                                    Sewa</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Sewa kantor,
                                                    kendaraan, dll</div>
                                            </div>
                                            <span class="text-sm font-bold text-purple-600 dark:text-purple-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.rent_expenses_total || 0)"></span>
                                        </div>
                                        <div
                                            class="space-y-1 pl-4 border-l-2 border-purple-300 dark:border-purple-700">
                                            <template
                                                x-for="rent in incomeStatementData.operating_expenses.rent_expenses"
                                                :key="rent.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="rent.nama"></span>
                                                    <span
                                                        class="text-xs font-medium text-purple-500 dark:text-purple-400"
                                                        x-text="formatCurrency(rent.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Admin Expenses -->
                                <template x-if="incomeStatementData.operating_expenses?.admin_expenses?.length > 0">
                                    <div
                                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Biaya
                                                    Administrasi</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">ATK, kantor,
                                                    administrasi</div>
                                            </div>
                                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.admin_expenses_total || 0)"></span>
                                        </div>
                                        <div class="space-y-1 pl-4 border-l-2 border-blue-300 dark:border-blue-700">
                                            <template
                                                x-for="admin in incomeStatementData.operating_expenses.admin_expenses"
                                                :key="admin.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="admin.nama"></span>
                                                    <span class="text-xs font-medium text-blue-500 dark:text-blue-400"
                                                        x-text="formatCurrency(admin.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Transport Expenses -->
                                <template
                                    x-if="incomeStatementData.operating_expenses?.transport_expenses?.length > 0">
                                    <div
                                        class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Biaya
                                                    Transport</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">BBM, parkir, tol,
                                                    perjalanan</div>
                                            </div>
                                            <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.transport_expenses_total || 0)"></span>
                                        </div>
                                        <div
                                            class="space-y-1 pl-4 border-l-2 border-indigo-300 dark:border-indigo-700">
                                            <template
                                                x-for="transport in incomeStatementData.operating_expenses.transport_expenses"
                                                :key="transport.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="transport.nama"></span>
                                                    <span
                                                        class="text-xs font-medium text-indigo-500 dark:text-indigo-400"
                                                        x-text="formatCurrency(transport.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Maintenance Expenses -->
                                <template
                                    x-if="incomeStatementData.operating_expenses?.maintenance_expenses?.length > 0">
                                    <div
                                        class="bg-teal-50 dark:bg-teal-900/20 border border-teal-200 dark:border-teal-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Biaya
                                                    Pemeliharaan</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Service,
                                                    perbaikan, perawatan</div>
                                            </div>
                                            <span class="text-sm font-bold text-teal-600 dark:text-teal-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.maintenance_expenses_total || 0)"></span>
                                        </div>
                                        <div class="space-y-1 pl-4 border-l-2 border-teal-300 dark:border-teal-700">
                                            <template
                                                x-for="maintenance in incomeStatementData.operating_expenses.maintenance_expenses"
                                                :key="maintenance.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="maintenance.nama"></span>
                                                    <span class="text-xs font-medium text-teal-500 dark:text-teal-400"
                                                        x-text="formatCurrency(maintenance.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Marketing Expenses -->
                                <template
                                    x-if="incomeStatementData.operating_expenses?.marketing_expenses?.length > 0">
                                    <div
                                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Biaya
                                                    Marketing</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Promosi, iklan,
                                                    marketing</div>
                                            </div>
                                            <span class="text-sm font-bold text-yellow-600 dark:text-yellow-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.marketing_expenses_total || 0)"></span>
                                        </div>
                                        <div
                                            class="space-y-1 pl-4 border-l-2 border-yellow-300 dark:border-yellow-700">
                                            <template
                                                x-for="marketing in incomeStatementData.operating_expenses.marketing_expenses"
                                                :key="marketing.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="marketing.nama"></span>
                                                    <span
                                                        class="text-xs font-medium text-yellow-500 dark:text-yellow-400"
                                                        x-text="formatCurrency(marketing.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Professional Services Expenses -->
                                <template
                                    x-if="incomeStatementData.operating_expenses?.professional_expenses?.length > 0">
                                    <div
                                        class="bg-lime-50 dark:bg-lime-900/20 border border-lime-200 dark:border-lime-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Jasa
                                                    Profesional</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Konsultan,
                                                    auditor, legal, pajak</div>
                                            </div>
                                            <span class="text-sm font-bold text-lime-600 dark:text-lime-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.professional_expenses_total || 0)"></span>
                                        </div>
                                        <div class="space-y-1 pl-4 border-l-2 border-lime-300 dark:border-lime-700">
                                            <template
                                                x-for="professional in incomeStatementData.operating_expenses.professional_expenses"
                                                :key="professional.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="professional.nama"></span>
                                                    <span class="text-xs font-medium text-lime-500 dark:text-lime-400"
                                                        x-text="formatCurrency(professional.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Insurance Expenses -->
                                <template
                                    x-if="incomeStatementData.operating_expenses?.insurance_expenses?.length > 0">
                                    <div
                                        class="bg-cyan-50 dark:bg-cyan-900/20 border border-cyan-200 dark:border-cyan-800 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Biaya
                                                    Asuransi</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Premi asuransi,
                                                    perlindungan</div>
                                            </div>
                                            <span class="text-sm font-bold text-cyan-600 dark:text-cyan-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.insurance_expenses_total || 0)"></span>
                                        </div>
                                        <div class="space-y-1 pl-4 border-l-2 border-cyan-300 dark:border-cyan-700">
                                            <template
                                                x-for="insurance in incomeStatementData.operating_expenses.insurance_expenses"
                                                :key="insurance.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="insurance.nama"></span>
                                                    <span class="text-xs font-medium text-cyan-500 dark:text-cyan-400"
                                                        x-text="formatCurrency(insurance.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Other Expenses -->
                                <template x-if="incomeStatementData.operating_expenses?.other_expenses?.length > 0">
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">Beban
                                                    Operasional Lainnya</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Beban operasional
                                                    lain dari jurnal</div>
                                            </div>
                                            <span class="text-sm font-bold text-gray-600 dark:text-gray-400"
                                                x-text="formatCurrency(incomeStatementData.operating_expenses?.other_expenses_total || 0)"></span>
                                        </div>
                                        <div class="space-y-1 pl-4 border-l-2 border-gray-300 dark:border-gray-600">
                                            <template
                                                x-for="expense in incomeStatementData.operating_expenses.other_expenses"
                                                :key="expense.id">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-600 dark:text-gray-400"
                                                        x-text="expense.nama"></span>
                                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                                        x-text="formatCurrency(expense.balance)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                                <!-- Total Operating Expenses -->
                                <div
                                    class="bg-red-100 dark:bg-red-900/40 border-2 border-red-300 dark:border-red-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900 dark:text-white">Total Beban
                                            Operasional</span>
                                        <span class="text-lg font-bold text-red-600 dark:text-red-400"
                                            x-text="formatCurrency(incomeStatementData.operating_expenses?.total_operating_expenses || 0)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Net Income -->
                        <div x-show="!loading"
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-900 dark:text-white">LABA BERSIH</span>
                                <span class="text-xl font-bold"
                                    :class="(incomeStatementData.totals?.net_income || 0) >= 0 ?
                                        'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                    x-text="formatCurrency(incomeStatementData.totals?.net_income || 0)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Original Income Statement Report -->
        <div x-show="false"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                    </svg>
                    Laporan Laba Rugi
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Income -->
                    <div>
                        <h4 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-4">Pendapatan
                        </h4>
                        <div x-show="!loading && incomeStatementData.income?.length > 0">
                            <template x-for="income in incomeStatementData.income" :key="income.id">
                                <div
                                    class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="income.nama"></span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400" x-text="income.kode">
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400"
                                        x-text="formatCurrency(income.balance)"></span>
                                </div>
                            </template>
                            <div class="pt-4 mt-4 border-t-2 border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900 dark:text-white">Total
                                        Pendapatan</span>
                                    <span class="text-base font-bold text-green-600 dark:text-green-400"
                                        x-text="formatCurrency(incomeStatementData.totals?.total_income || 0)"></span>
                                </div>
                            </div>
                        </div>
                        <div x-show="!loading && (!incomeStatementData.income || incomeStatementData.income.length === 0)"
                            class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada data pendapatan</p>
                        </div>
                    </div>

                    <!-- Expenses -->
                    <div>
                        <h4 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Beban</h4>
                        <div x-show="!loading && incomeStatementData.expenses?.length > 0">
                            <template x-for="expense in incomeStatementData.expenses" :key="expense.id">
                                <div
                                    class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="expense.nama"></span>
                                        <div class="text-xs text-gray-500 dark:text-gray-400" x-text="expense.kode">
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-red-600 dark:text-red-400"
                                        x-text="formatCurrency(expense.balance)"></span>
                                </div>
                            </template>
                            <div class="pt-4 mt-4 border-t-2 border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-bold text-gray-900 dark:text-white">Total
                                        Beban</span>
                                    <span class="text-base font-bold text-red-600 dark:text-red-400"
                                        x-text="formatCurrency(incomeStatementData.totals?.total_expenses || 0)"></span>
                                </div>
                            </div>
                        </div>
                        <div x-show="!loading && (!incomeStatementData.expenses || incomeStatementData.expenses.length === 0)"
                            class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada data beban</p>
                        </div>
                    </div>
                </div>

                <!-- Net Income -->
                <div class="mt-6 pt-6 border-t-2 border-gray-300 dark:border-gray-600">
                    <div
                        class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">Laba Bersih</span>
                            <span class="text-xl font-bold"
                                :class="(incomeStatementData.totals?.net_income || 0) >= 0 ?
                                    'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                x-text="formatCurrency(incomeStatementData.totals?.net_income || 0)"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Flow Report -->
        <div x-show="filter.report_type === 'cash_flow'" class="space-y-6">
            <!-- Charts Section for Cash Flow -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Cash Flow Summary Chart -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                    clip-rule="evenodd" />
                            </svg>
                            Ringkasan Arus Kas
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="cashFlowSummaryChart" class="w-full h-64"></canvas>
                    </div>
                </div>

                <!-- Cash vs Bank Chart -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Kas vs Bank
                        </h3>
                    </div>
                    <div class="p-6">
                        <canvas id="cashVsBankChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Trend Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"
                                clip-rule="evenodd" />
                        </svg>
                        Tren Arus Kas Bulanan
                    </h3>
                </div>
                <div class="p-6">
                    <canvas id="monthlyTrendChart" class="w-full h-80"></canvas>
                </div>
            </div>

            <!-- Cash Flow Data Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                clip-rule="evenodd" />
                        </svg>
                        Laporan Arus Kas
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Loading State -->
                    <div x-show="loading" class="flex justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Cash Accounts -->
                        <div>
                            <h4 class="text-lg font-semibold text-blue-600 dark:text-blue-400 mb-4">Kas</h4>
                            <div x-show="!loading && cashFlowData.kas_transactions?.length > 0">
                                <template x-for="kas in cashFlowData.kas_transactions" :key="kas.kas_id">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <h5 class="font-semibold text-gray-900 dark:text-white mb-2"
                                            x-text="kas.kas?.nama || 'Kas'"></h5>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span
                                                    class="text-sm text-gray-600 dark:text-gray-400">Penerimaan</span>
                                                <span class="text-sm font-semibold text-green-600 dark:text-green-400"
                                                    x-text="formatCurrency(kas.total_masuk)"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span
                                                    class="text-sm text-gray-600 dark:text-gray-400">Pengeluaran</span>
                                                <span class="text-sm font-semibold text-red-600 dark:text-red-400"
                                                    x-text="formatCurrency(kas.total_keluar)"></span>
                                            </div>
                                            <div
                                                class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                                                <span
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">Net</span>
                                                <span class="text-sm font-bold"
                                                    :class="(kas.total_masuk - kas.total_keluar) >= 0 ?
                                                        'text-green-600 dark:text-green-400' :
                                                        'text-red-600 dark:text-red-400'"
                                                    x-text="formatCurrency(kas.total_masuk - kas.total_keluar)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div class="pt-4 mt-4 border-t-2 border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900 dark:text-white">Total
                                            Kas</span>
                                        <span class="text-base font-bold text-blue-600 dark:text-blue-400"
                                            x-text="formatCurrency(cashFlowData.totals?.total_kas_masuk - cashFlowData.totals?.total_kas_keluar || 0)"></span>
                                    </div>
                                </div>
                            </div>
                            <div x-show="!loading && (!cashFlowData.kas_transactions || cashFlowData.kas_transactions.length === 0)"
                                class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">Tidak ada transaksi kas</p>
                            </div>
                        </div>

                        <!-- Bank Accounts -->
                        <div>
                            <h4 class="text-lg font-semibold text-purple-600 dark:text-purple-400 mb-4">Bank
                            </h4>
                            <div x-show="!loading && cashFlowData.bank_transactions?.length > 0">
                                <template x-for="bank in cashFlowData.bank_transactions" :key="bank.rekening_id">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                                        <h5 class="font-semibold text-gray-900 dark:text-white mb-2"
                                            x-text="bank.rekening?.nama_bank + ' - ' + bank.rekening?.nomor_rekening || 'Bank'">
                                        </h5>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span
                                                    class="text-sm text-gray-600 dark:text-gray-400">Penerimaan</span>
                                                <span class="text-sm font-semibold text-green-600 dark:text-green-400"
                                                    x-text="formatCurrency(bank.total_masuk)"></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span
                                                    class="text-sm text-gray-600 dark:text-gray-400">Pengeluaran</span>
                                                <span class="text-sm font-semibold text-red-600 dark:text-red-400"
                                                    x-text="formatCurrency(bank.total_keluar)"></span>
                                            </div>
                                            <div
                                                class="flex justify-between border-t border-gray-200 dark:border-gray-600 pt-2">
                                                <span
                                                    class="text-sm font-semibold text-gray-900 dark:text-white">Net</span>
                                                <span class="text-sm font-bold"
                                                    :class="(bank.total_masuk - bank.total_keluar) >= 0 ?
                                                        'text-green-600 dark:text-green-400' :
                                                        'text-red-600 dark:text-red-400'"
                                                    x-text="formatCurrency(bank.total_masuk - bank.total_keluar)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div class="pt-4 mt-4 border-t-2 border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-bold text-gray-900 dark:text-white">Total
                                            Bank</span>
                                        <span class="text-base font-bold text-purple-600 dark:text-purple-400"
                                            x-text="formatCurrency(cashFlowData.totals?.total_bank_masuk - cashFlowData.totals?.total_bank_keluar || 0)"></span>
                                    </div>
                                </div>
                            </div>
                            <div x-show="!loading && (!cashFlowData.bank_transactions || cashFlowData.bank_transactions.length === 0)"
                                class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">Tidak ada transaksi bank</p>
                            </div>
                        </div>
                    </div>

                    <!-- Net Cash Flow -->
                    <div x-show="!loading" class="mt-6 pt-6 border-t-2 border-gray-300 dark:border-gray-600">
                        <div
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Penerimaan
                                    </div>
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400"
                                        x-text="formatCurrency(cashFlowData.totals?.total_masuk || 0)"></div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Pengeluaran
                                    </div>
                                    <div class="text-lg font-bold text-red-600 dark:text-red-400"
                                        x-text="formatCurrency(cashFlowData.totals?.total_keluar || 0)"></div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Arus Kas Bersih</div>
                                    <div class="text-xl font-bold"
                                        :class="(cashFlowData.totals?.net_cash_flow || 0) >= 0 ?
                                            'text-green-600 dark:text-green-400' :
                                            'text-red-600 dark:text-red-400'"
                                        x-text="formatCurrency(cashFlowData.totals?.net_cash_flow || 0)"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full mx-4">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div>
                    <div class="text-gray-700 dark:text-gray-300">Memuat laporan keuangan...</div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function keuanganLaporanController() {
            return {
                loading: false,
                filter: {
                    report_type: 'balance_sheet',
                    tanggal_awal: @js($tanggalAwal->format('Y-m-d')),
                    tanggal_akhir: @js($tanggalAkhir->format('Y-m-d'))
                },
                balanceSheetData: {},
                incomeStatementData: {},
                cashFlowData: {},
                charts: {},

                init() {
                    this.fetchData();
                },

                async fetchData() {
                    this.loading = true;

                    try {
                        let url = '';
                        let params = new URLSearchParams();

                        if (this.filter.report_type !== 'balance_sheet') {
                            params.append('tanggal_awal', this.filter.tanggal_awal);
                        }
                        params.append('tanggal_akhir', this.filter.tanggal_akhir);

                        switch (this.filter.report_type) {
                            case 'balance_sheet':
                                url = '{{ route('test.financial.balance-sheet') }}';
                                break;
                            case 'income_statement':
                                url = '{{ route('test.financial.income-statement') }}';
                                break;
                            case 'cash_flow':
                                url = '{{ route('test.financial.cash-flow') }}';
                                break;
                        }

                        console.log('Fetching:', `${url}?${params}`); // Debug log
                        const response = await fetch(`${url}?${params}`);
                        const result = await response.json();

                        console.log('API Response:', result); // Debug log
                        if (result.success) {
                            if (this.filter.report_type === 'balance_sheet') {
                                this.balanceSheetData = result.data;
                                console.log('Balance Sheet Data:', this.balanceSheetData); // Debug log
                                this.$nextTick(() => this.renderBalanceSheetCharts());
                            } else if (this.filter.report_type === 'income_statement') {
                                this.incomeStatementData = result.data;
                                console.log('Income Statement Data:', this.incomeStatementData); // Debug log
                                this.$nextTick(() => this.renderIncomeStatementCharts());
                            } else if (this.filter.report_type === 'cash_flow') {
                                this.cashFlowData = result.data;
                                console.log('Cash Flow Data:', this.cashFlowData); // Debug log
                                this.$nextTick(() => this.renderCashFlowCharts());
                            }
                        } else {
                            console.error('API Error:', result); // Debug log
                            throw new Error(result.message || 'Terjadi kesalahan');
                        }
                    } catch (error) {
                        console.error('Error fetching data:', error);
                        alert('Terjadi kesalahan saat memuat data: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },

                destroyCharts() {
                    Object.values(this.charts).forEach(chart => {
                        if (chart && typeof chart.destroy === 'function') {
                            chart.destroy();
                        }
                    });
                    this.charts = {};
                },

                renderBalanceSheetCharts() {
                    this.destroyCharts();

                    if (!this.balanceSheetData.chart_data) return;

                    // Assets Composition Chart
                    const assetsCtx = document.getElementById('assetsChart');
                    if (assetsCtx && this.balanceSheetData.chart_data.assets_composition) {
                        this.charts.assets = new Chart(assetsCtx, {
                            type: 'doughnut',
                            data: this.balanceSheetData.chart_data.assets_composition,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 10
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => {
                                                const value = this.formatCurrency(context.raw);
                                                const percentage = ((context.raw / context.dataset.data.reduce((
                                                    a, b) => a + b, 0)) * 100).toFixed(1);
                                                return `${context.label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Liabilities vs Equity Chart
                    const liabEquityCtx = document.getElementById('liabilitiesEquityChart');
                    if (liabEquityCtx && this.balanceSheetData.chart_data.liabilities_equity) {
                        this.charts.liabEquity = new Chart(liabEquityCtx, {
                            type: 'pie',
                            data: this.balanceSheetData.chart_data.liabilities_equity,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 10
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => {
                                                const value = this.formatCurrency(context.raw);
                                                const percentage = ((context.raw / context.dataset.data.reduce((
                                                    a, b) => a + b, 0)) * 100).toFixed(1);
                                                return `${context.label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                },

                renderIncomeStatementCharts() {
                    this.destroyCharts();

                    if (!this.incomeStatementData.chart_data) return;

                    // Revenue Breakdown Chart
                    const revenueCtx = document.getElementById('revenueChart');
                    if (revenueCtx && this.incomeStatementData.chart_data.revenue_breakdown) {
                        this.charts.revenue = new Chart(revenueCtx, {
                            type: 'doughnut',
                            data: this.incomeStatementData.chart_data.revenue_breakdown,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 10
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => {
                                                const value = this.formatCurrency(context.raw);
                                                const percentage = ((context.raw / context.dataset.data.reduce((
                                                    a, b) => a + b, 0)) * 100).toFixed(1);
                                                return `${context.label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Expense Breakdown Chart
                    const expenseCtx = document.getElementById('expenseChart');
                    if (expenseCtx && this.incomeStatementData.chart_data.expense_breakdown) {
                        this.charts.expense = new Chart(expenseCtx, {
                            type: 'doughnut',
                            data: this.incomeStatementData.chart_data.expense_breakdown,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 12,
                                            padding: 10
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => {
                                                const value = this.formatCurrency(context.raw);
                                                const percentage = ((context.raw / context.dataset.data.reduce((
                                                    a, b) => a + b, 0)) * 100).toFixed(1);
                                                return `${context.label}: ${value} (${percentage}%)`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Profitability Chart
                    const profitabilityCtx = document.getElementById('profitabilityChart');
                    if (profitabilityCtx && this.incomeStatementData.chart_data.profitability) {
                        this.charts.profitability = new Chart(profitabilityCtx, {
                            type: 'bar',
                            data: this.incomeStatementData.chart_data.profitability,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: (value) => this.formatCurrency(value)
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => this.formatCurrency(context.raw)
                                        }
                                    }
                                }
                            }
                        });
                    }
                },

                renderCashFlowCharts() {
                    this.destroyCharts();

                    if (!this.cashFlowData.chart_data) return;

                    // Cash Flow Summary Chart
                    const summaryCtx = document.getElementById('cashFlowSummaryChart');
                    if (summaryCtx && this.cashFlowData.chart_data.cash_flow_summary) {
                        this.charts.summary = new Chart(summaryCtx, {
                            type: 'bar',
                            data: this.cashFlowData.chart_data.cash_flow_summary,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: (value) => this.formatCurrency(value)
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) =>
                                                `${context.label}: ${this.formatCurrency(context.raw)}`
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Cash vs Bank Chart
                    const cashBankCtx = document.getElementById('cashVsBankChart');
                    if (cashBankCtx && this.cashFlowData.chart_data.cash_vs_bank) {
                        this.charts.cashBank = new Chart(cashBankCtx, {
                            type: 'bar',
                            data: this.cashFlowData.chart_data.cash_vs_bank,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: (value) => this.formatCurrency(value)
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) =>
                                                `${context.dataset.label}: ${this.formatCurrency(context.raw)}`
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Monthly Trend Chart
                    const monthlyCtx = document.getElementById('monthlyTrendChart');
                    if (monthlyCtx && this.cashFlowData.chart_data.monthly_trend) {
                        this.charts.monthly = new Chart(monthlyCtx, {
                            type: 'line',
                            data: this.cashFlowData.chart_data.monthly_trend,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: (value) => this.formatCurrency(value)
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) =>
                                                `${context.dataset.label}: ${this.formatCurrency(context.raw)}`
                                        }
                                    }
                                },
                                elements: {
                                    line: {
                                        tension: 0.4
                                    }
                                }
                            }
                        });
                    }
                },

                $watch: {
                    'filter.report_type'() {
                        this.destroyCharts();
                        this.fetchData();
                    }
                },

                exportExcel() {
                    const params = new URLSearchParams({
                        report_type: this.filter.report_type,
                        tanggal_awal: this.filter.tanggal_awal,
                        tanggal_akhir: this.filter.tanggal_akhir
                    });

                    window.open(`{{ route('laporan.keuangan.export.excel') }}?${params}`, '_blank');
                },

                exportPdf() {
                    const params = new URLSearchParams({
                        report_type: this.filter.report_type,
                        tanggal_awal: this.filter.tanggal_awal,
                        tanggal_akhir: this.filter.tanggal_akhir
                    });

                    window.open(`{{ route('laporan.keuangan.export.pdf') }}?${params}`, '_blank');
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(amount || 0);
                },

                getToday() {
                    return new Date().toISOString().split('T')[0];
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
</x-app-layout>
