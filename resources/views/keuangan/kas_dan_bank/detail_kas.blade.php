<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <!-- Global function definitions -->
    <script>
        // Pre-define the transaction detail functions to avoid "not defined" errors
        // These are temporary placeholders that will be replaced by the real functions from simple-modal.js
        if (typeof window.showSimpleModal !== 'function') {
            window.showSimpleModal = function(id, noBukti, tanggal, keterangan, jenis, jumlah, createdAt, updatedAt,
                relatedId, relatedType) {
                // Check if the script is still loading
                alert('Loading modal system. Please try again in a moment.');

                // Attempt to load the script again
                const script = document.createElement('script');
                script.src = "{{ asset('js/simple-modal.js') }}?v=" + new Date().getTime();
                script.onload = function() {
                    if (typeof window.showSimpleModal === 'function' &&
                        window.showSimpleModal !== arguments.callee) {
                        window.showSimpleModal(id, noBukti, tanggal, keterangan, jenis, jumlah, createdAt,
                            updatedAt,
                            relatedId, relatedType);
                    }
                };
                document.head.appendChild(script);
            };
        }

        if (typeof window.closeSimpleModal !== 'function') {
            window.closeSimpleModal = function() {
                // The actual implementation will be loaded from simple-modal.js or defined later in the page
            };
        }

        // For backward compatibility - ensure these aliases always exist and point to the correct functions
        window.showTransactionDetail = window.showSimpleModal;
        window.closeTransactionDetail = window.closeSimpleModal;

        // When document is ready, ensure the aliases are properly set
        document.addEventListener('DOMContentLoaded', function() {
            // Make sure aliases are correctly set
            window.showTransactionDetail = window.showSimpleModal;
            window.closeTransactionDetail = window.closeSimpleModal;
        });
    </script>

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Toast Notification --}}
        <div id="toast-notification"
            class="hidden fixed top-16 right-4 z-50 transform transition-transform duration-300 ease-in-out translate-x-full">
            <div class="flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800"
                role="alert">
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z" />
                    </svg>
                    <span class="sr-only">Info icon</span>
                </div>
                <div id="toast-message" class="ml-3 text-sm font-normal">Filter berhasil diterapkan.</div>
                <button type="button"
                    class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                    data-dismiss-target="#toast-notification" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Header Section with improved styling --}}
        <div
            class="mb-8 bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row">
                <!-- Kas Info Section -->
                <div class="p-6 flex-grow">
                    <div class="flex items-start">
                        <div
                            class="h-16 w-16 flex-shrink-0 flex items-center justify-center rounded-lg bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30">
                            <svg class="h-10 w-10 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kas->nama }}</h2>
                            <div class="mt-1 flex items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $kas->deskripsi }}</span>
                            </div>
                            <div class="mt-2">
                                @if ($kas->is_aktif)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                        Non-aktif
                                    </span>
                                @endif

                                <!-- Test button for modal -->
                                <button type="button"
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400"
                                    onclick="showSimpleModal('test-id', 'TEST-001', '13 Juni 2025', 'Test Keterangan', 'masuk', 1000000, '13 Juni 2025 12:00', '13 Juni 2025 12:00', '', '')">
                                    Test Modal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kas Saldo Section -->
                <div
                    class="p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                    <div class="flex flex-col h-full justify-center">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($kas->saldo, 0, ',', '.') }}
                        </div>
                        <div class="mt-4 flex space-x-3">
                            <button type="button"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                                <svg class="mr-2 -ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Tambah Transaksi
                            </button>
                            <button type="button"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                                <svg class="mr-2 -ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Edit Kas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transaction Summary Card --}}
        <div id="transactionSummary"
            class="mb-6 bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-4 sm:p-5">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Ringkasan Transaksi</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-4">Ringkasan transaksi kas berdasarkan filter
                    yang diterapkan</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Money In -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100 dark:border-gray-600 shadow-sm">
                        <div class="flex items-center">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400 mr-4 flex-shrink-0">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Masuk</p>
                                <p class="text-lg font-bold text-green-600 dark:text-green-400" id="totalMasuk">Rp
                                    {{ number_format($totalMasuk, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Money Out -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100 dark:border-gray-600 shadow-sm">
                        <div class="flex items-center">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 mr-4 flex-shrink-0">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Keluar</p>
                                <p class="text-lg font-bold text-red-600 dark:text-red-400" id="totalKeluar">Rp
                                    {{ number_format($totalKeluar, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Net Change -->
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100 dark:border-gray-600 shadow-sm">
                        <div class="flex items-center">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 mr-4 flex-shrink-0">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m9-9H3" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perubahan Bersih</p>
                                <p id="perubahanBersih"
                                    class="text-lg font-bold {{ $totalMasuk - $totalKeluar >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }}">
                                    Rp {{ number_format($totalMasuk - $totalKeluar, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Bar with Filtering --}}
        <div
            class="mb-6 bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                    <div class="mb-4 sm:mb-0">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Riwayat Transaksi</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftar lengkap transaksi kas
                            {{ $kas->nama }}</p>

                        {{-- Date Range Display --}}
                        <div class="mt-2 flex flex-wrap gap-2">
                            @if (request('tanggal_mulai') && request('tanggal_akhir'))
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}
                                </span>
                            @elseif(request('periode') && request('periode') != 'all')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    {{ request('periode') }} Hari Terakhir
                                </span>
                            @endif

                            @if (request('jenis') && request('jenis') != 'all')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm {{ request('jenis') == 'masuk' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                    <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="{{ request('jenis') == 'masuk' ? 'M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75' : 'M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75' }}" />
                                    </svg>
                                    {{ request('jenis') == 'masuk' ? 'Transaksi Masuk' : 'Transaksi Keluar' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Filter & Actions - Modern UI --}}
                    <form id="filterForm" action="{{ route('keuangan.kas-dan-bank.kas', $kas->id) }}" method="GET"
                        class="w-full sm:w-auto">
                        <div class="flex flex-wrap gap-3 items-center">
                            <div class="relative min-w-[160px]">
                                <select name="jenis" id="jenisFilter"
                                    class="appearance-none bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md h-10 pl-3 pr-8 py-0 w-full hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="all" {{ request('jenis', 'all') == 'all' ? 'selected' : '' }}>
                                        Semua Transaksi</option>
                                    <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>
                                        Transaksi Masuk</option>
                                    <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>
                                        Transaksi Keluar</option>
                                </select>

                            </div>

                            <div class="relative min-w-[180px]">
                                <select name="periode" id="periodeSelect"
                                    class="appearance-none bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md h-10 pl-3 pr-8 py-0 w-full hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="30" {{ request('periode', '30') == '30' ? 'selected' : '' }}>30
                                        Hari Terakhir</option>
                                    <option value="60" {{ request('periode') == '60' ? 'selected' : '' }}>60 Hari
                                        Terakhir</option>
                                    <option value="90" {{ request('periode') == '90' ? 'selected' : '' }}>90 Hari
                                        Terakhir</option>
                                    <option value="custom" {{ request('periode') == 'custom' ? 'selected' : '' }}>
                                        Pilih Rentang Tanggal</option>
                                    <option value="all" {{ request('periode') == 'all' ? 'selected' : '' }}>Semua
                                        Waktu</option>
                                </select>

                            </div>

                            <div id="dateRangePicker"
                                class="{{ request('periode') == 'custom' || (request('tanggal_mulai') && request('tanggal_akhir')) ? 'flex' : 'hidden' }} space-x-2 items-center"
                                style="transition: opacity 0.2s ease-in-out; opacity: 1;">
                                <div class="relative">
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                        value="{{ request('tanggal_mulai') }}"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md h-10 px-3 py-0 w-40 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <span class="text-gray-500 dark:text-gray-400">-</span>
                                <div class="relative">
                                    <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                        value="{{ request('tanggal_akhir') }}"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-md h-10 px-3 py-0 w-40 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <button type="button" id="filterButton"
                                    class="flex items-center justify-center h-10 px-4 py-2 bg-primary-600 text-white rounded-md shadow hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <svg class="mr-2 -ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                    Filter
                                </button>

                                <button type="button" id="resetButton"
                                    class="flex items-center justify-center h-10 px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md shadow hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <svg class="mr-2 -ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Reset
                                </button>
                                <button type="button" id="printButton"
                                    class="flex items-center justify-center h-10 px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md shadow hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                    <svg class="mr-2 -ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                                    </svg>
                                    Cetak
                                </button>

                                <!-- Filter Keyboard Shortcuts Help -->
                                <div class="relative group">
                                    <button type="button"
                                        class="flex items-center justify-center h-10 w-10 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                        </svg>
                                    </button>
                                    <div
                                        class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 border border-gray-200 dark:border-gray-700 transform opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-200 z-50">
                                        <h5
                                            class="text-sm font-semibold text-gray-900 dark:text-white mb-2 border-b border-gray-200 dark:border-gray-700 pb-1">
                                            Pintasan Keyboard</h5>
                                        <ul class="space-y-2 text-xs">
                                            <li class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-300">Terapkan filter</span>
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">Enter</span>
                                            </li>
                                            <li class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-300">Reset filter</span>
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">Esc</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Advanced filters removed --}}
                    </form>
                </div>
            </div>
        </div>

        {{-- Transactions --}}
        <div
            class="bg-white dark:bg-gray-800 shadow rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 relative">

            {{-- Loading Overlay for Table --}}
            <div id="tableLoadingOverlay"
                class="hidden absolute inset-0 bg-gray-200/50 dark:bg-gray-900/60 items-center justify-center z-10 rounded-xl backdrop-blur-sm">
                <div
                    class="flex flex-col items-center bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                    <div
                        class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-600 dark:border-primary-500">
                    </div>
                    <span class="mt-4 text-gray-700 dark:text-gray-300 font-medium">Sedang memuat data...</span>
                </div>
            </div>

            <div id="tableContent" x-data="{}">
                @if ($transaksi->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16">
                        <div class="bg-green-50 dark:bg-green-900/30 rounded-full p-4 mb-6">
                            <svg class="w-16 h-16 text-green-400 dark:text-green-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Belum ada transaksi kas</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-6 max-w-sm text-center">
                            Tambahkan transaksi kas untuk mulai mencatat pemasukan dan pengeluaran dana kas Anda.
                        </p>
                        <div>
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 transform hover:-translate-y-0.5">
                                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Tambah Transaksi Baru
                            </button>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                        No. Bukti
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                        Dokumen Terkait
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                        Jenis
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider dark:text-gray-300">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach ($transaksi as $trx)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150 ease-in-out cursor-pointer hover:shadow-sm relative transform hover:scale-[1.01]"
                                        data-id="{{ $trx->id }}" data-no-bukti="{{ $trx->no_bukti }}"
                                        data-tanggal="{{ \Carbon\Carbon::parse($trx->tanggal)->format('d F Y') }}"
                                        data-keterangan="{{ $trx->keterangan }}" data-jenis="{{ $trx->jenis }}"
                                        data-jumlah="{{ $trx->jumlah }}"
                                        data-created-at="{{ \Carbon\Carbon::parse($trx->created_at)->format('d F Y H:i') }}"
                                        data-updated-at="{{ \Carbon\Carbon::parse($trx->updated_at)->format('d F Y H:i') }}"
                                        data-related-id="{{ $trx->related_id ?? '' }}"
                                        data-related-type="{{ $trx->related_type ?? '' }}"
                                        onclick="showSimpleModal('{{ $trx->id }}', '{{ $trx->no_bukti }}', '{{ \Carbon\Carbon::parse($trx->tanggal)->format('d F Y') }}', '{{ $trx->keterangan }}', '{{ $trx->jenis }}', '{{ $trx->jumlah }}', '{{ \Carbon\Carbon::parse($trx->created_at)->format('d F Y H:i') }}', '{{ \Carbon\Carbon::parse($trx->updated_at)->format('d F Y H:i') }}', '{{ $trx->related_id ?? '' }}', '{{ $trx->related_type ?? '' }}')">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                {{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-200 font-medium">
                                                {{ $trx->no_bukti }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-gray-200 line-clamp-2">
                                                {{ $trx->keterangan }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                @if ($trx->related_id && $trx->related_type)
                                                    <a href="{{ $trx->jenis == 'masuk' ? route('keuangan.piutang-usaha.show', $trx->related_id) : route('keuangan.hutang-usaha.show', $trx->related_id) }}"
                                                        target="_blank"
                                                        class="inline-flex items-center text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 hover:underline transition-colors"
                                                        onclick="event.stopPropagation()">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" class="w-4 h-4 mr-1">
                                                            <path fill-rule="evenodd"
                                                                d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ class_basename($trx->related_type) }}

                                                    </a>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($trx->jenis == 'masuk')
                                                <span
                                                    class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" class="w-3.5 h-3.5 mr-1">
                                                        <path fill-rule="evenodd"
                                                            d="M1.22 5.222a.75.75 0 011.06 0L7 9.942l3.768-3.769a.75.75 0 011.113.058 20.908 20.908 0 013.813 7.254l1.574-2.727a.75.75 0 011.3.75l-2.475 4.286a.75.75 0 01-1.025.275l-4.287-2.475a.75.75 0 01.75-1.3l2.71 1.565a19.422 19.422 0 00-3.013-6.024L7.53 11.533a.75.75 0 01-1.06 0l-5.25-5.25a.75.75 0 010-1.06z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Masuk
                                                </span>
                                            @else
                                                <span
                                                    class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" class="w-3.5 h-3.5 mr-1">
                                                        <path fill-rule="evenodd"
                                                            d="M12.577 4.878a.75.75 0 01.919-.53l4.78 1.281a.75.75 0 01.531.919l-1.281 4.78a.75.75 0 01-1.449-.387l.81-3.022a19.407 19.407 0 00-5.594 5.203.75.75 0 01-1.139.093L7 10.06l-4.72 4.72a.75.75 0 01-1.06-1.061l5.25-5.25a.75.75 0 011.06 0l3.074 3.073a20.923 20.923 0 015.545-4.931l-3.042-.815a.75.75 0 01-.53-.919z"
                                                            clip-rule="evenodd" />
                                                    </svg>

                                                    Keluar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div
                                                class="text-sm font-medium {{ $trx->jenis == 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                {{ $trx->jenis == 'masuk' ? '+' : '-' }} Rp
                                                {{ number_format($trx->jumlah, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="javascript:void(0)"
                                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 rounded-full p-1.5 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
                                                    title="Detail"
                                                    onclick="event.stopPropagation(); showSimpleModal('{{ $trx->id }}', '{{ $trx->no_bukti }}', '{{ \Carbon\Carbon::parse($trx->tanggal)->format('d F Y') }}', '{{ $trx->keterangan }}', '{{ $trx->jenis }}', '{{ $trx->jumlah }}', '{{ \Carbon\Carbon::parse($trx->created_at)->format('d F Y H:i') }}', '{{ \Carbon\Carbon::parse($trx->updated_at)->format('d F Y H:i') }}', '{{ $trx->related_id ?? '' }}', '{{ $trx->related_type ?? '' }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="bg-white dark:bg-gray-800 px-4 py-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6 kas-pagination"
                        x-data="{ paginationLinks: true }">
                        {{ $transaksi->appends(request()->except('page'))->links('vendor.pagination.tailwind-custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

{{-- JavaScript untuk menampilkan date range picker dan handling AJAX loading --}}

<!-- Transaction Detail Modal -->
<div id="transactionDetailModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div id="modal-backdrop"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity backdrop-blur-sm"
            onclick="closeSimpleModal()"></div>

        <!-- Modal positioning trick -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50"
            tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="closeSimpleModal()"
                    class="bg-white dark:bg-gray-800 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                        id="transaction-icon-container">
                        <!-- Icon will be set by JavaScript -->
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                            id="transaction-title">
                            Detail Transaksi
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400" id="transaction-subtitle">
                                <!-- Will be set by JavaScript -->
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700">
                <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="px-4 py-3 grid grid-cols-3 gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            No. Bukti
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white col-span-2" id="detail-reference">
                            <!-- Will be set by JavaScript -->
                        </dd>
                    </div>
                    <div class="px-4 py-3 grid grid-cols-3 gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Tanggal
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white col-span-2" id="detail-date">
                            <!-- Will be set by JavaScript -->
                        </dd>
                    </div>
                    <div class="px-4 py-3 grid grid-cols-3 gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Jenis Transaksi
                        </dt>
                        <dd class="text-sm col-span-2" id="detail-type">
                            <!-- Will be set by JavaScript -->
                        </dd>
                    </div>
                    <div class="px-4 py-3 grid grid-cols-3 gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Jumlah
                        </dt>
                        <dd class="text-sm font-semibold col-span-2" id="detail-amount">
                            <!-- Will be set by JavaScript -->
                        </dd>
                    </div>
                    <div class="px-4 py-3 grid grid-cols-3 gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Keterangan
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white col-span-2" id="detail-description">
                            <!-- Will be set by JavaScript -->
                        </dd>
                    </div>
                    <div class="px-4 py-3 grid grid-cols-3 gap-4 sm:px-6" id="related-document-container">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Dokumen Terkait
                        </dt>
                        <dd class="text-sm text-gray-900 dark:text-white col-span-2" id="detail-related">
                            <!-- Will be set by JavaScript -->
                        </dd>
                    </div>
                    <div class="px-4 py-3 grid grid-cols-3 gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Dibuat Pada
                        </dt>
                        <dd class="text-sm text-gray-500 dark:text-gray-400 col-span-2" id="detail-created">
                            <!-- Will be set by JavaScript -->
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    onclick="closeSimpleModal()">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Direct inclusion of the modal script to ensure it's available immediately after the modal HTML -->
<script src="{{ asset('js/simple-modal.js') }}?v={{ time() }}"></script>

<script>
    // Define handlePaginationClick globally for Alpine.js pagination
    window.handlePaginationClick = function(url) {
        // Get reference to the loadData function
        if (window.loadDataFunction) {
            window.loadDataFunction(url);
        } else {
            // Fallback: Reload the page with the new URL
            window.location.href = url;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Make loadData function available globally for Alpine.js
        window.loadDataFunction = loadData;
        window.loadDataFunction = loadData;

        // Fungsi untuk menampilkan atau menyembunyikan date range picker dengan animasi halus
        function toggleDateRangePicker() {
            if (periodeSelect.value === 'custom') {
                // Tambahkan opacity dan transition untuk efek smooth
                dateRangePicker.style.opacity = '0';
                dateRangePicker.classList.remove('hidden');
                dateRangePicker.classList.add('flex');
                // Trigger reflow
                void dateRangePicker.offsetWidth;
                dateRangePicker.style.transition = 'opacity 0.2s ease-in-out';
                dateRangePicker.style.opacity = '1';
            } else {
                // Jangan langsung sembunyikan, transisi opacity dulu
                if (!dateRangePicker.classList.contains('hidden')) {
                    dateRangePicker.style.transition = 'opacity 0.2s ease-in-out';
                    dateRangePicker.style.opacity = '0';
                    // Sembunyikan setelah animasi selesai
                    setTimeout(function() {
                        dateRangePicker.classList.remove('flex');
                        dateRangePicker.classList.add('hidden');
                    }, 200); // Durasi timeout sama dengan durasi transisi
                }
            }
        }

        // Event listener untuk perubahan pada dropdown
        periodeSelect.addEventListener('change', toggleDateRangePicker);

        // Panggil fungsi saat halaman dimuat untuk menangani nilai yang sudah dipilih
        toggleDateRangePicker();

        // Fungsi untuk menampilkan loading overlay
        function showLoading() {
            tableLoadingOverlay.classList.remove('hidden');
            tableLoadingOverlay.classList.add('flex');
        }

        // Fungsi untuk menyembunyikan loading overlay
        function hideLoading() {
            tableLoadingOverlay.classList.remove('flex');
            tableLoadingOverlay.classList.add('hidden');
        }

        // Fungsi untuk memuat data dengan AJAX
        async function loadData(url) {
            showLoading();

            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout
                const response = await fetch(url, {
                    signal: controller.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html, */*'
                    },
                    credentials: 'same-origin'
                });
                clearTimeout(timeoutId);
                if (!response.ok) {
                    throw new Error(
                        `Network response was not ok: ${response.status} ${response.statusText}`);
                }

                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Ambil konten table dari response
                const newTableContent = doc.getElementById('tableContent');
                if (newTableContent) {
                    // Update the content
                    tableContent.innerHTML = newTableContent.innerHTML;

                    // Alpine.js will automatically initialize on new content
                    // but we need to ensure it's done by dispatching an Alpine event
                    if (window.Alpine) {
                        window.Alpine.initTree(tableContent);
                    }

                    // Update ringkasan transaksi
                    updateTransactionSummary(doc);

                    // Update URL di browser tanpa reload
                    history.pushState({}, '', url);

                    // Check for pagination links
                    setTimeout(() => {
                        attachPaginationListeners();
                    }, 100);
                } else {
                    alert('Error: Unable to update content. Please try refreshing the page.');
                }
            } catch (error) {
                alert('Failed to load data. Please check your connection and try again.');
            } finally {
                hideLoading();
            }
        }

        // Fungsi untuk memperbarui ringkasan transaksi
        function updateTransactionSummary(doc) {
            // Ambil data ringkasan dari respons
            const newTotalMasuk = doc.getElementById('totalMasuk');
            const newTotalKeluar = doc.getElementById('totalKeluar');
            const newPerubahanBersih = doc.getElementById('perubahanBersih');

            // Update ringkasan transaksi jika elemen ditemukan
            if (newTotalMasuk && document.getElementById('totalMasuk')) {
                document.getElementById('totalMasuk').innerHTML = newTotalMasuk.innerHTML;
            }

            if (newTotalKeluar && document.getElementById('totalKeluar')) {
                document.getElementById('totalKeluar').innerHTML = newTotalKeluar.innerHTML;
            }

            if (newPerubahanBersih && document.getElementById('perubahanBersih')) {
                // Update kelas warna juga
                const currentPerubahanBersih = document.getElementById('perubahanBersih');
                currentPerubahanBersih.className = newPerubahanBersih.className;
                currentPerubahanBersih.innerHTML = newPerubahanBersih.innerHTML;
            }
        }

        // Event listener untuk form filter
        filterButton.addEventListener('click', function(event) {
            event.preventDefault();
            applyFilter();
        });

        // Function to apply filter
        function applyFilter() {
            const formData = new FormData(filterForm);
            const searchParams = new URLSearchParams(formData);
            const url = `${filterForm.action}?${searchParams.toString()}`;
            loadData(url);
            showToast("Filter berhasil diterapkan");
        }

        // Function to show toast notification
        function showToast(message) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');

            if (!toast || !toastMessage) return;

            toastMessage.textContent = message;
            toast.classList.remove('hidden', 'translate-x-full');
            toast.classList.add('translate-x-0');

            // Auto-hide after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                // After animation completes, hide it
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 300);
            }, 3000);
        }

        // Event listener untuk tombol reset
        resetButton.addEventListener('click', function() {
            const baseUrl = filterForm.action;
            loadData(baseUrl);
            // Reset form fields
            filterForm.reset();
            // Reset date fields jika ada custom date range
            if (document.getElementById('tanggal_mulai')) {
                document.getElementById('tanggal_mulai').value = '';
            }
            if (document.getElementById('tanggal_akhir')) {
                document.getElementById('tanggal_akhir').value = '';
            }
            // Sembunyikan date range picker
            dateRangePicker.style.opacity = '0';
            setTimeout(function() {
                dateRangePicker.classList.remove('flex');
                dateRangePicker.classList.add('hidden');
            }, 200);

            // Show toast notification
            showToast("Filter telah direset");
        });

        // Event listener untuk tombol cetak
        const printButton = document.getElementById('printButton');
        if (printButton) {
            printButton.addEventListener('click', function() {
                // Buat URL yang sama dengan filter yang sedang diterapkan
                const formData = new FormData(filterForm);
                const searchParams = new URLSearchParams(formData);
                searchParams.append('print', 'true'); // Tambahkan parameter print=true
                const printUrl = `${filterForm.action}?${searchParams.toString()}`;

                // Buka halaman cetak di tab baru
                window.open(printUrl, '_blank');
            });
        }

        // Tambahkan keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            // Check if focus is in form input
            const activeElement = document.activeElement;
            const isInputField = activeElement.tagName === 'INPUT' ||
                activeElement.tagName === 'SELECT' ||
                activeElement.tagName === 'TEXTAREA';

            // Enter key in form triggers filter
            if (event.key === 'Enter' && isInputField && filterForm.contains(activeElement)) {
                event.preventDefault();
                applyFilter();
            }

            // Escape key clears filters
            if (event.key === 'Escape') {
                if (isInputField && filterForm.contains(activeElement)) {
                    // If in a filter field, just blur the field
                    activeElement.blur();
                } else {
                    // Reset filters
                    resetButton.click();
                }
            }
        });

        // Fungsi untuk attach event listeners pada pagination links
        function attachPaginationListeners() {
            // We don't need to attach click events anymore since Alpine.js handles it
            const paginationLinks = document.querySelectorAll('#tableContent .kas-pagination .pagination a');
        }

        // Attach event listeners pada pagination links saat halaman pertama kali dimuat
        attachPaginationListeners();
    });
</script>

@include('keuangan.kas_dan_bank.detail_kas_scripts')

@include('keuangan.kas_dan_bank.detail_kas_head')
