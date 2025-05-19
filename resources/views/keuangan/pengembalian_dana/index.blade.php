<x-app-layout> @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <style>
            /* Custom animations */
            @keyframes pulseOnce {
                0% {
                    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 5px rgba(59, 130, 246, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
                }
            }

            .animate-pulse-once {
                animation: pulseOnce 0.5s ease-in-out;
            }

            /* Custom Select2 styling */
            .select2-container--default .select2-selection--single {
                height: 42px;
                padding: 8px 10px;
                border-color: rgb(209 213 219);
                border-radius: 0.5rem;
                line-height: 1.5rem;
            }

            /* Custom Flatpickr styling */
            .flatpickr-calendar {
                border-radius: 0.5rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                border: 1px solid rgb(229 231 235);
            }

            .flatpickr-day.selected,
            .flatpickr-day.startRange,
            .flatpickr-day.endRange {
                background: #3b82f6;
                border-color: #3b82f6;
            }

            .flatpickr-day.today {
                border-color: #3b82f6;
            }

            /* Sortable table headers */
            .sortable {
                cursor: pointer;
            }

            .sort-icon {
                opacity: 0.5;
                transition: transform 0.2s ease;
            }

            .sortable:hover .sort-icon {
                opacity: 1;
            }

            .sort-asc .sort-icon {
                opacity: 1;
                transform: rotate(180deg);
            }

            .sort-desc .sort-icon {
                opacity: 1;
            }

            /* Focus effect for form controls */
            input:focus,
            select:focus {
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
                border-color: #3b82f6;
            }

            /* Enhanced input animations */
            input,
            .select2-container--default .select2-selection--single {
                transition: all 0.2s ease-in-out;
            }

            input:hover:not(:focus),
            .select2-container--default .select2-selection--single:hover {
                border-color: #93c5fd;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 42px;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: rgb(31 41 55);
                border-color: rgb(75 85 99);
                color: white;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: rgb(229 231 235);
                line-height: 1.5rem;
            }

            .dark .select2-dropdown {
                background-color: rgb(31 41 55);
                border-color: rgb(75 85 99);
                color: white;
            }

            .dark .select2-search__field {
                background-color: rgb(55 65 81);
                color: white;
                border-color: rgb(75 85 99) !important;
            }

            .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: rgb(59 130 246);
            }

            .dark .select2-container--default .select2-results__option[aria-selected=true] {
                background-color: rgb(107 114 128);
            }
        </style>
    @endpush

    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 h-12 w-1.5 rounded-full mr-3">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Pengembalian Dana
                            Supplier
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Dokumentasi pengembalian dana yang diterima dari supplier <span
                                class="text-gray-500 dark:text-gray-500">(data bersifat final dan tidak dapat
                                diubah)</span>
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('keuangan.pengembalian-dana.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-blue-500 hover:to-blue-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Tambah Pengembalian Dana</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Dashboard</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="#"
                        class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Keuangan</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </li>
                <li class="text-gray-800 dark:text-gray-100">
                    Pengembalian Dana Supplier
                </li>
            </ol>
        </nav>

        {{-- Main Content --}}
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if (session('success'))
                    <div
                        class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 p-4 mb-6 rounded-r-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-600 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 dark:text-green-300">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-4 mb-6 rounded-r-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <div
                        class="p-3 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-400 rounded-r-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Catatan: Dokumen pengembalian dana yang sudah direkam tidak dapat diubah atau
                                    dihapus karena terkait dengan data keuangan perusahaan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div
                    class="mb-6 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Header section with title -->
                    <div
                        class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-medium text-blue-800 dark:text-blue-300 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter Data Pengembalian Dana
                        </h3>
                    </div>

                    <!-- Filter content -->
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <!-- Date range filter -->
                            <div class="space-y-2">
                                <label for="date_range"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Rentang Tanggal
                                    </div>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="date_range"
                                        class="flatpickr block w-full pl-10 pr-10 py-2.5 h-[42px] rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-300 dark:focus:ring-blue-600 dark:focus:border-blue-500 transition-all duration-200"
                                        placeholder="Pilih rentang tanggal">
                                </div>
                            </div>

                            <!-- Supplier filter -->
                            <div class="space-y-2">
                                <label for="supplier_filter"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Supplier
                                    </div>
                                </label>
                                <select id="supplier_filter"
                                    class="select2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-300 dark:focus:ring-blue-600 dark:focus:border-blue-500 transition-all duration-200">
                                    <option value="">Semua Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Quick search -->
                            <div class="space-y-2">
                                <label for="search_filter"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Pencarian Cepat
                                    </div>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="search_filter"
                                        class="block w-full pl-10 pr-4 py-2.5 h-[42px] rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-300 dark:focus:ring-blue-600 dark:focus:border-blue-500 transition-all duration-200"
                                        placeholder="Cari nomor, supplier, atau PO">
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none opacity-0">
                                        <!-- Removed "Type to search" text -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div
                            class="flex md:justify-end mt-6 space-x-3 border-t border-gray-100 dark:border-gray-700 pt-5">
                            <div class="flex gap-3 w-full md:w-auto">
                                <button type="button" id="reset_filter"
                                    class="w-full md:w-auto px-4 py-2.5 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-all duration-200 flex items-center justify-center border border-gray-300 dark:border-gray-600 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Reset Filter
                                </button>
                                <button type="button" id="apply_filter"
                                    class="w-full md:w-auto px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white rounded-lg transition-all duration-200 flex items-center justify-center shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pengembalian-table-container"
                    class="overflow-x-auto ring-1 ring-gray-200 dark:ring-gray-700 rounded-xl shadow-md">
                    <table id="pengembalian-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/90 dark:to-gray-700/80">
                            <tr>
                                <th scope="col"
                                    class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No.
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable"
                                    data-sort="nomor">
                                    <div class="flex items-center cursor-pointer">
                                        Nomor
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 sort-icon"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable"
                                    data-sort="tanggal">
                                    <div class="flex items-center cursor-pointer">
                                        Tanggal
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 sort-icon"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable"
                                    data-sort="supplier">
                                    <div class="flex items-center cursor-pointer">
                                        Supplier
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 sort-icon"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No. PO
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sortable"
                                    data-sort="jumlah">
                                    <div class="flex items-center cursor-pointer">
                                        Jumlah
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 sort-icon"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Metode
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Akun
                                </th>
                                <th scope="col"
                                    class="px-4 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                            id="pengembalian-data">
                            <!-- Table data will be loaded here via AJAX -->
                        </tbody>
                    </table>
                    <div id="loading-indicator" class="py-10 flex flex-col items-center justify-center"
                        style="display: none;">
                        <div class="relative flex">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                            <div
                                class="animate-spin rounded-full h-10 w-10 border-r-2 border-blue-600 absolute opacity-40">
                            </div>
                            <div
                                class="animate-spin rounded-full h-10 w-10 border-t-2 border-blue-600 absolute opacity-20 delay-150">
                            </div>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400 mt-3">Memuat data...</span>
                    </div>
                    <div id="empty-state" class="hidden">
                        <div class="px-4 py-10 text-sm text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="rounded-full bg-gray-100 dark:bg-gray-800/80 p-4 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm2.5 1h5a.5.5 0 01.5.5v.5a.5.5 0 01-.5.5h-5a.5.5 0 01-.5-.5V5.5a.5.5 0 01.5-.5zm5.5 9a.5.5 0 01-.5.5h-5a.5.5 0 01-.5-.5V12a.5.5 0 01.5-.5h5a.5.5 0 01.5.5v2.5zM5.5 9h9a.5.5 0 01.5.5v.5a.5.5 0 01-.5.5h-9a.5.5 0 01-.5-.5V9.5a.5.5 0 01.5-.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <p class="text-lg font-medium mb-2">Belum ada data pengembalian dana</p>
                                <p class="text-sm text-gray-400 max-w-sm mx-auto">Klik tombol "Tambah Pengembalian
                                    Dana"
                                    untuk memulai pencatatan dana yang dikembalikan oleh supplier</p>
                                <a href="{{ route('keuangan.pengembalian-dana.create') }}"
                                    class="mt-5 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Buat Pengembalian Dana Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-center" id="pagination-container">
                    <!-- Pagination will be loaded here -->
                </div>

                <!-- Data summary -->
                <div class="mt-4 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 px-1">
                    <div id="data-summary" class="hidden">
                        <span id="showing-entries">Menampilkan 1-10 dari 0 entri</span>
                    </div>
                    <div class="ml-auto">
                        <span id="last-updated" class="italic">Terakhir diperbarui:
                            <time>{{ now()->format('d M Y, H:i') }}</time></span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium text-gray-900 dark:text-white">Catatan:</span> Dokumen pengembalian
                        dana yang sudah tercatat merupakan dokumen resmi keuangan dan bersifat final. Untuk keperluan
                        koreksi atau pembatalan, mohon hubungi administrator keuangan.
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('.select2').select2({
                    placeholder: "Pilih supplier",
                    allowClear: true,
                    width: '100%',
                    selectionCssClass: 'py-2 h-[42px]',
                    dropdownCssClass: 'text-sm rounded-lg shadow-md border-gray-300'
                });

                // Initialize Flatpickr for date range
                const flatpickrInstance = flatpickr("#date_range", {
                    mode: "range",
                    dateFormat: "d/m/Y",
                    altInput: true,
                    altFormat: "d M Y",
                    locale: {
                        rangeSeparator: ' - '
                    },
                    placeholder: "Pilih rentang tanggal",
                    disableMobile: "true"
                });

                // Variables for sorting
                let currentSort = {
                    field: 'tanggal',
                    direction: 'desc'
                };

                // Show active filters indicator function
                function updateFilterIndicators() {
                    const dateRange = $('#date_range').val();
                    const supplierId = $('#supplier_filter').val();
                    const searchQuery = $('#search_filter').val();

                    let activeFilters = 0;

                    // Count active filters
                    if (dateRange) activeFilters++;
                    if (supplierId) activeFilters++;
                    if (searchQuery) activeFilters++;

                    // Add visual indicator to filter section
                    if (activeFilters > 0) {
                        $('.filter-badge').remove();
                        $('h3:contains("Filter Data Pengembalian Dana")').append(
                            `<span class="filter-badge inline-flex items-center justify-center ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 rounded-full">${activeFilters} aktif</span>`
                        );
                    } else {
                        $('.filter-badge').remove();
                    }
                }

                // Apply visual feedback to inputs
                function applyInputFeedback() {
                    // Date range input
                    if ($('#date_range').val()) {
                        $('#date_range').addClass('border-blue-500 dark:border-blue-400').addClass(
                        'animate-pulse-once');
                        setTimeout(() => {
                            $('#date_range').removeClass('animate-pulse-once');
                        }, 500);
                    } else {
                        $('#date_range').removeClass('border-blue-500 dark:border-blue-400');
                    }

                    // Apply similar visual feedback for other inputs
                    if ($('#supplier_filter').val()) {
                        const $selection = $('#supplier_filter').next('.select2-container').find('.select2-selection');
                        $selection.addClass('border-blue-500 dark:border-blue-400 animate-pulse-once');
                        setTimeout(() => {
                            $selection.removeClass('animate-pulse-once');
                        }, 500);
                    } else {
                        $('#supplier_filter').next('.select2-container').find('.select2-selection').removeClass(
                            'border-blue-500 dark:border-blue-400');
                    }

                    if ($('#search_filter').val()) {
                        $('#search_filter').addClass('border-blue-500 dark:border-blue-400 animate-pulse-once');
                        setTimeout(() => {
                            $('#search_filter').removeClass('animate-pulse-once');
                        }, 500);
                    } else {
                        $('#search_filter').removeClass('border-blue-500 dark:border-blue-400');
                    }
                }

                // Click event for sortable headers with improved visual feedback
                $('.sortable').on('click', function() {
                    const sortField = $(this).data('sort');

                    // Add visual indication of clicked state
                    $(this).addClass('bg-blue-50 dark:bg-blue-900/20');
                    setTimeout(() => {
                        $(this).removeClass('bg-blue-50 dark:bg-blue-900/20');
                    }, 150);

                    // Toggle direction if clicking the same column
                    if (currentSort.field === sortField) {
                        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        currentSort.field = sortField;
                        currentSort.direction = 'asc';
                    }

                    // Update sort indicators
                    $('.sortable').removeClass('sort-asc sort-desc');
                    $(this).addClass(currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc');

                    // Client-side sorting (will be applied after data is fetched)
                    sortAndRenderData();
                });

                // Load initial data
                loadPengembalianData();

                // Apply filter button click with visual feedback
                $('#apply_filter').on('click', function() {
                    // Add visual feedback for button click
                    const $btn = $(this);
                    $btn.addClass('transform scale-95').attr('disabled', true);
                    setTimeout(() => {
                        $btn.removeClass('transform scale-95').attr('disabled', false);
                    }, 300);

                    updateFilterIndicators();
                    applyInputFeedback();
                    loadPengembalianData();
                });

                // Search input key-up with debounce
                let searchTimer;
                $('#search_filter').on('keyup', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(function() {
                        updateFilterIndicators();
                        applyInputFeedback();
                        loadPengembalianData();
                    }, 500); // 500ms delay
                });

                // Input change event handlers for visual feedback
                $('#date_range').on('change', function() {
                    applyInputFeedback();
                });

                $('#supplier_filter').on('change', function() {
                    applyInputFeedback();
                });

                // Reset filter button click with smooth animation
                $('#reset_filter').on('click', function() {
                    // Add visual feedback for button click
                    const $btn = $(this);
                    $btn.addClass('transform scale-95').attr('disabled', true);

                    // Add animation for resetting filters
                    $('#date_range, #search_filter').addClass('transition-all duration-500');
                    $('#supplier_filter').next('.select2-container').find('.select2-selection').addClass(
                        'transition-all duration-500');

                    setTimeout(() => {
                        // Reset flatpickr date picker
                        flatpickrInstance.clear();

                        // Reset supplier select dropdown (need to use Select2 specific method)
                        $('#supplier_filter').val(null).trigger('change');

                        // Reset search filter
                        $('#search_filter').val('');

                        $btn.removeClass('transform scale-95').attr('disabled', false);
                        // Remove filter badge
                        $('.filter-badge').remove();

                        // Reset visual feedback
                        applyInputFeedback();

                        // Reload data immediately after resetting filters
                        loadPengembalianData(1);
                    }, 300);
                });

                // Current data cache
                let currentData = [];
                let currentStartIndex = 0;

                // Function to load data via AJAX
                function loadPengembalianData(page = 1) {
                    $('#loading-indicator').show();
                    $('#pengembalian-data').empty();
                    $('#empty-state').addClass('hidden');

                    // Get filter values
                    const dateRange = $('#date_range').val();
                    const supplierId = $('#supplier_filter').val();
                    const searchQuery = $('#search_filter').val();

                    $.ajax({
                        url: '{{ route('keuangan.pengembalian-dana.data') }}',
                        type: 'GET',
                        data: {
                            date_range: dateRange,
                            supplier_id: supplierId,
                            search_query: searchQuery,
                            page: page
                        },
                        success: function(response) {
                            $('#loading-indicator').hide();

                            // Update data summary
                            $('#data-summary').removeClass('hidden');
                            $('#showing-entries').text(
                                `Menampilkan ${response.from || 0}-${response.to || 0} dari ${response.total} entri`
                            );
                            $('#last-updated time').text(new Date().toLocaleString('id-ID', {
                                day: 'numeric',
                                month: 'short',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            }));

                            if (response.data.length === 0) {
                                $('#empty-state').removeClass('hidden');
                            } else {
                                // Cache the data for sorting
                                currentData = response.data;
                                currentStartIndex = response.from;

                                // Sort and render the data
                                sortAndRenderData();
                            }

                            // Render pagination
                            $('#pagination-container').html(response.pagination);

                            // Add event listeners for pagination links
                            $('#pagination-container a').on('click', function(e) {
                                e.preventDefault();
                                const href = $(this).attr('href');
                                const pageNum = href.split('page=')[1];
                                loadPengembalianData(pageNum);
                            });
                        },
                        error: function(xhr) {
                            $('#loading-indicator').hide();
                            alert('Terjadi kesalahan saat memuat data.');
                            console.error(xhr);
                        }
                    });
                }

                // Function to sort and render data
                function sortAndRenderData() {
                    if (currentData.length === 0) return;

                    // Clone the data for sorting
                    let sortedData = [...currentData];

                    // Apply sorting based on current sort settings
                    sortedData.sort((a, b) => {
                        let valueA, valueB;

                        switch (currentSort.field) {
                            case 'nomor':
                                valueA = a.nomor;
                                valueB = b.nomor;
                                break;
                            case 'tanggal':
                                valueA = new Date(a.tanggal);
                                valueB = new Date(b.tanggal);
                                break;
                            case 'supplier':
                                valueA = a.supplier_nama;
                                valueB = b.supplier_nama;
                                break;
                            case 'jumlah':
                                valueA = a.jumlah;
                                valueB = b.jumlah;
                                break;
                            default:
                                valueA = a.tanggal;
                                valueB = b.tanggal;
                        }

                        if (valueA < valueB) {
                            return currentSort.direction === 'asc' ? -1 : 1;
                        }
                        if (valueA > valueB) {
                            return currentSort.direction === 'asc' ? 1 : -1;
                        }
                        return 0;
                    });

                    // Render the sorted data
                    renderTable(sortedData, currentStartIndex);
                }

                // Function to render table data
                function renderTable(data, startIndex) {
                    let html = '';

                    data.forEach((refund, index) => {
                        html += `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150">
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ${startIndex + index}
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <span class="font-semibold">${refund.nomor}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ${refund.tanggal_formatted}
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="text-gray-700 dark:text-gray-300">${refund.supplier_nama}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="text-gray-700 dark:text-gray-300">${refund.purchase_order_nomor}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">${refund.jumlah_formatted}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full shadow-sm
                                ${refund.metode_penerimaan === 'kas' 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 border border-green-200 dark:border-green-800' 
                                    : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800'}">
                                    ${refund.metode_penerimaan.charAt(0).toUpperCase() + refund.metode_penerimaan.slice(1)}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <span class="text-gray-700 dark:text-gray-300">${refund.akun}</span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="${refund.detail_url}"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                                        title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" class="w-4 h-4">
                                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                            <path fill-rule="evenodd"
                                                d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="${refund.print_url}"
                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                                        title="Cetak">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a1 1 0 001 1h8a1 1 0 001-1v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a1 1 0 00-1-1H6a1 1 0 00-1 1zm8 2H7V5h6v1zm-3 11H7v-5h3v5zm4-5h1v3h-1v-3zm-6 0H7V9h1v3z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                    });

                    $('#pengembalian-data').html(html);
                }
            });
        </script>
    @endpush
</x-app-layout>
