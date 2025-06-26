<x-app-layout :breadcrumbs="[['label' => 'Produksi'], ['label' => 'Perintah Produksi']]" :currentPage="'Daftar Perintah Produksi'">

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Animated table rows */
        tbody tr {
            animation-duration: 0.3s;
            transform-origin: center;
        }

        /* Table hover effects */
        tbody tr:hover td {
            @apply bg-gray-50 dark:bg-gray-700;
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
            box-shadow: 0 0 0 2px rgba(var(--color-primary-500), 0.3);
            border-color: rgb(var(--color-primary-500));
        }

        /* Enhanced input animations */
        input,
        select {
            transition: all 0.2s ease-in-out;
        }

        input:hover:not(:focus),
        select:hover:not(:focus) {
            border-color: rgb(var(--color-primary-300));
        }

        /* Loading indicator */
        #table-loading-overlay {
            background-color: rgba(255, 255, 255, 0.7);
            z-index: 50;
            backdrop-filter: blur(2px);
            transition: all 0.2s ease-in-out;
            border-radius: 0.5rem;
        }

        .dark #table-loading-overlay {
            background-color: rgba(31, 41, 55, 0.7);
        }
    </style>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="workOrderTableManager()" x-init="init()">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Daftar Perintah Produksi</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola daftar perintah produksi untuk PT Sinar Surya Semestaraya
            </p>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            {{-- Total Perintah Produksi Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Work Order</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $workOrders->total() ?? 0 }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Direncanakan Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-gray-100 dark:bg-gray-700/30 p-3.5">
                            <svg class="h-7 w-7 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Direncanakan</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $workOrders->where('status', 'direncanakan')->count() ?? 0 }}
                                </p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Berjalan Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 p-3.5">
                            <svg class="h-7 w-7 text-yellow-500 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sedang Berjalan</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $workOrders->where('status', 'berjalan')->count() ?? 0 }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Action Card --}}
            <a href="{{ route('produksi.work-order.create') }}"
                class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl transition-all duration-300 hover:-translate-y-1 group">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80 uppercase tracking-wider">Aksi Cepat</p>
                            <p class="mt-1 text-lg font-semibold text-white">Buat Work Order Baru</p>
                        </div>
                        <div
                            class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/20 group-hover:bg-white/30 text-white transition-all duration-200">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Enhanced Search and Filter --}}
        <div
            class="mb-6 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header section with title -->
            <div
                class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-medium text-blue-800 dark:text-blue-300 flex items-center">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Filter Work Order
                    <span class="filter-badge ml-2 hidden"></span>
                </h3>
            </div>

            <!-- Filter content -->
            <div class="p-5">
                <form action="{{ route('produksi.work-order.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <!-- Status Filter -->
                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select id="status" name="status"
                                class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 text-sm dark:bg-gray-700 dark:text-white">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status
                                </option>
                                <option value="direncanakan"
                                    {{ request('status') == 'direncanakan' ? 'selected' : '' }}>
                                    Direncanakan</option>
                                <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>
                                    Berjalan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>

                        <!-- Search Filter -->
                        <div>
                            <label for="search"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cari</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nomor, produk, sales order..."
                                    class="pl-10 pr-3 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <!-- Period Filter -->
                        <div>
                            <label for="periode"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Periode</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="text" id="periode" name="periode"
                                    value="{{ request('periode') }}" placeholder="Pilih periode tanggal"
                                    class="pl-10 pr-3 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm dark:bg-gray-700 dark:text-white daterange">
                            </div>
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Terapkan Filter
                        </button>
                        <a href="{{ route('produksi.work-order.index') }}"
                            class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Filter
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div
            class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:shadow-lg relative">
            <!-- Loading Overlay -->
            <div id="table-loading-overlay"
                class="absolute inset-0 bg-white/80 dark:bg-gray-800/80 z-10 hidden items-center justify-center">
                <div class="flex flex-col items-center">
                    <div
                        class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary-600 dark:border-primary-400">
                    </div>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">Memuat data...</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <x-table-header name="nomor" sortable label="Nomor WO" />
                            <x-table-header name="tanggal" sortable label="Tanggal" />
                            <x-table-header name="produk" sortable label="Produk" />
                            <x-table-header name="quantity" label="Jumlah" />
                            <x-table-header name="tanggal_mulai" sortable label="Tanggal Mulai" />
                            <x-table-header name="deadline" label="Deadline" />
                            <x-table-header name="status" sortable label="Status" />
                            <x-table-header label="Aksi" />
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($workOrders as $wo)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-150">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $wo->nomor }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $wo->tanggal ? date('d/m/Y', strtotime($wo->tanggal)) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $wo->produk->nama ?? 'Produk tidak ditemukan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">{{ $wo->quantity }}</span>
                                    {{ $wo->satuan->nama ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $wo->tanggal_mulai ? date('d/m/Y', strtotime($wo->tanggal_mulai)) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $wo->deadline ? date('d/m/Y', strtotime($wo->deadline)) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full 
                                        {{ $wo->status == 'direncanakan' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                        {{ $wo->status == 'berjalan' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-300' : '' }}
                                        {{ $wo->status == 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300' : '' }}
                                        {{ $wo->status == 'dibatalkan' ? 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300' : '' }}
                                        {{ $wo->status == 'qc_reject' ? 'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300' : '' }}
                                        {{ $wo->status == 'qc_pass' ? 'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300' : '' }}">
                                        {{ ucwords(str_replace('_', ' ', $wo->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('produksi.work-order.show', $wo->id) }}"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor" class="w-4 h-4">
                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                <path fill-rule="evenodd"
                                                    d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>

                                        @if ($wo->status === 'direncanakan')
                                            <a href="{{ route('produksi.work-order.edit', $wo->id) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-indigo-100 text-gray-700 dark:text-white dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 transition-colors border border-dashed border-indigo-300"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path
                                                        d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                    <path
                                                        d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if ($wo->status === 'direncanakan')
                                            <a href="{{ route('produksi.work-order.change-status', ['id' => $wo->id, 'status' => 'berjalan']) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-gray-700 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300"
                                                title="Mulai Produksi"
                                                onclick="return confirm('Apakah Anda yakin ingin memulai proses produksi?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if ($wo->status === 'berjalan')
                                            <a href="{{ route('produksi.work-order.create-qc', $wo->id) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                                                title="Buat QC">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                    <path fill-rule="evenodd"
                                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if ($wo->status === 'selesai_produksi')
                                            <a href="{{ route('produksi.work-order.create-qc', $wo->id) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-green-100 text-gray-700 dark:text-white dark:bg-green-900/20 dark:hover:bg-green-900/30 transition-colors border border-dashed border-green-300"
                                                title="Buat QC">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                    <path fill-rule="evenodd"
                                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if ($wo->status === 'qc_pass')
                                            <a href="{{ route('produksi.work-order.change-status', ['id' => $wo->id, 'status' => 'selesai']) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-indigo-100 text-gray-700 dark:text-white dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 transition-colors border border-dashed border-indigo-300"
                                                title="Selesai"
                                                onclick="return confirm('Apakah Anda yakin akan menyelesaikan work order ini? Stok akan ditambahkan ke gudang hasil.')">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if ($wo->status === 'qc_reject')
                                            <a href="{{ route('produksi.work-order.change-status', ['id' => $wo->id, 'status' => 'berjalan']) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-gray-700 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300"
                                                title="Kembali ke Produksi"
                                                onclick="return confirm('Apakah Anda yakin akan mengembalikan work order ini ke tahap produksi?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8"
                                    class="px-6 py-10 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-3"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                        </svg>
                                        <span class="text-gray-500 dark:text-gray-400 text-base">Tidak ada data
                                            work order</span>
                                        <a href="{{ route('produksi.work-order.create') }}"
                                            class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Buat Work Order Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if ($workOrders->previousPageUrl())
                            <a href="{{ $workOrders->previousPageUrl() }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&status={{ request('status') }}&periode={{ request('periode') }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Previous
                            </a>
                        @else
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-500 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                Previous
                            </span>
                        @endif

                        @if ($workOrders->nextPageUrl())
                            <a href="{{ $workOrders->nextPageUrl() }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&status={{ request('status') }}&periode={{ request('periode') }}"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Next
                            </a>
                        @else
                            <span
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-500 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Showing
                                <span class="font-medium">{{ $workOrders->firstItem() ?? 0 }}</span>
                                to
                                <span class="font-medium">{{ $workOrders->lastItem() ?? 0 }}</span>
                                of
                                <span class="font-medium">{{ $workOrders->total() }}</span>
                                results
                            </p>
                        </div>

                        {{ $workOrders->appends([
                                'sort' => request('sort'),
                                'direction' => request('direction'),
                                'search' => request('search'),
                                'status' => request('status'),
                                'periode' => request('periode'),
                            ])->links() }}
                    </div>
                </div>
            </div>

            {{-- Data summary --}}
            <div class="mt-1 mb-2 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 px-6">
                <div id="data-summary">
                    <span id="showing-entries">Menampilkan
                        {{ $workOrders->firstItem() ?? 0 }}-{{ $workOrders->lastItem() ?? 0 }}
                        dari {{ $workOrders->total() ?? 0 }} entri</span>
                </div>
                <div class="ml-auto">
                    <span id="last-updated" class="italic">Terakhir diperbarui:
                        {{ now()->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Daterange picker
                flatpickr('.daterange', {
                    mode: 'range',
                    dateFormat: 'd/m/Y',
                    locale: 'id'
                });
            });

            function workOrderTableManager() {
                return {
                    isLoading: false,

                    init() {
                        // Handle filter badge visibility
                        const hasFilters =
                            {{ request('search') || (request('status') && request('status') != 'all') || request('periode') ? 'true' : 'false' }};
                        if (hasFilters) {
                            const filterBadge = document.querySelector('.filter-badge');
                            if (filterBadge) {
                                filterBadge.classList.remove('hidden');
                                filterBadge.textContent = 'Filter Aktif';
                                filterBadge.classList.add('bg-primary-100', 'text-primary-800', 'dark:bg-primary-800',
                                    'dark:text-primary-200', 'px-2', 'py-0.5', 'rounded-md', 'text-xs');
                            }
                        }

                        // Add animations to table rows
                        const tableRows = document.querySelectorAll('tbody tr');
                        tableRows.forEach((row, index) => {
                            row.style.animationDelay = `${index * 0.05}s`;
                            row.classList.add('animate__animated', 'animate__fadeIn');
                        });

                        // Initialize tooltips for action buttons
                        const actionButtons = document.querySelectorAll('[title]');
                        actionButtons.forEach(button => {
                            const tooltip = button.getAttribute('title');
                            if (tooltip) {
                                // You can implement tooltip functionality here if needed
                            }
                        });

                        // Set up event listeners for sort headers and filter form
                        this.setupLoadingListeners();
                    },

                    showLoading() {
                        this.isLoading = true;
                        const overlay = document.getElementById('table-loading-overlay');
                        if (overlay) {
                            overlay.classList.remove('hidden');
                            overlay.classList.add('flex');
                        }
                    },

                    hideLoading() {
                        this.isLoading = false;
                        const overlay = document.getElementById('table-loading-overlay');
                        if (overlay) {
                            overlay.classList.add('hidden');
                            overlay.classList.remove('flex');
                        }
                    },

                    setupLoadingListeners() {
                        // Add click event listeners to all sort headers
                        const sortHeaders = document.querySelectorAll('th a[href*="sort="]');
                        sortHeaders.forEach(header => {
                            header.addEventListener('click', (e) => {
                                // Show loading overlay before navigating
                                this.showLoading();
                            });
                        });

                        // Add submit event listener to the filter form
                        const filterForm = document.querySelector('form[action*="work-order.index"]');
                        if (filterForm) {
                            filterForm.addEventListener('submit', (e) => {
                                // Show loading overlay before submitting the form
                                this.showLoading();
                            });
                        }

                        // Add click event listeners to pagination links
                        const paginationLinks = document.querySelectorAll('.pagination a');
                        paginationLinks.forEach(link => {
                            link.addEventListener('click', (e) => {
                                // Show loading overlay before navigating
                                this.showLoading();
                            });
                        });
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
