<x-app-layout :breadcrumbs="[['label' => 'Penjualan', 'url' => route('penjualan.delivery-order.index')], ['label' => 'Delivery Order']]" :currentPage="'Delivery Order'">

    <!-- Add Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding-left: 32px !important;
            background-color: white !important;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            color: #374151 !important;
            font-size: 0.875rem !important;
            padding-left: 0 !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f3f4f6 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 8px !important;
        }

        .select2-dropdown {
            border-radius: 0.375rem !important;
            border-color: #d1d5db !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            z-index: 99999 !important;
            min-width: 250px !important;
            max-width: 400px !important;
            width: auto !important;
        }

        .dark .select2-dropdown {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
        }

        .select2-search--dropdown .select2-search__field {
            border-radius: 0.375rem !important;
            border-color: #d1d5db !important;
            padding: 8px 12px !important;
            width: 100% !important;
        }

        .dark .select2-search--dropdown .select2-search__field {
            background-color: #374151 !important;
            color: #f3f4f6 !important;
            border-color: #6b7280 !important;
        }

        .select2-results__option {
            padding: 8px 12px !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        .dark .select2-results__option {
            color: #f3f4f6 !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb !important;
            color: white !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #eff6ff !important;
            color: #1e40af !important;
        }

        .dark .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #1e3a8a !important;
            color: #dbeafe !important;
        }

        .customer-option {
            display: flex;
            flex-direction: column;
            max-width: 100%;
        }

        .customer-name {
            font-weight: 500;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .customer-details {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dark .customer-details {
            color: #9ca3af;
        }

        .customer-select-wrapper {
            position: relative;
            z-index: 10;
        }

        .customer-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 20;
            pointer-events: none;
        }

        .select2-container {
            width: 100% !important;
            z-index: 30;
        }

        /* Ensure dropdown appears above filter panel */
        .select2-container--open {
            z-index: 99999 !important;
        }

        /* Ensure filter panel doesn't cut off dropdown */
        .filter-panel-container {
            overflow: visible !important;
        }

        /* Additional styling for better dropdown visibility */
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
        }

        .dark .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        /* Responsive dropdown width */
        @media (max-width: 640px) {
            .select2-dropdown {
                min-width: 200px !important;
                max-width: 300px !important;
            }
        }
    </style>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="deliveryOrderTableManager()" x-init="init()">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            Delivery Order
                        </h1>
                        <a href="{{ route('penjualan.delivery-order.create') }}"
                            class="ml-4 inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Delivery Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="mt-6 space-y-4">
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
                                <span x-show="search || periode || status || customer_id || gudang_id" x-cloak>Filter
                                    aktif</span>
                                <span x-show="!search && !periode && !status && !customer_id && !gudang_id">Tidak ada
                                    filter</span>
                            </span>
                            <button @click="filterPanelOpen = !filterPanelOpen" type="button"
                                class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium">
                                <span x-text="filterPanelOpen ? 'Sembunyikan' : 'Tampilkan'"></span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 ml-1 transition-transform duration-200"
                                    :class="filterPanelOpen ? 'rotate-180' : ''" viewBox="0 0 20 20"
                                    fill="currentColor">
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
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 overflow-visible filter-panel-container">
                        <form @submit.prevent="applyFilters" class="space-y-3">
                            <!-- All Controls in One Row for larger screens -->
                            <div class="grid grid-cols-1 lg:grid-cols-5 gap-3">
                                <!-- Search -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300">Pencarian</label>
                                        <span x-show="search"
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 ml-2">
                                            Aktif
                                        </span>
                                    </div>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="text" name="search" placeholder="Cari nomor DO, customer..."
                                            x-model="search"
                                            class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    </div>
                                </div>

                                <!-- Status -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300">Status</label>
                                        <span x-show="status"
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 ml-2">
                                            Aktif
                                        </span>
                                    </div>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select name="status" x-model="status"
                                            class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white appearance-none">
                                            <option value="">Semua Status</option>
                                            <option value="draft">Draft</option>
                                            <option value="dikirim">Dikirim</option>
                                            <option value="diterima">Diterima</option>
                                            <option value="dibatalkan">Dibatalkan</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Customer -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300">Customer</label>
                                        <span x-show="customer_id"
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 ml-2">
                                            Aktif
                                        </span>
                                    </div>
                                    <div class="relative mt-1 customer-select-wrapper">
                                        <div class="customer-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select name="customer_id" x-model="customer_id" id="customer-select"
                                            class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                            <option value="">Semua Customer</option>
                                            @foreach ($customers as $customer)
                                                @if ($customer->nama || $customer->company)
                                                    <option value="{{ $customer->id }}"
                                                        data-company="{{ $customer->company ?? '' }}"
                                                        data-nama="{{ $customer->nama ?? '' }}"
                                                        data-email="{{ $customer->email ?? '' }}"
                                                        data-phone="{{ $customer->no_telp ?? '' }}"
                                                        data-address="{{ $customer->alamat ?? '' }}">
                                                        {{ $customer->company ?? $customer->nama }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Gudang -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300">Gudang</label>
                                        <span x-show="gudang_id"
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 ml-2">
                                            Aktif
                                        </span>
                                    </div>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4zm3 1h6v4H7V5zm8 8V7h2v6h-2zM7 9h6v2H7V9zm0 4h6v2H7v-2z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select name="gudang_id" x-model="gudang_id"
                                            class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white appearance-none">
                                            <option value="">Semua Gudang</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Periode -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300">Periode</label>
                                        <span x-show="periode"
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 ml-2">
                                            Aktif
                                        </span>
                                    </div>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <select name="periode" x-model="periode"
                                            class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white appearance-none">
                                            <option value="">Semua Waktu</option>
                                            <option value="today">Hari Ini</option>
                                            <option value="yesterday">Kemarin</option>
                                            <option value="this_week">Minggu Ini</option>
                                            <option value="last_week">Minggu Lalu</option>
                                            <option value="this_month">Bulan Ini</option>
                                            <option value="last_month">Bulan Lalu</option>
                                            <option value="this_year">Tahun Ini</option>
                                            <option value="last_year">Tahun Lalu</option>
                                            <option value="custom">Kustom</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Date Range (only visible when periode is 'custom') -->
                            <div x-show="periode === 'custom'" x-transition
                                class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                                <div>
                                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal
                                        Mulai</label>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="date" x-model="date_start"
                                            class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-700 dark:text-gray-300">Tanggal
                                        Akhir</label>
                                    <div class="relative mt-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <input type="date" x-model="date_end"
                                            class="pl-8 py-1.5 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-2">
                                <button type="button" @click="resetFilters"
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="mt-8 overflow-hidden">
                <div class="overflow-x-auto">
                    <div class="min-w-full">
                        <div x-show="isLoading" class="py-12 flex justify-center">
                            <div class="flex flex-col items-center">
                                <svg class="animate-spin h-10 w-10 text-primary-500 mb-4"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Memuat data...</p>
                            </div>
                        </div>

                        <table x-show="!isLoading" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col" @click="setSorting('id')"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none">
                                        <div class="flex items-center">
                                            <span>No</span>
                                            <svg x-show="sort_field === 'id' && sort_direction === 'asc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field === 'id' && sort_direction === 'desc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field !== 'id'"
                                                class="ml-1 h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3-3a1 1 0 001.414 1.414L5 6.414V12z" />
                                                <path
                                                    d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="setSorting('nomor')"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none">
                                        <div class="flex items-center">
                                            <span>Nomor DO</span>
                                            <svg x-show="sort_field === 'nomor' && sort_direction === 'asc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field === 'nomor' && sort_direction === 'desc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field !== 'nomor'"
                                                class="ml-1 h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3-3a1 1 0 001.414 1.414L5 6.414V12z" />
                                                <path
                                                    d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="setSorting('tanggal')"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none">
                                        <div class="flex items-center">
                                            <span>Tanggal</span>
                                            <svg x-show="sort_field === 'tanggal' && sort_direction === 'asc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field === 'tanggal' && sort_direction === 'desc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field !== 'tanggal'"
                                                class="ml-1 h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3-3a1 1 0 001.414 1.414L5 6.414V12z" />
                                                <path
                                                    d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="setSorting('sales_order_id')"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none">
                                        <div class="flex items-center">
                                            <span>Sales Order</span>
                                            <svg x-show="sort_field === 'sales_order_id' && sort_direction === 'asc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field === 'sales_order_id' && sort_direction === 'desc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field !== 'sales_order_id'"
                                                class="ml-1 h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3-3a1 1 0 001.414 1.414L5 6.414V12z" />
                                                <path
                                                    d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="setSorting('customer_id')"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none">
                                        <div class="flex items-center">
                                            <span>Customer</span>
                                            <svg x-show="sort_field === 'customer_id' && sort_direction === 'asc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field === 'customer_id' && sort_direction === 'desc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field !== 'customer_id'"
                                                class="ml-1 h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3-3a1 1 0 001.414 1.414L5 6.414V12z" />
                                                <path
                                                    d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="setSorting('gudang_id')"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 select-none">
                                        <div class="flex items-center">
                                            <span>Gudang</span>
                                            <svg x-show="sort_field === 'gudang_id' && sort_direction === 'asc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field === 'gudang_id' && sort_direction === 'desc'"
                                                class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg x-show="sort_field !== 'gudang_id'"
                                                class="ml-1 h-4 w-4 text-gray-400 opacity-0 group-hover:opacity-100"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3-3a1 1 0 001.414 1.414L5 6.414V12z" />
                                                <path
                                                    d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody x-html="tableHtml"
                                class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div x-html="paginationHtml" class="mt-4">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    <script>
        function deliveryOrderTableManager() {
            return {
                // State variables
                search: '',
                status: '',
                customer_id: '',
                gudang_id: '',
                periode: '',
                date_start: '',
                date_end: '',
                sort_field: 'tanggal',
                sort_direction: 'desc',
                tableHtml: '',
                paginationHtml: '',
                isLoading: true,

                init() {
                    // Initialize from URL parameters if any
                    const urlParams = new URLSearchParams(window.location.search);
                    this.search = urlParams.get('search') || '';
                    this.status = urlParams.get('status') || '';
                    this.customer_id = urlParams.get('customer_id') || '';
                    this.gudang_id = urlParams.get('gudang_id') || '';
                    this.periode = urlParams.get('periode') || '';
                    this.date_start = urlParams.get('date_start') || '';
                    this.date_end = urlParams.get('date_end') || '';
                    this.sort_field = urlParams.get('sort_field') || 'tanggal';
                    this.sort_direction = urlParams.get('sort_direction') || 'desc';

                    // Wait for DOM and libraries to be ready
                    this.waitForLibraries().then(() => {
                        this.initCustomerSelect();
                    });

                    // Fetch the table data and setup pagination listeners
                    this.fetchTable().then(() => {
                        this.$nextTick(() => {
                            this.setupPaginationListeners();
                        });
                    });
                },

                waitForLibraries() {
                    return new Promise((resolve) => {
                        const checkLibraries = () => {
                            if (typeof $ !== 'undefined' && $.fn.select2) {
                                console.log('jQuery and Select2 are ready');
                                resolve();
                            } else {
                                console.log('Waiting for jQuery and Select2...');
                                setTimeout(checkLibraries, 100);
                            }
                        };
                        checkLibraries();
                    });
                },

                initCustomerSelect() {
                    try {
                        if (typeof $ === 'undefined') {
                            console.error('jQuery is not available');
                            return;
                        }

                        if (!$.fn.select2) {
                            console.error('Select2 is not available');
                            return;
                        }

                        const self = this;
                        const $select = $('#customer-select');

                        if ($select.length === 0) {
                            console.error('Customer select element not found');
                            return;
                        }

                        // Destroy existing Select2 instance if any
                        if ($select.hasClass('select2-hidden-accessible')) {
                            $select.select2('destroy');
                        }

                        $select.select2({
                            placeholder: 'Pilih customer...',
                            allowClear: true,
                            width: '100%',
                            dropdownParent: $('body'),
                            dropdownAutoWidth: false,
                            templateResult: function(customer) {
                                return self.formatCustomerResult(customer);
                            },
                            templateSelection: function(customer) {
                                return self.formatCustomerSelection(customer);
                            },
                            escapeMarkup: function(markup) {
                                return markup;
                            }
                        }).on('select2:select select2:unselect', function(e) {
                            // Update Alpine.js model when Select2 changes
                            self.customer_id = $(this).val() || '';
                        }).on('select2:open', function(e) {
                            // Ensure dropdown appears in the correct position with proper width
                            setTimeout(function() {
                                const $dropdown = $('.select2-dropdown');
                                const $container = $('.customer-select-wrapper');

                                if ($dropdown.length && $container.length) {
                                    const containerWidth = $container.outerWidth();
                                    const minWidth = Math.max(containerWidth, 250);
                                    const maxWidth = Math.min(window.innerWidth - 40, 400);

                                    $dropdown.css({
                                        'z-index': '99999',
                                        'position': 'absolute',
                                        'min-width': minWidth + 'px',
                                        'max-width': maxWidth + 'px',
                                        'width': 'auto'
                                    });
                                }
                            }, 1);
                        });

                        // Set initial value if exists
                        if (this.customer_id) {
                            $select.val(this.customer_id).trigger('change.select2');
                        }

                        console.log('Select2 initialized successfully');
                    } catch (error) {
                        console.error('Error initializing Select2:', error);
                    }
                },

                formatCustomerResult(customer) {
                    if (!customer.id) {
                        return customer.text;
                    }

                    const $option = $(customer.element);
                    const company = $option.data('company');
                    const nama = $option.data('nama');
                    const email = $option.data('email');
                    const phone = $option.data('phone');
                    const address = $option.data('address');

                    let html = '<div class="customer-option">';

                    // Main name/company
                    if (company) {
                        html += `<div class="customer-name" title="${company}">${company}</div>`;
                        if (nama) {
                            html += `<div class="customer-details" title="Kontak: ${nama}">Kontak: ${nama}</div>`;
                        }
                    } else if (nama) {
                        html += `<div class="customer-name" title="${nama}">${nama}</div>`;
                    }

                    // Contact details
                    let contactDetails = [];
                    if (email) contactDetails.push(email);
                    if (phone) contactDetails.push(phone);

                    if (contactDetails.length > 0) {
                        const contactText = contactDetails.join(' • ');
                        html += `<div class="customer-details" title="${contactText}">${contactText}</div>`;
                    }

                    // Address (shortened for display)
                    if (address && address.trim()) {
                        const shortAddress = address.length > 50 ? address.substring(0, 50) + '...' : address;
                        html += `<div class="customer-details" title="${address}">${shortAddress}</div>`;
                    }

                    html += '</div>';

                    return html;
                },

                formatCustomerSelection(customer) {
                    if (!customer.id) {
                        return customer.text;
                    }

                    const $option = $(customer.element);
                    const company = $option.data('company');
                    const nama = $option.data('nama');

                    return company || nama || customer.text;
                },

                fetchTable(page = 1) {
                    this.isLoading = true;

                    // Construct the URL
                    const baseUrl = "{{ route('penjualan.delivery-order.table') }}";
                    const finalUrl = new URL(baseUrl);

                    // Add parameters
                    if (this.search) finalUrl.searchParams.append('search', this.search);
                    if (this.status) finalUrl.searchParams.append('status', this.status);
                    if (this.customer_id) finalUrl.searchParams.append('customer_id', this.customer_id);
                    if (this.gudang_id) finalUrl.searchParams.append('gudang_id', this.gudang_id);
                    if (this.periode) finalUrl.searchParams.append('periode', this.periode);
                    if (this.date_start) finalUrl.searchParams.append('date_start', this.date_start);
                    if (this.date_end) finalUrl.searchParams.append('date_end', this.date_end);
                    if (this.sort_field) finalUrl.searchParams.append('sort_field', this.sort_field);
                    if (this.sort_direction) finalUrl.searchParams.append('sort_direction', this.sort_direction);
                    if (page > 1) finalUrl.searchParams.append('page', page);

                    // Add cache-busting parameter
                    finalUrl.searchParams.append('_', new Date().getTime());

                    let urlToFetch = finalUrl.toString();

                    return fetch(urlToFetch, {
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
                            this.sort_field = data.sort_field || this.sort_field;
                            this.sort_direction = data.sort_direction || this.sort_direction;

                            // Add event listeners to pagination links after DOM update
                            this.$nextTick(() => {
                                this.setupPaginationListeners();
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching table data:', error);
                            this.isLoading = false;
                            this.tableHtml =
                                `<tr><td colspan="8" class="px-5 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-red-50 dark:bg-red-900/20">
                                            <svg class="h-12 w-12 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-base font-medium text-red-500">Error: ${error.message}</p>
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
                        });
                },

                setSorting(field) {
                    // If clicking the same field, toggle direction
                    if (this.sort_field === field) {
                        this.sort_direction = this.sort_direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        // New field, default to ascending
                        this.sort_field = field;
                        this.sort_direction = 'asc';
                    }

                    this.fetchTable();
                },

                applyFilters() {
                    this.fetchTable();
                    this.updateUrlParams();
                },

                resetFilters() {
                    this.search = '';
                    this.status = '';
                    this.customer_id = '';
                    this.gudang_id = '';
                    this.periode = '';
                    this.date_start = '';
                    this.date_end = '';

                    // Reset Select2 dropdown safely
                    try {
                        if (typeof $ !== 'undefined' && $.fn.select2) {
                            const $select = $('#customer-select');
                            if ($select.length && $select.hasClass('select2-hidden-accessible')) {
                                $select.val(null).trigger('change');
                            }
                        }
                    } catch (error) {
                        console.error('Error resetting Select2:', error);
                    }

                    // Fetch with reset values
                    this.fetchTable();
                    this.updateUrlParams();
                },

                updateUrlParams() {
                    // Update URL parameters without reloading the page
                    const url = new URL(window.location);

                    // Set or remove parameters
                    this.setOrRemoveParam(url, 'search', this.search);
                    this.setOrRemoveParam(url, 'status', this.status);
                    this.setOrRemoveParam(url, 'customer_id', this.customer_id);
                    this.setOrRemoveParam(url, 'gudang_id', this.gudang_id);
                    this.setOrRemoveParam(url, 'periode', this.periode);
                    this.setOrRemoveParam(url, 'date_start', this.date_start);
                    this.setOrRemoveParam(url, 'date_end', this.date_end);
                    this.setOrRemoveParam(url, 'sort_field', this.sort_field);
                    this.setOrRemoveParam(url, 'sort_direction', this.sort_direction);

                    // Update URL without page reload
                    window.history.pushState({}, '', url);
                },

                setOrRemoveParam(url, key, value) {
                    if (value) {
                        url.searchParams.set(key, value);
                    } else {
                        url.searchParams.delete(key);
                    }
                },

                setupPaginationListeners() {
                    // Add event listeners to pagination links
                    const paginationLinks = document.querySelectorAll('[x-html="paginationHtml"] a');
                    paginationLinks.forEach(link => {
                        link.addEventListener('click', (e) => {
                            e.preventDefault();
                            const url = new URL(link.href);
                            const page = url.searchParams.get('page');
                            if (page) {
                                this.handlePagination(page);
                            }
                        });
                    });
                },

                handlePagination(page) {
                    // Create URL with current filters and pagination
                    const baseUrl = new URL('/penjualan/delivery-orders/ajax/table', window.location.origin);

                    // Add all current parameters
                    if (this.search) baseUrl.searchParams.append('search', this.search);
                    if (this.status && this.status !== 'semua') baseUrl.searchParams.append('status', this.status);
                    if (this.customer_id) baseUrl.searchParams.append('customer_id', this.customer_id);
                    if (this.gudang_id) baseUrl.searchParams.append('gudang_id', this.gudang_id);
                    if (this.date_start) baseUrl.searchParams.append('date_start', this.date_start);
                    if (this.date_end) baseUrl.searchParams.append('date_end', this.date_end);
                    if (this.periode && this.periode !== 'custom') baseUrl.searchParams.append('periode', this.periode);

                    // Add sorting and pagination
                    baseUrl.searchParams.append('sort_field', this.sort_field);
                    baseUrl.searchParams.append('sort_direction', this.sort_direction);
                    baseUrl.searchParams.append('page', page);

                    // Add cache buster
                    baseUrl.searchParams.append('_', Date.now());

                    this.isLoading = true;

                    fetch(baseUrl.toString(), {
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
                            this.isLoading = false;

                            if (data.error) {
                                this.tableHtml =
                                    `<tr><td colspan="8" class="px-5 py-10 text-center text-red-500">${data.error}</td></tr>`;
                                this.paginationHtml = '';
                                return;
                            }

                            this.tableHtml = data.table_html;
                            this.paginationHtml = data.pagination_html;
                            this.sort_field = data.sort_field || this.sort_field;
                            this.sort_direction = data.sort_direction || this.sort_direction;

                            // Setup listeners again for new pagination links
                            this.$nextTick(() => {
                                this.setupPaginationListeners();
                            });
                        })
                        .catch(error => {
                            console.error('Error in pagination:', error);
                            this.isLoading = false;
                            this.tableHtml =
                                `<tr><td colspan="8" class="px-5 py-10 text-center text-red-500">Error: ${error.message}</td></tr>`;
                            this.paginationHtml = '';
                        });
                },
            };
        }

        // Enhanced DOM ready check with multiple fallbacks
        function initializeWhenReady() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', checkLibrariesAndLog);
            } else {
                checkLibrariesAndLog();
            }
        }

        function checkLibrariesAndLog() {
            console.log('DOM ready, checking libraries...');

            // Check jQuery
            if (typeof $ !== 'undefined') {
                console.log('✓ jQuery is available:', $.fn.jquery);
            } else {
                console.warn('✗ jQuery is not available');
                // Try to load jQuery if not available
                loadJQuery();
                return;
            }

            // Check Select2
            if ($.fn.select2) {
                console.log('✓ Select2 is available');
            } else {
                console.warn('✗ Select2 is not available');
                // Try to load Select2 if not available
                loadSelect2();
                return;
            }
        }

        function loadJQuery() {
            if (typeof $ === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
                script.onload = function() {
                    console.log('jQuery loaded dynamically');
                    loadSelect2();
                };
                script.onerror = function() {
                    console.error('Failed to load jQuery');
                };
                document.head.appendChild(script);
            }
        }

        function loadSelect2() {
            if (typeof $ !== 'undefined' && !$.fn.select2) {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
                script.onload = function() {
                    console.log('Select2 loaded dynamically');
                };
                script.onerror = function() {
                    console.error('Failed to load Select2');
                };
                document.head.appendChild(script);
            }
        }

        // Initialize when ready
        initializeWhenReady();

        // Additional window load fallback
        window.addEventListener('load', function() {
            setTimeout(checkLibrariesAndLog, 500);
        });
    </script>
</x-app-layout>
