<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Bill of Material'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Bill of Material (BOM)</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola daftar Bill of Material untuk produksi PT Sinar Surya Semestaraya
            </p>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            {{-- Total BOM Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total BOM</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $totalBOM ?? 0 }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Active BOM Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 p-3.5">
                            <svg class="h-7 w-7 text-emerald-500 dark:text-emerald-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                BOM Aktif</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $activeBOM ?? 0 }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Non Aktif BOM Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-red-100 dark:bg-red-900/30 p-3.5">
                            <svg class="h-7 w-7 text-red-500 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                BOM Non Aktif</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $inactiveBOM ?? 0 }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Action Card --}}
            @if (auth()->user()->hasPermission('bill_of_material.create'))
                <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-bom-modal', {detail: {}}))"
                    class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white/80 uppercase tracking-wider">Aksi Cepat</p>
                                <p class="mt-1 text-lg font-semibold text-white">Buat BOM Baru</p>
                            </div>
                            <div
                                class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/20 group-hover:bg-white/30 text-white transition-all duration-200">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @else
                <div class="bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-lg rounded-xl opacity-50">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi Cepat</p>
                                <p class="mt-1 text-lg font-semibold text-gray-600 dark:text-gray-300">Buat BOM Baru</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tidak ada akses</p>
                            </div>
                            <div
                                class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                    Filter Data BOM
                    <span class="filter-badge ml-2 hidden"></span>
                </h3>
            </div>

            <!-- Filter content -->
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Produk Filter -->
                    <div>
                        <label for="produk_filter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Produk</label>
                        <select id="produk_filter"
                            class="select2 w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 text-sm dark:bg-gray-700 dark:text-white">
                            <option value="">Semua Produk</option>
                            @foreach ($produks as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->nama }} ({{ $produk->kode }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Filter -->
                    <div>
                        <label for="search_filter"
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
                            <input type="text" id="search_filter" name="search_filter"
                                placeholder="Cari nama atau kode BOM"
                                class="pl-10 pr-3 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Status filter -->
                <div class="mt-4">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</span>
                        <div class="flex items-center">
                            <input type="radio" id="status_all" name="status_filter" value=""
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded-full"
                                checked>
                            <label for="status_all"
                                class="ml-2 text-sm text-gray-700 dark:text-gray-300">Semua</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="status_active" name="status_filter" value="1"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded-full">
                            <label for="status_active"
                                class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="status_inactive" name="status_filter" value="0"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded-full">
                            <label for="status_inactive" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Non
                                Aktif</label>
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button type="button" id="apply_filter"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                        Terapkan Filter
                    </button>
                    <button type="button" id="reset_filter"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        {{-- Batch Actions --}}
        @if (auth()->user()->hasPermission('bill_of_material.view'))
            <div
                class="mb-4 bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden ring-1 ring-gray-200 dark:ring-gray-700 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Batch Actions</h3>
                    </div>
                    <button onclick="batchUpdateBOMCosts()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Update Semua Harga BOM
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Update harga beli produk untuk semua BOM yang
                    memiliki komponen dengan harga valid</p>
            </div>
        @endif

        {{-- BOM Table --}}
        <div id="bom-table-container"
            class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden ring-1 ring-gray-200 dark:ring-gray-700">
            <div class="overflow-x-auto relative">
                {{-- Loading Indicator (Only appears over the table section) --}}
                <div id="loading-indicator"
                    class="py-10 flex flex-col items-center justify-center absolute top-0 left-0 w-full h-full bg-white/60 dark:bg-gray-800/60 z-10 backdrop-blur-sm"
                    style="display: none;">
                    <div class="relative flex">
                        <div class="w-12 h-12 rounded-full border-4 border-primary-200 dark:border-primary-700"></div>
                        <div
                            class="w-12 h-12 rounded-full border-4 border-transparent border-t-primary-500 animate-spin absolute">
                        </div>
                    </div>
                    <span class="text-sm text-gray-800 dark:text-gray-200 font-medium mt-3">Memuat data...</span>
                </div>

                <table id="bom-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" data-sort="kode"
                                class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex items-center">
                                    Kode BOM
                                    <svg class="w-4 h-4 ml-1 sort-icon" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" data-sort="nama"
                                class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex items-center">
                                    Nama BOM
                                    <svg class="w-4 h-4 ml-1 sort-icon" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" data-sort="produk"
                                class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex items-center">
                                    Produk
                                    <svg class="w-4 h-4 ml-1 sort-icon" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" data-sort="versi"
                                class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex items-center">
                                    Versi
                                    <svg class="w-4 h-4 ml-1 sort-icon" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col" data-sort="is_active"
                                class="sortable px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <div class="flex items-center">
                                    Status
                                    <svg class="w-4 h-4 ml-1 sort-icon" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Cost Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                        id="bom-table-body">
                        @include('produksi.BOM._table_body')
                    </tbody>
                </table>
            </div>



            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700" id="pagination-container">
                @if (isset($boms) && method_exists($boms, 'hasPages') && $boms->hasPages())
                    {{ $boms->links() }}
                @endif
            </div>

            {{-- Data summary --}}
            <div class="mt-1 mb-2 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 px-6">
                <div id="data-summary">
                    <span id="showing-entries">Menampilkan {{ $boms->firstItem() ?? 0 }}-{{ $boms->lastItem() ?? 0 }}
                        dari {{ $boms->total() ?? 0 }} entri</span>
                </div>
                <div class="ml-auto">
                    <span id="last-updated" class="italic">Terakhir diperbarui:
                        {{ now()->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Create/Edit BOM --}}
    <div x-data="bomModalForm()" x-init="window.addEventListener('open-bom-modal', event => {
        mode = event.detail.id ? 'edit' : 'create';
        if (mode === 'edit') {
            // Load data for editing
            formData = {
                id: event.detail.id,
                nama: event.detail.nama,
                kode: event.detail.kode,
                produk_id: event.detail.produk_id,
                deskripsi: event.detail.deskripsi,
                versi: event.detail.versi,
                is_active: event.detail.is_active
            };
    
            // No need for special initialization here, 
            // x-model will handle the select element automatically
        } else {
            // Reset for creation
            formData = { id: null, nama: '', kode: '', produk_id: '', deskripsi: '', versi: '1.0', is_active: true };
            // Generate BOM code automatically
            generateBOMCode();
        }
        open = true;
    })" @keydown.esc.window="open = false">
        <!-- Modal background -->
        <div x-show="open"
            class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity z-40"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Modal panel -->
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Modal content -->
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-primary-600 dark:text-primary-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    x-text="mode === 'create' ? 'Tambah BOM Baru' : 'Edit BOM'"></h3>
                                <div class="mt-4 space-y-4">
                                    <!-- Nama BOM -->
                                    <div>
                                        <label for="nama"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama BOM
                                            <span class="text-red-600">*</span></label>
                                        <input type="text" name="nama" id="nama" x-model="formData.nama"
                                            class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-show="errors.nama"
                                            x-text="errors.nama"></p>
                                    </div>

                                    <!-- Kode BOM -->
                                    <div>
                                        <label for="kode"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode BOM
                                            <span class="text-red-600">*</span></label>
                                        <div class="flex">
                                            <input type="text" name="kode" id="kode"
                                                x-model="formData.kode"
                                                class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md">
                                            <button type="button" @click="generateBOMCode()"
                                                class="mt-1 ml-2 px-3 py-1 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-500">
                                                Generate
                                            </button>
                                        </div>
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-show="errors.kode"
                                            x-text="errors.kode"></p>
                                    </div>

                                    <!-- Produk (Enhanced Searchable Dropdown) -->
                                    <div x-data="dropdownSearch('produk_id',
                                        {{ json_encode(
                                            $produks->map(function ($produk) {
                                                    return [
                                                        'value' => strval($produk->id),
                                                        'label' => $produk->nama . ' (' . $produk->kode . ')',
                                                        'nama' => $produk->nama,
                                                        'kode' => $produk->kode,
                                                    ];
                                                })->toArray(),
                                        ) }}, { value: '', label: '' })" @click.away="open = false" class="relative">
                                        <label for="produk_search"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Produk
                                            <span class="text-red-600">*</span></label>
                                        <input type="hidden" name="produk_id" :value="selectedOption.value"
                                            x-model="formData.produk_id"
                                            @change="formData.produk_id = $event.target.value">
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <input type="text" id="produk_search"
                                                placeholder="-- Pilih atau cari produk --"
                                                class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 pl-10 pr-10 mt-1 focus:ring-primary-500 focus:border-primary-500"
                                                @click="open = true" @focus="open = true" :value="displayValue()"
                                                autocomplete="off">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <div x-show="open" x-transition
                                            class="absolute z-20 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-lg max-h-60 overflow-auto border border-gray-200 dark:border-gray-600"
                                            style="display: none;">
                                            <div
                                                class="sticky top-0 bg-white dark:bg-gray-700 p-2 border-b border-gray-200 dark:border-gray-600">
                                                <div class="relative">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 text-gray-400" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <input type="text"
                                                        class="w-full p-2 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                                        placeholder="Ketik untuk mencari produk..." x-model="search"
                                                        @click.stop>
                                                </div>
                                            </div>
                                            <ul class="py-1">
                                                <template x-for="option in filteredOptions()" :key="option.value">
                                                    <li>
                                                        <button type="button"
                                                            class="w-full px-4 py-2.5 text-left hover:bg-primary-50 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm flex items-center transition-colors duration-150"
                                                            @click="select(option); updateBOMName(option)">
                                                            <span class="mr-2 flex-shrink-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                                </svg>
                                                            </span>
                                                            <span x-text="option.label"></span>
                                                        </button>
                                                    </li>
                                                </template>
                                                <li x-show="filteredOptions().length === 0"
                                                    class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 mr-2 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 14h.01M19 21a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    Tidak ada produk yang ditemukan
                                                </li>
                                            </ul>
                                        </div>

                                        <div x-show="selectedOption.value"
                                            class="mt-2 flex items-center py-1 px-2 rounded-md bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800"
                                            style="display: none;">
                                            <span
                                                class="text-xs font-medium text-primary-700 dark:text-primary-300">Produk
                                                terpilih:</span>
                                            <span
                                                class="ml-1.5 text-sm text-primary-800 dark:text-primary-200 font-medium"
                                                x-text="selectedOption.label"></span>
                                            <button type="button"
                                                @click="selectedOption = {value: '', label: ''}; formData.produk_id = ''; open = true;"
                                                class="ml-auto text-primary-500 hover:text-primary-700 dark:hover:text-primary-300"
                                                title="Hapus pilihan">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"
                                            x-show="errors.produk_id" x-text="errors.produk_id"></p>
                                    </div>

                                    <!-- Versi -->
                                    <div>
                                        <label for="versi"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Versi
                                            BOM</label>
                                        <input type="text" name="versi" id="versi" x-model="formData.versi"
                                            class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md">
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400" x-show="errors.versi"
                                            x-text="errors.versi"></p>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div>
                                        <label for="deskripsi"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                                        <textarea id="deskripsi" name="deskripsi" rows="3" x-model="formData.deskripsi"
                                            class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md"></textarea>
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"
                                            x-show="errors.deskripsi" x-text="errors.deskripsi"></p>
                                    </div>

                                    <!-- Status -->
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="is_active" name="is_active" type="checkbox"
                                                x-model="formData.is_active"
                                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="is_active"
                                                class="font-medium text-gray-700 dark:text-gray-300">Aktif</label>
                                            <p class="text-gray-500 dark:text-gray-400">BOM ini aktif dan dapat
                                                digunakan untuk produksi.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="submitForm">
                            <span x-text="mode === 'create' ? 'Simpan' : 'Update'"></span>
                        </button>
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="open = false">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function bomModalForm() {
                return {
                    open: false,
                    mode: 'create',
                    formData: {
                        id: null,
                        nama: '',
                        kode: '',
                        produk_id: '',
                        deskripsi: '',
                        versi: '1.0',
                        is_active: true
                    },
                    errors: {},


                    // Method to generate a unique BOM code
                    generateBOMCode() {
                        const date = new Date();
                        const year = date.getFullYear().toString().substr(-2); // Last 2 digits of year
                        const month = ('0' + (date.getMonth() + 1)).slice(-2); // Month with leading zero
                        const day = ('0' + date.getDate()).slice(-2); // Day with leading zero
                        const hours = ('0' + date.getHours()).slice(-2); // Hours with leading zero
                        const minutes = ('0' + date.getMinutes()).slice(-2); // Minutes with leading zero
                        const seconds = ('0' + date.getSeconds()).slice(-2); // Seconds with leading zero
                        const randomDigits = Math.floor(1000 + Math.random() * 9000); // 4 random digits

                        // Format: BOM-YYMMDD-HHMMSS-RAND
                        this.formData.kode = `BOM-${year}${month}${day}-${hours}${minutes}${seconds}-${randomDigits}`;
                    },

                    // Method to update BOM name when product changes
                    updateBOMName(option) {
                        console.log('updateBOMName called with option:', option);

                        // If option is passed directly from the dropdown search
                        if (option && option.nama) {
                            // Set both the name and the product ID
                            this.formData.nama = `BOM untuk ${option.nama}`;
                            this.formData.produk_id = option.value;
                            console.log('Set produk_id to:', option.value);
                        } else if (this.formData.produk_id) {
                            // Try to find the product in the dropdown
                            const dropdownEl = document.querySelector('[x-data^="dropdownSearch"]');
                            if (dropdownEl && dropdownEl.__x) {
                                const dropdownData = dropdownEl.__x.$data;
                                if (dropdownData.selectedOption && dropdownData.selectedOption.nama) {
                                    this.formData.nama = `BOM untuk ${dropdownData.selectedOption.nama}`;
                                    // Ensure produk_id is set properly
                                    this.formData.produk_id = dropdownData.selectedOption.value;
                                    console.log('Set produk_id from dropdown to:', dropdownData.selectedOption.value);
                                } else if (dropdownData.options && this.formData.produk_id) {
                                    // Try to find the product in the options array by its ID
                                    const foundOption = dropdownData.options.find(opt => opt.value === this.formData.produk_id);
                                    if (foundOption && foundOption.nama) {
                                        this.formData.nama = `BOM untuk ${foundOption.nama}`;
                                        console.log('Set BOM name from options array for product ID:', this.formData.produk_id);
                                    }
                                }
                            }
                        }
                    },

                    // Submit form method
                    submitForm() {
                        // Log form data for debugging
                        console.log('Submitting form with data:', this.formData);

                        // Validation
                        this.errors = {};
                        if (!this.formData.nama) this.errors.nama = 'Nama BOM wajib diisi';
                        if (!this.formData.kode) this.errors.kode = 'Kode BOM wajib diisi';

                        // Enhanced check for produk_id
                        if (!this.formData.produk_id) {
                            console.log('produk_id is empty, attempting to retrieve it');
                            this.errors.produk_id = 'Produk wajib dipilih';

                            // First try: Get from dropdown component
                            const dropdownEl = document.querySelector('[x-data^="dropdownSearch"]');
                            if (dropdownEl && dropdownEl.__x) {
                                const dropdownData = dropdownEl.__x.$data;
                                if (dropdownData.selectedOption && dropdownData.selectedOption.value) {
                                    this.formData.produk_id = dropdownData.selectedOption.value;
                                    console.log('Retrieved produk_id from dropdown component:', this.formData.produk_id);
                                    delete this.errors.produk_id;
                                }
                            }

                            // Second try: Get from hidden input
                            if (!this.formData.produk_id) {
                                const hiddenInput = document.querySelector('input[name="produk_id"]');
                                if (hiddenInput && hiddenInput.value) {
                                    this.formData.produk_id = hiddenInput.value;
                                    console.log('Retrieved produk_id from hidden input:', this.formData.produk_id);
                                    delete this.errors.produk_id;
                                }
                            }

                            // Third try: Get from Alpine store if available
                            if (!this.formData.produk_id && window.Alpine && window.Alpine.store) {
                                const produkState = window.Alpine.store('produkState');
                                if (produkState && produkState.selectedProductId) {
                                    this.formData.produk_id = produkState.selectedProductId;
                                    console.log('Retrieved produk_id from Alpine store:', this.formData.produk_id);
                                    delete this.errors.produk_id;
                                }
                            }
                        }

                        // Submit if no errors
                        if (Object.keys(this.errors).length === 0) {
                            const url = this.mode === 'create' ?
                                '{{ route('produksi.bom.store') }}' :
                                '{{ route('produksi.bom.index') }}/' + this.formData.id;

                            const method = this.mode === 'create' ? 'POST' : 'PATCH';

                            // Create form data
                            const formData = new FormData();
                            for (const key in this.formData) {
                                if (key === 'is_active') {
                                    formData.append(key, this.formData[key] ? 1 : 0);
                                } else if (this.formData[key] !== null) {
                                    formData.append(key, this.formData[key]);
                                }
                            }

                            // Add CSRF token
                            formData.append('_token', '{{ csrf_token() }}');
                            if (method === 'PATCH') {
                                formData.append('_method', 'PATCH');
                            }

                            // Submit the form
                            fetch(url, {
                                    method: method === 'PATCH' ? 'POST' :
                                    method, // For PATCH, we still send as POST but with _method field
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        if (response.status === 422) {
                                            // Validation errors
                                            return response.json().then(data => {
                                                if (data.errors) {
                                                    // Set validation errors
                                                    this.errors = data.errors;
                                                    throw new Error('Validation failed');
                                                }
                                                return data;
                                            });
                                        }
                                        throw new Error('Server error');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        // Display success notification
                                        window.dispatchEvent(new CustomEvent('notify', {
                                            detail: {
                                                type: 'success',
                                                title: 'Berhasil',
                                                message: data.message
                                            }
                                        }));

                                        // Reload page to get updated data
                                        window.location.reload();
                                    } else {
                                        // Display error notification
                                        window.dispatchEvent(new CustomEvent('notify', {
                                            detail: {
                                                type: 'error',
                                                title: 'Gagal',
                                                message: data.message ||
                                                    'Terjadi kesalahan saat memproses permintaan'
                                            }
                                        }));
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    if (error.message !== 'Validation failed') {
                                        window.dispatchEvent(new CustomEvent('notify', {
                                            detail: {
                                                type: 'error',
                                                title: 'Error',
                                                message: 'Terjadi kesalahan saat memproses permintaan'
                                            }
                                        }));
                                    }
                                })
                                .finally(() => {
                                    // Only close the modal if there are no validation errors
                                    if (Object.keys(this.errors).length === 0) {
                                        this.open = false;
                                    }
                                });
                        }
                    }
                };
            }
        </script>
    </div>

    {{-- Modal Delete Confirmation --}}
    <div x-data="{
        open: false,
        bomId: null,
        deleteBOM(id) {
            const url = '{{ route('produksi.bom.index') }}/' + id;
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'DELETE');
    
            fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: 'success',
                                title: 'Berhasil',
                                message: data.message
                            }
                        }));
    
                        // Reload page
                        window.location.reload();
                    } else {
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: 'error',
                                title: 'Gagal',
                                message: data.message
                            }
                        }));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: {
                            type: 'error',
                            title: 'Error',
                            message: 'Terjadi kesalahan saat menghapus data'
                        }
                    }));
                })
                .finally(() => {
                    this.open = false;
                });
        }
    }" x-init="window.addEventListener('open-delete-modal', event => {
        bomId = event.detail.id;
        open = true;
    })" @keydown.esc.window="open = false">
        <!-- Modal background -->
        <div x-show="open"
            class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity z-40"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Modal panel -->
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Modal content -->
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    Hapus BOM
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Apakah Anda yakin ingin menghapus BOM ini? Tindakan ini tidak dapat dibatalkan.
                                        Semua data yang terkait dengan BOM ini juga akan dihapus.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="deleteBOM(bomId)">
                            Hapus
                        </button>
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="open = false">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Delete functionality moved into the Alpine.js component
        </script>
    </div>

    <!-- Notification toast -->
    <div x-data="{ notificationOpen: false, notificationMessage: '', notificationTitle: '', notificationType: 'success' }" x-init="window.addEventListener('notify', event => {
        notificationMessage = event.detail.message || '';
        notificationTitle = event.detail.title || 'Notifikasi';
        notificationType = event.detail.type || 'success';
        notificationOpen = true;
        setTimeout(() => { notificationOpen = false }, 3000);
    })">
        <div x-show="notificationOpen" x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 flex items-start justify-end px-4 py-6 pointer-events-none sm:p-6 z-50"
            style="top: 60px;">
            <div
                class="w-full max-w-sm overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow-lg pointer-events-auto ring-1 ring-black ring-opacity-5">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0" x-show="notificationType === 'success'">
                            <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-shrink-0" x-show="notificationType === 'error'">
                            <svg class="h-6 w-6 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="notificationTitle">
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" x-text="notificationMessage"></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button @click="notificationOpen = false"
                                class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <span class="sr-only">Tutup</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </script>

    @push('styles')
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
                background: rgb(var(--color-primary-500));
                border-color: rgb(var(--color-primary-500));
            }

            .flatpickr-day.today {
                border-color: rgb(var(--color-primary-500));
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
            .select2-container--default .select2-selection--single {
                transition: all 0.2s ease-in-out;
            }

            input:hover:not(:focus),
            .select2-container--default .select2-selection--single:hover {
                border-color: rgb(var(--color-primary-300));
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
                background-color: rgb(var(--color-primary-600));
            }

            .dark .select2-container--default .select2-results__option[aria-selected=true] {
                background-color: rgb(107 114 128);
            }

            /* Hide Alpine.js elements before initialization */
            [x-cloak] {
                display: none !important;
            }

            /* Dropdown search styling */
            .dropdown-search {
                position: relative;
            }

            .dropdown-search .dropdown-content {
                position: absolute;
                z-index: 9999;
                width: 100%;
                max-height: 250px;
                overflow-y: auto;
            }

            /* Alpine.js Dropdown Styling with Dark Mode Support */
            .dropdown-search input[type="text"] {
                @apply w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm;
                height: 38px;
                padding-top: 4px;
            }

            .dropdown-search .dropdown-content {
                @apply bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-lg rounded-md;
            }

            .dropdown-search .search-input {
                @apply border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white;
            }

            .dropdown-search li button {
                @apply px-3 py-2 text-gray-700 dark:text-gray-300;
            }

            .dropdown-search li button:hover {
                @apply bg-primary-500 dark:bg-primary-600 text-white;
            }

            .dropdown-search li button:focus {
                @apply bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300;
            }

            .dropdown-search input[type="text"]:focus {
                @apply border-primary-500 dark:border-primary-500 dark:ring-primary-500 dark:shadow-none;
            }

            /* Enhanced loading indicator that only affects the table area */
            #loading-indicator {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.7);
                z-index: 50;
                backdrop-filter: blur(2px);
                transition: all 0.2s ease-in-out;
                border-radius: 0.5rem;
            }

            .dark #loading-indicator {
                background-color: rgba(31, 41, 55, 0.7);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                // Create a global store for product selection state
                Alpine.store('produkState', {
                    selectedProductId: null
                });

                Alpine.data('dropdownSearch', function(fieldName, options, selected) {
                    return {
                        search: '',
                        open: false,
                        options: options || [],
                        selectedOption: selected || {
                            value: '',
                            label: ''
                        },

                        filteredOptions() {
                            if (!this.search) return this.options;
                            return this.options.filter(option =>
                                option.label.toLowerCase().includes(this.search.toLowerCase())
                            );
                        },

                        select(option) {
                            this.selectedOption = option;
                            this.open = false;
                            this.search = '';
                            console.log('Selected product option:', option);

                            // Update the input to show the selected value
                            const input = this.$el.querySelector('input[id$="_search"]');
                            if (input) {
                                input.value = option.label;
                            }

                            // Directly update the hidden input value to ensure formData is updated
                            const hiddenInput = this.$el.querySelector('input[name="produk_id"]');
                            if (hiddenInput) {
                                hiddenInput.value = option.value;
                                // Trigger a change event to ensure Alpine.js detects the change
                                hiddenInput.dispatchEvent(new Event('input', {
                                    bubbles: true
                                }));
                                console.log('Updated hidden input value to:', option.value);
                            }

                            // Find the parent form component and update its formData directly if possible
                            const formEl = document.querySelector('[x-data="bomModalForm()"]');
                            if (formEl && formEl.__x) {
                                const formData = formEl.__x.$data.formData;
                                formData.produk_id = option.value;
                                console.log('Updated formData.produk_id directly to:', option.value);
                            }

                            // Update global Alpine.js context
                            window.Alpine.store('produkState', {
                                selectedProductId: option.value
                            });

                            // Dispatch event for other components to react to selection
                            this.$dispatch('option-selected', {
                                fieldName: fieldName,
                                value: option.value,
                                option: option
                            });
                        },

                        init() {
                            // If we have a selected value but no full object, find it in options
                            if (typeof this.selectedOption === 'string' && this.selectedOption !== '') {
                                const foundOption = this.options.find(opt => opt.value === this.selectedOption);
                                if (foundOption) {
                                    this.selectedOption = foundOption;
                                }
                            }

                            // If selectedOption is a primitive value, convert it to object
                            if (this.selectedOption && typeof this.selectedOption.value === 'undefined') {
                                this.selectedOption = {
                                    value: this.selectedOption,
                                    label: ''
                                };
                            }
                        },

                        // Display selected value in the input field
                        displayValue() {
                            return this.selectedOption && this.selectedOption.value ? this.selectedOption
                                .label : '';
                        }
                    };
                });
            });

            // Function to handle modals and dropdowns when Alpine initializes
            document.addEventListener('DOMContentLoaded', function() {
                // Re-initialize when the modal is opened
                window.addEventListener('open-bom-modal', function() {
                    setTimeout(function() {
                        // Any modal-specific initialization can go here
                    }, 100);
                });
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            $(document).ready(function() {
                // Initialize Select2 for filter dropdown
                $('.select2').select2({
                    placeholder: "Pilih produk",
                    allowClear: true,
                    width: '100%',
                    selectionCssClass: 'py-2 h-[42px]',
                    dropdownCssClass: 'text-sm rounded-lg shadow-md border-gray-300'
                });

                // Variables for sorting
                let currentSort = {
                    field: 'kode',
                    direction: 'desc'
                };

                // Show active filters indicator function
                function updateFilterIndicators() {
                    const produkId = $('#produk_filter').val();
                    const searchQuery = $('#search_filter').val();
                    const statusFilter = $('input[name="status_filter"]:checked').val();

                    let activeFilters = 0;

                    // Count active filters
                    if (produkId) activeFilters++;
                    if (searchQuery) activeFilters++;
                    if (statusFilter !== "") activeFilters++;

                    // Add visual indicator to filter section
                    if (activeFilters > 0) {
                        $('.filter-badge').remove();
                        $('h3:contains("Filter Data BOM")').append(
                            `<span class="filter-badge inline-flex items-center justify-center ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 rounded-full">${activeFilters} aktif</span>`
                        ).removeClass('hidden');
                    } else {
                        $('.filter-badge').remove();
                    }
                }

                // Apply visual feedback to inputs
                function applyInputFeedback() {
                    // Apply visual feedback for inputs
                    if ($('#produk_filter').val()) {
                        const $selection = $('#produk_filter').next('.select2-container').find('.select2-selection');
                        $selection.addClass('border-primary-500 dark:border-primary-400 animate-pulse-once');
                        setTimeout(() => {
                            $selection.removeClass('animate-pulse-once');
                        }, 500);
                    } else {
                        $('#produk_filter').next('.select2-container').find('.select2-selection').removeClass(
                            'border-primary-500 dark:border-primary-400');
                    }

                    if ($('#search_filter').val()) {
                        $('#search_filter').addClass('border-primary-500 dark:border-primary-400 animate-pulse-once');
                        setTimeout(() => {
                            $('#search_filter').removeClass('animate-pulse-once');
                        }, 500);
                    } else {
                        $('#search_filter').removeClass('border-primary-500 dark:border-primary-400');
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

                    // Show loading indicator just over the table area when sorting
                    $('#loading-indicator').fadeIn(100);

                    // Load data with new sorting
                    loadBOMData();
                });

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
                    loadBOMData();
                });

                // Search input key-up with debounce
                let searchTimer;
                $('#search_filter').on('keyup', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(function() {
                        updateFilterIndicators();
                        applyInputFeedback();
                        loadBOMData();
                    }, 500); // 500ms delay
                });

                // Input change event handlers for visual feedback
                $('#produk_filter').on('change', function() {
                    applyInputFeedback();
                });

                $('input[name="status_filter"]').on('change', function() {
                    updateFilterIndicators();
                });

                // Reset filter button click with smooth animation
                $('#reset_filter').on('click', function() {
                    // Add visual feedback for button click
                    const $btn = $(this);
                    $btn.addClass('transform scale-95').attr('disabled', true);

                    // Add animation for resetting filters
                    $('#search_filter').addClass('transition-all duration-500');
                    $('#produk_filter').next('.select2-container').find('.select2-selection').addClass(
                        'transition-all duration-500');

                    setTimeout(() => {
                        // Reset produk select dropdown
                        $('#produk_filter').val(null).trigger('change');

                        // Reset search filter
                        $('#search_filter').val('');

                        // Reset status filter
                        $('#status_all').prop('checked', true);

                        $btn.removeClass('transform scale-95').attr('disabled', false);

                        // Remove filter badge
                        $('.filter-badge').remove();

                        // Reset visual feedback
                        applyInputFeedback();

                        // Reload data immediately after resetting filters
                        loadBOMData();
                    }, 300);
                });

                // Function to load BOM data via AJAX
                function loadBOMData(page = 1) {
                    // Show loading indicator with transition (only over the table)
                    $('#loading-indicator').fadeIn(100);
                    $('#bom-table-body').empty();
                    $('#empty-state').addClass('hidden');

                    // Get filter values
                    const produkId = $('#produk_filter').val();
                    const searchQuery = $('#search_filter').val();
                    const statusFilter = $('input[name="status_filter"]:checked').val();

                    $.ajax({
                        url: '{{ route('produksi.bom.data') }}',
                        type: 'GET',
                        data: {
                            produk_id: produkId,
                            search_query: searchQuery,
                            status: statusFilter,
                            sort_field: currentSort.field,
                            sort_direction: currentSort.direction,
                            page: page
                        },
                        success: function(response) {
                            $('#loading-indicator').fadeOut(150);
                            $('#bom-table-body').html(response.html);

                            // Update data summary if provided in the response
                            if (response.from !== undefined && response.to !== undefined && response
                                .total !== undefined) {
                                $('#data-summary').removeClass('hidden');
                                $('#showing-entries').text(
                                    `Menampilkan ${response.from}-${response.to} dari ${response.total} entri`
                                );
                                $('#last-updated').text(`Terakhir diperbarui: ${new Date().toLocaleString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}`);
                            }

                            // Show empty state if no data
                            if (response.empty) {
                                $('#empty-state').removeClass('hidden');
                            }

                            // Update pagination if provided
                            if (response.pagination) {
                                $('#pagination-container').html(response.pagination);

                                // Add event listeners for pagination links
                                $('#pagination-container a').on('click', function(e) {
                                    e.preventDefault();
                                    const href = $(this).attr('href');
                                    const pageNum = href.split('page=')[1];
                                    loadBOMData(pageNum);
                                });
                            }
                        },
                        error: function(xhr) {
                            $('#loading-indicator').fadeOut(150);
                            console.error('Error loading BOM data:', xhr);

                            // Dispatch notification event
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    title: 'Error',
                                    message: 'Terjadi kesalahan saat memuat data BOM.'
                                }
                            }));
                        }
                    });
                }

                // Initialize once on page load
                updateFilterIndicators();
            });

            // BOM Cost Update Function
            function updateBOMCost(bomId) {
                // Confirm action
                if (!confirm('Apakah Anda yakin ingin mengupdate harga beli produk berdasarkan BOM ini?')) {
                    return;
                }

                // Show loading state
                const button = event.target.closest('button');
                const originalContent = button.innerHTML;
                button.disabled = true;
                button.innerHTML =
                    '<svg class="animate-spin w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';

                // Make AJAX request
                $.ajax({
                    url: `/produksi/bom/${bomId}/update-cost`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'success',
                                    title: 'Berhasil',
                                    message: response.message ||
                                        'Harga beli produk berhasil diupdate dari BOM.'
                                }
                            }));

                            // Optionally refresh the table data
                            if (typeof loadBOMData === 'function') {
                                loadBOMData(1); // Reload current page
                            }
                        } else {
                            // Show error notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    title: 'Error',
                                    message: response.message || 'Gagal mengupdate harga beli produk.'
                                }
                            }));
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating BOM cost:', xhr);

                        // Show error notification
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: 'error',
                                title: 'Error',
                                message: 'Terjadi kesalahan saat mengupdate harga beli produk.'
                            }
                        }));
                    },
                    complete: function() {
                        // Restore button state
                        button.disabled = false;
                        button.innerHTML = originalContent;
                    }
                });
            }

            // Make function globally available
            window.updateBOMCost = updateBOMCost;

            // Batch Update BOM Costs Function
            function batchUpdateBOMCosts() {
                // Confirm action
                if (!confirm(
                        'Apakah Anda yakin ingin mengupdate harga beli produk untuk semua BOM yang memiliki komponen dengan harga valid?\n\nProses ini mungkin memerlukan waktu beberapa saat.'
                    )) {
                    return;
                }

                // Show loading state
                const button = event.target.closest('button');
                const originalContent = button.innerHTML;
                button.disabled = true;
                button.innerHTML =
                    '<svg class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processing...';

                // Make AJAX request
                $.ajax({
                    url: '/produksi/bom/batch-update-costs',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/json'
                    },
                    timeout: 60000, // 60 seconds timeout for batch operation
                    success: function(response) {
                        if (response.success) {
                            // Show success notification with details
                            const message = response.message || 'Batch update harga BOM berhasil dilakukan.';
                            const details = response.updated_count ?
                                ` (${response.updated_count} produk diupdate)` : '';

                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'success',
                                    title: 'Batch Update Berhasil',
                                    message: message + details
                                }
                            }));

                            // Refresh the table data
                            if (typeof loadBOMData === 'function') {
                                loadBOMData(1); // Reload current page
                            }
                        } else {
                            // Show error notification
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: {
                                    type: 'error',
                                    title: 'Batch Update Gagal',
                                    message: response.message ||
                                        'Gagal melakukan batch update harga BOM.'
                                }
                            }));
                        }
                    },
                    error: function(xhr) {
                        console.error('Error in batch update BOM costs:', xhr);

                        let errorMessage = 'Terjadi kesalahan saat melakukan batch update harga BOM.';
                        if (xhr.status === 504 || xhr.status === 408) {
                            errorMessage =
                                'Batch update memerlukan waktu lebih lama dari biasanya. Silakan periksa hasilnya dalam beberapa saat.';
                        }

                        // Show error notification
                        window.dispatchEvent(new CustomEvent('notify', {
                            detail: {
                                type: 'error',
                                title: 'Batch Update Error',
                                message: errorMessage
                            }
                        }));
                    },
                    complete: function() {
                        // Restore button state
                        button.disabled = false;
                        button.innerHTML = originalContent;
                    }
                });
            }

            // Make function globally available
            window.batchUpdateBOMCosts = batchUpdateBOMCosts;
        </script>
    @endpush
</x-app-layout>
