<x-app-layout :breadcrumbs="[['label' => 'Inventaris'], ['label' => 'Permintaan Barang']]" :currentPage="'Permintaan Barang'">
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
        @if (isset($pendingSalesOrders) && count($pendingSalesOrders) > 0)
            {{-- Notifikasi Sales Order yang belum memiliki permintaan barang --}}
            <div
                class="mb-8 bg-blue-50 dark:bg-blue-900/30 rounded-xl shadow-sm p-6 border border-blue-200 dark:border-blue-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="flex-1">
                        <h2
                            class="text-xl md:text-2xl font-bold text-blue-800 dark:text-blue-300 flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600 dark:text-blue-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg> Sales Order Menunggu Diproses
                        </h2>
                        <p class="mt-2 text-blue-700 dark:text-blue-400">
                            Terdapat {{ count($pendingSalesOrders) }} sales order yang belum memiliki permintaan barang.
                            Anda dapat membuat permintaan barang secara otomatis dari sales order berikut.
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('inventaris.permintaan-barang.index', ['hide_pending' => true]) }}"
                            class="inline-flex items-center px-4 py-2.5 bg-white border border-blue-300 rounded-md font-semibold text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-300 dark:bg-blue-800 dark:border-blue-600 dark:text-blue-200 dark:hover:bg-blue-700 transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Sembunyikan Notifikasi
                        </a>
                    </div>
                </div>

                <div
                    class="mt-6 overflow-x-auto rounded-lg border border-blue-200 dark:border-blue-700 bg-white dark:bg-gray-800">
                    <table class="min-w-full divide-y divide-blue-200 dark:divide-blue-700">
                        <thead class="bg-blue-50 dark:bg-blue-900/50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    Nomor SO
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    Jumlah Item
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-blue-200 dark:divide-blue-700">
                            @foreach ($pendingSalesOrders as $so)
                                <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $so['nomor'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($so['tanggal'])->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ $so['customer_nama'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        @if ($so['status_pengiriman'] == 'belum_dikirim')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-600 shadow-sm">
                                                Belum Dikirim
                                            </span>
                                        @elseif($so['status_pengiriman'] == 'sebagian')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-600 shadow-sm">
                                                Pengiriman Sebagian
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ $so['jumlah_item'] }} item
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button type="button"
                                            class="auto-process-btn inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105"
                                            data-so-id="{{ $so['id'] }}" data-so-nomor="{{ $so['nomor'] }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Proses Otomatis
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Enhanced Overview Header --}}
        <div
            class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg> Permintaan Barang
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Kelola semua permintaan barang untuk proses pemenuhan pesanan dan pengiriman kepada pelanggan.
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                    @if (auth()->user()->hasPermission('permintaan_barang.create'))
                        <button type="button" id="autoCreateBtn"
                            class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 transform hover:scale-105 {{ isset($pendingSalesOrders) && count($pendingSalesOrders) == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ isset($pendingSalesOrders) && count($pendingSalesOrders) == 0 ? 'disabled' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Otomatis dari Sales Order
                            @if (isset($pendingSalesOrders) && count($pendingSalesOrders) > 0)
                                <span
                                    class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ count($pendingSalesOrders) }}
                                </span>
                            @endif
                        </button>
                    @else
                        <button disabled
                            class="inline-flex items-center px-4 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-500 bg-gray-300 cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
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
        <div x-data="{
            showFilters: localStorage.getItem('permintaanBarangFilterState') === 'open' || false,
            toggleState() {
                this.showFilters = !this.showFilters;
                localStorage.setItem('permintaanBarangFilterState', this.showFilters ? 'open' : 'closed');
            }
        }" x-init="$watch('showFilters', value => localStorage.setItem('permintaanBarangFilterState', value ? 'open' : 'closed'))"
            class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center cursor-pointer" @click="toggleState()">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Filter</h3>
                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <span x-show="!showFilters">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    <span x-show="showFilters">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
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
                <form action="{{ route('inventaris.permintaan-barang.index') }}" method="GET" id="filterForm"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Search Filter --}}
                    <div>
                        <label for="searchInput"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="text" id="searchInput" name="search"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white pl-10 pr-4 py-2 text-sm focus:border-primary-500 focus:ring-primary-500"
                                placeholder="Cari nomor, customer..." value="{{ request('search') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-300"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label for="statusFilter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select id="statusFilter" name="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status
                            </option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>
                                Menunggu</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>
                                Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan</option>
                        </select>
                    </div>

                    {{-- Sales Order Filter --}}
                    <div>
                        <label for="salesOrderFilter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sales Order</label>
                        <select id="salesOrderFilter" name="sales_order_id"
                            class="select2-search mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Sales Order</option>
                            @foreach ($salesOrders as $so)
                                <option value="{{ $so->id }}"
                                    {{ request('sales_order_id') == $so->id ? 'selected' : '' }}>
                                    {{ $so->nomor }} - {{ $so->customer->nama ?? $so->customer->company }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Gudang Filter --}}
                    <div>
                        <label for="gudangFilter"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gudang</label>
                        <select id="gudangFilter" name="gudang_id"
                            class="select2-search mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Semua Gudang</option>
                            @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}"
                                    {{ request('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                    {{ $gudang->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date Filters --}}
                    <div>
                        <label for="tanggal_awal"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                        <input type="date" id="tanggal_awal" name="tanggal_awal"
                            value="{{ request('tanggal_awal') }}"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="tanggal_akhir"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                            value="{{ request('tanggal_akhir') }}"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Filter Actions --}}
                    <div class="col-span-1 sm:col-span-2 flex items-end">
                        <div class="flex space-x-3">
                            <a href="{{ route('inventaris.permintaan-barang.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 transform hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div
                    class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Permintaan Barang</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total:
                            {{ $permintaanBarang->total() }} permintaan</p>
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
                            <input type="text" id="quickSearch"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Cari cepat...">
                        </div>
                    </div>
                </div>
                <div id="table-container"
                    class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                    @include('inventaris.permintaan_barang._table')
                </div>
                <div class="mt-6" id="pagination-container">
                    {{ $permintaanBarang->links() }}
                </div>
            </div>
        </div>
    </div> <!-- Modal: Auto Create Permintaan Barang -->
    <div id="autoCreateModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div> <span
                class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="autoCreateForm" action="{{ route('inventaris.permintaan-barang.auto-generate') }}"
                    method="POST" class="{{ count($pendingSalesOrders) == 0 ? 'hidden' : '' }}"> @csrf <div
                        class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title"> Otomatis Buat Permintaan Barang </h3>
                                <div id="modal-content-container">
                                    @if (isset($pendingSalesOrders) && count($pendingSalesOrders) == 0)
                                        <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/30 p-4 mb-4 mt-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-yellow-400"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                        Tidak ada Sales Order yang tersedia untuk dibuat permintaan
                                                        barang. Sales Order yang sudah memiliki permintaan barang atau
                                                        status pengirimannya sudah selesai tidak ditampilkan.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4 space-y-4">
                                    <div> <label for="autoSalesOrder"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Sales
                                            Order <span class="text-red-500">*</span> </label> <select
                                            id="autoSalesOrder" name="sales_order_id"
                                            class="select2-modal mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            required>
                                            <option value="">-- Pilih Sales Order --</option>
                                            @foreach ($pendingSalesOrders as $so)
                                                <option value="{{ $so['id'] }}">{{ $so['nomor'] }} -
                                                    {{ $so['customer_nama'] }}
                                                    @if ($so['status_pengiriman'] == 'sebagian')
                                                        (Pengiriman Sebagian)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select> </div>
                                    <div> <label for="autoGudang"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Gudang
                                            <span class="text-red-500">*</span> </label> <select id="autoGudang"
                                            name="gudang_id"
                                            class="select2-modal mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            required>
                                            <option value="">-- Pilih Gudang --</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}"
                                                    {{ $gudang->id == 1 ? 'selected' : '' }}>{{ $gudang->nama }}
                                                </option>
                                            @endforeach
                                        </select> </div>
                                    <div class="rounded-md bg-blue-50 dark:bg-blue-900/30 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0"> <svg class="h-5 w-5 text-blue-400"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd" />
                                                </svg> </div>
                                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                                    Terdapat {{ count($pendingSalesOrders) }} sales order yang dapat
                                                    diproses.
                                                    <br>
                                                    Permintaan barang akan otomatis dibuat berdasarkan item pada Sales
                                                    Order yang dipilih.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"> <button
                            type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-300 transform hover:scale-105">
                            Buat Permintaan </button> <button type="button" id="cancelAutoCreate"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2.5 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-300">
                            Batal </button> </div>
                </form>
            </div>
        </div>
    </div> @push('styles')
        <!-- CDN links for required CSS -->
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <style>
            /* Badge styles */
            .badge {
                @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
            }

            .badge-menunggu {
                @apply bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400;
            }

            .badge-diproses {
                @apply bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400;
            }

            .badge-selesai {
                @apply bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400;
            }

            .badge-dibatalkan {
                @apply bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400;
            }

            /* Modal animation */
            .modal-enter-active,
            .modal-leave-active {
                transition: opacity 0.3s;
            }

            .modal-enter-from,
            .modal-leave-to {
                opacity: 0;
            }

            /* Select2 customization for dark mode */
            .select2-container--default .select2-selection--single {
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                height: 38px;
                padding: 4px 2px;
                display: flex;
                align-items: center;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: #374151;
                border-color: #4B5563;
                color: #F9FAFB;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #F9FAFB;
            }

            .dark .select2-dropdown {
                background-color: #374151;
                border-color: #4B5563;
                color: #F9FAFB;
            }

            .dark .select2-container--default .select2-results__option {
                color: #F9FAFB;
            }

            .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #4F46E5;
            }

            /* Filter animation */
            .filter-transition-enter-active,
            .filter-transition-leave-active {
                transition: all 0.3s ease-out;
            }

            .filter-transition-enter-from,
            .filter-transition-leave-to {
                opacity: 0;
                transform: translateY(-10px);
            }

            .filter-transition-enter-to,
            .filter-transition-leave-from {
                opacity: 1;
                transform: translateY(0);
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Required JS libraries in the correct order -->
        <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ensure jQuery is loaded before proceeding
                if (typeof jQuery === 'undefined') {
                    console.error('jQuery is not loaded! Waiting for it to load...');

                    // Try again in a moment - jQuery might be loading asynchronously
                    setTimeout(function checkJquery() {
                        if (typeof jQuery !== 'undefined') {
                            initializeComponents(jQuery);
                        } else {
                            console.error('jQuery still not available. Please check your jQuery inclusion.');
                        }
                    }, 500);
                } else {
                    // jQuery is available, initialize components
                    initializeComponents(jQuery);
                }

                function initializeComponents($) {
                    // Initialize Select2 if available
                    if ($.fn && $.fn.select2) {
                        $('.select2-search').select2({
                            theme: 'default',
                            width: '100%'
                        });
                    } else {
                        console.warn('Select2 plugin not loaded properly');
                    }

                    // Initialize DateRangePicker if needed
                    if ($.fn && $.fn.daterangepicker && typeof moment !== 'undefined') {
                        // Add any date range picker initialization here if needed
                    } else {
                        console.warn('DateRangePicker or moment.js not loaded properly');
                    }

                    // Quick search functionality
                    $('#quickSearch').on('keyup', function() {
                        var value = $(this).val().toLowerCase();
                        $("#table-container table tbody tr").filter(function() {
                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                        });
                    });

                    // Modal handling
                    $('#autoCreateBtn').on('click', function() {
                        $('#autoCreateModal').removeClass('hidden');

                        // Check if we have pending sales orders
                        const pendingSalesOrdersCount =
                            {{ isset($pendingSalesOrders) ? count($pendingSalesOrders) : 0 }};

                        // Show a message if no pending sales orders are available
                        if (pendingSalesOrdersCount === 0) {
                            $('#modal-content-container').html(`
                                <div class="rounded-md bg-yellow-50 dark:bg-yellow-900/30 p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                Tidak ada Sales Order yang tersedia untuk dibuat permintaan barang. Sales Order yang sudah memiliki permintaan barang atau status pengirimannya sudah selesai tidak ditampilkan.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            `);
                            $('#autoCreateForm').hide();
                        } else {
                            $('#autoCreateForm').show();

                            // Set default gudang to ID 1
                            $("#autoGudang").val("1");
                        }

                        // Initialize Select2 for modal elements with special configuration to prevent modal closing
                        if ($.fn.select2) {
                            $('.select2-modal').select2({
                                width: '100%',
                                dropdownParent: $('#autoCreateModal'),
                                // Prevent the dropdown from closing the modal
                                closeOnSelect: false
                            }).on('select2:opening', function() {
                                // Prevent any propagation that might close the modal
                                $(this).data('select2').$dropdown.find(':input').click(function(e) {
                                    e.stopPropagation();
                                });
                            });
                        }
                    });

                    $('#cancelAutoCreate').on('click', function() {
                        $('#autoCreateModal').addClass('hidden');
                    });

                    // Prevent modal from closing when clicking on select2 dropdown
                    $(document).on('click', '.select2-container, .select2-dropdown, .select2-search, .select2-results',
                        function(e) {
                            e.stopPropagation();
                        });

                    // Close modal when clicking outside
                    $(document).on('mouseup', function(e) {
                        var modal = $(".inline-block.align-bottom");
                        if (modal.length && !modal.is(e.target) && modal.has(e.target).length === 0 &&
                            !$(e.target).hasClass('select2-container') &&
                            !$(e.target).hasClass('select2-dropdown') &&
                            !$(e.target).closest('.select2-container, .select2-dropdown').length) {
                            $('#autoCreateModal').addClass('hidden');
                        }
                    });

                    // Auto process Sales Order buttons
                    $('.auto-process-btn').on('click', function() {
                        const salesOrderId = $(this).data('so-id');
                        const salesOrderNomor = $(this).data('so-nomor');
                        const statusPengiriman = $(this).closest('tr').find('td:nth-child(4)').text().trim();

                        // Konfirmasi gudang yang akan digunakan
                        Swal.fire({
                            title: 'Pilih Gudang',
                            html: `
                                <div class="mb-4 text-left">
                                    <p class="mb-3">Anda akan membuat permintaan barang otomatis untuk:</p>
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-gray-50 dark:bg-gray-800">
                                        <p><strong>Sales Order:</strong> ${salesOrderNomor}</p>
                                        <p><strong>Status Pengiriman:</strong> ${statusPengiriman}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                            ${statusPengiriman.includes('Sebagian') ? 
                                            'Permintaan barang akan dibuat untuk item yang belum dikirim dalam Sales Order ini.' : 
                                            'Permintaan barang akan dibuat untuk semua item dalam Sales Order ini.'}
                                        </p>
                                    </div>
                                </div>
                                <p class="mb-2 text-left">Pilih gudang sumber:</p>
                                <select id="gudangSelector" class="swal2-select w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach ($gudangs as $gudang)
                                        <option value="{{ $gudang->id }}" {{ $gudang->id == 1 ? 'selected' : '' }}>{{ $gudang->nama }}</option>
                                    @endforeach
                                </select>
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Proses Otomatis',
                            cancelButtonText: 'Batal',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            focusConfirm: false,
                            preConfirm: () => {
                                const gudangId = document.getElementById('gudangSelector').value;
                                if (!gudangId) {
                                    Swal.showValidationMessage(
                                        'Silakan pilih gudang terlebih dahulu!');
                                    return false;
                                }
                                return gudangId;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const gudangId = result.value;
                                processAutoRequest(salesOrderId, gudangId);
                            }
                        });
                    });

                    // Function to process automatic request
                    function processAutoRequest(salesOrderId, gudangId) {
                        Swal.fire({
                            title: 'Memproses...',
                            html: 'Sedang membuat permintaan barang otomatis. Mohon tunggu...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // AJAX request to create permintaan barang
                        $.ajax({
                            url: "{{ route('inventaris.permintaan-barang.auto-proses') }}",
                            type: "POST",
                            data: {
                                sales_order_id: salesOrderId,
                                gudang_id: gudangId,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    if (response.produk_tidak_tersedia && response.produk_tidak_tersedia
                                        .length > 0) {
                                        // Show warning about products with insufficient stock
                                        let produkList = response.produk_tidak_tersedia.map(item =>
                                            `<li class="text-left mb-1">
                                                <span class="font-semibold">${item.nama}</span> (${item.kode}): 
                                                Diminta ${item.jumlah_diminta}, Tersedia ${item.jumlah_tersedia}
                                            </li>`
                                        ).join('');

                                        Swal.fire({
                                            title: 'Permintaan Dibuat dengan Catatan',
                                            html: `
                                                <p class="mb-3 text-left">Permintaan barang berhasil dibuat, namun beberapa produk memiliki stok yang tidak mencukupi:</p>
                                                <ul class="mb-3 pl-5 list-disc">
                                                    ${produkList}
                                                </ul>
                                                <p class="text-left">Anda akan diarahkan ke halaman detail permintaan barang.</p>
                                            `,
                                            icon: 'warning',
                                            confirmButtonText: 'Lihat Detail',
                                            confirmButtonColor: '#3085d6'
                                        }).then(() => {
                                            window.location.href = response.redirect;
                                        });
                                    } else {
                                        // Show success message
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: 'Permintaan barang berhasil dibuat dari Sales Order',
                                            icon: 'success',
                                            confirmButtonColor: '#3085d6',
                                            confirmButtonText: 'Lihat Detail'
                                        }).then(() => {
                                            window.location.href = response.redirect;
                                        });
                                    }
                                } else if (response.status === 'warning') {
                                    Swal.fire({
                                        title: 'Perhatian',
                                        text: response.message,
                                        icon: 'warning',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Lihat Permintaan'
                                    }).then(() => {
                                        window.location.href = response.redirect;
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                let errorMessage = "Terjadi kesalahan saat membuat permintaan barang";

                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }

                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        });
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
