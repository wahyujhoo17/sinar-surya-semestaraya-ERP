<x-app-layout :breadcrumbs="[['label' => 'CRM', 'url' => route('crm.prospek.index')], ['label' => 'Prospek & Lead']]" :currentPage="'Prospek & Lead'">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Only use the toast notification component, not the inline flash messages -->
    <x-toast-notification />

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="prospekTableManager()" x-init="init()">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Manajemen Prospek & Lead
                        </h1>
                    </div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Kelola data prospek, calon pelanggan, dan lead penjualan untuk meningkatkan konversi penjualan.
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex-shrink-0 flex">
                    <a href="{{ route('crm.prospek.create') }}"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Prospek Baru
                    </a>
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
                    Filter Prospek
                </h2>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-600 italic dark:text-gray-400 sm:inline-block">
                        <span x-show="search || status || sumber || periode" x-cloak>Filter
                            aktif</span>
                        <span x-show="!search && !status && !sumber && !periode">Tidak ada
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
                    <!-- Filter Controls -->
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
                                </div>
                                <input type="text" name="search" placeholder="Cari nama, perusahaan, email, dll"
                                    x-model="search"
                                    class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
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
                                    <option value="baru">Baru</option>
                                    <option value="tertarik">Tertarik</option>
                                    <option value="negosiasi">Negosiasi</option>
                                    <option value="menolak">Menolak</option>
                                    <option value="menjadi_customer">Menjadi Customer</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sumber -->
                        <div>
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Sumber</label>
                                <span x-show="sumber"
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 ml-2">
                                    Aktif
                                </span>
                            </div>
                            <div class="relative mt-1">
                                <select x-model="sumber"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    <option value="">Semua Sumber</option>
                                    <option value="website">Website</option>
                                    <option value="referral">Referral</option>
                                    <option value="pameran">Pameran</option>
                                    <option value="media_sosial">Media Sosial</option>
                                    <option value="cold_call">Cold Call</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
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
                                    <option value="last_7_days">7 Hari Terakhir</option>
                                    <option value="last_30_days">30 Hari Terakhir</option>
                                    <option value="this_month">Bulan Ini</option>
                                    <option value="last_month">Bulan Lalu</option>
                                    <option value="this_year">Tahun Ini</option>
                                    <option value="custom">Kustom</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end pt-2 space-x-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="resetFilters"
                            class="px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                            Reset
                        </button>
                        <button type="submit"
                            class="px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Prospek & Lead</h3>
                <div class="flex items-center space-x-3">
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400 mr-2">Tampilkan:</span>
                        <select x-model="perPage" @change="changePerPage"
                            class="block w-20 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <button @click="refreshData"
                        class="p-1.5 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Table View -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer" @click="sortBy('nama_prospek')">
                                    Nama Prospek
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor"
                                        :class="{ 'text-primary-500': sortColumn === 'nama_prospek' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer" @click="sortBy('perusahaan')">
                                    Perusahaan
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor"
                                        :class="{ 'text-primary-500': sortColumn === 'perusahaan' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer" @click="sortBy('email')">
                                    Kontak
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor"
                                        :class="{ 'text-primary-500': sortColumn === 'email' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer" @click="sortBy('status')">
                                    Status
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor"
                                        :class="{ 'text-primary-500': sortColumn === 'status' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer" @click="sortBy('sumber')">
                                    Sumber
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor"
                                        :class="{ 'text-primary-500': sortColumn === 'sumber' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer" @click="sortBy('tanggal_kontak')">
                                    Tanggal Kontak
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor"
                                        :class="{ 'text-primary-500': sortColumn === 'tanggal_kontak' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex items-center cursor-pointer" @click="sortBy('nilai_potensi')">
                                    Nilai Potensi
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor"
                                        :class="{ 'text-primary-500': sortColumn === 'nilai_potensi' }">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Loading state -->
                        <template x-if="isLoading">
                            <tr>
                                <td colspan="8"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="py-8 flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 animate-spin text-primary-600 mb-4" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <p class="text-base">Memuat data prospek...</p>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- No data state -->
                        <template x-if="!isLoading && (!Array.isArray(prospekList) || prospekList.length === 0)">
                            <tr>
                                <td colspan="8"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-12 w-12 mx-auto text-gray-400 dark:text-gray-500 mb-3"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 11H5m14 0a2 2 0 002 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="text-base">Tidak ada data prospek ditemukan</p>
                                        <p class="text-sm mt-1">Mulai tambahkan prospek baru atau ubah filter
                                            pencarian.</p>
                                        <button @click="resetFilters"
                                            class="mt-3 inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Reset Filter
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Data rows -->
                        <template x-if="!isLoading && Array.isArray(prospekList) && prospekList.length > 0">
                            <template x-for="(item, index) in prospekList" :key="item ? (item.id || index) : index">
                                <tr :class="index % 2 === 0 ? '' : 'bg-gray-50 dark:bg-gray-700/50'">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        <a :href="item && item.id ? '/crm/prospek/' + item.id : '#'"
                                            class="hover:text-primary-600 dark:hover:text-primary-400">
                                            <span x-text="item && item.nama_prospek ? item.nama_prospek : '-'"></span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                        x-text="item && item.perusahaan ? item.perusahaan : '-'"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col">
                                            <span x-text="item && item.email ? item.email : '-'"></span>
                                            <span x-text="item && item.telepon ? item.telepon : '-'"></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="{
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': item &&
                                                    item.status === 'baru',
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300': item &&
                                                    item.status === 'tertarik',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300': item &&
                                                    item.status === 'negosiasi',
                                                'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300': item &&
                                                    item.status === 'menolak',
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300': item &&
                                                    item.status === 'menjadi_customer',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': !
                                                    item ||
                                                    !item.status
                                            }">
                                            <span
                                                x-text="item && item.status ? getStatusLabel(item.status) : '-'"></span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                        x-text="item && item.sumber ? getSumberLabel(item.sumber) : '-'"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                        x-text="item && item.tanggal_kontak ? formatDate(item.tanggal_kontak) : '-'">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                        x-text="item && item.nilai_potensi ? formatCurrency(item.nilai_potensi) : '-'">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end items-center space-x-2">
                                            <a :href="item && item.id ? '/crm/prospek/' + item.id : '#'"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                                                title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a :href="item && item.id ? '/crm/prospek/' + item.id + '/edit' : '#'"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-amber-100 text-amber-600 dark:text-amber-400 dark:bg-amber-900/20 dark:hover:bg-amber-900/30 transition-colors border border-dashed border-amber-300"
                                                title="Edit Prospek">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button @click="item && item.id ? deleteProspek(item.id) : null"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-red-600 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300"
                                                title="Hapus Prospek">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan <span x-text="startEntry"></span> hingga <span x-text="endEntry"></span> dari
                        <span x-text="totalEntries"></span> data
                    </div>
                    <div class="flex space-x-1">
                        <button @click="prevPage" :disabled="page === 1"
                            :class="{ 'opacity-50 cursor-not-allowed': page === 1 }"
                            class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                            Sebelumnya
                        </button>
                        <button @click="nextPage" :disabled="page === totalPages"
                            :class="{ 'opacity-50 cursor-not-allowed': page === totalPages }"
                            class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                            Berikutnya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function prospekTableManager() {
            return {
                prospekList: [],
                page: 1,
                perPage: 10,
                totalEntries: 0,
                totalPages: 1,
                sortColumn: 'created_at',
                sortDirection: 'desc',
                search: '',
                status: '',
                sumber: '',
                periode: '',
                prospekToDelete: null,
                isLoading: false,

                init() {
                    // Only use the toast notification system for all messages
                    this.loadData();
                },

                loadData() {
                    // Show loading state
                    this.isLoading = true;
                    this.prospekList = []; // Clear the list when loading to avoid display issues

                    // Build query parameters
                    const params = new URLSearchParams({
                        page: this.page,
                        per_page: this.perPage,
                        sort_column: this.sortColumn,
                        sort_direction: this.sortDirection,
                        search: this.search,
                        status: this.status,
                        sumber: this.sumber,
                        periode: this.periode
                    });

                    // Make AJAX request to get data
                    fetch(`/crm/prospek/data?${params.toString()}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            this.prospekList = Array.isArray(data.data) ? data.data : [];
                            this.totalEntries = data.total || 0;
                            this.calculatePagination();
                            this.isLoading = false;
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            this.isLoading = false;
                            this.prospekList = [];

                            // Show error notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    message: 'Gagal memuat data prospek. Silakan coba lagi nanti.',
                                    timeout: 5000 // Auto-hide after 5 seconds
                                }
                            }));

                            // Fallback to server-rendered data if AJAX fails
                            if (Array.isArray(@json($prospeks ?? []))) {
                                this.prospekList = @json($prospeks ?? []);
                                this.totalEntries = this.prospekList.length;
                                this.calculatePagination();
                            } else {
                                this.totalEntries = 0;
                                this.totalPages = 1;
                            }
                            this.calculatePagination();
                        });
                },

                calculatePagination() {
                    this.totalPages = Math.max(1, Math.ceil(this.totalEntries / this.perPage));
                    if (this.page > this.totalPages) {
                        this.page = this.totalPages || 1; // Ensure we default to page 1 if totalPages is 0
                    }
                    if (this.page < 1) {
                        this.page = 1;
                    }
                },

                get startEntry() {
                    return this.totalEntries === 0 ? 0 : (this.page - 1) * this.perPage + 1;
                },

                get endEntry() {
                    return Math.min(this.page * this.perPage, this.totalEntries);
                },

                nextPage() {
                    if (this.page < this.totalPages) {
                        this.page++;
                        this.loadData();
                    }
                },

                prevPage() {
                    if (this.page > 1) {
                        this.page--;
                        this.loadData();
                    }
                },

                changePerPage() {
                    this.page = 1;
                    this.loadData();
                },

                sortBy(column) {
                    if (this.sortColumn === column) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortColumn = column;
                        this.sortDirection = 'asc';
                    }
                    this.loadData();
                },

                applyFilters() {
                    this.page = 1;
                    this.loadData();
                },

                resetFilters() {
                    this.search = '';
                    this.status = '';
                    this.sumber = '';
                    this.periode = '';
                    this.page = 1;
                    this.loadData();
                },

                refreshData() {
                    this.loadData();
                },

                deleteProspek(id) {
                    if (!id) {
                        console.error('No valid ID provided for deletion');
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: 'error',
                                message: 'ID Prospek tidak valid untuk dihapus.',
                                timeout: 5000
                            }
                        }));
                        return;
                    }

                    this.prospekToDelete = id;
                    // Use global confirmation function with fallback if not available
                    if (typeof window.confirmDelete === 'function') {
                        window.confirmDelete(
                            'Apakah Anda yakin ingin menghapus prospek ini? Tindakan ini tidak dapat dibatalkan.',
                            () => {
                                // Create form element dynamically
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `/crm/prospek/${id}`;
                                form.style.display = 'none';

                                const csrfToken = document.createElement('input');
                                csrfToken.type = 'hidden';
                                csrfToken.name = '_token';
                                csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;

                                const methodField = document.createElement('input');
                                methodField.type = 'hidden';
                                methodField.name = '_method';
                                methodField.value = 'DELETE';

                                form.appendChild(csrfToken);
                                form.appendChild(methodField);
                                document.body.appendChild(form);

                                form.submit();
                            });
                    } else {
                        // Fallback if confirmDelete is not available
                        if (confirm(
                                'Apakah Anda yakin ingin menghapus prospek ini? Tindakan ini tidak dapat dibatalkan.')) {
                            // Create form element dynamically
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/crm/prospek/${id}`;
                            form.style.display = 'none';

                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';

                            form.appendChild(csrfToken);
                            form.appendChild(methodField);
                            document.body.appendChild(form);

                            form.submit();
                        }
                    }
                },

                confirmDelete() {
                    // Submit the form
                    document.getElementById('delete-form').submit();
                },

                formatDate(dateString) {
                    if (!dateString) return '-';
                    try {
                        const options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        return new Date(dateString).toLocaleDateString('id-ID', options);
                    } catch (error) {
                        console.error('Error formatting date:', error);
                        return '-';
                    }
                },

                formatCurrency(value) {
                    if (!value) return 'Rp 0';
                    try {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(value);
                    } catch (error) {
                        console.error('Error formatting currency:', error);
                        return 'Rp 0';
                    }
                },

                getStatusLabel(status) {
                    if (!status) return '-';
                    const labels = {
                        'baru': 'Baru',
                        'tertarik': 'Tertarik',
                        'negosiasi': 'Negosiasi',
                        'menolak': 'Menolak',
                        'menjadi_customer': 'Menjadi Customer'
                    };
                    return labels[status] || status;
                },

                getSumberLabel(sumber) {
                    if (!sumber) return '-';
                    const labels = {
                        'website': 'Website',
                        'referral': 'Referral',
                        'pameran': 'Pameran',
                        'media_sosial': 'Media Sosial',
                        'cold_call': 'Cold Call',
                        'lainnya': 'Lainnya'
                    };
                    return labels[sumber] || sumber;
                }
            };
        }
    </script>
</x-app-layout>
