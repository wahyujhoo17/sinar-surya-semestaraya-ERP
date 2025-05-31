<x-app-layout :breadcrumbs="[['label' => 'Penjualan', 'url' => route('penjualan.invoice.index')], ['label' => 'Riwayat Transaksi']]" :currentPage="'Riwayat Transaksi'">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="riwayatTransaksiTableManager()" x-init="init()">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Riwayat Transaksi Penjualan
                        </h1>
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
                        <span x-show="search || periode || jenis || status" x-cloak>Filter
                            aktif</span>
                        <span x-show="!search && !periode && !jenis && !status">Tidak ada
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
                                    placeholder="Cari nama customer, nomor transaksi, dsb" x-model="search"
                                    class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                            </div>
                        </div>

                        <!-- Periode -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Periode</label>
                                <span x-show="periode"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <select x-model="periode"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    <option value="">Semua Periode</option>
                                    <option value="today">Hari Ini</option>
                                    <option value="yesterday">Kemarin</option>
                                    <option value="last7days">7 Hari Terakhir</option>
                                    <option value="last30days">30 Hari Terakhir</option>
                                    <option value="thisMonth">Bulan Ini</option>
                                    <option value="lastMonth">Bulan Lalu</option>
                                    <option value="thisYear">Tahun Ini</option>
                                    <option value="custom">Kustom...</option>
                                </select>
                            </div>
                        </div>

                        <!-- Jenis Transaksi -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Jenis
                                    Transaksi</label>
                                <span x-show="jenis"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <select x-model="jenis"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    <option value="">Semua Jenis</option>
                                    <option value="quotation">Quotation</option>
                                    <option value="sales_order">Sales Order</option>
                                    <option value="delivery_order">Delivery Order</option>
                                    <option value="invoice">Invoice</option>
                                    <option value="retur">Retur Penjualan</option>
                                    <option value="nota_kredit">Nota Kredit</option>
                                </select>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <span x-show="status"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <select x-model="status"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    <option value="">Semua Status</option>
                                    <option value="draft">Draft</option>
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="dibatalkan">Dibatalkan</option>
                                    <option value="belum_bayar">Belum Bayar</option>
                                    <option value="sebagian">Bayar Sebagian</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Date Range -->
                    <div x-show="periode === 'custom'" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                            <div class="mt-1">
                                <input type="date" x-model="startDate"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                            <div class="mt-1">
                                <input type="date" x-model="endDate"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" @click="resetFilters"
                            class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Reset
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="py-3 px-4 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Riwayat Transaksi
                </h3>
                <div class="flex items-center space-x-2">
                    <button @click="exportData('excel')"
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-1 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M14.5,3H9.5C8.67,3 8,3.67 8,4.5V15.5C8,16.33 8.67,17 9.5,17H14.5C15.33,17 16,16.33 16,15.5V4.5C16,3.67 15.33,3 14.5,3M10.5,6H13.5V7.5H10.5V6M13.5,11H10.5V9.5H13.5V11M10.5,12.5H13.5V14H10.5V12.5M16,19.5C16,20.33 15.33,21 14.5,21H9.5C8.67,21 8,20.33 8,19.5V18H16V19.5M19,9H17V4.5C17,3.12 15.88,2 14.5,2H9.5C8.12,2 7,3.12 7,4.5V9H5C3.9,9 3,9.9 3,11V19C3,20.1 3.9,21 5,21H7V19.5C7,20.88 8.12,22 9.5,22H14.5C15.88,22 17,20.88 17,19.5V21H19C20.1,21 21,20.1 21,19V11C21,9.9 20.1,9 19,9Z" />
                        </svg>
                        Export Excel
                    </button>
                    <button @click="exportData('pdf')"
                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="w-4 h-4 mr-1 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M19 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3M9.5 11.5C9.5 12.33 8.83 13 8 13H7V15H5.5V9H8C8.83 9 9.5 9.67 9.5 10.5V11.5M14.5 15H13V13.5H14.5V15M14.5 12H13V10.5H14.5V12M14.5 9H13V7.5H14.5V9M18.5 14.5C18.5 14.91 18.29 15.28 17.96 15.45L17.37 15.75L17.89 17H16.42L16 16H15.5V17H14V13H16.5C17.33 13 18 13.67 18 14.5M18.5 10.5C18.5 11.33 17.83 12 17 12H15.5V9H17C17.83 9 18.5 9.67 18.5 10.5M7 10.5H8.5V11.5H7V10.5M15.5 14.5H16.5V15H15.5V14.5M17 10.5H15.5V11.5H17V10.5Z" />
                        </svg>
                        Export PDF
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                No. Dokumen
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jenis
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="(transaksi, index) in transaksiList" :key="index">
                            <tr :class="index % 2 === 0 ? '' : 'bg-gray-50 dark:bg-gray-900/20'">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span x-text="formatDate(transaksi.tanggal)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span x-text="transaksi.nomor"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="getJenisClass(transaksi.jenis)"
                                        class="px-2 py-1 rounded-md text-xs font-medium"
                                        x-text="getJenisLabel(transaksi.jenis)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span x-text="transaksi.customer_nama"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span x-text="formatCurrency(transaksi.total)"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span :class="getStatusClass(transaksi.status)"
                                        class="px-2 py-1 rounded-md text-xs font-medium"
                                        x-text="getStatusLabel(transaksi.status)">
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span x-text="transaksi.user_nama"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a :href="getDetailUrl(transaksi)"
                                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        </template>
                        <!-- Empty State -->
                        <tr x-show="transaksiList.length === 0">
                            <td colspan="8"
                                class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-3"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p>Tidak ada data transaksi</p>
                                    <p class="mt-1">Sesuaikan filter pencarian atau buat transaksi baru</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="previousPage" :disabled="currentPage === 1"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Sebelumnya
                    </button>
                    <button @click="nextPage" :disabled="currentPage === totalPages"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Selanjutnya
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Menampilkan
                            <span class="font-medium" x-text="(currentPage - 1) * perPage + 1"></span>
                            sampai
                            <span class="font-medium" x-text="Math.min(currentPage * perPage, totalItems)"></span>
                            dari
                            <span class="font-medium" x-text="totalItems"></span>
                            hasil
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            <button @click="previousPage" :disabled="currentPage === 1"
                                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <span class="sr-only">Sebelumnya</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <template x-for="page in displayedPages" :key="page">
                                <button @click="goToPage(page)"
                                    :class="{
                                        'bg-primary-50 dark:bg-primary-900/30 border-primary-500 dark:border-primary-500 text-primary-600 dark:text-primary-400': currentPage ===
                                            page,
                                        'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600': currentPage !==
                                            page
                                    }"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                    <span x-text="page"></span>
                                </button>
                            </template>
                            <button @click="nextPage" :disabled="currentPage === totalPages"
                                :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <span class="sr-only">Selanjutnya</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function riwayatTransaksiTableManager() {
                return {
                    transaksiList: [],
                    currentPage: 1,
                    totalPages: 1,
                    totalItems: 0,
                    perPage: 10,
                    search: '',
                    periode: '',
                    jenis: '',
                    status: '',
                    startDate: '',
                    endDate: '',
                    isLoading: false,

                    init() {
                        this.fetchData();
                    },

                    fetchData() {
                        this.isLoading = true;

                        // Prepare the API request parameters
                        const params = {
                            search: this.search,
                            periode: this.periode,
                            jenis: this.jenis,
                            status: this.status,
                            startDate: this.startDate,
                            endDate: this.endDate,
                            page: this.currentPage,
                            perPage: this.perPage
                        };

                        // Make AJAX request to the backend API
                        fetch(`/penjualan/riwayat-transaksi/data?${new URLSearchParams(params)}`)
                            .then(response => response.json())
                            .then(data => {
                                this.transaksiList = data.transactions;
                                this.totalItems = data.total;
                                this.totalPages = data.lastPage;
                                this.isLoading = false;
                            })
                            .catch(error => {
                                console.error('Error fetching transaction data:', error);
                                this.isLoading = false;
                                // Show error message to user
                                alert('Terjadi kesalahan saat mengambil data transaksi. Silakan coba lagi.');
                            });
                    },

                    applyFilters() {
                        this.currentPage = 1;
                        this.fetchData();
                    },

                    resetFilters() {
                        this.search = '';
                        this.periode = '';
                        this.jenis = '';
                        this.status = '';
                        this.startDate = '';
                        this.endDate = '';
                        this.currentPage = 1;
                        this.fetchData();
                    },

                    previousPage() {
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

                    goToPage(page) {
                        this.currentPage = page;
                        this.fetchData();
                    },

                    get displayedPages() {
                        const pages = [];
                        const maxVisiblePages = 5;

                        if (this.totalPages <= maxVisiblePages) {
                            for (let i = 1; i <= this.totalPages; i++) {
                                pages.push(i);
                            }
                        } else {
                            const halfVisible = Math.floor(maxVisiblePages / 2);
                            let start = this.currentPage - halfVisible;
                            let end = this.currentPage + halfVisible;

                            if (start < 1) {
                                start = 1;
                                end = maxVisiblePages;
                            }

                            if (end > this.totalPages) {
                                end = this.totalPages;
                                start = this.totalPages - maxVisiblePages + 1;
                            }

                            for (let i = start; i <= end; i++) {
                                pages.push(i);
                            }
                        }

                        return pages;
                    },

                    formatDate(dateString) {
                        const options = {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        };
                        return new Date(dateString).toLocaleDateString('id-ID', options);
                    },

                    formatCurrency(amount) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(amount);
                    },

                    getJenisLabel(jenis) {
                        const labels = {
                            'quotation': 'Quotation',
                            'sales_order': 'Sales Order',
                            'delivery_order': 'Delivery Order',
                            'invoice': 'Invoice',
                            'retur': 'Retur',
                            'nota_kredit': 'Nota Kredit'
                        };
                        return labels[jenis] || jenis;
                    },

                    getJenisClass(jenis) {
                        const classes = {
                            'quotation': 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                            'sales_order': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                            'delivery_order': 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
                            'invoice': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'retur': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'nota_kredit': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                        };
                        return classes[jenis] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
                    },

                    getStatusLabel(status) {
                        if (status && status.includes('/')) {
                            // Handle combined status format from sales_order
                            const [paymentStatus, shippingStatus] = status.split('/');
                            return this.getPaymentStatusLabel(paymentStatus);
                        }

                        const labels = {
                            'draft': 'Draft',
                            'diproses': 'Diproses',
                            'selesai': 'Selesai',
                            'dibatalkan': 'Dibatalkan',
                            'belum_bayar': 'Belum Bayar',
                            'sebagian': 'Bayar Sebagian',
                            'lunas': 'Lunas'
                        };
                        return labels[status] || status;
                    },

                    getPaymentStatusLabel(status) {
                        const labels = {
                            'belum_bayar': 'Belum Bayar',
                            'sebagian': 'Bayar Sebagian',
                            'lunas': 'Lunas'
                        };
                        return labels[status] || status;
                    },

                    getStatusClass(status) {
                        if (status && status.includes('/')) {
                            // Handle combined status format from sales_order
                            const [paymentStatus, shippingStatus] = status.split('/');
                            return this.getPaymentStatusClass(paymentStatus);
                        }

                        const classes = {
                            'draft': 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300',
                            'diproses': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                            'selesai': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'dibatalkan': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                            'belum_bayar': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'sebagian': 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                            'lunas': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                        };
                        return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
                    },

                    getPaymentStatusClass(status) {
                        const classes = {
                            'belum_bayar': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                            'sebagian': 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                            'lunas': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                        };
                        return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
                    },

                    getDetailUrl(transaksi) {
                        const routes = {
                            'quotation': `/penjualan/quotation/${transaksi.id}`,
                            'sales_order': `/penjualan/sales-order/${transaksi.id}`,
                            'delivery_order': `/penjualan/delivery-order/${transaksi.id}`,
                            'invoice': `/penjualan/invoice/${transaksi.id}`,
                            'retur': `/penjualan/retur-penjualan/${transaksi.id}`,
                            'nota_kredit': `/penjualan/nota-kredit/${transaksi.id}`
                        };
                        return routes[transaksi.jenis] || '#';
                    },

                    exportData(type) {
                        // Prepare the export parameters
                        const params = {
                            search: this.search,
                            periode: this.periode,
                            jenis: this.jenis,
                            status: this.status,
                            startDate: this.startDate,
                            endDate: this.endDate
                        };

                        // Build the URL with parameters
                        const url = `/penjualan/riwayat-transaksi/export/${type}?${new URLSearchParams(params)}`;

                        // Open the export URL in a new window/tab
                        window.open(url, '_blank');
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
