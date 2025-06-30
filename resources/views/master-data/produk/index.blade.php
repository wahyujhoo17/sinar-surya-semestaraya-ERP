<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Master Data Produk</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola data produk, kategori, dan inventori PT Sinar Surya Semestaraya
            </p>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8"> {{-- Adjusted gap --}}
            {{-- Total Products Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            {{-- Adjusted padding & rounding --}}
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="ml-4"> {{-- Adjusted margin --}}
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Produk</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $produks->total() }}
                                </p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                                {{-- Adjusted margin --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Categories Counter Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-purple-100 dark:bg-purple-900/30 p-3.5">
                            <svg class="h-7 w-7 text-purple-500 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kategori</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $kategoris->count() }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">kategori</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Active Products Card --}}
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
                                Produk Aktif</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Produk::where('is_active', true)->count() }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Quick Action Card (Made Fully Clickable) --}}
            @if (auth()->user()->hasPermission('produk.create'))
                <a href="#"
                    @click.prevent="window.dispatchEvent(new CustomEvent('open-produk-modal', {detail: {}}))"
                    class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 overflow-hidden shadow-lg rounded-xl transition-all duration-300 hover:shadow-md hover:translate-y-[-2px] group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white/80 uppercase tracking-wider">Aksi Cepat</p>
                                <p class="mt-1 text-lg font-semibold text-white">Tambah Produk Baru</p>
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
                <div class="bg-gray-300 dark:bg-gray-600 overflow-hidden shadow-lg rounded-xl opacity-60">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi Cepat</p>
                                <p class="mt-1 text-lg font-semibold text-gray-700 dark:text-gray-300">Tidak Ada Akses
                                </p>
                            </div>
                            <div
                                class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-gray-400/30 text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v7.5a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Main Content Area --}}
        <div x-data="productTableManager()"> {{-- Alpine component --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300">
                <div class="p-6 sm:p-7 text-gray-900 dark:text-gray-100">

                    {{-- Header with Quick Search and Action Buttons --}}
                    <div class="mb-6 sm:mb-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="mb-4 sm:mb-0">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white leading-tight">
                                    Katalog Produk</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua produk perusahaan
                                    Anda di sini</p>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                                {{-- Bulk Actions --}}
                                @if (auth()->user()->hasPermission('produk.delete'))
                                    <div x-show="selectedProducts.length > 0"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="flex items-center mr-3 bg-white dark:bg-gray-700/60 border border-gray-300 dark:border-gray-600 rounded-lg p-1 shadow-sm">
                                        <span class="px-2 text-sm text-gray-600 dark:text-gray-300"><span
                                                x-text="selectedProducts.length"></span> item dipilih</span>
                                        <button @click="confirmDeleteSelected"
                                            class="ml-1 px-2 py-1 text-xs text-white bg-red-600 hover:bg-red-700 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                            <span class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Hapus
                                            </span>
                                        </button>
                                    </div>
                                @endif

                                {{-- Column Selector Dropdown --}}
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h5a2 2 0 002-2V7a2 2 0 00-2-2h-5a2 2 0 00-2 2m0 10l-3-3m3 3l3-3" />
                                        </svg>
                                        Kolom
                                        <svg class="ml-1.5 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-transition
                                        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-40 max-h-96 overflow-y-auto"
                                        style="display: none;">
                                        <div class="py-1" role="menu" aria-orientation="vertical"
                                            aria-labelledby="options-menu">
                                            <template x-for="(isVisible, column) in visibleColumns"
                                                :key="column">
                                                <label
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 cursor-pointer">
                                                    <input type="checkbox" x-model="visibleColumns[column]"
                                                        class="mr-2 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                                    <span
                                                        x-text="column.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()).replace('Sku', 'SKU')"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Search Form - Tanpa tombol submit --}}
                                <div class="relative w-full sm:w-auto grow sm:grow-0">
                                    <div class="relative w-full">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                            </svg>
                                        </div>
                                        <input type="text" x-model.debounce.300ms="search"
                                            placeholder="Cari produk..."
                                            class="pl-10 pr-10 py-2 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white shadow-sm">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" @click="search = ''; applyFilters()"
                                                x-show="search" x-transition
                                                class="p-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-full">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tambahkan di bagian header setelah search --}}
                                <div class="flex items-center space-x-2">
                                    {{-- Export Button --}}
                                    @if (auth()->user()->hasPermission('produk.export'))
                                        <a href="{{ route('master.produk.export') }}"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Export
                                        </a>
                                    @endif

                                    {{-- Import Button --}}
                                    @if (auth()->user()->hasPermission('produk.import'))
                                        <button type="button" @click="$dispatch('open-modal', 'import-modal')"
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            Import
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Section --}}
                    <div
                        class="mb-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 transition-all duration-300">
                        <div class="p-4">
                            <form @submit.prevent="applyFilters">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                    {{-- Custom Kategori Dropdown --}}
                                    <div x-data="{
                                        open: false,
                                        selectedKategoriId: '{{ request('kategori_id', '') }}',
                                        selectedKategoriNama: '{{ $kategoris->firstWhere('id', request('kategori_id'))?->nama ?? 'Semua Kategori' }}'
                                    }" x-init="$watch('selectedKategoriId', value => filters.kategori_id = value)" class="relative">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1">Kategori</label>
                                        <button type="button" @click="open = !open"
                                            class="relative w-full cursor-default rounded-md bg-white dark:bg-gray-700 py-1.5 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm shadow-sm">
                                            <span class="block truncate" x-text="selectedKategoriNama"></span>
                                        </button>
                                        <div x-cloak x-show="open" @click.away="open = false"
                                            class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-sm shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div @click="selectedKategoriId = ''; selectedKategoriNama = 'Semua Kategori'; open = false"
                                                class="relative cursor-default select-none py-2 pl-10 pr-4 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <span class="block truncate">Semua Kategori</span>
                                            </div>
                                            @isset($kategoris)
                                                @foreach ($kategoris as $kategori)
                                                    <div @click="selectedKategoriId = '{{ $kategori->id }}'; selectedKategoriNama = '{{ $kategori->nama }}'; open = false"
                                                        class="relative cursor-default select-none py-2 pl-10 pr-4 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                        <span class="block truncate">{{ $kategori->nama }}</span>
                                                    </div>
                                                @endforeach
                                            @endisset
                                        </div>
                                    </div>

                                    {{-- Custom Jenis Produk Dropdown --}}
                                    <div x-data="{
                                        open: false,
                                        selectedJenisId: '{{ request('jenis_id', '') }}',
                                        selectedJenisNama: '{{ $jenisProduks->firstWhere('id', request('jenis_id'))?->nama ?? 'Semua Jenis' }}'
                                    }" x-init="$watch('selectedJenisId', value => filters.jenis_id = value)" class="relative">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1">Jenis
                                            Produk</label>
                                        <button type="button" @click="open = !open"
                                            class="relative w-full cursor-default rounded-md bg-white dark:bg-gray-700 py-1.5 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm shadow-sm">
                                            <span class="block truncate" x-text="selectedJenisNama"></span>
                                            <span
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                        <div x-cloak x-show="open" @click.away="open = false"
                                            class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-sm shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div @click="selectedJenisId = ''; selectedJenisNama = 'Semua Jenis'; open = false"
                                                class="relative cursor-default select-none py-2 pl-10 pr-4 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <span class="block truncate">Semua Jenis</span>
                                            </div>
                                            @foreach ($jenisProduks as $jenis)
                                                <div @click="selectedJenisId = '{{ $jenis->id }}'; selectedJenisNama = '{{ $jenis->nama }}'; open = false"
                                                    class="relative cursor-default select-none py-2 pl-10 pr-4 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <span class="block truncate">{{ $jenis->nama }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Harga Minimum --}}
                                    <div class="relative">
                                        <label for="min_price"
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1">Harga
                                            Min</label>
                                        <div class="relative">
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 text-xs">Rp</span>
                                            <input type="number" name="min_price" id="min_price" min="0"
                                                placeholder="0" x-model.lazy="filters.min_price"
                                                class="pl-9 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white py-1.5 px-3 shadow-sm">
                                        </div>
                                    </div>

                                    {{-- Harga Maksimum --}}
                                    <div class="relative">
                                        <label for="max_price"
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1">Harga
                                            Max</label>
                                        <div class="relative">
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 text-xs">Rp</span>
                                            <input type="number" name="max_price" id="max_price" min="0"
                                                placeholder="∞" x-model.lazy="filters.max_price"
                                                class="pl-9 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white py-1.5 px-3 shadow-sm">
                                        </div>
                                    </div>
                                </div>

                                {{-- Filter Buttons --}}
                                <div class="mt-3 flex justify-end space-x-2">
                                    <button type="button" @click="resetFilters()"
                                        class="px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-500 transition-all duration-200 shadow-sm">
                                        Reset
                                    </button>
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-primary-600 text-white text-xs font-medium rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary-500 transition-all duration-200 dark:bg-primary-700 dark:hover:bg-primary-600">
                                        <span class="flex items-center">
                                            Filter
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Container for Table and Pagination --}}
                    <div id="product-table-container" class="relative">
                        {{-- Table --}}
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                            {{-- Per Page Selector --}}
                            <div
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Menampilkan <span x-text="firstItem"></span> sampai <span
                                        x-text="lastItem"></span> dari <span x-text="totalResults"></span> hasil
                                </div>
                                <div>
                                    <label for="per-page"
                                        class="text-xs text-gray-500 dark:text-gray-400">Tampilkan:</label>
                                    <select id="per-page" x-model="perPage" @change="fetchProducts()"
                                        class="ml-1 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs border border-gray-300 dark:border-gray-600 rounded py-1 pl-2 pr-6 focus:outline-none focus:ring-1 focus:ring-primary-500">
                                        @foreach ([10, 25, 50, 100] as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        @if (auth()->user()->hasPermission('produk.delete'))
                                            <th scope="col"
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 w-8">
                                                <input type="checkbox" :checked="allSelected"
                                                    @click="toggleSelectAll()"
                                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                            </th>
                                        @endif
                                        {{-- Gambar --}}
                                        <template x-if="visibleColumns.gambar">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 w-16">
                                                Gambar
                                            </th>
                                        </template>
                                        {{-- Produk (Nama & Kode) --}}
                                        <template x-if="visibleColumns.produk">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span>Produk</span>
                                                    <button type="button" @click="sortBy('nama')"
                                                        class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                        <template
                                                            x-if="sortField === 'nama' && sortDirection === 'asc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                            </svg>
                                                        </template>
                                                        <template
                                                            x-if="sortField === 'nama' && sortDirection === 'desc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </template>
                                                        <template x-if="sortField !== 'nama'">
                                                            <svg class="w-3 h-3 opacity-30" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4">
                                                                </path>
                                                            </svg>
                                                        </template>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        {{-- SKU --}}
                                        <template x-if="visibleColumns.sku">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                SKU
                                            </th>
                                        </template>
                                        {{-- Kategori --}}
                                        <template x-if="visibleColumns.kategori">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span>Kategori</span>
                                                    <button type="button" @click="sortBy('kategori_id')"
                                                        class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                        <template
                                                            x-if="sortField === 'kategori_id' && sortDirection === 'asc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                            </svg>
                                                        </template>
                                                        <template
                                                            x-if="sortField === 'kategori_id' && sortDirection === 'desc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </template>
                                                        <template x-if="sortField !== 'kategori_id'">
                                                            <svg class="w-3 h-3 opacity-30" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4">
                                                                </path>
                                                            </svg>
                                                        </template>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        {{-- Jenis --}}
                                        <template x-if="visibleColumns.jenis">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span>Jenis</span>
                                                    <button type="button" @click="sortBy('jenis_id')"
                                                        class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                        <template
                                                            x-if="sortField === 'jenis_id' && sortDirection === 'asc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                            </svg>
                                                        </template>
                                                        <template
                                                            x-if="sortField === 'jenis_id' && sortDirection === 'desc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </template>
                                                        <template x-if="sortField !== 'jenis_id'">
                                                            <svg class="w-3 h-3 opacity-30" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4">
                                                                </path>
                                                            </svg>
                                                        </template>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        {{-- Stok --}}
                                        <template x-if="visibleColumns.stok">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span>Stok</span>
                                                    <button type="button" @click="sortBy('total_stok')"
                                                        class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                        <template
                                                            x-if="sortField === 'total_stok' && sortDirection === 'asc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                            </svg>
                                                        </template>
                                                        <template
                                                            x-if="sortField === 'total_stok' && sortDirection === 'desc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </template>
                                                        <template x-if="sortField !== 'total_stok'">
                                                            <svg class="w-3 h-3 opacity-30" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4">
                                                                </path>
                                                            </svg>
                                                        </template>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        {{-- Satuan --}}
                                        <template x-if="visibleColumns.satuan">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Satuan
                                            </th>
                                        </template>
                                        {{-- Harga Jual --}}
                                        <template x-if="visibleColumns.harga_jual">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <div class="flex items-center justify-end">
                                                    <span>Harga Jual</span>
                                                    <button type="button" @click="sortBy('harga_jual')"
                                                        class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                        <template
                                                            x-if="sortField === 'harga_jual' && sortDirection === 'asc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                            </svg>
                                                        </template>
                                                        <template
                                                            x-if="sortField === 'harga_jual' && sortDirection === 'desc'">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </template>
                                                        <template x-if="sortField !== 'harga_jual'">
                                                            <svg class="w-3 h-3 opacity-30" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4">
                                                                </path>
                                                            </svg>
                                                        </template>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        {{-- Harga Beli --}}
                                        <template x-if="visibleColumns.harga_beli">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Harga Beli
                                            </th>
                                        </template>
                                        {{-- Merek --}}
                                        <template x-if="visibleColumns.merek">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Merek
                                            </th>
                                        </template>
                                        {{-- Ukuran --}}
                                        <template x-if="visibleColumns.ukuran">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Ukuran
                                            </th>
                                        </template>
                                        {{-- Material --}}
                                        <template x-if="visibleColumns.material">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Material
                                            </th>
                                        </template>
                                        {{-- Kualitas --}}
                                        <template x-if="visibleColumns.kualitas">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Kualitas
                                            </th>
                                        </template>
                                        {{-- Sub Kategori --}}
                                        <template x-if="visibleColumns.sub_kategori">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Sub Kategori
                                            </th>
                                        </template>
                                        {{-- Deskripsi --}}
                                        <template x-if="visibleColumns.deskripsi">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Deskripsi
                                            </th>
                                        </template>
                                        {{-- Bahan --}}
                                        <template x-if="visibleColumns.bahan">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Bahan
                                            </th>
                                        </template>
                                        {{-- Status --}}
                                        <template x-if="visibleColumns.status">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Status
                                            </th>
                                        </template>
                                        {{-- Aksi --}}
                                        <th scope="col"
                                            class="px-5 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody x-html="tableHtml"
                                    class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800"
                                    x-transition:enter="transition-opacity duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    {{-- @include('master-data.produk._table_body', ['produks' => $produks]) --}}
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div id="pagination-container" x-html="paginationHtml"
                            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">
                            {{ $produks->links('vendor.pagination.tailwind-custom') }}
                        </div>

                        {{-- Loading Indicator --}}
                        <div x-show="loading" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="absolute inset-0 bg-white/70 dark:bg-gray-800/70 flex items-center justify-center z-30">
                            <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pastikan action URL-nya benar - PERBAIKAN --}}
    <form id="bulk-delete-form" action="{{ route('master.produk.bulk-destroy.any') }}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <div id="selected-products-container">
            {{-- Input akan dimasukkan secara dinamis saat submit --}}
        </div>
    </form>

    <script>
        function productTableManager() {
            // Function to load columns from localStorage or set defaults
            const loadVisibleColumns = () => {
                const stored = localStorage.getItem('produk_visible_columns');
                // Define default visibility for all relevant columns
                const defaults = {
                    gambar: true,
                    produk: true, // Combined nama & kode
                    kategori: true,
                    jenis: true,
                    stok: true, // Combined total_stok & stok_minimum
                    satuan: true,
                    harga_jual: true,
                    harga_beli: false, // Default hidden
                    merek: false, // Default hidden
                    sku: false, // product_sku, default hidden
                    ukuran: false, // Default hidden
                    material: false, // type_material, default hidden
                    kualitas: false, // Default hidden
                    sub_kategori: false, // Default hidden
                    deskripsi: false, // Default hidden
                    bahan: false, // Default hidden
                    status: true, // is_active
                };
                try {
                    // Merge stored settings with defaults, ensuring all keys exist
                    const parsed = stored ? JSON.parse(stored) : {};
                    // Ensure all default keys exist in the final object
                    const finalColumns = {
                        ...defaults
                    };
                    for (const key in defaults) {
                        if (parsed.hasOwnProperty(key)) {
                            finalColumns[key] = parsed[key];
                        }
                    }
                    return finalColumns;
                } catch (e) {
                    console.error("Failed to parse visible columns from localStorage", e);
                    return defaults;
                }
            };

            return {
                search: '{{ request('search', '') }}',
                filters: {
                    kategori_id: '{{ request('kategori_id', '') }}',
                    jenis_id: '{{ request('jenis_id', '') }}',
                    min_price: '{{ request('min_price', '') }}',
                    max_price: '{{ request('max_price', '') }}',
                },
                perPage: '{{ request('per_page', 10) }}',
                currentPage: '{{ $produks->currentPage() }}',
                sortField: '{{ request('sort', 'created_at') }}',
                sortDirection: '{{ request('direction', 'desc') }}',
                tableHtml: '',
                paginationHtml: '',
                loading: false,
                totalResults: {{ $produks->total() }},
                firstItem: {{ $produks->firstItem() ?? 0 }},
                lastItem: {{ $produks->lastItem() ?? 0 }},
                selectedProducts: [],
                allSelected: false,
                visibleColumns: loadVisibleColumns(), // Load initial state

                init() {
                    this.tableHtml = document.querySelector('#product-table-container tbody').innerHTML;
                    this.paginationHtml = document.querySelector('#pagination-container').innerHTML;

                    this.$watch('paginationHtml', () => setTimeout(() => this.attachPaginationListener(), 50));
                    this.$watch('search', value => {
                        this.currentPage = 1;
                        this.fetchProducts();
                    });
                    this.$watch('selectedProducts', value => {
                        const checkboxes = document.querySelectorAll('input[name="product_ids[]"]');
                        this.allSelected = checkboxes.length > 0 && value.length === checkboxes.length;
                    });
                    this.$watch('perPage', value => {
                        this.currentPage = 1;
                        this.fetchProducts();
                    });
                    // Watch for changes in visibleColumns
                    this.$watch('visibleColumns', (newValue) => {
                        localStorage.setItem('produk_visible_columns', JSON.stringify(newValue));
                        this.fetchProducts(); // Refetch data when columns change
                        this.updateEmptyRowColspan();
                    }, {
                        deep: true
                    });

                    setTimeout(() => this.attachPaginationListener(), 50);
                    window.addEventListener('refresh-product-table', () => this.fetchProducts());
                    this.fetchProducts(); // Initial fetch
                    this.updateEmptyRowColspan(); // Set initial colspan
                },

                toggleSelectAll() {
                    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]');
                    if (this.allSelected) {
                        this.selectedProducts = [];
                        checkboxes.forEach(cb => {
                            cb.checked = false;
                        });
                    } else {
                        this.selectedProducts = Array.from(checkboxes).map(cb => cb.value);
                        checkboxes.forEach(cb => {
                            cb.checked = true;
                        });
                    }
                    this.allSelected = !this.allSelected;
                },

                updateSelectedProducts(event, id) {
                    if (event.target.checked) {
                        if (!this.selectedProducts.includes(id)) {
                            this.selectedProducts.push(id);
                        }
                    } else {
                        this.selectedProducts = this.selectedProducts.filter(prodId => prodId !== id);
                    }
                    const checkboxes = document.querySelectorAll('input[name="product_ids[]"]');
                    this.allSelected = checkboxes.length > 0 && this.selectedProducts.length === checkboxes.length;
                },

                confirmDeleteSelected() {
                    if (this.selectedProducts.length === 0) {
                        notify('Silakan pilih minimal 1 produk untuk dihapus.', 'warning', 'Peringatan');
                        return;
                    }
                    confirmDelete(
                        `Apakah Anda yakin ingin menghapus <strong>${this.selectedProducts.length}</strong> produk yang dipilih?`,
                        () => {
                            const container = document.getElementById('selected-products-container');
                            container.innerHTML = '';
                            this.selectedProducts.forEach(id => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'ids[]';
                                input.value = id;
                                container.appendChild(input);
                            });
                            document.getElementById('bulk-delete-form').submit();
                            // Reset selection after submit
                            this.selectedProducts = [];
                            this.allSelected = false;
                        }
                    );
                },

                attachPaginationListener() {
                    const links = document.querySelectorAll('#pagination-container a');
                    links.forEach(link => {
                        link.addEventListener('click', e => {
                            e.preventDefault();
                            const url = new URL(link.href);
                            this.currentPage = url.searchParams.get('page') || 1;
                            this.fetchProducts();
                        });
                    });
                },

                buildQueryString() {
                    const params = new URLSearchParams();
                    params.append('page', this.currentPage);
                    params.append('per_page', this.perPage);
                    if (this.search) params.append('search', this.search);
                    if (this.filters.kategori_id) params.append('kategori_id', this.filters.kategori_id);
                    if (this.filters.jenis_id) params.append('jenis_id', this.filters.jenis_id);
                    if (this.filters.min_price) params.append('min_price', this.filters.min_price);
                    if (this.filters.max_price) params.append('max_price', this.filters.max_price);
                    if (this.sortField) params.append('sort', this.sortField);
                    if (this.sortDirection) params.append('direction', this.sortDirection);
                    // Send visible columns state
                    params.append('visible_columns', JSON.stringify(this.visibleColumns));
                    return params.toString();
                },

                fetchProducts() {
                    this.loading = true;
                    const queryString = this.buildQueryString();
                    const url = `{{ route('master.produk.index') }}?${queryString}`;
                    setTimeout(() => {
                        fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => {
                                if (!response.ok) throw new Error(`Network response error: ${response.status}`);
                                return response.json();
                            })
                            .then(data => {
                                if (!data || !data.success) throw new Error(data?.message ||
                                    'Terjadi kesalahan saat memproses data');
                                this.tableHtml = data.table_html;
                                this.paginationHtml = data.pagination_html;
                                this.totalResults = data.total_results;
                                this.firstItem = data.first_item || 0;
                                this.lastItem = data.last_item || 0;
                                this.loading = false;
                                this.selectedProducts = [];
                                this.allSelected = false;
                                this.updateEmptyRowColspan(); // Update colspan after fetch
                            })
                            .catch(error => {
                                console.error('Error fetching products:', error);
                                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                                this.loading = false;
                            });
                    }, 50);
                },

                applyFilters() {
                    this.currentPage = 1;
                    this.fetchProducts();
                },

                resetFilters() {
                    this.filters = {
                        kategori_id: '',
                        jenis_id: '',
                        min_price: '',
                        max_price: '',
                    };
                    this.fetchProducts();
                },

                sortBy(field) {
                    if (this.sortField === field) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDirection = 'asc';
                    }
                    this.fetchProducts();
                },

                calculateColspan() {
                    let count = 2;
                    Object.values(this.visibleColumns).forEach(isVisible => {
                        if (isVisible) {
                            count++;
                        }
                    });
                    return count;
                },

                updateEmptyRowColspan() {
                    this.$nextTick(() => {
                        const emptyRowTd = document.getElementById('empty-row-td');
                        if (emptyRowTd) {
                            emptyRowTd.colSpan = this.calculateColspan();
                        }
                    });
                }
            }
        }
    </script>

    {{-- Modal Component --}}
    <x-modal-produk :kategoris="$kategoris" :satuans="$satuans" :jenisProduks="$jenisProduks" />

    {{-- Import Modal --}}
    <div x-data="{
        isUploading: false,
        progress: 0,
        handleImport(e) {
            let form = e.target;
            let formData = new FormData(form);
            this.isUploading = true;
            this.progress = 0;
    
            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    this.isUploading = false;
                    if (data.success) {
                        notify(data.message, 'success');
                        window.dispatchEvent(new CustomEvent('refresh-product-table'));
                        $dispatch('close-modal', 'import-modal');
                    } else {
                        notify(data.message, 'error');
                    }
                })
                .catch(error => {
                    this.isUploading = false;
                    notify('Gagal mengupload file', 'error');
                });
        }
    }">
        <x-modal name="import-modal" :show="false" focusable>
            <form @submit.prevent="handleImport" action="{{ route('master.produk.import') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Import Data Produk</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload file Excel (.xlsx atau .xls)</p>

                    <div class="mt-4">
                        <input type="file" name="file" accept=".xlsx,.xls" required
                            class="block w-full text-sm text-gray-900 dark:text-gray-100
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-md file:border-0
                                      file:text-sm file:font-medium
                                      file:bg-primary-50 file:text-primary-700
                                      file:dark:bg-primary-900/50 file:dark:text-primary-400
                                      hover:file:bg-primary-100 hover:file:dark:bg-primary-900
                                      focus:outline-none">
                    </div>

                    {{-- Di dalam modal import, tambahkan link download template --}}
                    <div class="mt-2 flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <a href="{{ route('master.produk.template') }}"
                            class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                            Download template Excel
                        </a>
                    </div>

                    <div x-show="isUploading" class="mt-4">
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-primary-600 dark:bg-primary-500 h-2.5 rounded-full"
                                x-bind:style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" x-bind:disabled="isUploading"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-primary-700 dark:hover:bg-primary-600 disabled:opacity-50">
                        Import
                    </button>
                    <button type="button" @click="$dispatch('close-modal', 'import-modal')"
                        x-bind:disabled="isUploading"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        Batal
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

    <script>
        // Add these methods to your existing productTableManager
        function openAddModal() {
            window.dispatchEvent(new CustomEvent('open-produk-modal', {
                detail: {}
            }));
        }

        function openEditModal(id) {
            // Fetch product details first
            fetch(`/master-data/produk/${id}/get`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.product) {
                        window.dispatchEvent(new CustomEvent('open-produk-modal', {
                            detail: {
                                mode: 'edit',
                                product: data.product
                            }
                        }));
                    } else {
                        notify('Gagal memuat data produk', 'error', 'Error');
                    }
                })
                .catch(error => {
                    // console.error('Error fetching product:', error);
                    notify('Terjadi kesalahan saat memuat data', 'error', 'Error');
                });
        }
    </script>

    <script>
        // Cek apakah URL memiliki parameter open_modal
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open_modal') === 'create') {
                // Open the modal automatically
                window.dispatchEvent(new CustomEvent('open-produk-modal', {
                    detail: {}
                }));

                // Hapus parameter dari URL untuk menghindari modal terbuka kembali saat refresh
                const newUrl = window.location.pathname;
                history.replaceState({}, document.title, newUrl);
            }
        });
    </script>
</x-app-layout>
