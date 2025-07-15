@php
    // Helper function to get status color
    function returStatusColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'gray';
            case 'menunggu_persetujuan':
                return 'yellow';
            case 'disetujui':
                return 'green';
            case 'ditolak':
                return 'red';
            case 'diproses':
                return 'blue';
            case 'menunggu_barang_pengganti':
                return 'amber';
            case 'selesai':
                return 'emerald';
            default:
                return 'primary';
        }
    }

    // Helper function for status icon
    function returStatusIcon($status)
    {
        switch ($status) {
            case 'draft':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>';
            case 'menunggu_persetujuan':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            case 'disetujui':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            case 'ditolak':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            case 'diproses':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>';
            case 'menunggu_barang_pengganti':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
            case 'selesai':
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            default:
                return '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        }
    }

    // Helper function to format status text to display version (without underscores)
    function formatStatusText($status)
    {
        switch ($status) {
            case 'draft':
                return 'Draft';
            case 'menunggu_persetujuan':
                return 'Menunggu Persetujuan';
            case 'disetujui':
                return 'Disetujui';
            case 'ditolak':
                return 'Ditolak';
            case 'diproses':
                return 'Diproses';
            case 'menunggu_barang_pengganti':
                return 'Menunggu Barang Pengganti';
            case 'selesai':
                return 'Selesai';
            case 'semua':
                return 'Semua';
            default:
                return ucfirst($status);
        }
    }
@endphp

<x-app-layout>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Modern Select2 styling */
        .select2-container {
            width: 100% !important;
        }

        /* Make dropdown wider */
        .select2-dropdown {
            /* width: auto !important; */
            min-width: 100px !important;
            /* Increased minimum width */
            border-color: #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        /* Additional class for even wider dropdown when needed */
        .select2-dropdown-wider {
            min-width: 100px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            background-color: #ffffff;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: #1f2937;
            border-color: #374151;
            color: #ffffff;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #cbd5e1;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.5);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937;
            line-height: 1.25rem;
            padding-left: 0;
            padding-right: 20px;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f3f4f6;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 5px;
        }

        .select2-dropdown {
            border-color: #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .dark .select2-dropdown {
            background-color: #1f2937;
            border-color: #374151;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: #e2e8f0;
            border-radius: 0.375rem;
        }

        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #374151;
            border-color: #4b5563;
            color: #f3f4f6;
        }

        .dark .select2-results__option {
            color: #f3f4f6;
        }

        .dark .select2-results__option[aria-selected="true"] {
            background-color: #374151;
        }

        /* Sortable table styles */
        .sortable {
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .sortable:hover {
            background-color: rgba(243, 244, 246, 0.6);
        }

        .dark .sortable:hover {
            background-color: rgba(55, 65, 81, 0.6);
        }

        .sort-icon {
            transition: transform 0.2s ease;
        }

        .sort-asc .sort-icon {
            opacity: 1 !important;
            transform: rotate(180deg);
        }

        .sort-desc .sort-icon {
            opacity: 1 !important;
        }

        .sortable-active {
            background-color: rgba(219, 234, 254, 0.4);
        }

        .dark .sortable-active {
            background-color: rgba(30, 58, 138, 0.4);
        }

        /* Modern Card Hover Effects */
        .status-card {
            transform: translateY(0);
            transition: all 0.2s ease-in-out;
        }

        .status-card:hover {
            transform: translateY(-2px);
        }

        /* Text truncation for card titles */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Responsive grid adjustments */
        @media (max-width: 640px) {
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .xl\:grid-cols-7 {
                grid-template-columns: repeat(7, minmax(0, 1fr));
            }
        }
    </style>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Stats and Summary --}}
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Retur Penjualan</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola pengembalian barang dari customer
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if (auth()->user()->hasPermission('retur_penjualan.create'))
                        <a href="{{ route('penjualan.retur.create') }}"
                            class="group inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg font-semibold text-sm text-white shadow-sm hover:from-primary-700 hover:to-primary-800 active:from-primary-800 active:to-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200">
                            <span
                                class="relative flex h-5 w-5 items-center justify-center rounded-md bg-white/20 group-hover:bg-white/30 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            <span>Tambah Retur</span>
                        </a>
                    @endif

                    <a href="{{ route('penjualan.retur.analyze') }}"
                        class="group inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 rounded-lg font-semibold text-sm text-white shadow-sm hover:from-green-700 hover:to-green-800 active:from-green-800 active:to-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200">
                        <span
                            class="relative flex h-5 w-5 items-center justify-center rounded-md bg-white/20 group-hover:bg-white/30 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </span>
                        <span>Analisis Retur</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Modern Compact Status Cards --}}
        <div class="mb-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">
            @foreach ($validStatuses as $status)
                <div
                    class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-{{ returStatusColor($status) }}-200 dark:hover:border-{{ returStatusColor($status) }}-700 transition-all duration-200">
                    <!-- Gradient Background Accent -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-{{ returStatusColor($status) }}-50 to-transparent dark:from-{{ returStatusColor($status) }}-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    </div>

                    <div class="relative p-3">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-lg bg-{{ returStatusColor($status) }}-100 dark:bg-{{ returStatusColor($status) }}-900/30 group-hover:bg-{{ returStatusColor($status) }}-200 dark:group-hover:bg-{{ returStatusColor($status) }}-900/50 transition-colors duration-200">
                                <span
                                    class="w-4 h-4 text-{{ returStatusColor($status) }}-600 dark:text-{{ returStatusColor($status) }}-400">
                                    {!! returStatusIcon($status) !!}
                                </span>
                            </div>
                            <span
                                class="text-xs font-medium text-{{ returStatusColor($status) }}-600 dark:text-{{ returStatusColor($status) }}-400 bg-{{ returStatusColor($status) }}-100 dark:bg-{{ returStatusColor($status) }}-900/30 px-2 py-1 rounded-full">
                                {{ $statusCounts[$status] ?? 0 }}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xs font-medium text-gray-900 dark:text-white line-clamp-2 leading-tight">
                                {{ formatStatusText($status) }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $statusCounts[$status] == 1 ? 'item' : 'items' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Total Summary Card -->
            <div
                class="group relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg shadow-sm border border-blue-200 dark:border-blue-700 hover:shadow-md transition-all duration-200">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                </div>

                <div class="relative p-3">
                    <div class="flex items-center justify-between mb-2">
                        <div
                            class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-200 dark:bg-blue-800 group-hover:bg-blue-300 dark:group-hover:bg-blue-700 transition-colors duration-200">
                            <svg class="w-4 h-4 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <span
                            class="text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-200 dark:bg-blue-800 px-2 py-1 rounded-full">
                            {{ array_sum($statusCounts) }}
                        </span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-semibold text-blue-900 dark:text-blue-100">
                            Total Retur
                        </h3>
                        <p class="text-xs text-blue-700 dark:text-blue-300">
                            Semua status
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Enhanced Tab Navigation --}}
        <div class="mb-4">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex overflow-x-auto">
                    @foreach (array_merge(['semua'], $validStatuses) as $status)
                        <a href="{{ route('penjualan.retur.index', ['status' => $status]) }}"
                            class="flex items-center gap-2 whitespace-nowrap py-2.5 px-3 border-b-2 font-medium text-sm transition-colors duration-200 
                                {{ $currentStatus === $status
                                    ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                            <span class="flex-shrink-0 w-4 h-4 text-{{ returStatusColor($status) }}-500">
                                {!! returStatusIcon($status) !!}
                            </span>
                            <span>{{ formatStatusText($status) }}</span>
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 ml-1 text-xs font-medium text-gray-500 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                {{ $statusCounts[$status] ?? ($status === 'semua' ? array_sum($statusCounts) : 0) }}
                            </span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Enhanced Content Card --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden"
            x-data="returPenjualanTableManager()">

            {{-- Enhanced Filter Section --}}
            <div class="px-4 sm:px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                <form @submit.prevent="applyFilters"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:flex lg:flex-wrap gap-3 items-end">
                    <div class="w-full lg:flex-grow max-w-lg space-y-1">
                        <label for="search-input"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Pencarian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="search-input" x-model="search"
                                placeholder="Cari nomor, customer, SO..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        </div>
                    </div>

                    <div class="w-full sm:w-auto space-y-1">
                        <label for="date-filter"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Periode</label>
                        <select id="date-filter" x-model="dateFilter"
                            class="block w-full pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">Semua Tanggal</option>
                            <option value="today">Hari Ini</option>
                            <option value="yesterday">Kemarin</option>
                            <option value="this_week">Minggu Ini</option>
                            <option value="last_week">Minggu Lalu</option>
                            <option value="this_month">Bulan Ini</option>
                            <option value="last_month">Bulan Lalu</option>
                            <option value="range">Range Tanggal</option>
                        </select>
                    </div>

                    <template x-if="dateFilter === 'range'">
                        <div class="col-span-1 sm:col-span-2 md:col-span-1 flex items-end gap-2 w-full">
                            <div class="space-y-1 flex-1">
                                <label for="date-start"
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">Mulai</label>
                                <input type="date" id="date-start" x-model="dateStart"
                                    class="block w-full pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            </div>
                            <div class="self-center pt-5">—</div>
                            <div class="space-y-1 flex-1">
                                <label for="date-end"
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-300">Hingga</label>
                                <input type="date" id="date-end" x-model="dateEnd"
                                    class="block w-full pl-3 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            </div>
                        </div>
                    </template>

                    <div class="w-full sm:w-64 md:w-80 space-y-1">
                        <label for="customer-filter"
                            class="block text-xs font-medium text-gray-700 dark:text-gray-300">Customer</label>
                        <select id="customer-filter" x-model="customerId"
                            class="select2-customer-filter w-full pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            <option value="">Semua Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->company ?? $customer->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto lg:ml-auto">
                        <button type="submit"
                            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                        <button type="button" @click="resetFilters"
                            class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium text-sm rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Reset
                        </button>
                    </div>
                </form>
            </div>

            {{-- Loading State --}}
            <div x-show="loading" class="flex items-center justify-center p-8 bg-white dark:bg-gray-800">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600">
                        <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    <span class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300">Memuat data...</span>
                </div>
            </div>

            <div class="min-w-full align-middle overflow-hidden">
                <div id="table-content" class="overflow-x-auto">
                    <div x-show="!tableHtml">
                        @if (!request()->ajax())
                            @include('penjualan.retur_penjualan._table')
                        @endif
                    </div>
                    <div x-html="tableHtml" x-show="tableHtml"></div>
                </div>

                <div id="retur-pagination-container" class="p-4 border-t border-gray-100 dark:border-gray-700">
                    <div x-show="!paginationHtml">
                        @if (!request()->ajax())
                            {{ $returPenjualan->appends(request()->except('page'))->links('vendor.pagination.tailwind-custom') }}
                        @endif
                    </div>
                    <div x-html="paginationHtml" x-show="paginationHtml"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Select2 JS -->
        <!-- jQuery (required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function returPenjualanTableManager() {
                return {
                    tab: '{{ $currentStatus }}',
                    search: @json($search ?? ''),
                    dateFilter: @json($dateFilter ?? ''),
                    dateStart: @json($dateStart ?? ''),
                    dateEnd: @json($dateEnd ?? ''),
                    customerId: @json($customerId ?? ''),
                    loading: false,
                    tableHtml: '',
                    paginationHtml: '',
                    currentSort: {
                        field: 'tanggal', // Default sort field
                        direction: 'desc' // Default sort direction
                    },
                    init() {
                        // Initialize Select2 for customer dropdown
                        $(document).ready(() => {
                            $('.select2-customer-filter').select2({
                                placeholder: 'Pilih Customer',
                                allowClear: true,
                                width: '100%',
                                dropdownCssClass: 'select2-dropdown-wider'
                            });

                            // Sync Select2 with Alpine.js
                            $('.select2-customer-filter').on('change', (e) => {
                                this.customerId = e.target.value;
                            });

                            // Ensure Alpine.js updates Select2
                            this.$watch('customerId', (value) => {
                                if ($('.select2-customer-filter').val() !== value) {
                                    $('.select2-customer-filter').val(value).trigger('change');
                                }
                            });
                        });

                        // Initial table HTML already rendered server-side
                        this.attachPaginationListener();
                        this.attachSortableListeners();

                        // Listen for back/forward browser navigation to update filters
                        window.addEventListener('popstate', (event) => {
                            if (event.state) {
                                this.updateFiltersFromUrl();
                                this.fetchTable(false); // Don't push state on popstate
                            }
                        });

                        // Initialize filters from URL if they exist
                        this.updateFiltersFromUrl();
                    },
                    updateFiltersFromUrl() {
                        const url = new URL(window.location.href);
                        this.search = url.searchParams.get('search') || '';
                        this.dateFilter = url.searchParams.get('date_filter') || '';
                        this.dateStart = url.searchParams.get('date_start') || '';
                        this.dateEnd = url.searchParams.get('date_end') || '';
                        this.customerId = url.searchParams.get('customer_id') || '';
                        this.tab = url.searchParams.get('status') || '{{ $currentStatus }}';

                        // Also get sort parameters if available
                        const sortField = url.searchParams.get('sort_by') || 'tanggal';
                        const sortDirection = url.searchParams.get('sort_dir') || 'desc';
                        this.currentSort = {
                            field: sortField,
                            direction: sortDirection
                        };
                    },
                    applyFilters() {
                        this.loading = true;
                        this.fetchTable(true);
                    },
                    resetFilters() {
                        this.search = '';
                        this.dateFilter = '';
                        this.dateStart = '';
                        this.dateEnd = '';
                        this.customerId = '';
                        this.applyFilters();
                    },
                    handlePaginationClick(url) {
                        // Prevent default link behavior and handle pagination with current filters
                        this.loading = true;
                        
                        // Extract page number from URL
                        const urlObj = new URL(url);
                        const page = urlObj.searchParams.get('page') || '1';
                        
                        // Build params object with current filters and page
                        const paramsObj = {
                            status: this.tab,
                            page: page,
                            ajax: '1',
                            sort_by: this.currentSort.field,
                            sort_dir: this.currentSort.direction
                        };

                        if (this.search) paramsObj.search = this.search;
                        if (this.dateFilter) paramsObj.date_filter = this.dateFilter;
                        if (this.dateStart) paramsObj.date_start = this.dateStart;
                        if (this.dateEnd) paramsObj.date_end = this.dateEnd;
                        if (this.customerId) paramsObj.customer_id = this.customerId;

                        const params = new URLSearchParams(paramsObj);

                        // Update URL with page number
                        const newUrl = `{{ route('penjualan.retur.index') }}?${params.toString().replace('ajax=1&', '')}`;
                        window.history.pushState({ filters: paramsObj }, '', newUrl);

                        fetch(`{{ route('penjualan.retur.index') }}?${params}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
                                }

                                const contentType = response.headers.get('content-type');
                                if (!contentType || !contentType.includes('application/json')) {
                                    return response.text().then(text => {
                                        console.error('Unexpected response format:', text.substring(0, 500) + '...');
                                        throw new Error(`Expected JSON response but got ${contentType || 'unknown content type'}`);
                                    });
                                }

                                return response.json();
                            })
                            .then(data => {
                                if (!data || !data.html) {
                                    throw new Error('Received invalid data format from server');
                                }
                                this.tableHtml = data.html;
                                this.paginationHtml = data.pagination;
                                this.attachPaginationListener();
                                this.attachSortableListeners();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert(`Terjadi kesalahan saat memuat data: ${error.message}`);
                                this.loading = false;
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    },
                    attachSortableListeners() {
                        this.$nextTick(() => {
                            const sortableHeaders = document.querySelectorAll('.sortable');
                            sortableHeaders.forEach(header => {
                                header.addEventListener('click', () => {
                                    const sortField = header.getAttribute('data-sort');
                                    if (!sortField) return;

                                    // Add visual indication of clicked state
                                    header.classList.add('bg-blue-50', 'dark:bg-blue-900/20');
                                    setTimeout(() => {
                                        header.classList.remove('bg-blue-50',
                                            'dark:bg-blue-900/20');
                                    }, 150);

                                    // Toggle direction if clicking the same column
                                    if (this.currentSort.field === sortField) {
                                        this.currentSort.direction = this.currentSort.direction ===
                                            'asc' ? 'desc' : 'asc';
                                    } else {
                                        this.currentSort.field = sortField;
                                        this.currentSort.direction = 'asc';
                                    }

                                    // Update sort indicators
                                    sortableHeaders.forEach(h => {
                                        h.classList.remove('sort-asc', 'sort-desc',
                                            'sortable-active');
                                    });

                                    header.classList.add(
                                        this.currentSort.direction === 'asc' ? 'sort-asc' :
                                        'sort-desc',
                                        'sortable-active'
                                    );

                                    // Fetch sorted data
                                    this.loading = true;
                                    this.fetchTable(true);
                                });
                            });

                            // Apply current sort indicator on initial load or after AJAX update
                            if (this.currentSort.field) {
                                const activeHeader = document.querySelector(
                                    `.sortable[data-sort="${this.currentSort.field}"]`);
                                if (activeHeader) {
                                    sortableHeaders.forEach(h => {
                                        h.classList.remove('sort-asc', 'sort-desc', 'sortable-active');
                                    });

                                    activeHeader.classList.add(
                                        this.currentSort.direction === 'asc' ? 'sort-asc' : 'sort-desc',
                                        'sortable-active'
                                    );
                                }
                            }
                        });
                    },
                    fetchTable(updateUrl = true) {
                        // Build params object, only include non-empty values
                        const paramsObj = {
                            status: this.tab,
                            ajax: '1',
                            sort_by: this.currentSort.field,
                            sort_dir: this.currentSort.direction
                        };

                        if (this.search) paramsObj.search = this.search;
                        if (this.dateFilter) paramsObj.date_filter = this.dateFilter;
                        if (this.dateStart) paramsObj.date_start = this.dateStart;
                        if (this.dateEnd) paramsObj.date_end = this.dateEnd;
                        if (this.customerId) paramsObj.customer_id = this.customerId;

                        const params = new URLSearchParams(paramsObj);

                        // Update browser URL to maintain state and allow bookmarking
                        if (updateUrl) {
                            const newUrl = `{{ route('penjualan.retur.index') }}?${params.toString().replace('ajax=1&', '')}`;
                            window.history.pushState({
                                filters: paramsObj
                            }, '', newUrl);
                        }

                        fetch(`{{ route('penjualan.retur.index') }}?${params}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(
                                        `Network response was not ok: ${response.status} ${response.statusText}`);
                                }

                                const contentType = response.headers.get('content-type');
                                if (!contentType || !contentType.includes('application/json')) {
                                    return response.text().then(text => {
                                        console.error('Unexpected response format:', text.substring(0, 500) +
                                            '...');
                                        throw new Error(
                                            `Expected JSON response but got ${contentType || 'unknown content type'}`
                                        );
                                    });
                                }

                                return response.json();
                            })
                            .then(data => {
                                if (!data || !data.html) {
                                    throw new Error('Received invalid data format from server');
                                }
                                this.tableHtml = data.html;
                                this.paginationHtml = data.pagination;
                                this.attachPaginationListener();
                                this.attachSortableListeners();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                let errorMessage = error.message;

                                // Try to extract more details if it's a response error
                                if (error.response && error.response.json) {
                                    error.response.json().then(data => {
                                        console.error('Server error details:', data);
                                        if (data.message) {
                                            errorMessage = data.message;
                                        }
                                    }).catch(() => {
                                        // If we can't parse the JSON, just use the original error
                                    });
                                }

                                alert(`Terjadi kesalahan saat memuat data: ${errorMessage}`);
                                // Reset loading state but don't update table on error
                                this.loading = false;
                            })
                            .finally(() => {
                                this.loading = false;
                            });
                    },
                    attachPaginationListener() {
                        this.$nextTick(() => {
                            const paginationLinks = document.querySelectorAll('#retur-pagination-container a');
                            paginationLinks.forEach(link => {
                                link.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    const url = new URL(link.href);
                                    const page = url.searchParams.get('page');
                                    if (page) {
                                        this.loading = true;

                                        // Build params object with current filters
                                        const paramsObj = {
                                            status: this.tab,
                                            page: page,
                                            ajax: '1',
                                            sort_by: this.currentSort.field,
                                            sort_dir: this.currentSort.direction
                                        };

                                        if (this.search) paramsObj.search = this.search;
                                        if (this.dateFilter) paramsObj.date_filter = this.dateFilter;
                                        if (this.dateStart) paramsObj.date_start = this.dateStart;
                                        if (this.dateEnd) paramsObj.date_end = this.dateEnd;
                                        if (this.customerId) paramsObj.customer_id = this.customerId;

                                        const params = new URLSearchParams(paramsObj);

                                        // Update URL with page number
                                        const newUrl =
                                            `{{ route('penjualan.retur.index') }}?${params.toString().replace('ajax=1&', '')}`;
                                        window.history.pushState({
                                            filters: paramsObj
                                        }, '', newUrl);

                                        fetch(`{{ route('penjualan.retur.index') }}?${params}`, {
                                                headers: {
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                    'Accept': 'application/json'
                                                }
                                            })
                                            .then(response => {
                                                if (!response.ok) {
                                                    throw new Error(
                                                        `Network response was not ok: ${response.status} ${response.statusText}`
                                                    );
                                                }

                                                const contentType = response.headers.get(
                                                    'content-type');
                                                if (!contentType || !contentType.includes(
                                                        'application/json')) {
                                                    return response.text().then(text => {
                                                        console.error(
                                                            'Unexpected response format:',
                                                            text.substring(0, 500) +
                                                            '...');
                                                        throw new Error(
                                                            `Expected JSON response but got ${contentType || 'unknown content type'}`
                                                        );
                                                    });
                                                }

                                                return response.json();
                                            })
                                            .then(data => {
                                                if (!data || !data.html) {
                                                    throw new Error(
                                                        'Received invalid data format from server'
                                                    );
                                                }
                                                this.tableHtml = data.html;
                                                this.paginationHtml = data.pagination;
                                                this.attachPaginationListener();
                                                this.attachSortableListeners();
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert(
                                                    `Terjadi kesalahan saat memuat data: ${error.message}`
                                                );
                                                // Reset loading state but don't update table on error
                                                this.loading = false;
                                            })
                                            .finally(() => {
                                                this.loading = false;
                                            });
                                    }
                                });
                            });
                        });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
