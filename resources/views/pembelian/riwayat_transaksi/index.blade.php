@php
    // Helper function untuk warna status transaksi
    function getStatusColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'gray';
            case 'diproses':
                return 'blue';
            case 'dikirim':
                return 'amber';
            case 'selesai':
                return 'emerald';
            case 'dibatalkan':
                return 'red';
            default:
                return 'primary';
        }
    }

    // Helper function untuk nama status pembayaran
    function getPaymentStatusName($status)
    {
        switch ($status) {
            case 'belum_bayar':
                return 'Belum Bayar';
            case 'sebagian':
                return 'Sebagian';
            case 'lunas':
                return 'Lunas';
            default:
                return $status;
        }
    }

    // Helper function untuk warna status pembayaran
    function getPaymentStatusColor($status)
    {
        switch ($status) {
            case 'belum_bayar':
                return 'red';
            case 'sebagian':
                return 'amber';
            case 'lunas':
                return 'emerald';
            default:
                return 'gray';
        }
    }

    // Current sort parameters
    $sortColumn = request('sort', 'tanggal');
    $sortDirection = request('direction', 'desc');

    // Function to get sort direction icon
    function getSortIcon($column, $currentSort, $currentDirection)
    {
        if ($column !== $currentSort) {
            return 'none';
        }
        return $currentDirection === 'asc' ? 'asc' : 'desc';
    }

    // Function to get next sort direction
    function getNextDirection($column, $currentSort, $currentDirection)
    {
        if ($column !== $currentSort) {
            return 'asc';
        }
        return $currentDirection === 'asc' ? 'desc' : 'asc';
    }
@endphp

<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header with Stats --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Riwayat Transaksi
                                Pembelian</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Lihat dan kelola semua transaksi pembelian dan status pembayarannya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Purchase Overview Dashboard --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Total Value of Purchase Orders --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-primary-100 dark:bg-primary-800/30 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7 text-primary-600 dark:text-primary-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 01-2 2H8a2 2 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        Total Nilai Pembelian
                                    </dt>
                                    <dd>
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($totalPurchaseValue, 0, ',', '.') }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Outstanding Payments --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 dark:bg-red-800/30 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-600 dark:text-red-400"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        Hutang Pembelian
                                    </dt>
                                    <dd>
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Completed Payments --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-emerald-100 dark:bg-emerald-800/30 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-7 w-7 text-emerald-600 dark:text-emerald-400" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        Pembayaran Selesai
                                    </dt>
                                    <dd>
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">
                                            Rp {{ number_format($totalCompleted, 0, ',', '.') }}
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div x-data="transactionManager()"
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">

            {{-- Filter Tabs --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 pt-6 pb-2 flex overflow-x-auto scrollbar-hide">
                    <div
                        class="relative flex space-x-4 after:absolute after:bottom-0 after:left-0 after:right-0 after:h-px after:bg-gray-200 dark:after:bg-gray-700">
                        <button type="button" @click="changeTab('all')"
                            :class="tab === 'all' ? 'text-primary-600 border-primary-500' :
                                'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300'"
                            class="pb-4 px-2 font-medium text-sm border-b-2 whitespace-nowrap">
                            Semua Transaksi
                            <span
                                class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                {{ $statusCounts['all'] }}
                            </span>
                        </button>

                        @foreach (['draft', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $statusTab)
                            <button type="button" @click="changeTab('{{ $statusTab }}')"
                                :class="tab === '{{ $statusTab }}' ? 'text-primary-600 border-primary-500' :
                                    'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300'"
                                class="pb-4 px-2 font-medium text-sm border-b-2 whitespace-nowrap">
                                {{ ucfirst($statusTab) }}
                                <span
                                    class="ml-1 px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                    {{ $statusCounts[$statusTab] }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Search and Filters --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="relative flex-grow">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="relative rounded-md shadow-sm flex-grow min-w-[250px]">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" x-model="search" @input="debouncedSearch()"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md"
                                    placeholder="Cari nomor PO atau supplier...">
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <button type="button" @click="applyFilters"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Cari
                                </button>
                                <button type="button" @click="resetFilters"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Filters -->
                <div class="mt-4 flex flex-wrap gap-3">
                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter Tanggal
                            <span class="ml-1"
                                :class="dateFilter && dateFilter !== 'all' ? 'text-primary-600' : ''">
                                <span x-text="dateFilter && dateFilter !== 'all' ? '(Aktif)' : ''"></span>
                            </span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false"
                            class="origin-top-left absolute left-0 mt-2 w-96 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-[999]"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            style="max-height: 90vh; overflow-y: auto;">
                            <div class="py-1" role="none">
                                <div class="p-4 space-y-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Filter Tanggal
                                        </label>
                                        <div class="space-y-1">
                                            <div class="flex items-center">
                                                <input id="date-all" x-model="dateFilter" type="radio"
                                                    value="all"
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 dark:border-gray-600">
                                                <label for="date-all"
                                                    class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                                    Semua
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="date-today" x-model="dateFilter" type="radio"
                                                    value="today"
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 dark:border-gray-600">
                                                <label for="date-today"
                                                    class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                                    Hari Ini
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="date-week" x-model="dateFilter" type="radio"
                                                    value="this_week"
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 dark:border-gray-600">
                                                <label for="date-week"
                                                    class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                                    Minggu Ini
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="date-month" x-model="dateFilter" type="radio"
                                                    value="this_month"
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 dark:border-gray-600">
                                                <label for="date-month"
                                                    class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                                    Bulan Ini
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="date-last-month" x-model="dateFilter" type="radio"
                                                    value="last_month"
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 dark:border-gray-600">
                                                <label for="date-last-month"
                                                    class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                                    Bulan Lalu
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="date-range" x-model="dateFilter" type="radio"
                                                    value="range"
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 dark:border-gray-600">
                                                <label for="date-range"
                                                    class="ml-3 block text-sm text-gray-700 dark:text-gray-300">
                                                    Rentang Custom
                                                </label>
                                            </div>

                                            <div class="grid grid-cols-2 gap-2 mt-2">
                                                <div>
                                                    <label for="date-start-input"
                                                        class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                        Dari Tanggal
                                                    </label>
                                                    <input type="date" id="date-start-input" x-model="dateStart"
                                                        @change="dateFilter = 'range'"
                                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                </div>
                                                <div>
                                                    <label for="date-end-input"
                                                        class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                        Sampai Tanggal
                                                    </label>
                                                    <input type="date" id="date-end-input" x-model="dateEnd"
                                                        @change="dateFilter = 'range'"
                                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <button type="button" @click="applyFilters(); open = false"
                                            class="w-full bg-primary-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            Terapkan Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="relative inline-block text-left">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Filter Supplier
                            <span class="ml-1" :class="supplierId ? 'text-primary-600' : ''">
                                <span x-text="supplierId ? '(Aktif)' : ''"></span>
                            </span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false"
                            class="origin-top-left absolute left-0 mt-2 w-96 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-[999]"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            style="max-height: 90vh; overflow-y: auto;">
                            <div class="py-1" role="none">
                                <div class="p-4 space-y-4">
                                    <div class="space-y-2" x-data="{ supplierSearch: '', filteredSuppliers: [], selectedSupplier: null }" x-init="const suppliers = {{ Js::from($suppliers) }};
                                    filteredSuppliers = suppliers;
                                    selectedSupplier = suppliers.find(s => s.id == supplierId) || null;">
                                        <label for="supplier_search"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Cari Supplier
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd"
                                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <input type="text" x-model="supplierSearch"
                                                @input="
                                                    const suppliers = {{ Js::from($suppliers) }};
                                                    filteredSuppliers = suppliers.filter(s => s.nama.toLowerCase().includes(supplierSearch.toLowerCase())); 
                                                    if(supplierId) { 
                                                        selectedSupplier = filteredSuppliers.find(s => s.id == supplierId) || null; 
                                                    }
                                                "
                                                id="supplier_search"
                                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                                placeholder="Ketik nama supplier...">
                                        </div>

                                        <div
                                            class="mt-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md max-h-60 overflow-y-auto">
                                            <!-- Pilihan "Semua Supplier" -->
                                            <div class="py-1">
                                                <button
                                                    @click="supplierId = ''; selectedSupplier = null; supplierSearch = ''; open = false;"
                                                    type="button"
                                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white"
                                                    :class="{ 'bg-gray-100 dark:bg-gray-600': !supplierId }">
                                                    Semua Supplier
                                                </button>
                                            </div>

                                            <!-- List supplier yang difilter -->
                                            <template x-for="supplier in filteredSuppliers" :key="supplier.id">
                                                <div class="py-1">
                                                    <button
                                                        @click="supplierId = supplier.id; selectedSupplier = supplier; supplierSearch = supplier.nama; open = false;"
                                                        type="button"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white"
                                                        :class="{ 'bg-gray-100 dark:bg-gray-600': supplierId == supplier.id }">
                                                        <span x-text="supplier.nama"></span>
                                                    </button>
                                                </div>
                                            </template>

                                            <!-- Pesan jika tidak ada hasil -->
                                            <div x-show="filteredSuppliers.length === 0"
                                                class="py-2 px-4 text-sm text-gray-500 dark:text-gray-400">
                                                Tidak ada supplier yang ditemukan
                                            </div>
                                        </div>

                                        <!-- Input tersembunyi untuk menyimpan nilai ID supplier -->
                                        <input type="hidden" x-model="supplierId">
                                    </div>

                                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <button type="button" @click="applyFilters(); open = false"
                                            class="w-full bg-primary-600 border border-transparent rounded-md shadow-sm py-2 px-4 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            Terapkan Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loading Indicator --}}
            <div x-show="loading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                <span class="ml-3 text-gray-600 dark:text-gray-400">Memuat data...</span>
            </div>

            {{-- Transactions Table --}}
            <div id="transactions-table-container" class="overflow-x-auto min-h-[400px]" x-show="!loading">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button type="button" @click="sort('nomor')"
                                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    No. PO
                                    <span
                                        class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                                        <template x-if="sortColumn === 'nomor' && sortDirection === 'asc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn === 'nomor' && sortDirection === 'desc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn !== 'nomor'">
                                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button type="button" @click="sort('tanggal')"
                                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tanggal
                                    <span
                                        class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                                        <template x-if="sortColumn === 'tanggal' && sortDirection === 'asc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn === 'tanggal' && sortDirection === 'desc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn !== 'tanggal'">
                                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button type="button" @click="sort('supplier_id')"
                                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Supplier
                                    <span
                                        class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                                        <template x-if="sortColumn === 'supplier_id' && sortDirection === 'asc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn === 'supplier_id' && sortDirection === 'desc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn !== 'supplier_id'">
                                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4a1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button type="button" @click="sort('total')"
                                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total
                                    <span
                                        class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                                        <template x-if="sortColumn === 'total' && sortDirection === 'asc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn === 'total' && sortDirection === 'desc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l-3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn !== 'total'">
                                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button type="button" @click="sort('status')"
                                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status
                                    <span
                                        class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                                        <template x-if="sortColumn === 'status' && sortDirection === 'asc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293-3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn === 'status' && sortDirection === 'desc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l-3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn !== 'status'">
                                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </span>
                                </button>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left">
                                <button type="button" @click="sort('status_pembayaran')"
                                    class="group flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Pembayaran
                                    <span
                                        class="ml-1 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                                        <template x-if="sortColumn === 'status_pembayaran' && sortDirection === 'asc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293-3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template
                                            x-if="sortColumn === 'status_pembayaran' && sortDirection === 'desc'">
                                            <svg class="h-5 w-5 text-primary-500" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l-3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="sortColumn !== 'status_pembayaran'">
                                            <svg class="h-5 w-5 text-gray-400 invisible group-hover:visible"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 3a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L10 5.414 6.707 8.707a1 1 0 01-1.414-1.414l4-4A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </span>
                                </button>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $transaction->nomor }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $transaction->supplier->nama ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ getStatusColor($transaction->status) }}-100 text-{{ getStatusColor($transaction->status) }}-800 dark:bg-{{ getStatusColor($transaction->status) }}-800 dark:bg-opacity-30 dark:text-{{ getStatusColor($transaction->status) }}-400">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-100 text-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-800 dark:bg-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-800 dark:bg-opacity-30 dark:text-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-400">
                                        {{ getPaymentStatusName($transaction->status_pembayaran) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if (auth()->user()->hasPermission('riwayat_transaksi.view'))
                                        <a href="{{ route('pembelian.riwayat-transaksi.show', $transaction->id) }}"
                                            class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 bg-primary-50 dark:bg-primary-900/20 px-3 py-1 rounded-md">
                                            Detail
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <h3 class="mt-2 text-base font-medium text-gray-900 dark:text-gray-200">Tidak
                                            ada transaksi ditemukan</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada transaksi pembelian yang sesuai dengan filter yang dipilih.
                                        </p>
                                        <div class="mt-4">
                                            <a href="{{ route('pembelian.riwayat-transaksi.index') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                Lihat Semua Transaksi
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div id="pagination-container" class="border-t border-gray-200 dark:border-gray-700 px-6 py-4"
                x-html="paginationHtml">
                @if ($transactions->hasPages())
                    {{ $transactions->appends([
                            'status' => $selectedStatus,
                            'search' => $search,
                            'date_filter' => $dateFilter,
                            'date_start' => $dateStart,
                            'date_end' => $dateEnd,
                            'supplier_id' => $selectedSupplier,
                            'sort' => $sortColumn,
                            'direction' => $sortDirection,
                        ])->links('vendor.pagination.tailwind-custom') }}
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function transactionManager() {
                return {
                    tab: '{{ $selectedStatus }}',
                    search: '{{ $search }}',
                    dateFilter: '{{ $dateFilter }}',
                    dateStart: '{{ $dateStart }}',
                    dateEnd: '{{ $dateEnd }}',
                    supplierId: '{{ $selectedSupplier }}',
                    sortColumn: '{{ $sortColumn }}',
                    sortDirection: '{{ $sortDirection }}',
                    loading: false,
                    tableHtml: '',
                    paginationHtml: '',
                    searchTimeout: null,

                    init() {
                        this.tableHtml = document.getElementById('transactions-table-container').innerHTML;
                        this.paginationHtml = document.getElementById('pagination-container').innerHTML;
                        this.$watch('search', () => this.debouncedSearch());
                    },

                    debouncedSearch() {
                        clearTimeout(this.searchTimeout);
                        this.searchTimeout = setTimeout(() => {
                            this.applyFilters();
                        }, 500);
                    },

                    changeTab(newTab) {
                        if (this.tab === newTab) return;
                        this.tab = newTab;
                        this.applyFilters();
                    },

                    applyFilters() {
                        this.loading = true;

                        // Build URL with query parameters
                        const params = new URLSearchParams();
                        params.append('status', this.tab);
                        if (this.search) params.append('search', this.search);
                        if (this.dateFilter) params.append('date_filter', this.dateFilter);
                        if (this.dateStart) params.append('date_start', this.dateStart);
                        if (this.dateEnd) params.append('date_end', this.dateEnd);
                        if (this.supplierId) params.append('supplier_id', this.supplierId);
                        if (this.sortColumn) params.append('sort', this.sortColumn);
                        if (this.sortDirection) params.append('direction', this.sortDirection);

                        // Make AJAX request to get updated table content
                        fetch(`{{ route('pembelian.riwayat-transaksi.index') }}?${params.toString()}`, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Update table content with new data
                                document.getElementById('transactions-table-container').innerHTML = data.table_html;
                                document.getElementById('pagination-container').innerHTML = data.pagination_html;

                                // Also update our Alpine.js state
                                this.tableHtml = data.table_html;
                                this.paginationHtml = data.pagination_html;

                                // Update URL without page refresh
                                const url = `{{ route('pembelian.riwayat-transaksi.index') }}?${params.toString()}`;
                                window.history.pushState({
                                    path: url
                                }, '', url);

                                this.loading = false;
                            })
                            .catch(error => {
                                console.error('Error fetching data:', error);
                                this.loading = false;
                            });
                    },

                    resetFilters() {
                        this.tab = 'all';
                        this.search = '';
                        this.dateFilter = 'all';
                        this.dateStart = '';
                        this.dateEnd = '';
                        this.supplierId = '';
                        this.sortColumn = 'tanggal';
                        this.sortDirection = 'desc';
                        this.applyFilters();
                    },

                    sort(column) {
                        if (this.sortColumn === column) {
                            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            this.sortColumn = column;
                            this.sortDirection = 'asc';
                        }
                        this.applyFilters();
                    },

                    handlePaginationClick(url) {
                        this.loading = true;

                        // Make AJAX request to the pagination URL
                        fetch(url, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Update table content with new data
                                document.getElementById('transactions-table-container').innerHTML = data.table_html;
                                document.getElementById('pagination-container').innerHTML = data.pagination_html;

                                // Also update our Alpine.js state
                                this.tableHtml = data.table_html;
                                this.paginationHtml = data.pagination_html;

                                // Update URL without page refresh
                                window.history.pushState({
                                    path: url
                                }, '', url);

                                this.loading = false;
                            })
                            .catch(error => {
                                console.error('Error fetching pagination data:', error);
                                this.loading = false;
                            });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
