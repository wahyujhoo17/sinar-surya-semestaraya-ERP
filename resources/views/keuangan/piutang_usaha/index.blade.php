<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Stats --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Piutang Usaha</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Kelola piutang usaha berdasarkan invoice dan riwayat pembayaran dari customer.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dashboard Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                {{-- Total Piutang Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 p-3.5">
                                <svg class="h-7 w-7 text-indigo-500 dark:text-indigo-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total Piutang Usaha (Belum Lunas)</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">Rp
                                        {{ number_format($totalPiutang ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jumlah Invoice Belum Lunas Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 p-3.5">
                                <svg class="h-7 w-7 text-yellow-500 dark:text-yellow-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jumlah Invoice Belum Lunas</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $jumlahInvoiceBelumLunas ?? 0 }}</p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">Invoice</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jatuh Tempo 7 Hari Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 p-3.5">
                                <svg class="h-7 w-7 text-emerald-500 dark:text-emerald-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Invoice Jatuh Tempo (7 Hari)</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $jatuhTempoMingguIni ?? 0 }}
                                    </p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">Invoice</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div x-data="{
            init() {
                // Initialize table functionality if needed
            }
        }"
            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
            {{-- Enhanced Filter Section --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700">
                <form id="piutangFilterForm" action="{{ route('keuangan.piutang-usaha.index') }}" method="GET">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4">
                        <div class="flex items-center mb-2 sm:mb-0">
                            <div class="bg-primary-100 dark:bg-primary-900/30 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">
                                {{ __('Filter Piutang Usaha') }}</h3>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Gunakan filter untuk mempermudah pencarian data piutang</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div
                            class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                            <label for="customer_id"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Customer</label>
                            <select name="customer_id" id="customer_id"
                                class="pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors w-full select2-basic">
                                <option value="">-- Semua Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->kode_customer }} - {{ $customer->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Filter berdasarkan customer
                                tertentu</p>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                            <label for="start_date"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Invoice Mulai</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="start_date" type="date"
                                    class="pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors w-full"
                                    name="start_date" value="{{ request('start_date') }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tanggal awal periode invoice</p>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                            <label for="end_date"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Invoice Akhir</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input id="end_date" type="date"
                                    class="pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors w-full"
                                    name="end_date" value="{{ request('end_date') }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tanggal akhir periode invoice</p>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                            <label for="status_pembayaran_filter"
                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status
                                Pembayaran Invoice</label>
                            <select name="status_pembayaran_filter" id="status_pembayaran_filter"
                                class="pl-3 pr-8 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-colors w-full">
                                <option value="all"
                                    {{ request('status_pembayaran_filter') == 'all' ? 'selected' : '' }}>-- Semua
                                    Status --
                                </option>
                                <option value="belum_bayar"
                                    {{ request('status_pembayaran_filter') == 'belum_bayar' ? 'selected' : '' }}>Belum
                                    Bayar
                                </option>
                                <option value="sebagian"
                                    {{ request('status_pembayaran_filter') == 'sebagian' ? 'selected' : '' }}>
                                    Lunas Sebagian</option>
                                <option value="lunas"
                                    {{ request('status_pembayaran_filter') == 'lunas' ? 'selected' : '' }}>Lunas
                                </option>
                                <option value="jatuh_tempo"
                                    {{ request('status_pembayaran_filter') == 'jatuh_tempo' ? 'selected' : '' }}>Jatuh
                                    Tempo
                                </option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Filter berdasarkan status
                                pembayaran invoice</p>
                        </div>
                    </div>

                    <div
                        class="flex flex-wrap items-center justify-end space-x-0 space-y-2 sm:space-x-2 sm:space-y-0 mt-4">
                        @if (request()->has('customer_id') ||
                                request()->has('start_date') ||
                                request()->has('end_date') ||
                                (request()->has('status_pembayaran_filter') && request('status_pembayaran_filter') != 'all'))
                            <a href="{{ route('keuangan.piutang-usaha.index') }}"
                                class="px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-500 transition-all duration-200 shadow-sm w-full sm:w-auto justify-center flex items-center group">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-3.5 w-3.5 text-gray-500 group-hover:text-red-500 transition-colors"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <span>Reset Filter</span>
                                </span>
                            </a>
                        @endif
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-colors duration-200 dark:bg-primary-700 dark:hover:bg-primary-600 w-full sm:w-auto justify-center flex items-center">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <span>Terapkan Filter</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Table Container --}}
            <div class="relative px-1 sm:px-3 pb-6">
                {{-- Active Filters Summary --}}
                @if (request('customer_id') ||
                        request('start_date') ||
                        request('end_date') ||
                        (request('status_pembayaran_filter') && request('status_pembayaran_filter') != 'all'))
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 mb-4 mt-4 mx-2">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center">
                                <div class="flex items-center mb-2 sm:mb-0">
                                    <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 mr-2 flex-shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Filter
                                        Aktif:</span>
                                </div>
                                <div class="flex flex-wrap gap-2 sm:ml-3 max-w-2xl">
                                    @if (request('customer_id'))
                                        @php
                                            $selectedCustomer = $customers->firstWhere('id', request('customer_id'));
                                            $customerName = $selectedCustomer
                                                ? $selectedCustomer->kode_customer . ' - ' . $selectedCustomer->nama
                                                : 'Customer';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200">
                                            <svg class="w-3.5 h-3.5 mr-1 text-blue-600 dark:text-blue-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            <span class="font-semibold mr-1">Customer:</span> {{ $customerName }}
                                        </span>
                                    @endif

                                    @if (request('start_date') || request('end_date'))
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200">
                                            <svg class="w-3.5 h-3.5 mr-1 text-blue-600 dark:text-blue-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="font-semibold mr-1">Periode:</span>
                                            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }}
                                            -
                                            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Akhir' }}
                                        </span>
                                    @endif

                                    @if (request('status_pembayaran_filter') && request('status_pembayaran_filter') != 'all')
                                        @php
                                            $statusMap = [
                                                'belum_bayar' => [
                                                    'text' => 'Belum Bayar',
                                                    'icon' =>
                                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                                ],
                                                'sebagian' => [
                                                    'text' => 'Lunas Sebagian',
                                                    'icon' =>
                                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                                ],
                                                'lunas' => [
                                                    'text' => 'Lunas',
                                                    'icon' =>
                                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                                ],
                                                'jatuh_tempo' => [
                                                    'text' => 'Jatuh Tempo',
                                                    'icon' =>
                                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                                ],
                                            ];
                                            $status = $statusMap[request('status_pembayaran_filter')] ?? [
                                                'text' => 'Status',
                                                'icon' =>
                                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200">
                                            <svg class="w-3.5 h-3.5 mr-1 text-blue-600 dark:text-blue-300"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                {!! $status['icon'] !!}
                                            </svg>
                                            <span class="font-semibold mr-1">Status:</span> {{ $status['text'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('keuangan.piutang-usaha.index') }}"
                                class="mt-2 sm:mt-0 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center group transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="group-hover:underline">Hapus Semua Filter</span>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Table Header with Search and Actions --}}
                <div class="py-4 px-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3 sm:mb-0 flex items-center">
                            <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            {{ __('Daftar Piutang Usaha (Invoice)') }}
                            <span
                                class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">({{ $invoices->total() }}
                                data)</span>
                        </h3>
                        <div
                            class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                            <div class="relative w-full sm:w-auto">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" id="tableSearch"
                                    placeholder="Cari nomor invoice, customer, status..."
                                    class="pl-10 pr-10 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full sm:w-72 transition-all duration-200"
                                    aria-label="Pencarian invoice">
                                <div id="clearSearchBtn"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer hidden"
                                    title="Hapus pencarian" aria-label="Hapus pencarian" role="button"
                                    tabindex="0">
                                    <svg class="w-4 h-4 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route(
                                'keuangan.piutang-usaha.export',
                                array_merge(request()->all(), [
                                    'sort' => $sortColumn,
                                    'direction' => $sortDirection,
                                ]),
                            ) }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-full sm:w-auto justify-center transition-all duration-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                {{ __('Export Excel') }}
                            </a>
                            <a href="{{ route(
                                'keuangan.piutang-usaha.pdf',
                                array_merge(request()->all(), [
                                    'sort' => $sortColumn,
                                    'direction' => $sortDirection,
                                ]),
                            ) }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 w-full sm:w-auto justify-center transition-all duration-200"
                                target="_blank">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ __('Export PDF') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="piutangTable">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('No') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                <a href="{{ route(
                                    'keuangan.piutang-usaha.index',
                                    array_merge(request()->except(['sort', 'direction']), [
                                        'sort' => 'nomor',
                                        'direction' => $sortColumn === 'nomor' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                    ]),
                                ) }}"
                                    class="flex items-center group">
                                    {{ __('Nomor Invoice') }}
                                    <span class="ml-1">
                                        @if ($sortColumn === 'nomor')
                                            @if ($sortDirection === 'asc')
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                            @endif
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-400 group-hover:text-primary-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                <a href="{{ route(
                                    'keuangan.piutang-usaha.index',
                                    array_merge(request()->except(['sort', 'direction']), [
                                        'sort' => 'tanggal',
                                        'direction' => $sortColumn === 'tanggal' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                    ]),
                                ) }}"
                                    class="flex items-center group">
                                    {{ __('Tgl Invoice') }}
                                    <span class="ml-1">
                                        @if ($sortColumn === 'tanggal')
                                            @if ($sortDirection === 'asc')
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                            @endif
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-400 group-hover:text-primary-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Customer') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Nomor SO') }} <span class="text-gray-400">(Ref)</span>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                <a href="{{ route(
                                    'keuangan.piutang-usaha.index',
                                    array_merge(request()->except(['sort', 'direction']), [
                                        'sort' => 'total',
                                        'direction' => $sortColumn === 'total' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                    ]),
                                ) }}"
                                    class="flex items-center justify-end group">
                                    {{ __('Total Invoice') }}
                                    <span class="ml-1">
                                        @if ($sortColumn === 'total')
                                            @if ($sortDirection === 'asc')
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                            @endif
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-400 group-hover:text-primary-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Total Pembayaran') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Sisa Piutang') }}
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                <a href="{{ route(
                                    'keuangan.piutang-usaha.index',
                                    array_merge(request()->except(['sort', 'direction']), [
                                        'sort' => 'jatuh_tempo',
                                        'direction' => $sortColumn === 'jatuh_tempo' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                    ]),
                                ) }}"
                                    class="flex items-center justify-center group">
                                    {{ __('Jatuh Tempo') }}
                                    <span class="ml-1">
                                        @if ($sortColumn === 'jatuh_tempo')
                                            @if ($sortDirection === 'asc')
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                            @endif
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-400 group-hover:text-primary-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                <a href="{{ route(
                                    'keuangan.piutang-usaha.index',
                                    array_merge(request()->except(['sort', 'direction']), [
                                        'sort' => 'status_pembayaran',
                                        'direction' => $sortColumn === 'status_pembayaran' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                    ]),
                                ) }}"
                                    class="flex items-center justify-center group">
                                    {{ __('Status') }}
                                    <span class="ml-1">
                                        @if ($sortColumn === 'status_pembayaran')
                                            @if ($sortDirection === 'asc')
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                            @endif
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 text-gray-400 group-hover:text-primary-400"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($invoices as $index => $invoice)
                            @php
                                // Use accessor to ensure nota kredit is included in calculation
                                $totalPayments = $invoice->pembayaranPiutang->sum('jumlah');
                                $sisaPiutang = $invoice->sisa_piutang; // This accessor includes nota kredit

                                // Status display logic
                                $isOverdue =
                                    $invoice->jatuh_tempo &&
                                    \Carbon\Carbon::parse($invoice->jatuh_tempo)
                                        ->startOfDay()
                                        ->lt(\Carbon\Carbon::today()->startOfDay());

                                if ($sisaPiutang <= 0 && $invoice->status === 'lunas') {
                                    $statusText = 'Lunas';
                                    $statusClass =
                                        'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300';
                                } elseif ($isOverdue && $invoice->status !== 'lunas') {
                                    $statusText = 'Jatuh Tempo';
                                    $statusClass = 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300';
                                } elseif ($invoice->status === 'sebagian') {
                                    $statusText = 'Lunas Sebagian';
                                    $statusClass =
                                        'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300';
                                } elseif ($invoice->status === 'belum_bayar') {
                                    $statusText = 'Belum Bayar';
                                    $statusClass =
                                        'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300';
                                } elseif ($sisaPiutang <= 0) {
                                    $statusText = 'Lunas';
                                    $statusClass =
                                        'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300';
                                } else {
                                    $statusText = ucfirst(str_replace('_', ' ', $invoice->status ?? 'Tidak Diketahui'));
                                    $statusClass = 'bg-gray-100 dark:bg-gray-700/50 text-gray-800 dark:text-gray-300';
                                }
                            @endphp
                            <tr
                                class="{{ $index % 2 == 0 ? '' : 'bg-gray-50 dark:bg-gray-800/50' }} hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $index + 1 }}</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                    <a href="{{ route('penjualan.invoice.show', $invoice->id) }}" target="_blank"
                                        class="hover:underline">{{ $invoice->nomor }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ \Carbon\Carbon::parse($invoice->tanggal)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    {{ $invoice->customer->company ?? $invoice->customer->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if ($invoice->salesOrder)
                                        <a href="{{ route('penjualan.sales-order.show', $invoice->salesOrder->id) }}"
                                            target="_blank"
                                            class="hover:underline">{{ $invoice->salesOrder->nomor }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right">
                                    Rp {{ number_format($invoice->total ?? 0, 0, ',', '.') }}</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200 text-right">
                                    Rp {{ number_format($totalPayments, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right {{ $sisaPiutang > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    Rp {{ number_format($sisaPiutang, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if ($invoice->jatuh_tempo)
                                        @php
                                            $jatuhtempo = \Carbon\Carbon::parse($invoice->jatuh_tempo);
                                            $today = \Carbon\Carbon::now();
                                            $isOverdue = $jatuhtempo->isPast() && $statusText !== 'Lunas';
                                            $almostDue =
                                                !$isOverdue &&
                                                $jatuhtempo->isFuture() &&
                                                $jatuhtempo->diffInDays($today) <= 7 &&
                                                $statusText !== 'Lunas';

                                            // Calculate days and ensure it's an integer
                                            $daysLate = $isOverdue ? intval($jatuhtempo->diffInDays($today)) : 0;
                                            $daysRemaining = $almostDue ? intval($jatuhtempo->diffInDays($today)) : 0;
                                        @endphp
                                        <span
                                            class="{{ $isOverdue ? 'text-red-600 dark:text-red-400 font-bold' : ($almostDue ? 'text-yellow-600 dark:text-yellow-400 font-semibold' : 'text-gray-600 dark:text-gray-400') }}">
                                            {{ $jatuhtempo->format('d/m/Y') }}
                                            @if ($isOverdue)
                                                <span class="block text-xs text-red-600 dark:text-red-400">(Telat
                                                    {{ $daysLate }} hari)</span>
                                            @elseif($almostDue)
                                                <span
                                                    class="block text-xs text-yellow-600 dark:text-yellow-400">({{ $daysRemaining }}
                                                    hari lagi)</span>
                                            @endif
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    @if ($statusText == 'Lunas')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            {{ __('Lunas') }}
                                        </span>
                                    @elseif($statusText == 'Lunas Sebagian')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                            {{ __('Lunas Sebagian') }}
                                        </span>
                                    @elseif($statusText == 'Jatuh Tempo')
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                            {{ __('Jatuh Tempo') }}
                                        </span>
                                    @else
                                        {{-- Belum Bayar or other statuses --}}
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300">
                                            {{ __($statusText) }} {{-- Display the actual status --}}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center action-cell">
                                    <div class="flex justify-center space-x-1">
                                        <a href="{{ route('keuangan.piutang-usaha.show', $invoice->id) }}"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-primary-600 dark:text-primary-400 dark:bg-primary-900/20 dark:hover:bg-primary-900/30 transition-colors border border-dashed border-primary-300"
                                            title="Lihat Detail Piutang Invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor" class="w-4 h-4">
                                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                <path fill-rule="evenodd"
                                                    d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('keuangan.pembayaran-piutang.create', ['invoice_id' => $invoice->id]) }}"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-gray-200 text-gray-600 dark:text-gray-400 dark:bg-gray-700/50 dark:hover:bg-gray-600/50 transition-colors border border-dashed border-gray-300 dark:border-gray-600"
                                            title="Lihat Riwayat Pembayaran Invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor" class="w-4 h-4">
                                                <path fill-rule="evenodd"
                                                    d="M1 4a1 1 0 011-1h16a1 1 0 011 1v8a1 1 0 01-1 1H2a1 1 0 01-1-1V4zm12 4a3 3 0 11-6 0 3 3 0 016 0zM4 9a1 1 0 100-2 1 1 0 000 2zm13-1a1 1 0 11-2 0 1 1 0 012 0zM1.75 14.5a.75.75 0 000 1.5c4.417 0 8.693.603 12.749 1.73 1.111.309 2.251-.512 2.251-1.696v-.784a.75.75 0 00-1.5 0v.784a.272.272 0 01-.35.25A49.043 49.043 0 001.75 14.5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" {{-- Adjusted colspan --}}
                                    class="px-6 py-10 whitespace-nowrap text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p>{{ __('Tidak ada data piutang invoice') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan filter di
                                            atas untuk mencari data</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th colspan="7" {{-- Adjusted colspan --}}
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Total Sisa Piutang (dari Invoice ditampilkan):') }}</th>
                            <th
                                class="px-6 py-3 text-right text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                                Rp {{ number_format($totalSisaPiutangCurrent, 0, ',', '.') }}
                            </th>
                            <th colspan="3"></th> {{-- Adjusted colspan --}}
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 pagination-container">
                {{ $invoices->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
    </div>

    <x-slot name="scripts">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2
                $('.select2-basic').select2({
                    placeholder: "-- Pilih --",
                    allowClear: true,
                    width: '100%'
                });

                const searchInput = document.getElementById('tableSearch');
                // const table = document.getElementById('piutangTable'); // table variable is not directly used by top-level logic after this
                const clearSearchBtn = document.getElementById('clearSearchBtn');
                const filterForm = document.getElementById('piutangFilterForm');

                // These are the main containers in index.blade.php whose content will be updated
                const tableContainer = document.getElementById('piutangTable')?.closest('.overflow-x-auto');
                const paginationContainer = document.querySelector('.pagination-container');

                function handleAjaxLinkClick(event, linkUrl) {
                    event.preventDefault();
                    const url = `${linkUrl}${linkUrl.includes('?') ? '&' : '?'}ajax=1`;

                    if (!tableContainer) {
                        console.error("Main table container not found for AJAX update.");
                        return;
                    }

                    tableContainer.style.opacity = '0.5';
                    if (paginationContainer) paginationContainer.style.opacity = '0.5';

                    fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = html;

                            // Update table content from the partial
                            const newTableContentWrapper = tempDiv.querySelector('.overflow-x-auto');
                            if (newTableContentWrapper && tableContainer) {
                                tableContainer.innerHTML = newTableContentWrapper.innerHTML;
                            }

                            // Update pagination content from the partial
                            const newPaginationContentWrapper = tempDiv.querySelector('.pagination-container');
                            if (newPaginationContentWrapper && paginationContainer) {
                                paginationContainer.innerHTML = newPaginationContentWrapper.innerHTML;
                            } else if (paginationContainer && !newPaginationContentWrapper) {
                                // If response has no pagination (e.g. no results), clear existing pagination
                                paginationContainer.innerHTML = '';
                            }

                            history.pushState({}, '', linkUrl); // Use the original link URL for history

                            // Re-initialize functionalities for the new content
                            initializeTableSearch();
                            initializePaginationLinks();
                            initializeSortableHeaderLinks();
                        })
                        .catch(error => console.error('Error fetching AJAX data:', error))
                        .finally(() => {
                            if (tableContainer) tableContainer.style.opacity = '1';
                            if (paginationContainer) paginationContainer.style.opacity = '1';
                        });
                }

                if (filterForm && tableContainer) {
                    filterForm.addEventListener('submit', function(event) {
                        event.preventDefault();
                        const formData = new FormData(filterForm);
                        const params = new URLSearchParams(formData);
                        params.append('ajax', '1'); // Ensure backend knows it's AJAX
                        const url = `${filterForm.action}?${params.toString()}`;

                        tableContainer.style.opacity = '0.5';
                        if (paginationContainer) paginationContainer.style.opacity = '0.5';

                        fetch(url, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = html;

                                const newTableContentWrapper = tempDiv.querySelector('.overflow-x-auto');
                                if (newTableContentWrapper && tableContainer) {
                                    tableContainer.innerHTML = newTableContentWrapper.innerHTML;
                                }

                                const newPaginationContentWrapper = tempDiv.querySelector(
                                    '.pagination-container');
                                if (newPaginationContentWrapper && paginationContainer) {
                                    paginationContainer.innerHTML = newPaginationContentWrapper.innerHTML;
                                } else if (paginationContainer && !newPaginationContentWrapper) {
                                    paginationContainer.innerHTML = '';
                                }

                                // Clean URL for browser history (remove ajax=1)
                                const displayUrl =
                                    `${filterForm.action}?${params.toString().replace(/&?ajax=1&?/, '').replace(/ajax=1$/, '')}`;
                                history.pushState({}, '', displayUrl);

                                initializeTableSearch();
                                initializePaginationLinks();
                                initializeSortableHeaderLinks(); // Add this call
                            })
                            .catch(error => {
                                console.error('Error fetching filtered data:', error);
                            })
                            .finally(() => {
                                if (tableContainer) tableContainer.style.opacity = '1';
                                if (paginationContainer) paginationContainer.style.opacity = '1';
                            });
                    });
                }

                function initializeTableSearch() {
                    const currentTable = document.getElementById('piutangTable'); // Get the potentially new table
                    const currentSearchInput = document.getElementById('tableSearch');
                    const currentClearSearchBtn = document.getElementById('clearSearchBtn');

                    if (!currentTable || !currentSearchInput || !currentClearSearchBtn) return;

                    const tbody = currentTable.getElementsByTagName('tbody')[0];
                    if (!tbody) return;
                    let rows = Array.from(tbody.children).filter(child => child.tagName === 'TR' && !child.classList
                        .contains('empty-row') && !child.classList.contains('empty-search-results'));

                    // Debounce function to improve performance
                    function debounce(func, wait) {
                        let timeout;
                        return function() {
                            const context = this;
                            const args = arguments;
                            clearTimeout(timeout);
                            timeout = setTimeout(() => {
                                func.apply(context, args);
                            }, wait);
                        };
                    }

                    // Show/hide clear button based on search input
                    function toggleClearButton() {
                        if (searchInput.value.length > 0) {
                            clearSearchBtn.classList.remove('hidden');
                        } else {
                            clearSearchBtn.classList.add('hidden');
                        }
                    }

                    // Perform search on table
                    function performSearch() {
                        const searchTerm = searchInput.value.toLowerCase().trim();
                        let visibleCount = 0;

                        // Add loading state if search isn't empty
                        if (searchTerm.length > 0) {
                            searchInput.classList.add('bg-primary-50', 'dark:bg-primary-900/10');
                        } else {
                            searchInput.classList.remove('bg-primary-50', 'dark:bg-primary-900/10');
                        }

                        for (let i = 0; i < rows.length; i++) {
                            const row = rows[i];
                            // Skip the 'empty' row if it exists
                            if (row.classList.contains('empty-row')) continue;

                            const cells = row.getElementsByTagName('td');
                            let found = false;

                            // Reset any previous highlighting
                            for (let j = 0; j < cells.length; j++) {
                                const originalContent = cells[j].getAttribute('data-original-content');
                                if (originalContent) {
                                    cells[j].innerHTML = originalContent;
                                }
                            }

                            if (searchTerm) {
                                for (let j = 0; j < cells.length; j++) {
                                    // Skip action cells (usually the last column)
                                    if (cells[j].classList.contains('action-cell')) continue;

                                    const cellContent = cells[j].innerHTML;
                                    const cellText = cells[j].textContent.toLowerCase();

                                    if (cellText.includes(searchTerm)) {
                                        // Store original content if not already stored
                                        if (!cells[j].getAttribute('data-original-content')) {
                                            cells[j].setAttribute('data-original-content', cellContent);
                                        }

                                        // Only highlight text content, not HTML tags
                                        const regex = new RegExp(searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'),
                                            'gi');
                                        cells[j].innerHTML = cellContent.replace(
                                            regex,
                                            '<span class="bg-yellow-200 dark:bg-yellow-900 px-1 rounded">$&</span>'
                                        );

                                        found = true;
                                    }
                                }
                            } else {
                                // If search is empty, always show the row
                                found = true;
                            }

                            row.style.display = found ? '' : 'none';
                            if (found) visibleCount++;
                        }

                        // Show empty state message if no results found
                        const emptyRow = document.querySelector('.empty-search-results');
                        if (visibleCount === 0 && searchTerm !== '') {
                            if (!emptyRow) {
                                const newRow = document.createElement('tr');
                                newRow.className = 'empty-search-results';
                                newRow.innerHTML = `<td colspan="10" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">Tidak ada data ditemukan</p>
                                        <p class="text-sm mt-1">Tidak ada invoice yang cocok dengan pencarian "<span class="font-medium text-primary-600 dark:text-primary-400">${searchTerm}</span>"</p>
                                        <button id="resetSearchBtn" class="mt-3 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Hapus Pencarian
                                        </button>
                                    </div>
                                </td>`;
                                tbody.appendChild(newRow);

                                // Add event listener to the reset search button
                                document.getElementById('resetSearchBtn').addEventListener('click', clearSearch);
                            }
                        } else if (emptyRow) {
                            emptyRow.remove();
                        }

                        toggleClearButton();
                    }

                    // Clear search function
                    function clearSearch() {
                        searchInput.value = '';
                        searchInput.classList.remove('bg-primary-50', 'dark:bg-primary-900/10');

                        // Reset any highlighting
                        for (let i = 0; i < rows.length; i++) {
                            const row = rows[i];
                            const cells = row.getElementsByTagName('td');

                            for (let j = 0; j < cells.length; j++) {
                                const originalContent = cells[j].getAttribute('data-original-content');
                                if (originalContent) {
                                    cells[j].innerHTML = originalContent;
                                    cells[j].removeAttribute('data-original-content');
                                }
                            }

                            // Show all rows
                            row.style.display = '';
                        }

                        // Remove empty results message if exists
                        const emptyRow = document.querySelector('.empty-search-results');
                        if (emptyRow) {
                            emptyRow.remove();
                        }

                        toggleClearButton();
                        searchInput.focus();
                    }

                    // Add event listeners
                    searchInput.addEventListener('input', function() {
                        // Show/hide clear button immediately
                        toggleClearButton();
                        // Debounce the actual search to improve performance
                        debounce(performSearch, 300)();
                    });

                    searchInput.addEventListener('keyup', function(e) {
                        if (e.key === 'Escape') {
                            clearSearch();
                        }
                    });

                    clearSearchBtn.addEventListener('click', clearSearch);

                    // Support keyboard access for the clear button
                    clearSearchBtn.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            clearSearch();
                        }
                    });

                    // Initialize the clear button state on page load
                    toggleClearButton();
                }

                // Initialize Select2 for customer filter
                $('#customer_id').select2({
                    placeholder: "-- Semua Customer --",
                    allowClear: true,
                    width: '100%'

                });

                function initializePaginationLinks() {
                    if (!paginationContainer) return;
                    // Corrected selector for pagination links based on partial's structure
                    const paginationLinks = paginationContainer.querySelectorAll('a.pagination-link');
                    paginationLinks.forEach(link => {
                        link.addEventListener('click', function(event) {
                            handleAjaxLinkClick(event, this.href);
                        });
                    });
                }

                function initializeSortableHeaderLinks() {
                    if (!tableContainer) return;
                    const currentTable = tableContainer.querySelector('#piutangTable');
                    if (!currentTable) return;

                    const headerLinks = currentTable.querySelectorAll('thead th a');
                    headerLinks.forEach(link => {
                        link.addEventListener('click', function(event) {
                            handleAjaxLinkClick(event, this.href);
                        });
                    });
                }

                // Initial calls
                initializeTableSearch();
                initializePaginationLinks();
                initializeSortableHeaderLinks(); // Add this initial call
            });
        </script>
    </x-slot>
</x-app-layout>
