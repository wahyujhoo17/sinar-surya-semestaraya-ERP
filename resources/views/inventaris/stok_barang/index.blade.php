<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    {{-- Add x-cloak style at the top of the document for immediate effect --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        .tab-transition-enter-active,
        .tab-transition-leave-active {
            transition: all 0.3s ease-out;
        }

        .tab-transition-enter-from,
        .tab-transition-leave-to {
            opacity: 0;
            transform: translateY(10px);
        }

        .tab-transition-enter-to,
        .tab-transition-leave-from {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="{
        activeTab: '{{ $gudangs->first()->id ?? 'none' }}',
        isLoading: false,
        switchTab(tabId) {
            this.isLoading = true;
            this.activeTab = tabId;
            // Small delay to allow for smooth transition
            setTimeout(() => {
                this.isLoading = false;
            }, 150);
        }
    }">
        {{-- Enhanced Overview Header --}}
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0v10l-8 4m0-10L4 7m8 4v10" />
                        </svg>
                        Stok Barang per Gudang
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Lihat jumlah stok barang yang tersedia di setiap gudang. Pilih gudang menggunakan tab di bawah
                        untuk
                        melihat detail stok yang tersedia.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    @if (auth()->user()->hasPermission('stok_barang.create'))
                        {{-- Action Buttons --}}
                        <button onclick="openInitializeModal()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Inisialisasi Stok
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if ($gudangs->isEmpty())
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300">
                <div class="p-8 sm:p-10 text-gray-900 dark:text-gray-100">
                    <div class="text-center py-12">
                        <div class="bg-gray-50 dark:bg-gray-700/50 inline-flex rounded-full p-4">
                            <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Tidak ada data gudang</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Belum ada gudang yang terdaftar untuk
                            menampilkan stok.</p>
                        <div class="mt-6">
                            <a href="#"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Tambah Gudang Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Enhanced Tab Buttons with x-cloak --}}
            <div class="mb-6 relative">
                <ul class="flex flex-wrap gap-2 text-sm font-medium" role="tablist">
                    @foreach ($gudangs as $gudang)
                        <li role="presentation">
                            <button @click="switchTab('{{ $gudang->id }}')"
                                :class="{
                                    'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': activeTab ==
                                        '{{ $gudang->id }}',
                                    'bg-white hover:bg-gray-50 text-gray-700 border-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300 dark:border-gray-600': activeTab !=
                                        '{{ $gudang->id }}'
                                }"
                                class="inline-flex items-center px-4 py-3 border-b-2 rounded-t-lg font-medium transition-all duration-150 ease-in-out"
                                type="button" role="tab" :aria-selected="activeTab == '{{ $gudang->id }}'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor"
                                    :class="{
                                        'text-primary-600 dark:text-primary-500': activeTab == '{{ $gudang->id }}',
                                        'text-gray-500 dark:text-gray-400': activeTab != '{{ $gudang->id }}'
                                    }">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                {{ $gudang->nama }}
                                <span class="ml-2 px-2.5 py-0.5 text-xs font-semibold rounded-full"
                                    :class="{
                                        'bg-primary-100 text-primary-700 dark:bg-primary-700 dark:text-primary-100': activeTab ==
                                            '{{ $gudang->id }}',
                                        'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200': activeTab !=
                                            '{{ $gudang->id }}'
                                    }">
                                    {{ $gudang->stok->count() }}
                                </span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Enhanced Tab Content Panels --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300">
                <div class="p-6 sm:p-7 text-gray-900 dark:text-gray-100 relative">
                    {{-- Global Loading Overlay --}}
                    <div x-show="isLoading" x-cloak
                        class="absolute inset-0 bg-white bg-opacity-60 dark:bg-gray-800 dark:bg-opacity-60 z-10 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="inline-block animate-spin h-8 w-8 text-primary-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Memuat data...</p>
                        </div>
                    </div>

                    @foreach ($gudangs as $gudang)
                        @php
                            // Prepare data for Alpine.js
                            $jsStokData = $gudang->stok->map(function ($itemStok) {
                                return [
                                    'id' => $itemStok->id,
                                    'produk_kode' => $itemStok->produk->kode ?? '-',
                                    'produk_nama' => $itemStok->produk->nama ?? 'Produk tidak ditemukan',
                                    'jumlah' => (int) $itemStok->jumlah,
                                    'satuan_nama' => $itemStok->produk->satuan->nama ?? '-',
                                    'lokasi_rak' => $itemStok->lokasi_rak ?? '-',
                                    'batch_number' => $itemStok->batch_number ?? '-',
                                ];
                            });

                            $jsonData = json_encode($jsStokData);
                        @endphp

                        <div x-show="!isLoading && activeTab == '{{ $gudang->id }}'" role="tabpanel" x-cloak
                            x-data='{
                                searchTerm: "",
                                originalStok: {{ $jsonData }},
                                sortColumn: "produk_kode",
                                sortDirection: "asc",
                                isInitialized: false,
                                init() {
                                    this.isInitialized = true;
                                },
                                sortBy(column) {
                                    if (this.sortColumn === column) {
                                        this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
                                    } else {
                                        this.sortColumn = column;
                                        this.sortDirection = "asc";
                                    }
                                },
                                get filteredStok() {
                                    if (this.searchTerm === "") {
                                        return this.sortedData;
                                    }
                                    const term = this.searchTerm.toLowerCase();
                                    return this.sortedData.filter(item => {
                                        return (item.produk_kode && item.produk_kode.toLowerCase().includes(term)) ||
                                               (item.produk_nama && item.produk_nama.toLowerCase().includes(term))
||
                                               (item.lokasi_rak && item.lokasi_rak.toLowerCase().includes(term)) ||
                                               (item.batch_number && item.batch_number.toLowerCase().includes(term));
                                    });
                                },
                                get sortedData() {
                                    return [...this.originalStok].sort((a, b) => {
                                        let modifier = this.sortDirection === "asc" ? 1 : -1;
                                        
                                        // Handle numeric sorting for jumlah
                                        if (this.sortColumn === "jumlah") {
                                            return modifier * (Number(a[this.sortColumn]) - Number(b[this.sortColumn]));
                                        }
                                        
                                        // Handle string sorting
                                        if (a[this.sortColumn] < b[this.sortColumn]) return -1 * modifier;
                                        if (a[this.sortColumn] > b[this.sortColumn]) return 1 * modifier;
                                        return 0;
                                    });
                                },
                                exportToCSV() {
                                    const headers = ["No", "Kode Produk", "Nama Produk", "Jumlah Stok", "Satuan", "Lokasi Rak", "Batch Number"];
                                    const csvData = [headers];
                                    
                                    this.filteredStok.forEach((item, index) => {
                                        csvData.push([
                                            index + 1,
                                            item.produk_kode,
                                            item.produk_nama,
                                            item.jumlah,
                                            item.satuan_nama,
                                            item.lokasi_rak,
                                            item.batch_number
                                        ]);
                                    });
                                    
                                    let csvContent = "data:text/csv;charset=utf-8," + 
                                        csvData.map(e => e.join(",")).join("\n");
                                    
                                    const encodedUri = encodeURI(csvContent);
                                    const link = document.createElement("a");
                                    link.setAttribute("href", encodedUri);
                                    link.setAttribute("download", `stok-gudang-{{ $gudang->nama }}.csv`);
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                }
                            }'
                            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="tab-content">

                            <div
                                class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                <div>
                                    <h3
                                        class="text-xl font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                        {{ $gudang->nama }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        <span class="font-medium">{{ $gudang->kode }}</span> - {{ $gudang->alamat }}
                                    </p>
                                </div>
                                @if (!$gudang->stok->isEmpty())
                                    <div class="mt-3 sm:mt-0 w-full sm:w-auto flex flex-col sm:flex-row gap-2">
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <input type="text" x-model.debounce.300ms="searchTerm"
                                                placeholder="Cari Kode/Nama/Lokasi..."
                                                class="block w-full pl-10 px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                        </div>
                                        <div class="flex justify-end">
                                            <button @click="exportToCSV()"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Export
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($gudang->stok->isEmpty())
                                <div
                                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 text-center">
                                    <div
                                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Gudang Kosong
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada stok barang di
                                        gudang ini.</p>
                                    <div class="mt-6">
                                        <a href="#"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            Tambah Stok Barang
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-4">
                                    <div
                                        class="p-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
                                        <div
                                            class="flex flex-wrap items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center gap-2 mb-2 sm:mb-0">
                                                <span>Sort by:</span>
                                                <div class="flex flex-wrap gap-1">
                                                    <button @click="sortBy('produk_kode')"
                                                        :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortColumn === 'produk_kode' }"
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium border dark:border-gray-600">
                                                        Kode
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1"
                                                            :class="sortColumn === 'produk_kode' ? 'opacity-100' : 'opacity-0'"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                :d="sortDirection === 'asc' ?
                                                                    'M8 9l4-4 4 4m0 6l-4 4-4-4' :
                                                                    'M16 15l-4 4-4-4m0-6l4-4 4 4'" />
                                                        </svg>
                                                    </button>
                                                    <button @click="sortBy('produk_nama')"
                                                        :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortColumn === 'produk_nama' }"
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium border dark:border-gray-600">
                                                        Nama
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1"
                                                            :class="sortColumn === 'produk_nama' ? 'opacity-100' : 'opacity-0'"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                :d="sortDirection === 'asc' ?
                                                                    'M8 9l4-4 4 4m0 6l-4 4-4-4' :
                                                                    'M16 15l-4 4-4-4m0-6l4-4 4 4'" />
                                                        </svg>
                                                    </button>
                                                    <button @click="sortBy('jumlah')"
                                                        :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortColumn === 'jumlah' }"
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium border dark:border-gray-600">
                                                        Jumlah
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1"
                                                            :class="sortColumn === 'jumlah' ? 'opacity-100' : 'opacity-0'"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                :d="sortDirection === 'asc' ?
                                                                    'M8 9l4-4 4 4m0 6l-4 4-4-4' :
                                                                    'M16 15l-4 4-4-4m0-6l4-4 4 4'" />
                                                        </svg>
                                                    </button>
                                                    <button @click="sortBy('lokasi_rak')"
                                                        :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortColumn === 'lokasi_rak' }"
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium border dark:border-gray-600">
                                                        Lokasi
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1"
                                                            :class="sortColumn === 'lokasi_rak' ? 'opacity-100' : 'opacity-0'"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                :d="sortDirection === 'asc' ?
                                                                    'M8 9l4-4 4 4m0 6l-4 4-4-4' :
                                                                    'M16 15l-4 4-4-4m0-6l4-4 4 4'" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button @click="sortDirection = 'asc'"
                                                    :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortDirection === 'asc' }"
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium border dark:border-gray-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                                    </svg>
                                                    A-Z
                                                </button>
                                                <button @click="sortDirection = 'desc'"
                                                    :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortDirection === 'desc' }"
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium border dark:border-gray-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                                                    </svg>
                                                    Z-A
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="overflow-x-auto" x-show="isInitialized"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform translate-y-4"
                                        x-transition:enter-end="opacity-100 transform translate-y-0">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                                <tr>
                                                    <th scope="col"
                                                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">
                                                        No</th>
                                                    <th scope="col"
                                                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer group"
                                                        @click="sortBy('produk_kode')">
                                                        <div class="flex items-center">
                                                            Kode Produk
                                                            <span
                                                                class="ml-1 inline-flex opacity-0 group-hover:opacity-100"
                                                                :class="{ 'opacity-100': sortColumn === 'produk_kode' }">
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'asc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'produk_kode' &&
                                                                        sortDirection === 'asc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'desc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'produk_kode' &&
                                                                        sortDirection === 'desc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer group"
                                                        @click="sortBy('produk_nama')">
                                                        <div class="flex items-center">
                                                            Nama Produk
                                                            <span
                                                                class="ml-1 inline-flex opacity-0 group-hover:opacity-100"
                                                                :class="{ 'opacity-100': sortColumn === 'produk_nama' }">
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'asc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'produk_nama' &&
                                                                        sortDirection === 'asc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'desc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'produk_nama' &&
                                                                        sortDirection === 'desc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer group"
                                                        @click="sortBy('jumlah')">
                                                        <div class="flex items-center">
                                                            Jumlah Stok
                                                            <span
                                                                class="ml-1 inline-flex opacity-0 group-hover:opacity-100"
                                                                :class="{ 'opacity-100': sortColumn === 'jumlah' }">
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'asc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'jumlah' &&
                                                                        sortDirection === 'asc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'desc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'jumlah' &&
                                                                        sortDirection === 'desc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                        Satuan</th>
                                                    <th scope="col"
                                                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer group"
                                                        @click="sortBy('lokasi_rak')">
                                                        <div class="flex items-center">
                                                            Lokasi Rak
                                                            <span
                                                                class="ml-1 inline-flex opacity-0 group-hover:opacity-100"
                                                                :class="{ 'opacity-100': sortColumn === 'lokasi_rak' }">
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'asc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'lokasi_rak' &&
                                                                        sortDirection === 'asc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'desc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'lokasi_rak' &&
                                                                        sortDirection === 'desc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </th>
                                                    <th scope="col"
                                                        class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer group"
                                                        @click="sortBy('batch_number')">
                                                        <div class="flex items-center">
                                                            Batch Number
                                                            <span
                                                                class="ml-1 inline-flex opacity-0 group-hover:opacity-100"
                                                                :class="{ 'opacity-100': sortColumn === 'batch_number' }">
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'asc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'batch_number' &&
                                                                        sortDirection === 'asc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                                <svg class="h-4 w-4"
                                                                    :class="sortDirection === 'desc' ? 'text-gray-500' :
                                                                        'text-gray-300'"
                                                                    viewBox="0 0 20 20" fill="currentColor"
                                                                    :style="sortColumn === 'batch_number' &&
                                                                        sortDirection === 'desc' ? '' :
                                                                        'display: none'">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody
                                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                <template x-for="(itemStok, index) in filteredStok"
                                                    :key="itemStok.id">
                                                    <tr
                                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500 dark:text-gray-400"
                                                            x-text="index + 1"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600 dark:text-primary-500"
                                                            x-text="itemStok.produk_kode"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                                            x-text="itemStok.produk_nama"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <span
                                                                class="px-3 py-1 inline-flex text-sm leading-5 font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100"
                                                                x-text="itemStok.jumlah"></span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                            x-text="itemStok.satuan_nama"></td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            <span class="inline-flex items-center"
                                                                x-show="itemStok.lokasi_rak !== '-'">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-4 w-4 mr-1 text-gray-400" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                                <span x-text="itemStok.lokasi_rak"></span>
                                                            </span>
                                                            <span x-show="itemStok.lokasi_rak === '-'"
                                                                class="text-gray-400">Tidak ada</span>
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                            <span x-show="itemStok.batch_number !== '-'"
                                                                class="px-2.5 py-0.5 rounded-full text-xs bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100"
                                                                x-text="itemStok.batch_number"></span>
                                                            <span x-show="itemStok.batch_number === '-'"
                                                                class="text-gray-400">-</span>
                                                        </td>
                                                    </tr>
                                                </template>

                                                <template x-if="filteredStok.length === 0 && searchTerm !== ''">
                                                    <tr>
                                                        <td colspan="7" class="px-6 py-8 text-center">
                                                            <div class="flex flex-col items-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-10 w-10 text-gray-300 dark:text-gray-500"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="1.5"
                                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                                </svg>
                                                                <p class="mt-2 text-gray-500 dark:text-gray-400">Tidak
                                                                    ada
                                                                    produk yang cocok dengan pencarian "<span
                                                                        class="font-medium"
                                                                        x-text="searchTerm"></span>".
                                                                </p>
                                                                <button @click="searchTerm = ''"
                                                                    class="mt-2 text-primary-600 dark:text-primary-500 hover:underline text-sm font-medium">
                                                                    Hapus pencarian
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </template>

                                                <template
                                                    x-if="filteredStok.length === 0 && searchTerm === '' && originalStok.length > 0">
                                                    <tr>
                                                        <td colspan="7"
                                                            class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                                            Tidak ada data stok untuk ditampilkan.
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg"
                                    x-show="isInitialized"
                                    x-transition:enter="transition ease-out duration-300 delay-150"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    <div>
                                        <p>Menampilkan <span class="font-semibold"
                                                x-text="filteredStok.length"></span>
                                            dari <span class="font-semibold" x-text="originalStok.length"></span>
                                            total item stok
                                            <span x-show="searchTerm !== ''" class="italic">
                                                (filter: "<span x-text="searchTerm"></span>")
                                            </span>
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Klik header kolom untuk mengurutkan data
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
    </div>

    {{-- Modal Konfirmasi Inisialisasi Stok --}}
    <div id="initializeModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 xl:w-2/5 shadow-lg rounded-lg bg-white dark:bg-gray-800">
            {{-- Loading Overlay --}}
            <div id="modalLoadingOverlay"
                class="absolute inset-0 bg-white bg-opacity-75 dark:bg-gray-800 dark:bg-opacity-75 z-10 rounded-lg hidden">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="inline-block animate-spin h-12 w-12 text-blue-600 mb-4"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="text-gray-700 dark:text-gray-300 font-medium">Memproses inisialisasi stok...</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mohon tunggu, proses ini mungkin
                            membutuhkan waktu beberapa saat.</p>
                    </div>
                </div>
            </div>

            <div class="mt-3" id="modalContent">
                {{-- Header --}}
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                Konfirmasi Inisialisasi Stok
                            </h3>
                        </div>
                    </div>
                    <button onclick="closeInitializeModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="mt-4">
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-yellow-600 dark:text-yellow-400 mt-0.5 mr-3 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-1">
                                    Perhatian!
                                </h4>
                                <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                    Proses ini akan membuat record stok dengan nilai <strong>0 (nol)</strong> untuk
                                    semua produk yang belum memiliki stok di semua gudang.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Info Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mr-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0v10l-8 4m0-10L4 7m8 4v10" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">Total Produk Aktif
                                    </p>
                                    <p class="text-lg font-bold text-blue-900 dark:text-blue-100" id="totalProducts">
                                        <span class="inline-flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                            Loading...
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400 mr-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m5 0v-9a1 1 0 00-1-1h-1a1 1 0 00-1 1v9m1 0h2m0 0h2m-2 0h-2" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">Total Gudang</p>
                                    <p class="text-lg font-bold text-green-900 dark:text-green-100"
                                        id="totalWarehouses">{{ $gudangs->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-6">
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Apa yang akan terjadi:</h5>
                        <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Sistem akan memeriksa semua produk aktif
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Membuat record stok 0 untuk produk yang belum ada stoknya
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Semua produk akan muncul di halaman stok barang
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Produk yang sudah ada stok tidak akan terpengaruh
                            </li>
                        </ul>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeInitializeModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 hover:bg-gray-200 dark:hover:bg-gray-500 rounded-md transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </button>
                        <button type="button" onclick="confirmInitialize()" id="confirmBtn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed rounded-md transition-colors duration-200 flex items-center min-w-[180px] justify-center">
                            <span id="confirmText" class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Ya, Inisialisasi Stok
                            </span>
                            <span id="confirmLoading" class="hidden">
                                <span class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Memproses...
                                </span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        // Modal functions
        function openInitializeModal() {
            document.getElementById('initializeModal').classList.remove('hidden');
            // Load total products count
            loadProductCount();
        }

        function closeInitializeModal() {
            document.getElementById('initializeModal').classList.add('hidden');
        }

        // Load product count for display in modal
        function loadProductCount() {
            fetch('{{ route('inventaris.produk.count') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('totalProducts').innerHTML = data.count;
                    } else {
                        document.getElementById('totalProducts').innerHTML = '<span class="text-red-500">Error</span>';
                    }
                })
                .catch(error => {
                    console.error('Error loading product count:', error);
                    document.getElementById('totalProducts').innerHTML = '<span class="text-red-500">-</span>';
                });
        }

        // Confirm and process initialization
        function confirmInitialize() {
            const confirmBtn = document.getElementById('confirmBtn');
            const confirmText = document.getElementById('confirmText');
            const confirmLoading = document.getElementById('confirmLoading');
            const modalOverlay = document.getElementById('modalLoadingOverlay');

            // Show loading overlay
            modalOverlay.classList.remove('hidden');

            // Disable button and show loading state
            confirmBtn.disabled = true;
            confirmText.classList.add('hidden');
            confirmLoading.classList.remove('hidden');

            fetch('{{ route('inventaris.stok.initialize') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loading overlay
                    modalOverlay.classList.add('hidden');

                    if (data.success) {
                        // Show success message
                        showNotification('success',
                            `Stok berhasil diinisialisasi untuk ${data.created} kombinasi produk-gudang.`);

                        // Close modal and reload page after delay
                        setTimeout(() => {
                            closeInitializeModal();
                            location.reload();
                        }, 2000);
                    } else {
                        showNotification('error', 'Error: ' + data.message);
                        resetConfirmButton();
                    }
                })
                .catch(error => {
                    // Hide loading overlay
                    modalOverlay.classList.add('hidden');
                    console.error('Error:', error);
                    showNotification('error', 'Terjadi kesalahan saat menginisialisasi stok.');
                    resetConfirmButton();
                });
        }

        // Reset confirm button state
        function resetConfirmButton() {
            const confirmBtn = document.getElementById('confirmBtn');
            const confirmText = document.getElementById('confirmText');
            const confirmLoading = document.getElementById('confirmLoading');
            const modalOverlay = document.getElementById('modalLoadingOverlay');

            // Hide loading overlay
            modalOverlay.classList.add('hidden');

            // Reset button state
            confirmBtn.disabled = false;
            confirmText.classList.remove('hidden');
            confirmLoading.classList.add('hidden');
        }

        // Show notification (toast-style notification)
        function showNotification(type, message) {
            // Create toast notification
            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 z-50 max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;

            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ?
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';

            toast.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="${bgColor} rounded-full p-1">
                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    ${icon}
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                ${type === 'success' ? 'Berhasil!' : 'Error!'}
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                                ${message}
                            </p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button class="rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 5000);
        }

        // Close modal when clicking outside
        document.getElementById('initializeModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInitializeModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeInitializeModal();
            }
        });
    </script>
    @endif
</x-app-layout>
