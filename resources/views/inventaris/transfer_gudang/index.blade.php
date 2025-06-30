<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
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

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Enhanced Overview Header --}}
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Transfer Antar Gudang
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Kelola perpindahan barang antar gudang untuk mengoptimalkan distribusi stok.
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    @if (auth()->user()->hasPermission('transfer_gudang.create'))
                        <a href="{{ route('inventaris.transfer-gudang.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Transfer Baru
                        </a>
                    @else
                        <button disabled
                            class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-lg font-semibold text-gray-500 cursor-not-allowed shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m0 0v-2m0 2l4-4m-4 4l-4-4" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-1 h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-3 4h6m-6 0h6m3-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tidak Ada Akses
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div x-data="{ showFilters: false }"
            class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center cursor-pointer" @click="showFilters = !showFilters">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Filter</h3>
                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span x-show="!showFilters">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span x-show="showFilters">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                </button>
            </div>

            <div x-cloak x-show="showFilters" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-4">
                <form action="{{ route('inventaris.transfer-gudang.index') }}" method="GET"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Status Filter --}}
                    <div>
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Sedang
                                Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                        </select>
                    </div>

                    {{-- Gudang Filter --}}
                    <div>
                        <label for="gudang"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gudang</label>
                        <select id="gudang" name="gudang"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Gudang</option>
                            @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}"
                                    {{ request('gudang') == $gudang->id ? 'selected' : '' }}>{{ $gudang->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date Range Filter --}}
                    <div>
                        <label for="tanggal_mulai"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                            value="{{ request('tanggal_mulai') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="tanggal_akhir"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                            value="{{ request('tanggal_akhir') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="col-span-1 sm:col-span-2 lg:col-span-4 flex justify-end space-x-3">
                        <button type="reset"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                            Reset
                        </button>
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Transfer List with Alpine.js --}}
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700">
            @if ($transfers->count() > 0)
                @php
                    $transfersData = $transfers->map(function ($transfer) {
                        return [
                            'id' => $transfer->id,
                            'nomor' => $transfer->nomor,
                            'tanggal' => $transfer->tanggal,
                            'tanggal_formatted' => \Carbon\Carbon::parse($transfer->tanggal)->format('d M Y'),
                            'gudang_asal' => $transfer->gudangAsal->nama,
                            'gudang_tujuan' => $transfer->gudangTujuan->nama,
                            'status' => $transfer->status,
                            'user_name' => $transfer->user->name,
                        ];
                    });
                    $jsonTransfers = json_encode($transfersData);
                @endphp

                <div x-data="{
                    transfers: {{ $jsonTransfers }},
                    searchTerm: '',
                    sortColumn: 'nomor',
                    sortDirection: 'desc',
                    isInitialized: false,
                
                    init() {
                        setTimeout(() => { this.isInitialized = true; }, 100);
                    },
                
                    sortBy(column) {
                        if (this.sortColumn === column) {
                            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sortColumn = column;
                            this.sortDirection = 'asc';
                        }
                    },
                
                    get filteredTransfers() {
                        if (!this.searchTerm) return this.sortedTransfers;
                
                        const term = this.searchTerm.toLowerCase();
                        return this.sortedTransfers.filter(item => {
                            return item.nomor.toLowerCase().includes(term) ||
                                item.gudang_asal.toLowerCase().includes(term) ||
                                item.gudang_tujuan.toLowerCase().includes(term) ||
                                item.status.toLowerCase().includes(term) ||
                                item.user_name.toLowerCase().includes(term);
                        });
                    },
                
                    get sortedTransfers() {
                        return [...this.transfers].sort((a, b) => {
                            const modifier = this.sortDirection === 'asc' ? 1 : -1;
                
                            // Handle date sorting
                            if (this.sortColumn === 'tanggal') {
                                return modifier * (new Date(a[this.sortColumn]) - new Date(b[this.sortColumn]));
                            }
                
                            // Handle string sorting
                            if (a[this.sortColumn] < b[this.sortColumn]) return -1 * modifier;
                            if (a[this.sortColumn] > b[this.sortColumn]) return 1 * modifier;
                            return 0;
                        });
                    },
                
                    exportToCSV() {
                        const headers = ['Nomor Transfer', 'Tanggal', 'Gudang Asal', 'Gudang Tujuan', 'Status', 'Dibuat Oleh'];
                        const csvData = [headers];
                
                        this.filteredTransfers.forEach(item => {
                            csvData.push([
                                item.nomor,
                                item.tanggal_formatted,
                                item.gudang_asal,
                                item.gudang_tujuan,
                                item.status === 'draft' ? 'Draft' :
                                item.status === 'diproses' ? 'Sedang Diproses' : 'Selesai',
                                item.user_name
                            ]);
                        });
                
                        let csvContent = 'data:text/csv;charset=utf-8,' +
                            csvData.map(e => e.join(',')).join('\n');
                
                        const encodedUri = encodeURI(csvContent);
                        const link = document.createElement('a');
                        link.setAttribute('href', encodedUri);
                        link.setAttribute('download', `transfer-gudang-{{ date('Y-m-d') }}.csv`);
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                }" class="p-4">

                    <div
                        class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                Daftar Transfer
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Total: {{ $transfers->count() }} transfer
                            </p>
                        </div>

                        <div class="mt-3 sm:mt-0 w-full sm:w-auto flex flex-col sm:flex-row gap-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" x-model.debounce.300ms="searchTerm"
                                    placeholder="Cari Transfer..."
                                    class="block w-full pl-10 px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                            </div>
                            <div class="flex justify-end">
                                <button @click="exportToCSV()"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-4">
                        <div
                            class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 rounded-t-lg">
                            <div
                                class="flex flex-wrap items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-2 mb-2 sm:mb-0">
                                    <span class="font-medium">Urutkan:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        <button @click="sortBy('nomor')"
                                            :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortColumn === 'nomor' }"
                                            class="inline-flex items-center px-2 py-1.5 rounded text-xs font-medium border dark:border-gray-600 transition-all duration-200">
                                            Nomor
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3 w-3 ml-1 transition-opacity duration-200"
                                                :class="sortColumn === 'nomor' ? 'opacity-100' : 'opacity-0'"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    :d="sortDirection === 'asc' ?
                                                        'M8 9l4-4 4 4m0 6l-4 4-4-4' :
                                                        'M16 15l-4 4-4-4m0-6l4-4 4 4'" />
                                            </svg>
                                        </button>
                                        <button @click="sortBy('tanggal')"
                                            :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortColumn === 'tanggal' }"
                                            class="inline-flex items-center px-2 py-1.5 rounded text-xs font-medium border dark:border-gray-600 transition-all duration-200">
                                            Tanggal
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3 w-3 ml-1 transition-opacity duration-200"
                                                :class="sortColumn === 'tanggal' ? 'opacity-100' : 'opacity-0'"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    :d="sortDirection === 'asc' ?
                                                        'M8 9l4-4 4 4m0 6l-4 4-4-4' :
                                                        'M16 15l-4 4-4-4m0-6l4-4 4 4'" />
                                            </svg>
                                        </button>
                                        <button @click="sortBy('gudang_asal')"
                                            :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortColumn === 'gudang_asal' }"
                                            class="inline-flex items-center px-2 py-1.5 rounded text-xs font-medium border dark:border-gray-600 transition-all duration-200">
                                            Gudang Asal
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3 w-3 ml-1 transition-opacity duration-200"
                                                :class="sortColumn === 'gudang_asal' ? 'opacity-100' : 'opacity-0'"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    :d="sortDirection === 'asc' ?
                                                        'M8 9l4-4 4 4m0 6l-4 4-4-4' :
                                                        'M16 15l-4 4-4-4m0-6l4-4 4 4'" />
                                            </svg>
                                        </button>
                                        <button @click="sortBy('gudang_tujuan')"
                                            :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortColumn === 'gudang_tujuan' }"
                                            class="inline-flex items-center px-2 py-1.5 rounded text-xs font-medium border dark:border-gray-600 transition-all duration-200">
                                            Gudang Tujuan
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3 w-3 ml-1 transition-opacity duration-200"
                                                :class="sortColumn === 'gudang_tujuan' ? 'opacity-100' : 'opacity-0'"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                                        class="inline-flex items-center px-2 py-1.5 rounded text-xs font-medium border dark:border-gray-600 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                        </svg>
                                        A-Z
                                    </button>
                                    <button @click="sortDirection = 'desc'"
                                        :class="{ 'bg-primary-50 text-primary-700 border-primary-500 dark:bg-primary-900/30 dark:text-primary-400': sortDirection === 'desc' }"
                                        class="inline-flex items-center px-2 py-1.5 rounded text-xs font-medium border dark:border-gray-600 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                            <table class="min-w-full border-separate border-spacing-0">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 cursor-pointer group"
                                            @click="sortBy('nomor')">
                                            <div class="flex items-center">
                                                <span>Nomor Transfer</span>
                                                <span
                                                    class="ml-1 inline-flex opacity-0 group-hover:opacity-100 transition-opacity"
                                                    :class="{ 'opacity-100': sortColumn === 'nomor' }">
                                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor"
                                                        :class="{ 'rotate-180': sortDirection === 'desc' }"
                                                        viewBox="0 0 20 20"
                                                        :style="sortColumn === 'nomor' ? '' : 'opacity: 0.5'">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 cursor-pointer group"
                                            @click="sortBy('tanggal')">
                                            <div class="flex items-center">
                                                <span>Tanggal</span>
                                                <span
                                                    class="ml-1 inline-flex opacity-0 group-hover:opacity-100 transition-opacity"
                                                    :class="{ 'opacity-100': sortColumn === 'tanggal' }">
                                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor"
                                                        :class="{ 'rotate-180': sortDirection === 'desc' }"
                                                        viewBox="0 0 20 20"
                                                        :style="sortColumn === 'tanggal' ? '' : 'opacity: 0.5'">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 cursor-pointer group"
                                            @click="sortBy('gudang_asal')">
                                            <div class="flex items-center">
                                                <span>Gudang Asal</span>
                                                <span
                                                    class="ml-1 inline-flex opacity-0 group-hover:opacity-100 transition-opacity"
                                                    :class="{ 'opacity-100': sortColumn === 'gudang_asal' }">
                                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor"
                                                        :class="{ 'rotate-180': sortDirection === 'desc' }"
                                                        viewBox="0 0 20 20"
                                                        :style="sortColumn === 'gudang_asal' ? '' : 'opacity: 0.5'">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 cursor-pointer group"
                                            @click="sortBy('gudang_tujuan')">
                                            <div class="flex items-center">
                                                <span>Gudang Tujuan</span>
                                                <span
                                                    class="ml-1 inline-flex opacity-0 group-hover:opacity-100 transition-opacity"
                                                    :class="{ 'opacity-100': sortColumn === 'gudang_tujuan' }">
                                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor"
                                                        :class="{ 'rotate-180': sortDirection === 'desc' }"
                                                        viewBox="0 0 20 20"
                                                        :style="sortColumn === 'gudang_tujuan' ? '' : 'opacity: 0.5'">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                                            Status
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                                            Dibuat Oleh
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(transfer, index) in filteredTransfers" :key="transfer.id">
                                        <tr :class="{
                                            'bg-gray-50 dark:bg-gray-700/20': index % 2 ===
                                                0,
                                            'bg-white dark:bg-gray-800': index % 2 === 1
                                        }"
                                            class="transition hover:bg-gray-100 dark:hover:bg-gray-700/40">
                                            <td :class="{ 'rounded-tl-lg': index === 0 }"
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700"
                                                x-text="transfer.nomor"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700"
                                                x-text="transfer.tanggal_formatted"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700"
                                                x-text="transfer.gudang_asal"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700"
                                                x-text="transfer.gudang_tujuan"></td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                                                <template x-if="transfer.status === 'draft'">
                                                    <span
                                                        class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-700/50 dark:text-yellow-100 border border-yellow-200 dark:border-yellow-600">
                                                        Draft
                                                    </span>
                                                </template>
                                                <template x-if="transfer.status === 'diproses'">
                                                    <span
                                                        class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-700/50 dark:text-blue-100 border border-blue-200 dark:border-blue-600">
                                                        Sedang Diproses
                                                    </span>
                                                </template>
                                                <template x-if="transfer.status === 'selesai'">
                                                    <span
                                                        class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-700/50 dark:text-green-100 border border-green-200 dark:border-green-600">
                                                        Selesai
                                                    </span>
                                                </template>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700"
                                                x-text="transfer.user_name"></td>
                                            <td :class="{ 'rounded-tr-lg': index === 0 }"
                                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-b border-gray-200 dark:border-gray-700">
                                                <div class="flex justify-end space-x-2">
                                                    {{-- View button --}}
                                                    @if (auth()->user()->hasPermission('transfer_gudang.view'))
                                                        <a :href="'{{ url('inventaris/transfer-gudang') }}/' + transfer.id"
                                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300 opacity-90 hover:opacity-100"
                                                            title="Lihat Detail">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 20 20" fill="currentColor"
                                                                class="w-4 h-4">
                                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </a>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 border border-dashed border-gray-300 cursor-not-allowed"
                                                            title="Tidak Ada Akses">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 20 20" fill="currentColor"
                                                                class="w-4 h-4">
                                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    @endif

                                                    {{-- Edit and Delete buttons (only visible for draft status) --}}
                                                    <template x-if="transfer.status === 'draft'">
                                                        <div class="flex space-x-2">
                                                            @if (auth()->user()->hasPermission('transfer_gudang.edit'))
                                                                <a :href="'{{ url('inventaris/transfer-gudang') }}/' + transfer
                                                                    .id + '/edit'"
                                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-gray-700 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300 opacity-90 hover:opacity-100"
                                                                    title="Edit">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 20 20" fill="currentColor"
                                                                        class="w-4 h-4">
                                                                        <path
                                                                            d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                                        <path
                                                                            d="M3.5 5.75c0 .69.56 1.25 1.25 1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                                                    </svg>
                                                                </a>
                                                            @else
                                                                <span
                                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 border border-dashed border-gray-300 cursor-not-allowed"
                                                                    title="Tidak Ada Akses">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 20 20" fill="currentColor"
                                                                        class="w-4 h-4">
                                                                        <path
                                                                            d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                                        <path
                                                                            d="M3.5 5.75c0 .69.56 1.25 1.25 1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                                                    </svg>
                                                                </span>
                                                            @endif

                                                            @if (auth()->user()->hasPermission('transfer_gudang.delete'))
                                                                <form
                                                                    :action="'{{ url('inventaris/transfer-gudang') }}/' +
                                                                    transfer.id"
                                                                    method="POST" class="inline"
                                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus transfer ini?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-gray-700 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300 opacity-90 hover:opacity-100"
                                                                        title="Hapus">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            viewBox="0 0 20 20" fill="currentColor"
                                                                            class="w-4 h-4">
                                                                            <path fill-rule="evenodd"
                                                                                d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <span
                                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 border border-dashed border-gray-300 cursor-not-allowed"
                                                                    title="Tidak Ada Akses">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 20 20" fill="currentColor"
                                                                        class="w-4 h-4">
                                                                        <path fill-rule="evenodd"
                                                                            d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </template>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>

                                    <template x-if="filteredTransfers.length === 0 && searchTerm !== ''">
                                        <tr>
                                            <td colspan="7" class="px-6 py-10 text-center">
                                                <div
                                                    class="flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-700/20 rounded-lg py-8">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-12 w-12 text-gray-400 mb-3" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    <p
                                                        class="text-gray-500 dark:text-gray-400 text-base font-medium mb-1">
                                                        Tidak ada hasil untuk pencarian: "<span x-text="searchTerm"
                                                            class="font-semibold"></span>"</p>
                                                    <p class="text-gray-400 dark:text-gray-500 text-sm mb-4">
                                                        Coba menggunakan kata kunci lain atau filter yang berbeda
                                                    </p>
                                                    <button @click="searchTerm = ''"
                                                        class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 font-medium hover:bg-gray-50 dark:hover:bg-gray-650 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                                        Hapus pencarian
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-600 dark:text-gray-400 flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700"
                        x-show="isInitialized" x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <div class="flex flex-col">
                            <p class="flex items-center space-x-1">
                                <span>Menampilkan</span>
                                <span
                                    class="px-2 py-0.5 bg-white dark:bg-gray-800 rounded border border-gray-300 dark:border-gray-600 font-semibold text-primary-600 dark:text-primary-400"
                                    x-text="filteredTransfers.length"></span>
                                <span>dari</span>
                                <span class="font-semibold" x-text="transfers.length"></span>
                                <span>total transfer</span>
                                <span x-show="searchTerm !== ''" class="italic ml-1">
                                    (filter: "<span x-text="searchTerm" class="font-medium"></span>")
                                </span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Klik header kolom untuk mengurutkan data
                            </p>
                        </div>
                        <div>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-400 border border-primary-200 dark:border-primary-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                                Urutan: <span class="font-medium" x-text="sortColumn"></span> (<span
                                    x-text="sortDirection === 'asc' ? 'A-Z' : 'Z-A'"></span>)
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-8 sm:p-10 text-center">
                    <div class="bg-gray-50 dark:bg-gray-700/50 inline-flex rounded-full p-4 mx-auto">
                        <svg class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum ada data transfer barang
                    </h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Belum ada transfer barang yang tercatat.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('inventaris.transfer-gudang.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Transfer Baru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
