<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
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
                <!-- Rekening Info Section -->
                <div class="p-6 flex-grow">
                    <div class="flex items-start">
                        <div
                            class="h-16 w-16 flex-shrink-0 flex items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30">
                            <svg class="h-10 w-10 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $rekening->nama_bank }}</h2>
                            <div class="mt-1 flex items-center">
                                <span
                                    class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $rekening->nomor_rekening }}</span>
                                <span class="mx-2 text-gray-400">•</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $rekening->atas_nama }}</span>
                                @if ($rekening->cabang)
                                    <span class="mx-2 text-gray-400">•</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Cabang
                                        {{ $rekening->cabang }}</span>
                                @endif
                            </div>
                            <div class="mt-2">
                                @if ($rekening->is_aktif)
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
                                <span
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                    {{ $rekening->is_perusahaan ? 'Rekening Perusahaan' : 'Rekening Pihak Ketiga' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saldo Section -->
                <div
                    class="p-6 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
                    <div class="flex flex-col h-full justify-center">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($rekening->saldo, 0, ',', '.') }}
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
                                Edit Rekening
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
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-4">Ringkasan transaksi rekening berdasarkan
                    filter yang diterapkan</p>

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
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftar lengkap transaksi rekening
                            {{ $rekening->nama_bank }} ({{ $rekening->nomor_rekening }})</p>

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
                    <form id="filterForm" action="{{ route('keuangan.kas-dan-bank.rekening', $rekening->id) }}"
                        method="GET" class="w-full sm:w-auto">
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

            <div id="tableContent">
                @if ($transaksi->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16">
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-full p-4 mb-6">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Belum ada transaksi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-6 max-w-sm text-center">
                            Belum ada transaksi yang tercatat pada rekening ini. Tambahkan transaksi untuk mulai
                            mencatat aliran dana rekening.
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
                                        No. Referensi
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
                                    <tr
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors duration-150 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                                {{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($trx->tanggal)->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-200 font-medium">
                                                {{ $trx->no_referensi }}
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
                                                    <a href="#"
                                                        class="inline-flex items-center text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 hover:underline transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" class="w-4 h-4 mr-1">
                                                            <path fill-rule="evenodd"
                                                                d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ class_basename($trx->related_type) }}
                                                        #{{ $trx->related_id }}
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($trx->jenis == 'masuk')
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
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
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
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
                                                <a href="#"
                                                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 rounded-full p-1.5 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
                                                    title="Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </a>
                                                <a href="#"
                                                    class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 rounded-full p-1.5 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors"
                                                    title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                                    </svg>
                                                </a>
                                                <a href="#"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 rounded-full p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                                    title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div
                        class="bg-white dark:bg-gray-800 px-4 py-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 sm:px-6">
                        {{ $transaksi->appends(request()->except('page'))->links('vendor.pagination.tailwind-custom') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

{{-- JavaScript untuk menampilkan date range picker dan handling AJAX loading --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing filters...');

        const periodeSelect = document.getElementById('periodeSelect');
        const dateRangePicker = document.getElementById('dateRangePicker');
        const filterForm = document.getElementById('filterForm');
        const filterButton = document.getElementById('filterButton');
        const resetButton = document.getElementById('resetButton');
        const tableLoadingOverlay = document.getElementById('tableLoadingOverlay');
        const tableContent = document.getElementById('tableContent');

        // Validation check for required elements
        if (!filterButton) {
            console.error('Filter button not found!');
            return;
        }

        if (!filterForm) {
            console.error('Filter form not found!');
            return;
        }

        if (!tableContent) {
            console.error('Table content container not found!');
            return;
        }

        console.log('All required elements found, continuing initialization...');

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
            console.log('Loading data from:', url);
            showLoading();

            try {
                console.log('Fetching data...');
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout
                const response = await fetch(url, {
                    signal: controller.signal
                });
                clearTimeout(timeoutId);
                if (!response.ok) {
                    throw new Error(
                        `Network response was not ok: ${response.status} ${response.statusText}`);
                }

                const html = await response.text();
                console.log('Response received, parsing HTML...');
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Ambil konten table dari response
                const newTableContent = doc.getElementById('tableContent');
                if (newTableContent) {
                    console.log('Table content found, updating...');
                    tableContent.innerHTML = newTableContent.innerHTML;

                    // Update ringkasan transaksi
                    console.log('Updating transaction summary...');
                    updateTransactionSummary(doc);

                    // Update URL di browser tanpa reload
                    history.pushState({}, '', url);

                    // Re-attach event listeners for pagination jika diperlukan
                    attachPaginationListeners();
                    console.log('Data update complete');
                } else {
                    console.error('Could not find tableContent element in response');
                    alert('Error: Unable to update content. Please try refreshing the page.');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
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
            console.log('Filter button clicked');
            event.preventDefault();
            applyFilter();
        });

        // Function to apply filter
        function applyFilter() {
            const formData = new FormData(filterForm);
            const searchParams = new URLSearchParams(formData);
            const url = `${filterForm.action}?${searchParams.toString()}`;
            console.log('Filter URL:', url);
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
            console.log('Reset button clicked');
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
            console.log('Print button found, adding event listener');
            printButton.addEventListener('click', function() {
                console.log('Print button clicked');
                // Buat URL yang sama dengan filter yang sedang diterapkan
                const formData = new FormData(filterForm);
                const searchParams = new URLSearchParams(formData);
                searchParams.append('print', 'true'); // Tambahkan parameter print=true
                const printUrl = `${filterForm.action}?${searchParams.toString()}`;
                console.log('Print URL:', printUrl);

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
                console.log('Enter key pressed in filter form');
                applyFilter();
            }

            // Escape key clears filters
            if (event.key === 'Escape') {
                console.log('Escape key pressed');
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
            const paginationLinks = document.querySelectorAll('.pagination a');
            console.log(`Found ${paginationLinks.length} pagination links`);

            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Pagination link clicked:', this.href);
                    loadData(this.href);
                });
            });
        }

        // Attach event listeners pada pagination links saat halaman pertama kali dimuat
        console.log('Attaching initial pagination listeners...');
        attachPaginationListeners();
    });
</script>

@include('keuangan.kas_dan_bank.detail_rekening_scripts')
