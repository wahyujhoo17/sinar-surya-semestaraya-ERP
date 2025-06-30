<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Perencanaan Produksi'],
]" :currentPage="'Perencanaan Produksi'">

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8" x-data="produksiTableManager()" x-init="init()">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Perencanaan Produksi</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola daftar rencana produksi untuk PT Sinar Surya Semesta.
            </p>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            {{-- Total Perencanaan Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Perencanaan</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $perencanaanProduksi->total() ?? 0 }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Menunggu Persetujuan Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 p-3.5">
                            <svg class="h-7 w-7 text-yellow-500 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Menunggu Persetujuan</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $perencanaanProduksi->where('status', 'menunggu_persetujuan')->count() ?? 0 }}
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
                        <div class="flex-shrink-0 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 p-3.5">
                            <svg class="h-7 w-7 text-emerald-500 dark:text-emerald-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sedang Berjalan</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ $perencanaanProduksi->where('status', 'berjalan')->count() ?? 0 }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Action Card --}}
            @if (auth()->user()->hasPermission('perencanaan_produksi.create'))
                <a href="{{ route('produksi.perencanaan-produksi.create') }}"
                    class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white/80 uppercase tracking-wider">Aksi Cepat</p>
                                <p class="mt-1 text-lg font-semibold text-white">Buat Perencanaan Baru</p>
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
                <div
                    class="bg-gradient-to-r from-gray-400 to-gray-500 dark:from-gray-600 dark:to-gray-700 overflow-hidden shadow-lg rounded-xl cursor-not-allowed opacity-60">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white/80 uppercase tracking-wider">Aksi Cepat</p>
                                <p class="mt-1 text-lg font-semibold text-white">Tidak Ada Akses</p>
                            </div>
                            <div
                                class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/20 text-white">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
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
                    Filter Perencanaan Produksi
                    <span class="filter-badge ml-2 hidden"></span>
                </h3>
            </div>

            <!-- Filter content -->
            <div class="p-5">
                <form action="{{ route('produksi.perencanaan-produksi.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Status Filter -->
                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select id="status" name="status"
                                class="w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 text-sm dark:bg-gray-700 dark:text-white">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status
                                </option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="menunggu_persetujuan"
                                    {{ request('status') == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu
                                    Persetujuan</option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                                <option value="berjalan" {{ request('status') == 'berjalan' ? 'selected' : '' }}>
                                    Berjalan</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
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
                                    placeholder="Cari nomor atau tanggal"
                                    class="pl-10 pr-3 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm dark:bg-gray-700 dark:text-white">
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
                        <a href="{{ route('produksi.perencanaan-produksi.index') }}"
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
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('produksi.perencanaan-produksi.index', [
                                    'sort' => 'nomor',
                                    'direction' => request('sort') === 'nomor' && request('direction') === 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'status' => request('status'),
                                ]) }}"
                                    class="group inline-flex items-center space-x-1">
                                    <span>Nomor</span>
                                    @if (request('sort') === 'nomor')
                                        @if (request('direction') === 'asc')
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                        @endif
                                    @else
                                        <svg class="h-4 w-4 text-gray-200 group-hover:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('produksi.perencanaan-produksi.index', [
                                    'sort' => 'tanggal',
                                    'direction' => request('sort') === 'tanggal' && request('direction') === 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'status' => request('status'),
                                ]) }}"
                                    class="group inline-flex items-center space-x-1">
                                    <span>Tanggal</span>
                                    @if (request('sort') === 'tanggal')
                                        @if (request('direction') === 'asc')
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                        @endif
                                    @else
                                        <svg class="h-4 w-4 text-gray-200 group-hover:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('produksi.perencanaan-produksi.index', [
                                    'sort' => 'sales_order',
                                    'direction' => request('sort') === 'sales_order' && request('direction') === 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'status' => request('status'),
                                ]) }}"
                                    class="group inline-flex items-center space-x-1">
                                    <span>Sales Order</span>
                                    @if (request('sort') === 'sales_order')
                                        @if (request('direction') === 'asc')
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                        @endif
                                    @else
                                        <svg class="h-4 w-4 text-gray-200 group-hover:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('produksi.perencanaan-produksi.index', [
                                    'sort' => 'customer',
                                    'direction' => request('sort') === 'customer' && request('direction') === 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'status' => request('status'),
                                ]) }}"
                                    class="group inline-flex items-center space-x-1">
                                    <span>Customer</span>
                                    @if (request('sort') === 'customer')
                                        @if (request('direction') === 'asc')
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                        @endif
                                    @else
                                        <svg class="h-4 w-4 text-gray-200 group-hover:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('produksi.perencanaan-produksi.index', [
                                    'sort' => 'status',
                                    'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'status' => request('status'),
                                ]) }}"
                                    class="group inline-flex items-center space-x-1">
                                    <span>Status</span>
                                    @if (request('sort') === 'status')
                                        @if (request('direction') === 'asc')
                                            <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            @else
                                                <svg class="h-4 w-4 text-gray-400 group-hover:text-gray-500"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                        @endif
                                    @else
                                        <svg class="h-4 w-4 text-gray-200 group-hover:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Dibuat Oleh
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($perencanaanProduksi as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ ($perencanaanProduksi->currentPage() - 1) * $perencanaanProduksi->perPage() + $index + 1 }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->nomor }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $item->tanggal->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    @if ($item->salesOrder)
                                        <a href="{{ route('penjualan.sales-order.show', $item->salesOrder->id) }}"
                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-150">
                                            {{ $item->salesOrder->nomor }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    @if ($item->salesOrder && $item->salesOrder->customer)
                                        {{ $item->salesOrder->customer->nama ?? $item->salesOrder->customer->company }}
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    @php
                                        $statusMap = [
                                            'draft' => [
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                'Draft',
                                            ],
                                            'menunggu_persetujuan' => [
                                                'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-300',
                                                'Menunggu Persetujuan',
                                            ],
                                            'disetujui' => [
                                                'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300',
                                                'Disetujui',
                                            ],
                                            'ditolak' => [
                                                'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-300',
                                                'Ditolak',
                                            ],
                                            'berjalan' => [
                                                'bg-primary-100 text-primary-800 dark:bg-primary-700 dark:text-primary-300',
                                                'Berjalan',
                                            ],
                                            'selesai' => [
                                                'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-300',
                                                'Selesai',
                                            ],
                                            'dibatalkan' => [
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                'Dibatalkan',
                                            ],
                                        ];

                                        $statusClass =
                                            $statusMap[$item->status][0] ??
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                        $statusText = $statusMap[$item->status][1] ?? 'Unknown';
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $item->creator->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if (auth()->user()->hasPermission('perencanaan_produksi.view'))
                                            <a href="{{ route('produksi.perencanaan-produksi.show', $item->id) }}"
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-indigo-100 text-gray-700 dark:text-white dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 transition-colors border border-dashed border-indigo-300"
                                                title="Lihat">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed border border-dashed border-gray-300"
                                                title="Tidak Ada Akses">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                </svg>
                                            </span>
                                        @endif

                                        @if ($item->status == 'draft')
                                            @if (auth()->user()->hasPermission('perencanaan_produksi.edit'))
                                                <a href="{{ route('produksi.perencanaan-produksi.edit', $item->id) }}"
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-gray-700 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300"
                                                    title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed border border-dashed border-gray-300"
                                                    title="Tidak Ada Akses">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                    </svg>
                                                </span>
                                            @endif

                                            @if (auth()->user()->hasPermission('perencanaan_produksi.approve'))
                                                <form
                                                    action="{{ route('produksi.perencanaan-produksi.submit', $item->id) }}"
                                                    method="POST" class="inline-block" x-data="{ processing: false }"
                                                    @submit.prevent="
                                                        if (processing) return;
                                                        processing = true;
                                                        $dispatch('open-modal', {
                                                            id: 'confirmationModal',
                                                            title: 'Konfirmasi',
                                                            message: 'Yakin ingin mengajukan perencanaan produksi ini?',
                                                            onConfirm: () => { 
                                                                setTimeout(() => { $el.submit(); }, 300);
                                                            },
                                                            onCancel: () => { processing = false; }
                                                        })
                                                    ">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" :disabled="processing"
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-green-100 text-gray-700 dark:text-white dark:bg-green-900/20 dark:hover:bg-green-900/30 transition-colors border border-dashed border-green-300"
                                                        title="Ajukan">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed border border-dashed border-gray-300"
                                                    title="Tidak Ada Akses">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @endif

                                        @if ($item->status == 'menunggu_persetujuan')
                                            @if (auth()->user()->hasPermission('perencanaan_produksi.approve'))
                                                <form
                                                    action="{{ route('produksi.perencanaan-produksi.approve', $item->id) }}"
                                                    method="POST" class="inline-block" x-data="{ processing: false }"
                                                    @submit.prevent="
                                                        if (processing) return;
                                                        processing = true;
                                                        $dispatch('open-modal', {
                                                            id: 'confirmationModal',
                                                            title: 'Konfirmasi',
                                                            message: 'Yakin ingin menyetujui perencanaan produksi ini?',
                                                            onConfirm: () => { 
                                                                setTimeout(() => { $el.submit(); }, 300);
                                                            },
                                                            onCancel: () => { processing = false; }
                                                        })
                                                    ">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" :disabled="processing"
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-green-100 text-gray-700 dark:text-white dark:bg-green-900/20 dark:hover:bg-green-900/30 transition-colors border border-dashed border-green-300"
                                                        title="Setujui">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <form
                                                    action="{{ route('produksi.perencanaan-produksi.reject', $item->id) }}"
                                                    method="POST" class="inline-block" x-data="{ processing: false }"
                                                    @submit.prevent="
                                                        if (processing) return;
                                                        processing = true;
                                                        $dispatch('open-modal', {
                                                            id: 'confirmationModal',
                                                            title: 'Konfirmasi',
                                                            message: 'Yakin ingin menolak perencanaan produksi ini?',
                                                            onConfirm: () => { 
                                                                setTimeout(() => { $el.submit(); }, 300);
                                                            },
                                                            onCancel: () => { processing = false; }
                                                        })
                                                    ">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-gray-700 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300"
                                                        title="Tolak">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed border border-dashed border-gray-300"
                                                    title="Tidak Ada Akses">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        @endif

                                        @if ($item->status == 'disetujui' && !$item->workOrders->count())
                                            @if (auth()->user()->hasPermission('perencanaan_produksi.approve'))
                                                <a href="{{ route('produksi.perencanaan-produksi.create-work-order', $item->id) }}"
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-indigo-100 text-gray-700 dark:text-white dark:bg-indigo-900/20 dark:hover:bg-indigo-900/30 transition-colors border border-dashed border-indigo-300"
                                                    title="Buat Work Order">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <span
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed border border-dashed border-gray-300"
                                                    title="Tidak Ada Akses">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                    </svg>
                                                </span>
                                            @endif
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
                                            perencanaan produksi</span>
                                        @if (auth()->user()->hasPermission('perencanaan_produksi.create'))
                                            <a href="{{ route('produksi.perencanaan-produksi.create') }}"
                                                class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Buat Perencanaan Baru
                                            </a>
                                        @else
                                            <div
                                                class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                                                <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                </svg>
                                                Tidak Ada Akses
                                            </div>
                                        @endif
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
                        @if ($perencanaanProduksi->previousPageUrl())
                            <a href="{{ $perencanaanProduksi->previousPageUrl() }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&status={{ request('status') }}"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Previous
                            </a>
                        @else
                            <span
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-500 dark:text-gray-500 bg-gray-100 dark:bg-gray-800 cursor-not-allowed">
                                Previous
                            </span>
                        @endif

                        @if ($perencanaanProduksi->nextPageUrl())
                            <a href="{{ $perencanaanProduksi->nextPageUrl() }}&sort={{ request('sort') }}&direction={{ request('direction') }}&search={{ request('search') }}&status={{ request('status') }}"
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
                                <span class="font-medium">{{ $perencanaanProduksi->firstItem() ?? 0 }}</span>
                                to
                                <span class="font-medium">{{ $perencanaanProduksi->lastItem() ?? 0 }}</span>
                                of
                                <span class="font-medium">{{ $perencanaanProduksi->total() }}</span>
                                results
                            </p>
                        </div>

                        {{ $perencanaanProduksi->appends([
                                'sort' => request('sort'),
                                'direction' => request('direction'),
                                'search' => request('search'),
                                'status' => request('status'),
                            ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function produksiTableManager() {
            return {
                isLoading: false,

                init() {
                    // Handle filter badge visibility
                    const hasFilters =
                        {{ request('search') || (request('status') && request('status') != 'all') ? 'true' : 'false' }};
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
                    const actionButtons = document.querySelectorAll('[data-tooltip]');
                    actionButtons.forEach(button => {
                        const tooltip = button.getAttribute('data-tooltip');
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
                    const filterForm = document.querySelector('form[action*="perencanaan-produksi.index"]');
                    if (filterForm) {
                        filterForm.addEventListener('submit', (e) => {
                            // Show loading overlay before submitting the form
                            this.showLoading();
                        });
                    }

                    // Add click event listener to pagination links
                    const paginationLinks = document.querySelectorAll('.pagination a');
                    paginationLinks.forEach(link => {
                        link.addEventListener('click', (e) => {
                            // Show loading overlay before navigating to next page
                            this.showLoading();
                        });
                    });

                    // Handle reset filter button
                    const resetFilterBtn = document.querySelector(
                        'a[href="{{ route('produksi.perencanaan-produksi.index') }}"]');
                    if (resetFilterBtn) {
                        resetFilterBtn.addEventListener('click', (e) => {
                            this.showLoading();
                        });
                    }
                }
            }
        }
    </script>
</x-app-layout>
