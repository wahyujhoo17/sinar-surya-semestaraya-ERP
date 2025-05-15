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
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Penyesuaian Stok
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Kelola penyesuaian stok untuk memperbaiki ketidaksesuaian antara pencatatan sistem dan stok
                        fisik.
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('inventaris.penyesuaian-stok.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Penyesuaian Baru
                    </a>
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
                <form action="{{ route('inventaris.penyesuaian-stok.index') }}" method="GET"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Status Filter --}}
                    <div>
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="draft" {{ $statusFilter === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="selesai" {{ $statusFilter === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    {{-- Gudang Filter --}}
                    <div>
                        <label for="gudang_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gudang</label>
                        <select id="gudang_id" name="gudang_id"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Gudang</option>
                            @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}"
                                    {{ request('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                    {{ $gudang->nama }}
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
                        <a href="{{ route('inventaris.penyesuaian-stok.index') }}"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                            Reset
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700">
            @if ($penyesuaianStok->count() > 0)
                @php
                    $penyesuaianData = $penyesuaianStok->map(function ($penyesuaian) {
                        return [
                            'id' => $penyesuaian->id,
                            'nomor' => $penyesuaian->nomor,
                            'tanggal' => $penyesuaian->tanggal,
                            'tanggal_formatted' => \Carbon\Carbon::parse($penyesuaian->tanggal)->format('d M Y'),
                            'gudang' => $penyesuaian->gudang->nama,
                            'user' => $penyesuaian->user->name,
                            'status' => $penyesuaian->status,
                            'jumlah_produk' => $penyesuaian->details->count(),
                        ];
                    });
                    $jsonPenyesuaian = json_encode($penyesuaianData);
                @endphp

                <div x-data="{
                    penyesuaian: {{ $jsonPenyesuaian }},
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
                
                    get filteredPenyesuaian() {
                        if (!this.searchTerm) return this.sortedPenyesuaian;
                
                        const term = this.searchTerm.toLowerCase();
                        return this.sortedPenyesuaian.filter(item => {
                            return item.nomor.toLowerCase().includes(term) ||
                                item.gudang.toLowerCase().includes(term) ||
                                item.status.toLowerCase().includes(term) ||
                                item.user.toLowerCase().includes(term);
                        });
                    },
                
                    get sortedPenyesuaian() {
                        return [...this.penyesuaian].sort((a, b) => {
                            const modifier = this.sortDirection === 'asc' ? 1 : -1;
                
                            // Handle date sorting
                            if (this.sortColumn === 'tanggal') {
                                return modifier * (new Date(a[this.sortColumn]) - new Date(b[this.sortColumn]));
                            }
                
                            // Handle number sorting
                            if (this.sortColumn === 'jumlah_produk') {
                                return modifier * (a[this.sortColumn] - b[this.sortColumn]);
                            }
                
                            // Handle string sorting
                            if (a[this.sortColumn] < b[this.sortColumn]) return -1 * modifier;
                            if (a[this.sortColumn] > b[this.sortColumn]) return 1 * modifier;
                            return 0;
                        });
                    },
                
                    exportToCSV() {
                        const headers = ['Nomor', 'Tanggal', 'Gudang', 'User', 'Status', 'Jumlah Produk'];
                        const csvData = [headers];
                
                        this.filteredPenyesuaian.forEach(item => {
                            csvData.push([
                                item.nomor,
                                item.tanggal_formatted,
                                item.gudang,
                                item.user,
                                item.status === 'draft' ? 'Draft' : 'Selesai',
                                item.jumlah_produk
                            ]);
                        });
                
                        let csvContent = 'data:text/csv;charset=utf-8,' +
                            csvData.map(e => e.join(',')).join('\n');
                
                        const encodedUri = encodeURI(csvContent);
                        const link = document.createElement('a');
                        link.setAttribute('href', encodedUri);
                        link.setAttribute('download', `penyesuaian-stok-{{ date('Y-m-d') }}.csv`);
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                }" class="p-4">

                    <div
                        class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                Daftar Penyesuaian Stok
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Total: {{ $penyesuaianStok->count() }} penyesuaian
                            </p>
                        </div>

                        <div class="mt-3 sm:mt-0 w-full sm:w-auto flex flex-col sm:flex-row gap-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" x-model.debounce.300ms="searchTerm"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Cari penyesuaian stok...">
                            </div>
                            <div class="flex justify-end">
                                <button @click="exportToCSV()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export CSV
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span>Menampilkan data penyesuaian stok</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span>Filter aktif:</span>
                                    <span class="font-medium">{{ request('status', 'semua') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto" x-show="isInitialized"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0">
                            <table class="min-w-full border-separate border-spacing-0">
                                <thead>
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 cursor-pointer group"
                                            @click="sortBy('nomor')">
                                            <div class="flex items-center">
                                                <span>Nomor</span>
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
                                            @click="sortBy('gudang')">
                                            <div class="flex items-center">
                                                <span>Gudang</span>
                                                <span
                                                    class="ml-1 inline-flex opacity-0 group-hover:opacity-100 transition-opacity"
                                                    :class="{ 'opacity-100': sortColumn === 'gudang' }">
                                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor"
                                                        :class="{ 'rotate-180': sortDirection === 'desc' }"
                                                        viewBox="0 0 20 20"
                                                        :style="sortColumn === 'gudang' ? '' : 'opacity: 0.5'">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 cursor-pointer group"
                                            @click="sortBy('user')">
                                            <div class="flex items-center">
                                                <span>User</span>
                                                <span
                                                    class="ml-1 inline-flex opacity-0 group-hover:opacity-100 transition-opacity"
                                                    :class="{ 'opacity-100': sortColumn === 'user' }">
                                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor"
                                                        :class="{ 'rotate-180': sortDirection === 'desc' }"
                                                        viewBox="0 0 20 20"
                                                        :style="sortColumn === 'user' ? '' : 'opacity: 0.5'">
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
                                            class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 cursor-pointer group"
                                            @click="sortBy('jumlah_produk')">
                                            <div class="flex items-center">
                                                <span>Jumlah Produk</span>
                                                <span
                                                    class="ml-1 inline-flex opacity-0 group-hover:opacity-100 transition-opacity"
                                                    :class="{ 'opacity-100': sortColumn === 'jumlah_produk' }">
                                                    <svg class="h-4 w-4 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor"
                                                        :class="{ 'rotate-180': sortDirection === 'desc' }"
                                                        viewBox="0 0 20 20"
                                                        :style="sortColumn === 'jumlah_produk' ? '' : 'opacity: 0.5'">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in filteredPenyesuaian" :key="item.id">
                                        <tr :class="{
                                            'bg-gray-50 dark:bg-gray-700/20': index % 2 === 0,
                                            'bg-white dark:bg-gray-800': index % 2 === 1
                                        }"
                                            class="transition hover:bg-gray-100 dark:hover:bg-gray-700/40">
                                            <td :class="{ 'rounded-tl-lg': index === 0 }"
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700"
                                                x-text="item.nomor"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700"
                                                x-text="item.tanggal_formatted"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700"
                                                x-text="item.gudang"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700"
                                                x-text="item.user"></td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                                                <template x-if="item.status === 'draft'">
                                                    <span
                                                        class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-700/50 dark:text-yellow-100 border border-yellow-200 dark:border-yellow-600">
                                                        Draft
                                                    </span>
                                                </template>
                                                <template x-if="item.status === 'selesai'">
                                                    <span
                                                        class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-700/50 dark:text-green-100 border border-green-200 dark:border-green-600">
                                                        Selesai
                                                    </span>
                                                </template>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                                                <span x-text="`${item.jumlah_produk} produk`"></span>
                                            </td>
                                            <td :class="{ 'rounded-tr-lg': index === 0 }"
                                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-b border-gray-200 dark:border-gray-700">
                                                <div class="flex justify-end space-x-2">
                                                    {{-- View button --}}
                                                    <a :href="'{{ url('inventaris/penyesuaian-stok') }}/' + item.id"
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300 opacity-90 hover:opacity-100"
                                                        title="Lihat Detail">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" class="w-4 h-4">
                                                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                            <path fill-rule="evenodd"
                                                                d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>

                                                    {{-- Edit and Delete buttons (only visible for draft status) --}}
                                                    <template x-if="item.status === 'draft'">
                                                        <div class="flex space-x-2">
                                                            <a :href="'{{ url('inventaris/penyesuaian-stok') }}/' + item.id +
                                                                '/edit'"
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

                                                            <form
                                                                :action="'{{ url('inventaris/penyesuaian-stok') }}/' + item.id"
                                                                method="POST" class="inline"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus penyesuaian stok ini?');">
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
                                                        </div>
                                                    </template>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>

                                    <template x-if="filteredPenyesuaian.length === 0 && searchTerm !== ''">
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
                                <span class="font-semibold" x-text="filteredPenyesuaian.length"></span>
                                <span>dari</span>
                                <span class="font-semibold" x-text="penyesuaian.length"></span>
                                <span>total penyesuaian stok</span>
                                <span x-show="searchTerm !== ''" class="italic ml-1">
                                    (filter: "<span x-text="searchTerm"></span>")
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
                                    x-text="sortDirection"></span>)
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
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum ada data penyesuaian stok
                    </h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Belum ada penyesuaian stok yang tercatat.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('inventaris.penyesuaian-stok.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Penyesuaian Stok
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
