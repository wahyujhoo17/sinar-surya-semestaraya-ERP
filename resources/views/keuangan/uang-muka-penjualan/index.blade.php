<x-app-layout>
    <x-slot name="header">
    </x-slot>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single {
                height: 42px;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 40px;
                padding-left: 12px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: #374151;
                border-color: #4b5563;
                color: white;
            }

            .dark .select2-dropdown {
                background-color: #374151;
                border-color: #4b5563;
            }

            .dark .select2-results__option {
                color: white;
            }

            .dark .select2-results__option--highlighted {
                background-color: #6366f1;
            }

            .summary-card {
                transition: all 0.3s ease;
            }

            .summary-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }

            @media (max-width: 768px) {
                .mobile-responsive-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 1rem;
                }

                .mobile-responsive-text {
                    font-size: 0.75rem;
                }

                .mobile-responsive-value {
                    font-size: 1rem;
                }

                .container-full-width {
                    width: 100vw;
                    margin-left: calc(-50vw + 50%);
                }
            }

            /* Full width container */
            .full-width-container {
                width: 100%;
                max-width: none;
            }

            /* Better table scrolling on mobile */
            @media (max-width: 640px) {
                .table-container {
                    margin-left: -1rem;
                    margin-right: -1rem;
                    border-radius: 0;
                }
            }
        </style>
    @endpush

    <div class="py-4 sm:py-6 lg:py-8" x-data="uangMukaPenjualanData()">
        <div class="w-full px-2 sm:px-4 lg:px-6 xl:px-8">
            {{-- Header --}}
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Uang Muka Penjualan</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">
                            Kelola penerimaan uang muka dari customer dan aplikasinya ke invoice
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('keuangan.uang-muka-penjualan.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg class="mr-2 -ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Uang Muka
                        </a>
                    </div>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
                {{-- Total Uang Muka Tersedia --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Tersedia
                                </dt>
                                <dd class="mt-2">
                                    <div
                                        class="flex items-baseline text-xl font-semibold text-gray-900 dark:text-white">
                                        <span class="truncate">Rp
                                            {{ number_format($uangMukaPenjualan->where('status', 'confirmed')->sum('jumlah_tersedia'), 0, ',', '.') }}</span>
                                        <span
                                            class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">IDR</span>
                                    </div>
                                </dd>
                            </div>
                            <div class="flex-shrink-0 rounded-lg bg-green-100 dark:bg-green-900/30 p-2">
                                <svg class="h-4 w-4 text-green-500 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Sudah Diaplikasikan --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total
                                    Teraplikasi</dt>
                                <dd class="mt-2">
                                    <div
                                        class="flex items-baseline text-xl font-semibold text-gray-900 dark:text-white">
                                        <span class="truncate">Rp
                                            {{ number_format($uangMukaPenjualan->sum('jumlah') - $uangMukaPenjualan->sum('jumlah_tersedia'), 0, ',', '.') }}</span>
                                        <span
                                            class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">IDR</span>
                                    </div>
                                </dd>
                            </div>
                            <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-2">
                                <svg class="h-4 w-4 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h4.125M8.25 8.25V6.108" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jumlah Transaksi --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total
                                    Transaksi</dt>
                                <dd class="mt-2">
                                    <div
                                        class="flex items-baseline text-xl font-semibold text-gray-900 dark:text-white">
                                        <span
                                            class="truncate">{{ number_format($uangMukaPenjualan->count(), 0, ',', '.') }}</span>
                                        <span
                                            class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">transaksi</span>
                                    </div>
                                </dd>
                            </div>
                            <div class="flex-shrink-0 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 p-2">
                                <svg class="h-4 w-4 text-yellow-500 dark:text-yellow-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Rata-rata Nominal --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Rata-rata
                                    Nominal</dt>
                                <dd class="mt-2">
                                    <div
                                        class="flex items-baseline text-xl font-semibold text-gray-900 dark:text-white">
                                        <span class="truncate">Rp
                                            {{ number_format($uangMukaPenjualan->count() > 0 ? $uangMukaPenjualan->avg('jumlah') : 0, 0, ',', '.') }}</span>
                                        <span
                                            class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">IDR</span>
                                    </div>
                                </dd>
                            </div>
                            <div class="flex-shrink-0 rounded-lg bg-purple-100 dark:bg-purple-900/30 p-2">
                                <svg class="h-4 w-4 text-purple-500 dark:text-purple-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Filter & Pencarian</h3>
                </div>
                <div class="p-4">
                    <form method="GET" action="{{ route('keuangan.uang-muka-penjualan.index') }}" class="space-y-4">
                        {{-- Hidden inputs to maintain sort parameters --}}
                        @if (request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        @if (request('direction'))
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Search --}}
                            <div class="sm:col-span-2 lg:col-span-1">
                                <label for="search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pencarian</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Nomor, customer, keterangan..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                            </div>

                            {{-- Customer --}}
                            <div class="sm:col-span-2 lg:col-span-1">
                                <label for="customer_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer</label>
                                <select name="customer_id" id="customer_id"
                                    class="select2-customer block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                    <option value="">Semua Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->nama ?? $customer->company }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select name="status" id="status"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="confirmed"
                                        {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="partially_applied"
                                        {{ request('status') == 'partially_applied' ? 'selected' : '' }}>Sebagian
                                        Teraplikasi</option>
                                    <option value="applied" {{ request('status') == 'applied' ? 'selected' : '' }}>
                                        Sudah Teraplikasi</option>
                                </select>
                            </div>

                            {{-- Date Range --}}
                            <div>
                                <label for="tanggal_awal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Periode</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="date" name="tanggal_awal" id="tanggal_awal"
                                        value="{{ request('tanggal_awal') }}" placeholder="Dari"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                    <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                        value="{{ request('tanggal_akhir') }}" placeholder="Sampai"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <a href="{{ route('keuangan.uang-muka-penjualan.index') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                        clip-rule="evenodd" />
                                </svg>
                                Reset Filter
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <svg class="mr-2 -ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-2 sm:px-4 py-3 sm:py-5">
                    <div class="overflow-x-auto -mx-2 sm:mx-0">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                            <a href="{{ route(
                                                'keuangan.uang-muka-penjualan.index',
                                                array_merge(request()->except(['sort', 'direction']), [
                                                    'sort' => 'nomor',
                                                    'direction' => $sortColumn === 'nomor' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                                ]),
                                            ) }}"
                                                class="flex items-center group">
                                                Nomor
                                                <span class="ml-1">
                                                    @if ($sortColumn === 'nomor')
                                                        @if ($sortDirection === 'asc')
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
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
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                            <a href="{{ route(
                                                'keuangan.uang-muka-penjualan.index',
                                                array_merge(request()->except(['sort', 'direction']), [
                                                    'sort' => 'tanggal',
                                                    'direction' => $sortColumn === 'tanggal' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                                ]),
                                            ) }}"
                                                class="flex items-center group">
                                                Tanggal
                                                <span class="ml-1">
                                                    @if ($sortColumn === 'tanggal')
                                                        @if ($sortDirection === 'asc')
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
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
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                            <a href="{{ route(
                                                'keuangan.uang-muka-penjualan.index',
                                                array_merge(request()->except(['sort', 'direction']), [
                                                    'sort' => 'customer_id',
                                                    'direction' => $sortColumn === 'customer_id' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                                ]),
                                            ) }}"
                                                class="flex items-center group">
                                                Customer
                                                <span class="ml-1">
                                                    @if ($sortColumn === 'customer_id')
                                                        @if ($sortDirection === 'asc')
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
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
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                            <a href="{{ route(
                                                'keuangan.uang-muka-penjualan.index',
                                                array_merge(request()->except(['sort', 'direction']), [
                                                    'sort' => 'jumlah',
                                                    'direction' => $sortColumn === 'jumlah' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                                ]),
                                            ) }}"
                                                class="flex items-center group">
                                                Jumlah
                                                <span class="ml-1">
                                                    @if ($sortColumn === 'jumlah')
                                                        @if ($sortDirection === 'asc')
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
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
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                            <a href="{{ route(
                                                'keuangan.uang-muka-penjualan.index',
                                                array_merge(request()->except(['sort', 'direction']), [
                                                    'sort' => 'jumlah_tersedia',
                                                    'direction' => $sortColumn === 'jumlah_tersedia' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                                ]),
                                            ) }}"
                                                class="flex items-center group">
                                                Tersedia
                                                <span class="ml-1">
                                                    @if ($sortColumn === 'jumlah_tersedia')
                                                        @if ($sortDirection === 'asc')
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
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
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                                            Pembayaran
                                        </th>
                                        <th scope="col"
                                            class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600/50">
                                            <a href="{{ route(
                                                'keuangan.uang-muka-penjualan.index',
                                                array_merge(request()->except(['sort', 'direction']), [
                                                    'sort' => 'status',
                                                    'direction' => $sortColumn === 'status' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                                ]),
                                            ) }}"
                                                class="flex items-center group">
                                                Status
                                                <span class="ml-1">
                                                    @if ($sortColumn === 'status')
                                                        @if ($sortDirection === 'asc')
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-4 w-4 text-primary-500" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
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
                                            class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($uangMukaPenjualan as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td
                                                class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $item->tanggal->format('d/m/Y') }}
                                                </div>
                                                {{ $item->nomor }}
                                            </td>
                                            <td
                                                class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 hidden sm:table-cell">
                                                {{ $item->tanggal->format('d/m/Y') }}
                                            </td>
                                            <td
                                                class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <div class="truncate max-w-32 sm:max-w-none">
                                                    {{ $item->customer->nama ?? $item->customer->company }}
                                                </div>
                                            </td>
                                            <td
                                                class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <div class="md:hidden text-xs text-gray-400 dark:text-gray-500">
                                                    Tersedia: Rp
                                                    {{ number_format($item->jumlah_tersedia, 0, ',', '.') }}
                                                </div>
                                                Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 hidden md:table-cell">
                                                Rp {{ number_format($item->jumlah_tersedia, 0, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 hidden lg:table-cell">
                                                @if ($item->metode_pembayaran == 'kas')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Kas: {{ $item->kas->nama ?? '-' }}
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Bank: {{ $item->rekeningBank->nama_bank ?? '-' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                @if ($item->status == 'pending')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        <span class="hidden sm:inline">Pending</span>
                                                        <span class="sm:hidden">P</span>
                                                    </span>
                                                @elseif($item->status == 'confirmed')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <span class="hidden sm:inline">Confirmed</span>
                                                        <span class="sm:hidden">C</span>
                                                    </span>
                                                @elseif($item->status == 'partially_applied')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        <span class="hidden sm:inline">Sebagian Teraplikasi</span>
                                                        <span class="sm:hidden">PA</span>
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                        <span class="hidden sm:inline">Sudah Teraplikasi</span>
                                                        <span class="sm:hidden">A</span>
                                                    </span>
                                                @endif
                                            </td>
                                            <td
                                                class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium action-cell">
                                                <div class="flex justify-end space-x-1">
                                                    <a href="{{ route('keuangan.uang-muka-penjualan.show', $item->id) }}"
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-primary-600 dark:text-primary-400 dark:bg-primary-900/20 dark:hover:bg-primary-900/30 transition-colors border border-dashed border-primary-300"
                                                        title="Lihat Detail Uang Muka">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" class="w-4 h-4">
                                                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                            <path fill-rule="evenodd"
                                                                d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>

                                                    @if ($item->status == 'confirmed' || $item->status == 'partially_applied')
                                                        <a href="{{ route('keuangan.uang-muka-penjualan.edit', $item->id) }}"
                                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-yellow-600 dark:text-yellow-400 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300"
                                                            title="Edit Uang Muka">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 20 20" fill="currentColor"
                                                                class="w-4 h-4">
                                                                <path
                                                                    d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                                                <path
                                                                    d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                                            </svg>
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('keuangan.uang-muka-penjualan.exportPdf', $item->id) }}"
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-red-600 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300"
                                                        target="_blank" title="Download PDF">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" class="w-4 h-4">
                                                            <path fill-rule="evenodd"
                                                                d="M4.5 2A1.5 1.5 0 0 0 3 3.5v13A1.5 1.5 0 0 0 4.5 18h11a1.5 1.5 0 0 0 1.5-1.5V7.621a1.5 1.5 0 0 0-.44-1.06l-4.12-4.122A1.5 1.5 0 0 0 11.378 2H4.5Zm2.25 8.5a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Zm0 3a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8"
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                                                Tidak ada data uang muka penjualan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if ($uangMukaPenjualan->hasPages())
                        <div class="mt-6 px-2 sm:px-0">
                            {{ $uangMukaPenjualan->appends(request()->query())->links('vendor.pagination.tailwind-custom') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            // Alpine.js data
            function uangMukaPenjualanData() {
                return {
                    // Handle pagination click events
                    handlePaginationClick(url) {
                        // Show loading state
                        this.showLoader();

                        // Navigate to the URL
                        window.location.href = url;
                    },

                    // Show loading state
                    showLoader() {
                        // You can implement loading spinner here if needed
                        console.log('Loading...');
                    }
                }
            }

            $(document).ready(function() {
                // Initialize Select2 for customer dropdown
                $('.select2-customer').select2({
                    placeholder: 'Pilih Customer',
                    allowClear: true,
                    width: '100%',
                    theme: 'default',
                    dropdownCssClass: 'select2-dropdown-dark',
                    language: {
                        noResults: function() {
                            return "Tidak ada customer yang ditemukan";
                        },
                        searching: function() {
                            return "Mencari...";
                        }
                    }
                });

                // Handle dark mode changes
                function updateSelect2Theme() {
                    const isDark = document.documentElement.classList.contains('dark');
                    if (isDark) {
                        $('.select2-container--default .select2-selection--single').addClass('dark');
                    } else {
                        $('.select2-container--default .select2-selection--single').removeClass('dark');
                    }
                }

                // Update theme on load
                updateSelect2Theme();

                // Watch for dark mode changes
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            updateSelect2Theme();
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            });
        </script>
    @endpush
</x-app-layout>
