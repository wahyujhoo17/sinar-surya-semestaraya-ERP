<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="max-w-full mx-auto py-4 sm:py-6 lg:py-8 px-4 sm:px-6 lg:px-8">
        <div x-data="reconciliationData()" x-init="init()"
            @unmatched-updated.window="() => { $nextTick(() => { console.log('Refreshing UI based on unmatched updates'); }) }">
            {{-- Responsive Header --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <div class="flex items-center mb-2 sm:mb-0">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-8 sm:h-10 w-1 rounded-full mr-3">
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1
                                class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white truncate">
                                Rekonsiliasi Bank
                            </h1>
                        </div>
                    </div>
                    <div class="ml-0 sm:ml-4 mt-1 sm:mt-0">
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                            Kelola rekonsiliasi antara saldo sistem internal dengan laporan bank resmi
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tutorial and Explanation Section --}}
            <div x-show="!selectedAccount" class="mb-8">
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">
                                Panduan Rekonsiliasi Bank
                            </h3>
                            <div class="text-sm text-blue-800 dark:text-blue-200 space-y-3">
                                <p class="font-medium">Rekonsiliasi Bank adalah proses mencocokkan catatan transaksi
                                    perusahaan dengan laporan bank resmi.</p>

                                <div class="grid md:grid-cols-2 gap-4 mt-4">
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-blue-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                            <span
                                                class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-2">1</span>
                                            Pilih Rekening & Periode
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Pilih rekening bank yang akan direkonsiliasi dan tentukan periode
                                            (bulan/tahun) yang ingin dicek.
                                        </p>
                                    </div>

                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-blue-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                            <span
                                                class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-2">2</span>
                                            Upload Bank Statement
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Upload file bank statement resmi (PDF/Excel) yang diperoleh dari bank untuk
                                            periode tersebut.
                                        </p>
                                    </div>

                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-blue-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                            <span
                                                class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-2">3</span>
                                            Auto Match Transaksi
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Sistem akan otomatis mencocokkan transaksi berdasarkan jumlah, tanggal, dan
                                            jenis transaksi.
                                        </p>
                                    </div>

                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-blue-200 dark:border-gray-600">
                                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                            <span
                                                class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-2">4</span>
                                            Analisis Selisih
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Review transaksi yang belum cocok dan identifikasi penyebab selisih (biaya
                                            admin, bunga, dll).
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-amber-500 mt-0.5 mr-2 flex-shrink-0"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12V15.75z" />
                                        </svg>
                                        <div>
                                            <h5 class="font-medium text-amber-800 dark:text-amber-200">Tujuan
                                                Rekonsiliasi:</h5>
                                            <ul class="text-sm text-amber-700 dark:text-amber-300 mt-1 space-y-1">
                                                <li>• Memastikan akurasi catatan keuangan perusahaan</li>
                                                <li>• Mengidentifikasi transaksi yang belum tercatat</li>
                                                <li>• Mendeteksi kesalahan atau fraud</li>
                                                <li>• Menyesuaikan saldo kas/bank di sistem</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Common Discrepancy Causes Section --}}
                                <div
                                    class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-red-500 mt-0.5 mr-2 flex-shrink-0"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m-9 4.5h6m-6 0l3-3m-3 3l3 3m3-6 3-3m0 0l3 3m-3-3v12" />
                                        </svg>
                                        <div class="flex-1">
                                            <h5 class="font-medium text-red-800 dark:text-red-200 mb-2">Penyebab Umum
                                                Selisih Rekonsiliasi:</h5>
                                            <div class="grid md:grid-cols-2 gap-3 text-sm">
                                                <div class="space-y-2">
                                                    <div class="flex items-start">
                                                        <span
                                                            class="inline-block w-2 h-2 bg-red-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                        <div>
                                                            <span
                                                                class="font-medium text-red-700 dark:text-red-300">Biaya
                                                                Administrasi Bank</span>
                                                            <p class="text-red-600 dark:text-red-400 text-xs">Biaya
                                                                bulanan, transfer, dll yang otomatis dipotong bank</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start">
                                                        <span
                                                            class="inline-block w-2 h-2 bg-red-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                        <div>
                                                            <span
                                                                class="font-medium text-red-700 dark:text-red-300">Bunga
                                                                Bank</span>
                                                            <p class="text-red-600 dark:text-red-400 text-xs">Bunga
                                                                tabungan atau bunga pinjaman yang belum dicatat</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start">
                                                        <span
                                                            class="inline-block w-2 h-2 bg-red-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                        <div>
                                                            <span
                                                                class="font-medium text-red-700 dark:text-red-300">Transaksi
                                                                Tertunda</span>
                                                            <p class="text-red-600 dark:text-red-400 text-xs">Cek yang
                                                                belum dicairkan atau transfer yang tertunda</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <div class="flex items-start">
                                                        <span
                                                            class="inline-block w-2 h-2 bg-red-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                        <div>
                                                            <span
                                                                class="font-medium text-red-700 dark:text-red-300">Deposit
                                                                dalam Perjalanan</span>
                                                            <p class="text-red-600 dark:text-red-400 text-xs">Setoran
                                                                yang sudah dicatat tapi belum masuk bank</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start">
                                                        <span
                                                            class="inline-block w-2 h-2 bg-red-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                        <div>
                                                            <span
                                                                class="font-medium text-red-700 dark:text-red-300">Kesalahan
                                                                Input</span>
                                                            <p class="text-red-600 dark:text-red-400 text-xs">Human
                                                                error dalam pencatatan jumlah atau tanggal</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-start">
                                                        <span
                                                            class="inline-block w-2 h-2 bg-red-400 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                                        <div>
                                                            <span
                                                                class="font-medium text-red-700 dark:text-red-300">Transaksi
                                                                Otomatis</span>
                                                            <p class="text-red-600 dark:text-red-400 text-xs">Auto debit
                                                                listrik, telepon, dll yang belum dijurnal</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Troubleshooting Guide --}}
                                <div
                                    class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-green-500 mt-0.5 mr-2 flex-shrink-0"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                                        </svg>
                                        <div class="flex-1">
                                            <h5 class="font-medium text-green-800 dark:text-green-200 mb-3">Panduan
                                                Mengatasi Selisih:</h5>

                                            <div class="space-y-3 text-sm">
                                                <div
                                                    class="bg-white dark:bg-gray-800 p-3 rounded border border-green-200 dark:border-gray-600">
                                                    <h6 class="font-medium text-green-800 dark:text-green-200 mb-2">1.
                                                        Jika Saldo Bank LEBIH BESAR dari ERP:</h6>
                                                    <ul
                                                        class="text-green-700 dark:text-green-300 space-y-1 text-xs pl-4">
                                                        <li>• Cek apakah ada bunga bank yang belum dijurnal</li>
                                                        <li>• Periksa deposit yang sudah masuk bank tapi belum dicatat
                                                            di ERP</li>
                                                        <li>• Verifikasi transaksi penerimaan yang mungkin terlewat</li>
                                                        <li>• Buat jurnal penyesuaian untuk transaksi yang sah</li>
                                                    </ul>
                                                </div>

                                                <div
                                                    class="bg-white dark:bg-gray-800 p-3 rounded border border-green-200 dark:border-gray-600">
                                                    <h6 class="font-medium text-green-800 dark:text-green-200 mb-2">2.
                                                        Jika Saldo Bank LEBIH KECIL dari ERP:</h6>
                                                    <ul
                                                        class="text-green-700 dark:text-green-300 space-y-1 text-xs pl-4">
                                                        <li>• Cek biaya administrasi bank yang belum dicatat</li>
                                                        <li>• Periksa cek yang belum dicairkan (outstanding checks)</li>
                                                        <li>• Verifikasi auto debit yang mungkin terlewat</li>
                                                        <li>• Pastikan tidak ada double entry di ERP</li>
                                                    </ul>
                                                </div>

                                                <div
                                                    class="bg-white dark:bg-gray-800 p-3 rounded border border-green-200 dark:border-gray-600">
                                                    <h6 class="font-medium text-green-800 dark:text-green-200 mb-2">3.
                                                        Langkah Penyelesaian:</h6>
                                                    <ol
                                                        class="text-green-700 dark:text-green-300 space-y-1 text-xs pl-4">
                                                        <li>1. Dokumentasikan semua selisih yang ditemukan</li>
                                                        <li>2. Buat jurnal penyesuaian untuk transaksi yang valid</li>
                                                        <li>3. Update saldo bank di sistem ERP</li>
                                                        <li>4. Simpan dokumen pendukung dan penjelasan</li>
                                                        <li>5. Lakukan review dan approval sesuai prosedur</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                {{-- Saldo ERP Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-2">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-500 dark:text-blue-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Saldo ERP
                                    </dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white"
                                        x-text="formatCurrency(erpBalance)"></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Saldo Bank Statement Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-green-100 dark:bg-green-900/30 p-2">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-green-500 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">
                                        <span x-show="!uploadedFile">Saldo Bank</span>
                                        <span x-show="uploadedFile">Saldo Bank Statement</span>
                                    </dt>
                                    <dd class="text-lg font-semibold text-gray-900 dark:text-white"
                                        x-text="formatCurrency(uploadedFile ? realBankBalance : bankBalance)"></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Selisih Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg border border-gray-200 dark:border-gray-700 sm:col-span-2 lg:col-span-1">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg p-2"
                                :class="difference === 0 ? 'bg-emerald-100 dark:bg-emerald-900/30' :
                                    'bg-red-100 dark:bg-red-900/30'">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6"
                                    :class="difference === 0 ? 'text-emerald-500 dark:text-emerald-400' :
                                        'text-red-500 dark:text-red-400'"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        :d="difference === 0 ?
                                            'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' :
                                            'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Selisih
                                    </dt>
                                    <dd class="text-lg font-semibold"
                                        :class="difference === 0 ? 'text-emerald-600 dark:text-emerald-400' :
                                            'text-red-600 dark:text-red-400'"
                                        x-text="formatCurrency(Math.abs(difference))"></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reconciliation Status Dashboard --}}
            <div x-show="selectedAccount" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3-9v9a2.25 2.25 0 01-2.25 2.25H6.375A2.25 2.25 0 014.125 16.5V7.875a2.25 2.25 0 012.25-2.25H10.5L12.75 7.5v4.875c0 .621.504 1.125 1.125 1.125h3.375c.621 0 1.125-.504 1.125-1.125V7.875a2.25 2.25 0 00-2.25-2.25H10.5m0 0L8.25 4.5" />
                            </svg>
                            Status Rekonsiliasi
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Step 1: Account Selection -->
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                        :class="selectedAccount ? 'bg-green-100 dark:bg-green-900/30' :
                                            'bg-gray-100 dark:bg-gray-700'">
                                        <svg class="w-4 h-4"
                                            :class="selectedAccount ? 'text-green-600 dark:text-green-400' : 'text-gray-400'"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                x-show="selectedAccount" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"
                                                x-show="!selectedAccount" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium"
                                        :class="selectedAccount ? 'text-green-600 dark:text-green-400' :
                                            'text-gray-500 dark:text-gray-400'">
                                        Rekening Dipilih
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-show="selectedAccount"
                                        x-text="currentAccountData ? currentAccountData.nama_bank : ''"></p>
                                </div>
                            </div>

                            <!-- Step 2: Period Selection -->
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                        :class="selectedPeriod ? 'bg-green-100 dark:bg-green-900/30' :
                                            'bg-gray-100 dark:bg-gray-700'">
                                        <svg class="w-4 h-4"
                                            :class="selectedPeriod ? 'text-green-600 dark:text-green-400' : 'text-gray-400'"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                x-show="selectedPeriod" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5"
                                                x-show="!selectedPeriod" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium"
                                        :class="selectedPeriod ? 'text-green-600 dark:text-green-400' :
                                            'text-gray-500 dark:text-gray-400'">
                                        Periode Dipilih
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-show="selectedPeriod"
                                        x-text="selectedPeriod"></p>
                                </div>
                            </div>

                            <!-- Step 3: Bank Statement Upload -->
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                        :class="uploadedFile ? 'bg-green-100 dark:bg-green-900/30' :
                                            'bg-gray-100 dark:bg-gray-700'">
                                        <svg class="w-4 h-4"
                                            :class="uploadedFile ? 'text-green-600 dark:text-green-400' : 'text-gray-400'"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                x-show="uploadedFile" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"
                                                x-show="!uploadedFile" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium"
                                        :class="uploadedFile ? 'text-green-600 dark:text-green-400' :
                                            'text-gray-500 dark:text-gray-400'">
                                        File Diupload
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-show="uploadedFile"
                                        x-text="uploadedFile ? uploadedFile.name : ''"></p>
                                </div>
                            </div>

                            <!-- Step 4: Reconciliation Status -->
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                        :class="difference === 0 ? 'bg-green-100 dark:bg-green-900/30' : (erpTransactions
                                            .length > 0 ? 'bg-yellow-100 dark:bg-yellow-900/30' :
                                            'bg-gray-100 dark:bg-gray-700')">
                                        <svg class="w-4 h-4"
                                            :class="difference === 0 ? 'text-green-600 dark:text-green-400' : (erpTransactions
                                                .length > 0 ? 'text-yellow-600 dark:text-yellow-400' :
                                                'text-gray-400')"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                x-show="difference === 0" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12V15.75z"
                                                x-show="difference !== 0 && erpTransactions.length > 0" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                x-show="erpTransactions.length === 0" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium"
                                        :class="difference === 0 ? 'text-green-600 dark:text-green-400' : (erpTransactions
                                            .length > 0 ? 'text-yellow-600 dark:text-yellow-400' :
                                            'text-gray-500 dark:text-gray-400')">
                                        <span x-show="difference === 0">Seimbang</span>
                                        <span x-show="difference !== 0 && erpTransactions.length > 0">Perlu
                                            Review</span>
                                        <span x-show="erpTransactions.length === 0">Belum Dimulai</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"
                                        x-show="erpTransactions.length > 0"
                                        x-text="`Selisih: ${formatCurrency(Math.abs(difference))}`"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-6">
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <span>Progress Rekonsiliasi</span>
                                <span
                                    x-text="`${Math.round(((selectedAccount ? 25 : 0) + (selectedPeriod ? 25 : 0) + (uploadedFile ? 25 : 0) + (difference === 0 && erpTransactions.length > 0 ? 25 : 0)))}%`"></span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-500 ease-out"
                                    :style="`width: ${Math.round(((selectedAccount ? 25 : 0) + (selectedPeriod ? 25 : 0) + (uploadedFile ? 25 : 0) + (difference === 0 && erpTransactions.length > 0 ? 25 : 0)))}%`">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Configuration Panel --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-8 border border-gray-200 dark:border-gray-700">
                <div
                    class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                        <div class="flex-1 min-w-0">
                            <h3
                                class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-primary-600 dark:text-primary-400 mr-2 flex-shrink-0"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="truncate">Pengaturan Rekonsiliasi</span>
                            </h3>
                            <p class="mt-1 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                Pilih rekening bank dan periode, lalu upload bank statement untuk memulai proses
                                rekonsiliasi
                            </p>
                        </div>
                        <div class="flex-shrink-0 sm:ml-4">
                            <div class="flex items-center space-x-2" x-show="selectedAccount">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 hidden sm:inline">Siap untuk
                                    rekonsiliasi</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="space-y-6 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-6">
                        {{-- Account Selection --}}
                        <div class="space-y-2">
                            <label for="account" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                    </svg>
                                    Pilih Rekening Bank
                                </span>
                            </label>
                            <select x-model="selectedAccount" @change="loadAccountData()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5 px-3 transition-colors duration-200">
                                <option value="">-- Pilih Rekening Bank --</option>
                                @foreach ($rekeningBanks as $rekening)
                                    <option value="{{ $rekening['id'] }}">{{ $rekening['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Period Selection --}}
                        <div class="space-y-2">
                            <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                    </svg>
                                    Periode Rekonsiliasi
                                </span>
                            </label>
                            <input type="month" x-model="selectedPeriod" @change="loadPeriodData()"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5 px-3 transition-colors duration-200">
                        </div>

                        {{-- Bank Statement Upload --}}
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    Upload Bank Statement
                                </span>
                            </label>
                            <div class="space-y-2">
                                {{-- File input wrapper dengan margin yang konsisten --}}
                                <div class="w-full">
                                    <input type="file" accept=".pdf,.xlsx,.csv" @change="handleFileUpload($event)"
                                        class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-300 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer transition-colors duration-200">
                                </div>
                                {{-- Button wrapper dengan proper spacing --}}
                                <div class="w-full">
                                    <button type="button" @click="processStatement()" :disabled="!uploadedFile"
                                        class="w-full sm:w-auto px-4 py-2.5 bg-primary-600 disabled:bg-gray-400 text-white text-sm font-medium rounded-md hover:bg-primary-700 disabled:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.611L5 14.5" />
                                        </svg>
                                        <span class="text-center">
                                            <span class="hidden sm:inline" x-show="!isLoading">Proses Bank
                                                Statement</span>
                                            <span class="sm:hidden" x-show="!isLoading">Proses File</span>
                                            <span x-show="isLoading">Memproses...</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-start">
                                <svg class="w-3 h-3 mr-1 mt-0.5 text-gray-400 flex-shrink-0"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l-.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <span>Mendukung: PDF, Excel (.xlsx), CSV (Maks. 10MB)</span>
                            </p>

                            {{-- Manual Input Alternative --}}
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <button type="button" @click="showManualInput()"
                                        class="flex-1 sm:flex-initial px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                        </svg>
                                        Input Manual
                                    </button>
                                    <button type="button" @click="showBulkManualInput()"
                                        class="flex-1 sm:flex-initial px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors duration-200 flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 0A2.25 2.25 0 015.625 3.375h13.5A2.25 2.25 0 0121.375 5.625m0 0v12.75m0 0a2.25 2.25 0 01-2.25 2.25M3.375 19.5a1.125 1.125 0 002.25 0m0 0v-3.375a1.125 1.125 0 011.125-1.125h3.375m0 0a2.25 2.25 0 012.25-2.25M10.5 6.75h.008v.008H10.5V6.75z" />
                                        </svg>
                                        Input Bulk
                                    </button>
                                    <button type="button" @click="removeAllManualTransactions()"
                                        x-show="bankTransactions.filter(t => t.isManual).length > 0"
                                        class="flex-1 sm:flex-initial px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md transition-colors duration-200 flex items-center justify-center"
                                        title="Hapus semua transaksi manual">
                                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Hapus Semua Manual
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    💡 Gunakan input manual jika file bank statement tidak dapat diproses otomatis<br>
                                    🗑️ Tombol "Hapus Semua Manual" akan muncul jika ada transaksi input manual
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Controls --}}
            <div x-show="selectedAccount" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-8">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        {{-- Status Information --}}
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <div>
                                <div class="flex items-center mb-1">
                                    <div class="w-2 h-2 rounded-full mr-2"
                                        :class="difference === 0 ? 'bg-green-500' : 'bg-red-500'"></div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status
                                        Rekonsiliasi</span>
                                </div>
                                <div class="text-lg font-medium"
                                    :class="difference === 0 ? 'text-green-600 dark:text-green-400' :
                                        'text-red-600 dark:text-red-400'"
                                    x-text="difference === 0 ? 'Seimbang' : 'Tidak Seimbang'"></div>
                            </div>

                            <div class="hidden lg:block w-px h-12 bg-gray-200 dark:bg-gray-700"></div>

                            <div>
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Transaksi Cocok
                                </div>
                                <div class="text-lg font-medium text-blue-600 dark:text-blue-400">
                                    <span
                                        x-text="(erpTransactions.filter(t => t.matched).length + bankTransactions.filter(t => t.matched).length)"></span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">/ <span
                                            x-text="(erpTransactions.length + bankTransactions.length)"></span></span>
                                </div>
                            </div>

                            <div class="hidden lg:block w-px h-12 bg-gray-200 dark:bg-gray-700"></div>

                            <div>
                                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Selisih Saldo
                                </div>
                                <div class="text-lg font-medium"
                                    :class="difference === 0 ? 'text-green-600 dark:text-green-400' :
                                        'text-red-600 dark:text-red-400'"
                                    x-text="formatCurrency(Math.abs(difference))"></div>
                            </div>
                        </div>

                        {{-- Action Buttons - Enhanced Responsive Layout --}}
                        <div class="flex flex-col sm:flex-row lg:flex-row xl:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                            {{-- Auto Match Button --}}
                            <button type="button" @click="autoMatch()"
                                :disabled="erpTransactions.length === 0 || bankTransactions.length === 0"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-2.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all duration-200 min-w-0">
                                <svg class="mr-1.5 -ml-0.5 h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                                </svg>
                                <span class="hidden lg:inline xl:inline">Auto Match Transaksi</span>
                                <span class="lg:hidden xl:hidden">Auto Match</span>
                            </button>

                            {{-- Export Button --}}
                            <button type="button" @click="exportReconciliation()"
                                :disabled="erpTransactions.length === 0"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-2.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-400 dark:disabled:text-gray-600 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-all duration-200 min-w-0">
                                <svg class="mr-1.5 -ml-0.5 h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                <span class="hidden lg:inline xl:inline">Export Excel</span>
                                <span class="lg:hidden xl:hidden">Export</span>
                            </button>

                            {{-- Save Button --}}
                            <button type="button" @click="saveReconciliation()"
                                :disabled="!selectedAccount || !selectedPeriod || erpTransactions.length === 0"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 dark:disabled:bg-gray-600 disabled:cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all duration-200 min-w-0">
                                <svg class="mr-1.5 -ml-0.5 h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span class="hidden lg:inline xl:inline">Simpan Rekonsiliasi</span>
                                <span class="lg:hidden xl:hidden">Simpan</span>
                            </button>

                            {{-- History Button --}}
                            <button type="button" @click="showReconciliationHistory()"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-2.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-all duration-200 min-w-0">
                                <svg class="mr-1.5 -ml-0.5 h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="hidden lg:inline xl:inline">Riwayat</span>
                                <span class="lg:hidden xl:hidden">History</span>
                            </button>

                            {{-- Debug Button (only in development) --}}
                            @if (config('app.debug'))
                                <button type="button" @click="debugUnmatchedState()"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 py-2.5 border border-yellow-300 dark:border-yellow-600 text-sm font-medium rounded-lg text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:focus:ring-offset-gray-800 transition-all duration-200 min-w-0">
                                    <svg class="mr-1.5 -ml-0.5 h-4 w-4 flex-shrink-0"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                    <span class="hidden lg:inline xl:inline">Debug</span>
                                    <span class="lg:hidden xl:hidden">Debug</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Simple placeholder for transaction panels and history --}}
            <div x-show="selectedAccount && erpTransactions.length > 0"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0" class="mb-8">

                {{-- Unmatched Transactions Summary --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- ERP Transactions Not Matched --}}
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                </svg>
                                Transaksi ERP Belum Cocok
                                <span
                                    class="ml-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs px-2 py-1 rounded-full"
                                    x-text="unmatchedErp.length"></span>
                            </h3>
                        </div>
                        <div class="p-4">
                            <div x-show="unmatchedErp.length === 0"
                                class="text-center py-4 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>Semua transaksi ERP sudah cocok</p>
                            </div>
                            <div class="space-y-2" x-show="unmatchedErp.length > 0">
                                <template x-for="transaction in unmatchedErp.slice(0, 5)" :key="transaction.id">
                                    <div
                                        class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate"
                                                x-text="transaction.description"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400"
                                                x-text="transaction.date + ' • ' + transaction.reference"></p>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <span class="text-sm font-medium"
                                                :class="transaction.amount > 0 ? 'text-green-600 dark:text-green-400' :
                                                    'text-red-600 dark:text-red-400'"
                                                x-text="formatCurrency(transaction.amount)"></span>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="unmatchedErp.length > 5" class="text-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400"
                                        x-text="`dan ${unmatchedErp.length - 5} transaksi lainnya`"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bank Transactions Not Matched --}}
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Transaksi Bank Belum Cocok
                                <span
                                    class="ml-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs px-2 py-1 rounded-full"
                                    x-text="unmatchedBank.length"></span>

                            </h3>
                        </div>
                        <div class="p-4">


                            <div x-show="unmatchedBank.length === 0"
                                class="text-center py-4 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p>Semua transaksi bank sudah cocok</p>
                            </div>
                            <div class="space-y-2" x-show="unmatchedBank.length > 0"
                                :key="'unmatched-bank-' + lastUpdate + '-' + unmatchedBank.length">



                                <!-- Try the simplest possible loop -->
                                <template x-for="(item, idx) in unmatchedBank" :key="'simple-' + idx">
                                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded mb-1">
                                        <div class="text-sm font-medium">
                                            Transaction #<span x-text="idx + 1"></span>
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            ID: <span x-text="item?.id || 'MISSING'"></span> |
                                            Desc: <span x-text="item?.description || 'MISSING'"></span> |
                                            Amount: <span x-text="item?.amount || 0"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions for Reconciliation --}}
                <div x-show="(unmatchedErp.length > 0 || unmatchedBank.length > 0)"
                    class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-amber-800 dark:text-amber-200">Rekonsiliasi Belum
                                Selesai</h4>
                            <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                                <p class="mb-2">Masih ada <span class="font-semibold"
                                        x-text="(unmatchedErp.length + unmatchedBank.length)"></span> transaksi yang
                                    belum dicocokkan.</p>

                                <!-- Common reasons for unmatched transactions -->
                                <div
                                    class="mt-3 p-3 bg-white dark:bg-gray-800 rounded border border-amber-200 dark:border-amber-600">
                                    <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">💡 Kemungkinan
                                        Penyebab Transaksi Tidak Cocok:</h5>
                                    <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                        <li>• <strong>Biaya Admin Bank:</strong> Biaya bulanan, transfer, atau ATM yang
                                            belum dicatat di ERP</li>
                                        <li>• <strong>Bunga Bank:</strong> Bunga tabungan atau deposito yang diterima
                                            dari bank</li>
                                        <li>• <strong>Transaksi Pending:</strong> Transaksi yang sudah dicatat di ERP
                                            tapi belum diproses bank</li>
                                        <li>• <strong>Perbedaan Waktu:</strong> Transaksi dicatat di tanggal berbeda
                                            antara ERP dan bank</li>
                                        <li>• <strong>Kesalahan Input:</strong> Salah nominal atau salah akun saat input
                                            di ERP</li>
                                        <li>• <strong>Transaksi Langsung:</strong> Transaksi yang dilakukan langsung di
                                            bank tanpa melalui ERP</li>
                                    </ul>
                                </div>

                                <!-- Actions to resolve -->
                                <div class="mt-3">
                                    <p class="text-xs text-amber-600 dark:text-amber-400 mb-2">Langkah Penyelesaian:
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        <button @click="autoMatch()" type="button"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-amber-800 dark:text-amber-200 bg-amber-100 dark:bg-amber-900/40 hover:bg-amber-200 dark:hover:bg-amber-900/60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-200">
                                            <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                            </svg>
                                            Auto Match Ulang
                                        </button>
                                        <button @click="showUnmatchedDetails = !showUnmatchedDetails" type="button"
                                            class="inline-flex items-center px-3 py-1.5 border border-amber-300 dark:border-amber-600 text-xs font-medium rounded-md text-amber-700 dark:text-amber-300 bg-white dark:bg-gray-800 hover:bg-amber-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-200">
                                            <svg class="mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span
                                                x-text="showUnmatchedDetails ? 'Sembunyikan Detail' : 'Lihat Detail'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed unmatched transactions (expandable) -->
                <div x-show="showUnmatchedDetails" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-2"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="mt-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Detail Transaksi Belum Cocok</h4>
                    </div>
                    <div class="p-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Unmatched ERP Transactions -->
                            <div x-show="unmatchedErp.length > 0">
                                <h5 class="text-sm font-medium text-red-700 dark:text-red-400 mb-3">
                                    ERP Transactions (Belum Ada di Bank)
                                </h5>
                                <div class="space-y-2">
                                    <template x-for="transaction in unmatchedErp" :key="transaction.id">
                                        <div
                                            class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white"
                                                        x-text="transaction.description"></p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400"
                                                        x-text="`${transaction.date} • Ref: ${transaction.reference}`">
                                                    </p>
                                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                        💡 Kemungkinan: Transaksi pending atau belum diproses bank
                                                    </p>
                                                </div>
                                                <div class="ml-3 text-right">
                                                    <span class="text-sm font-medium"
                                                        :class="transaction.amount > 0 ? 'text-green-600 dark:text-green-400' :
                                                            'text-red-600 dark:text-red-400'"
                                                        x-text="formatCurrency(transaction.amount)"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Unmatched Bank Transactions -->
                            <div x-show="unmatchedBank.length > 0">
                                <h5 class="text-sm font-medium text-orange-700 dark:text-orange-400 mb-3">
                                    Bank Transactions (Belum Ada di ERP)
                                </h5>
                                <div class="space-y-2">
                                    <template x-for="transaction in unmatchedBank" :key="transaction.id">
                                        <div class="p-3 rounded-lg relative"
                                            :class="transaction.isManual ?
                                                'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700' :
                                                'bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800'">
                                            <!-- Manual Input Indicator -->
                                            <div x-show="transaction.isManual" class="absolute top-2 right-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300"
                                                    title="Transaksi Input Manual">
                                                    <svg class="w-3 h-3 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                    Manual
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1" :class="transaction.isManual ? 'pr-16' : ''">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white"
                                                        x-text="transaction.description"></p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400"
                                                        x-text="`${transaction.date} • Ref: ${transaction.reference}`">
                                                    </p>
                                                    <!-- Different messages for manual vs automatic transactions -->
                                                    <p x-show="transaction.isManual"
                                                        class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                                        💻 Input manual • <span
                                                            x-text="transaction.type === 'credit' ? 'Kredit (Masuk)' : 'Debit (Keluar)'"></span>
                                                    </p>
                                                    <p x-show="!transaction.isManual"
                                                        class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                                                        💡 Kemungkinan: Biaya bank atau transaksi langsung di bank
                                                    </p>
                                                </div>
                                                <div class="ml-3 flex items-center space-x-2">
                                                    <span class="text-sm font-medium"
                                                        :class="transaction.amount > 0 ? 'text-green-600 dark:text-green-400' :
                                                            'text-red-600 dark:text-red-400'"
                                                        x-text="formatCurrency(transaction.amount)"></span>
                                                    <!-- Remove button for manual transactions -->
                                                    <button x-show="transaction.isManual"
                                                        @click.stop="removeManualTransaction(transaction.id)"
                                                        class="p-1.5 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-full transition-colors"
                                                        title="Hapus transaksi manual">
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loading State --}}
            <div x-show="isLoading" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl">
                    <div class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-lg font-medium text-gray-900 dark:text-white">Memproses data...</span>
                    </div>
                </div>
            </div>

            {{-- Placeholder when no account selected --}}
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <p>Pilih rekening bank dan periode untuk melihat data transaksi</p>
            </div>

            {{-- Manual Transaction Input Modal --}}
            <div x-show="showManualInputModal" class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div x-show="showManualInputModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        @click="showManualInputModal = false"></div>

                    <!-- Modal panel -->
                    <div x-show="showManualInputModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                        id="modal-title">
                                        Input Transaksi Bank Manual
                                    </h3>
                                    <div class="mt-4 space-y-4">
                                        <!-- Transaction Date -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Tanggal Transaksi *
                                            </label>
                                            <input type="date" x-model="manualTransaction.date"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                        </div>

                                        <!-- Reference Number -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                No. Referensi
                                            </label>
                                            <input type="text" x-model="manualTransaction.reference"
                                                placeholder="Masukkan nomor referensi (opsional)"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Deskripsi Transaksi *
                                            </label>
                                            <input type="text" x-model="manualTransaction.description"
                                                placeholder="Deskripsi transaksi"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                        </div>

                                        <!-- Transaction Type -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Jenis Transaksi *
                                            </label>
                                            <select x-model="manualTransaction.type"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                                <option value="credit">Kredit (Uang Masuk)</option>
                                                <option value="debit">Debit (Uang Keluar)</option>
                                            </select>
                                        </div>

                                        <!-- Amount -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Jumlah *
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" x-model="manualTransaction.amount"
                                                    step="0.01" min="0" placeholder="0.00"
                                                    class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                            </div>
                                        </div>

                                        <!-- Bank Balance (Optional) -->
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Saldo Bank Akhir (Opsional)
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" x-model="manualBankBalance" step="0.01"
                                                    min="0" placeholder="0.00"
                                                    class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                Jika diisi, akan memperbarui saldo real bank statement
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="addManualTransaction()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Tambah Transaksi
                            </button>
                            <button type="button" @click="showManualInputModal = false; resetManualForm()"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bulk Manual Input Modal --}}
            <div x-show="showBulkManualInputModal" class="fixed inset-0 z-50 overflow-y-auto"
                aria-labelledby="bulk-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div x-show="showBulkManualInputModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        @click="showBulkManualInputModal = false"></div>

                    <!-- Modal panel -->
                    <div x-show="showBulkManualInputModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">

                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 0A2.25 2.25 0 015.625 3.375h13.5A2.25 2.25 0 0121.375 5.625m0 0v12.75m0 0a2.25 2.25 0 01-2.25 2.25M3.375 19.5a1.125 1.125 0 002.25 0m0 0v-3.375a1.125 1.125 0 011.125-1.125h3.375m0 0a2.25 2.25 0 012.25-2.25M10.5 6.75h.008v.008H10.5V6.75z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                        id="bulk-modal-title">
                                        Input Transaksi Bank Massal
                                    </h3>
                                    <div class="mt-4">
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Data Transaksi
                                            </label>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                Format: Tanggal|Referensi|Deskripsi|Jenis|Jumlah (satu baris per
                                                transaksi)
                                            </p>
                                            <div
                                                class="bg-gray-100 dark:bg-gray-700 p-2 rounded text-xs font-mono mb-2">
                                                2024-01-15|TRF001|Transfer dari customer|credit|1000000<br>
                                                2024-01-16|TRF002|Pembayaran listrik|debit|500000<br>
                                                2024-01-17||Setoran tunai|credit|2000000
                                            </div>
                                            <textarea x-model="bulkInputText" rows="10" placeholder="Masukkan data transaksi, satu baris per transaksi..."
                                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm font-mono"></textarea>
                                        </div>

                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Saldo Bank Akhir (Opsional)
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" x-model="bulkBankBalance" step="0.01"
                                                    min="0" placeholder="0.00"
                                                    class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="processBulkInput()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Proses Data
                            </button>
                            <button type="button"
                                @click="showBulkManualInputModal = false; bulkInputText = ''; bulkBankBalance = 0"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function reconciliationData() {
            return {
                // Data Properties
                selectedAccount: '',
                selectedPeriod: '',
                erpBalance: 0,
                bankBalance: 0,
                realBankBalance: 0, // Saldo asli dari bank statement
                erpTransactions: [],
                bankTransactions: [],
                unmatchedBankTransactions: [], // Make this a reactive data property instead of computed
                unmatchedErpTransactions: [], // Make this a reactive data property instead of computed
                reconciliationHistory: [],
                uploadedFile: null,
                isLoading: false,
                currentAccountData: null,
                showUnmatchedDetails: false, // Untuk toggle detail transaksi belum cocok
                lastUpdate: Date.now(), // For forcing UI refresh

                // Manual Input Properties
                showManualInputModal: false,
                manualTransaction: {
                    date: '',
                    reference: '',
                    description: '',
                    amount: 0,
                    type: 'credit'
                },
                manualBankBalance: 0,

                // Bulk Manual Input Properties
                showBulkManualInputModal: false,
                bulkInputText: '',
                bulkBankBalance: 0,

                // Additional missing properties (if any)
                accounts: [],
                periods: [],

                // Computed Properties
                get difference() {
                    return this.realBankBalance - this.erpBalance;
                },

                get unmatchedErp() {
                    return this.unmatchedErpTransactions;
                },

                get unmatchedBank() {
                    console.log('🔄 Getting unmatchedBank:', this.unmatchedBankTransactions.length);
                    // Return the reactive data property instead of computed filtering
                    // This ensures proper reactivity in Alpine.js
                    return this.unmatchedBankTransactions;
                },

                // Debug method to check state
                debugUnmatchedState() {
                    console.log('🐛 Debug Unmatched State:');
                    console.log('- bankTransactions:', this.bankTransactions.length);
                    console.log('- unmatchedBankTransactions:', this.unmatchedBankTransactions.length);
                    console.log('- unmatchedBank computed:', this.unmatchedBank.length);
                    console.log('- bankTransactions details:', this.bankTransactions);
                    console.log('- unmatchedBankTransactions details:', this.unmatchedBankTransactions);
                    console.log('- unmatchedBank details:', this.unmatchedBank);

                    // Test each transaction in detail
                    console.log('🔍 Bank Transactions Analysis:');
                    this.bankTransactions.forEach((tx, index) => {
                        console.log(
                            `  [${index}] ID: ${tx.id}, matched: ${tx.matched}, isManual: ${tx.isManual}, desc: "${tx.description}"`
                        );
                    });

                    console.log('🔍 Unmatched Bank Transactions Analysis:');
                    this.unmatchedBankTransactions.forEach((tx, index) => {
                        console.log(
                            `  [${index}] ID: ${tx.id}, matched: ${tx.matched}, isManual: ${tx.isManual}, desc: "${tx.description}"`
                        );
                    });

                    // Check if unmatchedBank is actually an array
                    console.log('🔍 unmatchedBank type check:');
                    console.log('- Type:', typeof this.unmatchedBank);
                    console.log('- Is Array:', Array.isArray(this.unmatchedBank));
                    console.log('- Length:', this.unmatchedBank?.length);
                    console.log('- First item:', this.unmatchedBank?.[0]);

                    // Force refresh UI by dispatching Alpine event
                    this.$dispatch('force-ui-refresh');
                },

                // Method to update unmatched transactions
                updateUnmatchedTransactions() {
                    // Create new arrays to force reactivity (using spread operator)
                    this.unmatchedErpTransactions = [...this.erpTransactions.filter(t => !t.matched)];
                    this.unmatchedBankTransactions = [...this.bankTransactions.filter(t => !t.matched)];

                    console.log('🔄 Updated unmatched transactions:');
                    console.log('- ERP unmatched:', this.unmatchedErpTransactions.length);
                    console.log('- Bank unmatched:', this.unmatchedBankTransactions.length);
                    console.log('- Bank transactions details:', this.bankTransactions.map(t => ({
                        id: t.id,
                        matched: t.matched,
                        description: t.description
                    })));
                    console.log('- Unmatched bank details:', this.unmatchedBankTransactions.map(t => ({
                        id: t.id,
                        matched: t.matched,
                        description: t.description
                    })));

                    // Update last update timestamp to force UI refresh
                    this.lastUpdate = Date.now();

                    // Dispatch event to notify components of the update
                    this.$dispatch('unmatched-updated', {
                        erpCount: this.unmatchedErpTransactions.length,
                        bankCount: this.unmatchedBankTransactions.length
                    });

                    // Force Alpine.js to re-evaluate
                    this.$nextTick(() => {
                        console.log('🔄 $nextTick - final unmatched counts:', {
                            erp: this.unmatchedErpTransactions.length,
                            bank: this.unmatchedBankTransactions.length
                        });
                    });
                },

                // New method to verify data structure
                verifyDataStructure() {
                    console.log('🔍 Verifying data structure...');

                    // Check if arrays are actually arrays
                    console.log('- bankTransactions is array:', Array.isArray(this.bankTransactions));
                    console.log('- unmatchedBankTransactions is array:', Array.isArray(this.unmatchedBankTransactions));

                    // Check transaction structure
                    if (this.bankTransactions.length > 0) {
                        const firstTx = this.bankTransactions[0];
                        console.log('- First bank transaction structure:', {
                            hasId: 'id' in firstTx,
                            hasDescription: 'description' in firstTx,
                            hasAmount: 'amount' in firstTx,
                            hasMatched: 'matched' in firstTx,
                            hasIsManual: 'isManual' in firstTx,
                            structure: Object.keys(firstTx)
                        });
                    }

                    if (this.unmatchedBankTransactions.length > 0) {
                        const firstUnmatched = this.unmatchedBankTransactions[0];
                        console.log('- First unmatched transaction structure:', {
                            hasId: 'id' in firstUnmatched,
                            hasDescription: 'description' in firstUnmatched,
                            hasAmount: 'amount' in firstUnmatched,
                            hasMatched: 'matched' in firstUnmatched,
                            hasIsManual: 'isManual' in firstUnmatched,
                            structure: Object.keys(firstUnmatched)
                        });
                    }
                },

                // Initialization
                init() {
                    console.log('Reconciliation data initialized');
                    // Initialize with empty data - user must select account and period
                    this.resetData();

                    // Add watchers for better reactivity
                    this.$watch('bankTransactions', (newValue, oldValue) => {
                        console.log('🔄 Bank transactions changed:', {
                            oldCount: oldValue?.length || 0,
                            newCount: newValue?.length || 0
                        });
                        // Update the unmatched transactions when bank transactions change
                        this.updateUnmatchedTransactions();

                        // Force Alpine to detect the change
                        this.$nextTick(() => {
                            console.log('Bank transactions updated, unmatched count:', this.unmatchedBank
                                .length);
                        });
                    });

                    this.$watch('erpTransactions', (newValue, oldValue) => {
                        console.log('🔄 ERP transactions changed:', {
                            oldCount: oldValue?.length || 0,
                            newCount: newValue?.length || 0
                        });
                        this.updateUnmatchedTransactions();
                    });

                    // Initialize unmatched transactions
                    this.updateUnmatchedTransactions();
                },

                // Currency formatting
                formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(amount || 0);
                },

                // Load real bank balance from server
                async loadAccountData() {
                    if (!this.selectedAccount) {
                        this.resetData();
                        return;
                    }

                    this.isLoading = true;
                    try {
                        console.log('🔄 Loading real account data for account:', this.selectedAccount);

                        // Fetch rekening balance from server
                        const response = await fetch(
                            `/keuangan/rekonsiliasi/erp-transactions?rekening_id=${this.selectedAccount}&periode=${this.selectedPeriod || new Date().toISOString().slice(0, 7)}`, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            });

                        console.log('📡 Response status:', response.status);

                        if (response.ok) {
                            const result = await response.json();
                            console.log('📡 Response data:', result);

                            if (result.success) {
                                this.erpBalance = result.data.balance.closing || 0;
                                this.bankBalance = result.data.balance.closing || 0; // Initialize with ERP balance
                                this.erpTransactions = result.data.transactions || [];
                                this.currentAccountData = result.data;

                                console.log('✅ Real account data loaded:');
                                console.log('- ERP Balance:', this.formatCurrency(this.erpBalance));
                                console.log('- ERP Transactions:', this.erpTransactions.length);

                                // Show success message if no transactions
                                if (this.erpTransactions.length === 0) {
                                    console.log('ℹ️ No transactions found for selected period');
                                }
                            } else {
                                throw new Error(result.message || 'Failed to load account data');
                            }
                        } else {
                            const errorText = await response.text();
                            console.error('❌ HTTP Error:', response.status, errorText);
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }

                    } catch (error) {
                        console.error('❌ Error loading real account data:', error);

                        // Show user-friendly error message
                        const errorMessage =
                            `⚠️ Gagal memuat data rekening:\n${error.message}\n\nSilakan:\n• Periksa koneksi internet\n• Pastikan rekening dan periode valid\n• Coba refresh halaman`;
                        alert(errorMessage);

                        // Reset to empty state
                        this.resetData();
                    } finally {
                        this.isLoading = false;
                    }
                },

                // Load ERP transactions from server (DEPRECATED - use loadAccountData instead)
                async loadErpTransactions() {
                    console.warn('⚠️ loadErpTransactions() is deprecated. Use loadAccountData() instead.');
                    return this.loadAccountData();
                },

                // Load period data
                async loadPeriodData() {
                    if (this.selectedPeriod && this.selectedAccount) {
                        await this.loadAccountData();
                        console.log('Period data loaded for:', this.selectedPeriod);
                    }
                },

                // Show data requirement message
                showDataRequiredMessage() {
                    alert('📋 Sistem Rekonsiliasi Bank - Data Real\n\n' +
                        '✅ Langkah-langkah untuk memulai:\n' +
                        '1. Pilih rekening bank yang akan direkonsiliasi\n' +
                        '2. Pilih periode rekonsiliasi (bulan/tahun)\n' +
                        '3. Sistem akan memuat transaksi ERP secara otomatis\n' +
                        '4. Upload bank statement atau input manual\n' +
                        '5. Gunakan Auto Match untuk mencocokkan transaksi\n\n' +
                        '💡 Sistem ini menggunakan data real dari database.');
                },

                // Reset data when no account selected
                resetData() {
                    this.erpBalance = 0;
                    this.bankBalance = 0;
                    this.realBankBalance = 0;
                    this.erpTransactions = [];
                    this.bankTransactions = [];
                    this.unmatchedErpTransactions = [];
                    this.unmatchedBankTransactions = [];
                    this.currentAccountData = null;

                    // Update unmatched transactions after reset
                    this.updateUnmatchedTransactions();

                    // Ensure manual transaction is properly initialized
                    this.manualTransaction = {
                        date: '',
                        reference: '',
                        description: '',
                        amount: 0,
                        type: 'credit'
                    };
                },

                // Handle file upload
                handleFileUpload(event) {
                    this.uploadedFile = event.target.files[0];
                    console.log('File uploaded:', this.uploadedFile?.name);

                    // Validate file
                    if (this.uploadedFile) {
                        const allowedTypes = ['application/pdf',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'
                        ];
                        const maxSize = 10 * 1024 * 1024; // 10MB

                        if (!allowedTypes.includes(this.uploadedFile.type)) {
                            alert('Tipe file tidak didukung. Gunakan PDF, Excel (.xlsx), atau CSV.');
                            event.target.value = '';
                            this.uploadedFile = null;
                            return;
                        }

                        if (this.uploadedFile.size > maxSize) {
                            alert('Ukuran file terlalu besar. Maksimal 10MB.');
                            event.target.value = '';
                            this.uploadedFile = null;
                            return;
                        }
                    }
                }, // Process bank statement
                async processStatement() {
                    if (!this.uploadedFile) {
                        alert('Pilih file bank statement terlebih dahulu');
                        return;
                    }

                    if (!this.selectedAccount || !this.selectedPeriod) {
                        alert('Pilih rekening bank dan periode terlebih dahulu');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        console.log('🔄 Memproses bank statement:', this.uploadedFile.name);

                        // Create FormData for file upload
                        const formData = new FormData();
                        formData.append('statement_file', this.uploadedFile);
                        formData.append('rekening_id', this.selectedAccount);
                        formData.append('periode', this.selectedPeriod);

                        // Send file to backend for processing
                        const response = await fetch('/keuangan/rekonsiliasi/process-statement', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute(
                                        'content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            // Update bank transactions from processed file
                            this.bankTransactions = result.data.transactions || [];
                            this.realBankBalance = result.data.balance?.closing || 0;

                            console.log('✅ Bank statement berhasil diproses!');
                            console.log('📊 Hasil parsing:');
                            console.log('- Saldo akhir bank statement:', this.formatCurrency(this
                                .realBankBalance));
                            console.log('- Transaksi bank ditemukan:', this.bankTransactions.length);
                            console.log('- Selisih dengan ERP:', this.formatCurrency(Math.abs(this
                                .difference)));

                            // Force update UI components
                            this.forceUpdate();

                            // Show detailed success message
                            const successMessage = `✅ Bank statement berhasil diproses!\n\n` +
                                `📊 Hasil:\n` +
                                `• File: ${this.uploadedFile.name}\n` +
                                `• Saldo bank statement: ${this.formatCurrency(this.realBankBalance)}\n` +
                                `• Saldo ERP: ${this.formatCurrency(this.erpBalance)}\n` +
                                `• Selisih: ${this.formatCurrency(Math.abs(this.difference))}\n` +
                                `• Transaksi bank: ${this.bankTransactions.length} item\n\n` +
                                `💡 Selanjutnya gunakan "Auto Match" untuk mencocokkan transaksi.`;

                            alert(successMessage);

                        } else {
                            throw new Error(result.message || 'Gagal memproses bank statement');
                        }

                    } catch (error) {
                        console.error('❌ Error processing bank statement:', error);

                        // Show detailed error message with troubleshooting
                        let errorMessage = `❌ Gagal memproses bank statement: ${error.message}\n\n`;

                        if (error.message.includes('format')) {
                            errorMessage += `💡 Tips:\n` +
                                `• Pastikan file dalam format PDF, Excel (.xlsx), atau CSV\n` +
                                `• Cek apakah file rusak atau password-protected\n` +
                                `• Coba download ulang file dari bank\n\n`;
                        }

                        errorMessage += `🔄 Alternatif:\n` +
                            `• Coba upload file dalam format berbeda\n` +
                            `• Gunakan input manual jika parsing otomatis gagal\n` +
                            `• Hubungi administrator jika masalah berlanjut`;

                        alert(errorMessage);
                    } finally {
                        this.isLoading = false;
                    }
                },

                // Manual Input Methods
                showManualInput() {
                    if (!this.selectedAccount || !this.selectedPeriod) {
                        alert('Pilih rekening bank dan periode terlebih dahulu');
                        return;
                    }

                    this.manualTransaction = {
                        date: new Date().toISOString().slice(0, 10), // Today's date
                        reference: '',
                        description: '',
                        amount: 0,
                        type: 'credit'
                    };
                    this.showManualInputModal = true;
                },

                addManualTransaction() {
                    if (!this.manualTransaction.date || !this.manualTransaction.description || this
                        .manualTransaction
                        .amount === 0) {
                        alert('Harap lengkapi semua field yang diperlukan');
                        return;
                    }

                    // Add transaction to bank transactions list
                    const newTransaction = {
                        id: 'manual_' + Date.now(),
                        date: this.manualTransaction.date,
                        reference: this.manualTransaction.reference || 'MANUAL-' + Date.now(),
                        description: this.manualTransaction.description,
                        amount: parseFloat(this.manualTransaction.amount),
                        type: this.manualTransaction.type,
                        matched: false,
                        isManual: true
                    };

                    // Force reactivity by creating new array instead of push
                    this.bankTransactions = [...this.bankTransactions, newTransaction];

                    console.log('✅ Transaksi manual ditambahkan:', newTransaction);
                    console.log('📊 Total transaksi bank sekarang:', this.bankTransactions.length);

                    // Update real bank balance if provided
                    if (this.manualBankBalance > 0) {
                        this.realBankBalance = this.manualBankBalance;
                    }

                    // Force update UI components - with extra verification
                    console.log('🔄 Forcing update after manual transaction...');
                    this.forceUpdate();

                    // Additional verification
                    setTimeout(() => {
                        console.log('🔍 Post-add verification:');
                        console.log('- Total bank transactions:', this.bankTransactions.length);
                        console.log('- Unmatched bank:', this.unmatchedBank.length);
                        console.log('- Is new transaction in bank list:', this.bankTransactions.some(
                            t => t.id ===
                            newTransaction.id));
                        console.log('- Is new transaction unmatched:', this.unmatchedBank.some(t => t
                            .id ===
                            newTransaction.id));
                    }, 100);

                    // Close modal and reset form
                    this.showManualInputModal = false;
                    this.resetManualForm();

                    alert(
                        `✅ Transaksi berhasil ditambahkan!\n\n📝 Detail:\n• Tanggal: ${newTransaction.date}\n• Deskripsi: ${newTransaction.description}\n• Jumlah: ${this.formatCurrency(newTransaction.amount)}\n• Tipe: ${newTransaction.type === 'credit' ? 'Kredit (Masuk)' : 'Debit (Keluar)'}\n\n📊 Total transaksi bank: ${this.bankTransactions.length}`
                    );
                },

                resetManualForm() {
                    this.manualTransaction = {
                        date: '',
                        reference: '',
                        description: '',
                        amount: 0,
                        type: 'credit'
                    };
                    this.manualBankBalance = 0;
                },

                removeManualTransaction(transactionId) {
                    // Find the transaction to be removed
                    const transactionToRemove = this.bankTransactions.find(t => t.id === transactionId);

                    if (!transactionToRemove) {
                        alert('❌ Transaksi tidak ditemukan!');
                        return;
                    }

                    // Show detailed confirmation dialog
                    const confirmMessage = `🗑️ Hapus Transaksi Manual?\n\n` +
                        `📝 Detail transaksi:\n` +
                        `• Tanggal: ${transactionToRemove.date}\n` +
                        `• Deskripsi: ${transactionToRemove.description}\n` +
                        `• Referensi: ${transactionToRemove.reference}\n` +
                        `• Jumlah: ${this.formatCurrency(transactionToRemove.amount)}\n` +
                        `• Tipe: ${transactionToRemove.type === 'credit' ? 'Kredit (Masuk)' : 'Debit (Keluar)'}\n\n` +
                        `⚠️ Tindakan ini tidak dapat dibatalkan.\n` +
                        `Apakah Anda yakin ingin menghapus transaksi ini?`;

                    if (confirm(confirmMessage)) {
                        // Store for potential undo functionality
                        const removedTransaction = {
                            ...transactionToRemove
                        };

                        // Remove the transaction using filter to force reactivity
                        this.bankTransactions = this.bankTransactions.filter(t => t.id !== transactionId);

                        console.log('🗑️ Transaksi manual dihapus:', {
                            id: transactionId,
                            description: removedTransaction.description,
                            amount: removedTransaction.amount
                        });

                        // Force update UI components
                        this.forceUpdate();

                        // Show success message with summary
                        const remainingManualCount = this.bankTransactions.filter(t => t.isManual).length;
                        const successMessage = `✅ Transaksi berhasil dihapus!\n\n` +
                            `📊 Status setelah penghapusan:\n` +
                            `• Total transaksi bank: ${this.bankTransactions.length}\n` +
                            `• Transaksi manual tersisa: ${remainingManualCount}\n` +
                            `• Transaksi dari file: ${this.bankTransactions.length - remainingManualCount}`;

                        alert(successMessage);

                        // Auto-run matching if there are transactions available
                        if (this.erpTransactions.length > 0 && this.bankTransactions.length > 0) {
                            const shouldAutoMatch = confirm(
                                '🔍 Auto-match transaksi setelah penghapusan?\n\nSistem akan mencoba mencocokkan kembali transaksi yang tersisa.'
                            );
                            if (shouldAutoMatch) {
                                setTimeout(() => {
                                    this.autoMatch();
                                }, 500);
                            }
                        }
                    }
                },

                // New method to remove all manual transactions
                removeAllManualTransactions() {
                    const manualTransactions = this.bankTransactions.filter(t => t.isManual);

                    if (manualTransactions.length === 0) {
                        alert('ℹ️ Tidak ada transaksi manual untuk dihapus.');
                        return;
                    }

                    const confirmMessage = `🗑️ Hapus Semua Transaksi Manual?\n\n` +
                        `📊 Akan menghapus ${manualTransactions.length} transaksi manual:\n` +
                        manualTransactions.slice(0, 3).map(t =>
                            `• ${t.description} - ${this.formatCurrency(t.amount)}`).join('\n') +
                        (manualTransactions.length > 3 ? `\n• dan ${manualTransactions.length - 3} lainnya...` :
                            '') +
                        `\n\n⚠️ Tindakan ini tidak dapat dibatalkan.\n` +
                        `Apakah Anda yakin?`;

                    if (confirm(confirmMessage)) {
                        // Keep only non-manual transactions using filter to force reactivity
                        this.bankTransactions = this.bankTransactions.filter(t => !t.isManual);

                        console.log(`🗑️ ${manualTransactions.length} transaksi manual dihapus`);

                        // Force update UI components
                        this.forceUpdate();

                        alert(`✅ ${manualTransactions.length} transaksi manual berhasil dihapus!\n\n` +
                            `📊 Sisa transaksi bank: ${this.bankTransactions.length} (dari file)`);
                    }
                },

                // Bulk manual input from textarea
                showBulkManualInput() {
                    if (!this.selectedAccount || !this.selectedPeriod) {
                        alert('Pilih rekening bank dan periode terlebih dahulu');
                        return;
                    }

                    this.bulkInputText = '';
                    this.bulkBankBalance = 0;
                    this.showBulkManualInputModal = true;
                },

                processBulkInput() {
                    if (!this.bulkInputText.trim()) {
                        alert('Harap masukkan data transaksi');
                        return;
                    }

                    const lines = this.bulkInputText.split('\n').filter(line => line.trim());
                    let successCount = 0;
                    let errorCount = 0;
                    const errors = [];
                    const newTransactions = []; // Collect new transactions first

                    lines.forEach((line, index) => {
                        try {
                            const parts = line.split('|').map(part => part.trim());

                            if (parts.length >= 4) {
                                const [date, reference, description, type, amountStr] = parts;
                                const amount = parseFloat(amountStr);

                                if (isNaN(amount) || amount <= 0) {
                                    errors.push(`Baris ${index + 1}: Jumlah tidak valid`);
                                    errorCount++;
                                    return;
                                }

                                if (!['credit', 'debit'].includes(type.toLowerCase())) {
                                    errors.push(
                                        `Baris ${index + 1}: Jenis transaksi harus 'credit' atau 'debit'`
                                    );
                                    errorCount++;
                                    return;
                                }

                                const transaction = {
                                    id: 'bulk_' + Date.now() + '_' + index,
                                    date: date,
                                    reference: reference || 'BULK-' + Date.now() + '-' + (index +
                                        1),
                                    description: description,
                                    amount: amount,
                                    type: type.toLowerCase(),
                                    matched: false,
                                    isManual: true
                                };

                                newTransactions.push(transaction);
                                successCount++;
                            } else {
                                errors.push(
                                    `Baris ${index + 1}: Format tidak lengkap (minimal 4 kolom)`);
                                errorCount++;
                            }
                        } catch (error) {
                            console.error('Error processing line:', line, error);
                            errors.push(`Baris ${index + 1}: ${error.message}`);
                            errorCount++;
                        }
                    });

                    // Force reactivity by creating new array instead of multiple pushes
                    if (newTransactions.length > 0) {
                        this.bankTransactions = [...this.bankTransactions, ...newTransactions];
                        console.log('✅ Bulk transactions added:', newTransactions.length);
                        console.log('📊 Total transaksi bank sekarang:', this.bankTransactions.length);
                    }

                    // Update bank balance if provided
                    if (this.bulkBankBalance > 0) {
                        this.realBankBalance = this.bulkBankBalance;
                    }

                    // Force update UI components
                    this.forceUpdate();

                    // Close modal
                    this.showBulkManualInputModal = false;
                    this.bulkInputText = '';
                    this.bulkBankBalance = 0;

                    // Show results
                    let message =
                        `📊 Bulk Import Selesai!\n\n✅ Berhasil: ${successCount} transaksi\n❌ Error: ${errorCount} baris\n\n📊 Total transaksi bank: ${this.bankTransactions.length}`;

                    if (errors.length > 0 && errors.length <= 5) {
                        message += '\n\n🚫 Error yang ditemukan:\n' + errors.join('\n');
                    } else if (errors.length > 5) {
                        message += '\n\n🚫 Error ditemukan pada beberapa baris. Periksa format data.';
                    }

                    message += '\n\n💡 Silakan review transaksi yang ditambahkan sebelum melakukan auto match.';

                    alert(message);
                },

                // Auto match transactions
                autoMatch() {
                    console.log('🔍 Memulai proses auto matching...');

                    let matchedCount = 0;
                    const matchingRules = [];

                    // Create copies of arrays to ensure reactivity
                    this.erpTransactions = [...this.erpTransactions];
                    this.bankTransactions = [...this.bankTransactions];

                    this.erpTransactions.forEach((erpTx, erpIndex) => {
                        if (erpTx.matched) return; // Skip jika sudah dicocokkan

                        const bankMatchIndex = this.bankTransactions.findIndex(bankTx =>
                            !bankTx.matched && // Belum dicocokkan
                            Math.abs(erpTx.amount) === Math.abs(bankTx.amount) && // Jumlah sama
                            erpTx.type === bankTx.type // Jenis sama (credit/debit)
                        );

                        if (bankMatchIndex !== -1) {
                            const bankMatch = this.bankTransactions[bankMatchIndex];

                            // Update matched status and add cross-references
                            this.erpTransactions[erpIndex].matched = true;
                            this.erpTransactions[erpIndex].bankMatchId = bankMatch.id;
                            this.erpTransactions[erpIndex].bankMatchRef = bankMatch.reference ||
                                bankMatch.id;

                            this.bankTransactions[bankMatchIndex].matched = true;
                            this.bankTransactions[bankMatchIndex].erpMatchId = erpTx.id;
                            this.bankTransactions[bankMatchIndex].erpMatchRef = erpTx.reference || erpTx
                                .id;

                            matchedCount++;

                            matchingRules.push({
                                erpId: erpTx.id,
                                bankId: bankMatch.id,
                                amount: erpTx.amount,
                                erpDesc: erpTx.description,
                                bankDesc: bankMatch.description,
                                rule: 'Exact amount and type match'
                            });

                            console.log(
                                `✅ Match found: ERP "${erpTx.description}" ↔ Bank "${bankMatch.description}" (${this.formatCurrency(erpTx.amount)})`
                            );
                        }
                    });

                    // Force update unmatched transactions
                    this.updateUnmatchedTransactions();

                    // Show detailed results
                    const unmatchedErpCount = this.unmatchedErpTransactions.length;
                    const unmatchedBankCount = this.unmatchedBankTransactions.length;

                    console.log('📊 Hasil Auto Match:');
                    console.log(`• Transaksi berhasil dicocokkan: ${matchedCount}`);
                    console.log(`• Transaksi ERP belum cocok: ${unmatchedErpCount}`);
                    console.log(`• Transaksi Bank belum cocok: ${unmatchedBankCount}`);

                    let alertMessage = `🔍 Auto Match Selesai!\n\n` +
                        `📊 Hasil:\n` +
                        `• Berhasil dicocokkan: ${matchedCount} transaksi\n` +
                        `• ERP belum cocok: ${unmatchedErpCount} transaksi\n` +
                        `• Bank belum cocok: ${unmatchedBankCount} transaksi\n\n`;

                    if (matchedCount > 0) {
                        alertMessage += `✅ Transaksi yang berhasil dicocokkan:\n`;
                        matchingRules.forEach((rule, index) => {
                            alertMessage += `${index + 1}. ${this.formatCurrency(rule.amount)}\n`;
                        });
                        alertMessage += `\n`;
                    }

                    if (unmatchedErpCount > 0 || unmatchedBankCount > 0) {
                        alertMessage += `⚠️ Masih ada transaksi yang belum cocok.\n` +
                            `Kemungkinan penyebab:\n` +
                            `• Biaya admin bank\n` +
                            `• Bunga bank\n` +
                            `• Transaksi yang belum dicatat\n` +
                            `• Perbedaan waktu pencatatan\n\n` +
                            `💡 Review manual diperlukan untuk transaksi ini.`;
                    } else {
                        alertMessage += `🎉 Semua transaksi sudah berhasil dicocokkan!`;
                    }

                    // Force UI refresh
                    this.$nextTick(() => {
                        console.log('🔄 UI refresh after auto match');
                        this.lastUpdate = Date.now();
                    });

                    alert(alertMessage);
                },

                // Force update method to ensure UI reactivity
                forceUpdate() {
                    console.log('🔄 Force update called - Bank transactions before:', this.bankTransactions
                        .length);

                    // Manually update unmatched transactions first
                    this.updateUnmatchedTransactions();

                    // Manually refresh the unmatched bank transactions array to force reactivity
                    this.unmatchedBankTransactions = [...this.bankTransactions.filter(t => !t.matched)];

                    // Force Alpine.js to re-evaluate computed properties
                    this.$nextTick(() => {
                        console.log('🔄 Forcing UI update - Bank transactions:', this.bankTransactions
                            .length);
                        console.log('🔄 Unmatched bank:', this.unmatchedBank.length);
                        console.log('🔄 Unmatched ERP:', this.unmatchedErp.length);

                        // Additional debugging
                        console.log('🔄 Bank transactions details:', this.bankTransactions.map(t => ({
                            id: t.id,
                            description: t.description,
                            matched: t.matched,
                            isManual: t.isManual
                        })));
                    });

                    // Additional force update - trigger reactivity by reassigning arrays
                    const tempBankTransactions = [...this.bankTransactions];
                    const tempErpTransactions = [...this.erpTransactions];
                    this.bankTransactions = tempBankTransactions;
                    this.erpTransactions = tempErpTransactions;

                    // Force Alpine to refresh components that depend on these arrays
                    setTimeout(() => {
                        this.$dispatch('transactions-updated', {
                            bankCount: this.bankTransactions.length,
                            unmatchedBankCount: this.unmatchedBank.length,
                            unmatchedErpCount: this.unmatchedErp.length
                        });

                        // Update unmatched transactions again after reassignment
                        this.updateUnmatchedTransactions();
                    }, 100);
                },

                // Validate reconciliation data before saving
                validateReconciliationData() {
                    const errors = [];

                    // Basic validations
                    if (!this.selectedAccount) {
                        errors.push('Rekening bank belum dipilih');
                    }

                    if (!this.selectedPeriod) {
                        errors.push('Periode rekonsiliasi belum dipilih');
                    }

                    if (this.erpTransactions.length === 0) {
                        errors.push('Tidak ada transaksi ERP untuk periode ini');
                    }

                    // Data consistency validations
                    const matchedErpCount = this.erpTransactions.filter(t => t.matched).length;
                    const matchedBankCount = this.bankTransactions.filter(t => t.matched).length;

                    if (matchedErpCount !== matchedBankCount) {
                        errors.push(
                            `Jumlah transaksi ERP yang dicocokkan (${matchedErpCount}) tidak sama dengan Bank (${matchedBankCount})`
                        );
                    }

                    // Validate matched transactions have proper references
                    const matchedErpWithoutBankRef = this.erpTransactions.filter(t => t.matched && !t
                        .bankMatchId).length;
                    if (matchedErpWithoutBankRef > 0) {
                        errors.push(
                            `${matchedErpWithoutBankRef} transaksi ERP yang dicocokkan tidak memiliki referensi bank`
                        );
                    }

                    // Check for duplicate matches
                    const bankMatchIds = this.erpTransactions
                        .filter(t => t.matched && t.bankMatchId)
                        .map(t => t.bankMatchId);
                    const duplicateBankMatches = bankMatchIds.filter((id, index) => bankMatchIds.indexOf(id) !==
                        index);
                    if (duplicateBankMatches.length > 0) {
                        errors.push(`Ditemukan duplikasi pencocokan bank: ${duplicateBankMatches.join(', ')}`);
                    }

                    return {
                        isValid: errors.length === 0,
                        errors: errors
                    };
                },

                // Save reconciliation
                async saveReconciliation() {
                    // Pre-validation
                    const validation = this.validateReconciliationData();
                    if (!validation.isValid) {
                        const errorMessage =
                            `⚠️ Validasi data gagal:\n\n${validation.errors.map(e => `• ${e}`).join('\n')}\n\nSilakan perbaiki data sebelum menyimpan.`;
                        alert(errorMessage);
                        return;
                    }
                    if (!this.selectedAccount || !this.selectedPeriod) {
                        alert('Pilih rekening dan periode terlebih dahulu');
                        return;
                    }

                    // Validate reconciliation readiness
                    const matchedErpCount = this.erpTransactions.filter(t => t.matched).length;
                    const matchedBankCount = this.bankTransactions.filter(t => t.matched).length;
                    const unmatchedErpCount = this.unmatchedErp.length;
                    const unmatchedBankCount = this.unmatchedBank.length;

                    // Ask for confirmation if there are unmatched transactions
                    if (unmatchedErpCount > 0 || unmatchedBankCount > 0) {
                        const confirmMessage = `⚠️ Masih ada transaksi yang belum cocok:\n` +
                            `• ERP belum cocok: ${unmatchedErpCount} transaksi\n` +
                            `• Bank belum cocok: ${unmatchedBankCount} transaksi\n` +
                            `• Selisih: ${this.formatCurrency(Math.abs(this.difference))}\n\n` +
                            `Apakah Anda yakin ingin menyimpan rekonsiliasi ini?`;

                        if (!confirm(confirmMessage)) {
                            return;
                        }
                    }

                    this.isLoading = true;

                    try {
                        // Parse selectedPeriod to extract year and month
                        const [selectedYear, selectedMonth] = this.selectedPeriod.split('-');

                        // Prepare reconciliation data
                        const reconciliationData = {
                            rekening_id: this.selectedAccount,
                            periode: this.selectedPeriod,
                            tahun: parseInt(selectedYear),
                            bulan: selectedMonth,
                            erp_balance: this.erpBalance,
                            bank_balance: this.realBankBalance,
                            difference: this.difference,
                            status: (unmatchedErpCount === 0 && unmatchedBankCount === 0) ? 'balanced' : 'pending',

                            // Matched transactions with proper mapping
                            matched_transactions: this.erpTransactions
                                .filter(t => t.matched)
                                .map(t => ({
                                    erp_transaction_id: t.id,
                                    bank_transaction_id: t.bankMatchId || null,
                                    bank_transaction_ref: t.bankMatchRef || null,
                                    amount: parseFloat(t.amount),
                                    erp_description: t.description,
                                    bank_description: this.bankTransactions.find(bt => bt.id ===
                                        t.bankMatchId)?.description || '',
                                    match_type: 'auto',
                                    match_confidence: 'high',
                                    match_date: new Date().toISOString()
                                })),

                            // Unmatched ERP transactions with better structure
                            unmatched_erp: this.unmatchedErpTransactions.map(t => ({
                                transaction_id: t.id,
                                amount: parseFloat(t.amount),
                                description: t.description,
                                date: t.date,
                                type: t.type,
                                reference: t.reference || null,
                                reason: t.unmatchReason ||
                                    'Tidak ditemukan di bank statement'
                            })),

                            // Unmatched bank transactions with better structure
                            unmatched_bank: this.unmatchedBankTransactions.map(t => ({
                                transaction_ref: t.id,
                                amount: parseFloat(t.amount),
                                description: t.description,
                                date: t.date,
                                type: t.type,
                                reference: t.reference || null,
                                is_manual: t.isManual || false,
                                reason: t.unmatchReason || 'Tidak ditemukan di sistem ERP'
                            })),

                            // Summary statistics
                            summary: {
                                total_erp_transactions: this.erpTransactions.length,
                                total_bank_transactions: this.bankTransactions.length,
                                matched_count: matchedErpCount,
                                unmatched_erp_count: unmatchedErpCount,
                                unmatched_bank_count: unmatchedBankCount,
                                reconciliation_percentage: this.erpTransactions.length > 0 ?
                                    Math.round((matchedErpCount / this.erpTransactions.length) * 100) : 0
                            },

                            // Additional metadata
                            reconciled_by: '{{ auth()->user()->name ?? 'System' }}',
                            reconciled_at: new Date().toISOString(),
                            file_uploaded: this.uploadedFile ? this.uploadedFile.name : null
                        };

                        console.log('💾 Menyimpan data rekonsiliasi:', reconciliationData);

                        // Send to backend API
                        const response = await fetch('/keuangan/rekonsiliasi/save', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute(
                                        'content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(reconciliationData)
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            console.log('✅ Rekonsiliasi berhasil disimpan:', result);

                            // Show success message with detailed summary
                            const successMessage = `✅ Rekonsiliasi berhasil disimpan!\n\n` +
                                `📊 Ringkasan:\n` +
                                `• ID Rekonsiliasi: ${result.reconciliation_id || 'Generated'}\n` +
                                `• Rekening: ${this.accounts?.find(a => a.id == this.selectedAccount)?.label || 'N/A'}\n` +
                                `• Periode: ${this.selectedPeriod}\n` +
                                `• Status: ${result.status || reconciliationData.status}\n` +
                                `• Transaksi cocok: ${result.data?.matched_count || matchedErpCount}\n` +
                                `• ERP belum cocok: ${result.data?.unmatched_erp_count || unmatchedErpCount}\n` +
                                `• Bank belum cocok: ${result.data?.unmatched_bank_count || unmatchedBankCount}\n` +
                                `• Selisih: ${this.formatCurrency(Math.abs(this.difference))}\n\n` +
                                `🔍 Data tersimpan dan dapat dilihat di riwayat rekonsiliasi.`;

                            alert(successMessage);

                            // Optional: Reset form or redirect
                            // this.resetForm();

                        } else {
                            throw new Error(result.message || 'Gagal menyimpan rekonsiliasi');
                        }

                    } catch (error) {
                        console.error('❌ Error saving reconciliation:', error);

                        // Enhanced error logging for debugging
                        console.error('📋 Request data that failed:', {
                            reconciliationData,
                            selectedAccount: this.selectedAccount,
                            selectedPeriod: this.selectedPeriod,
                            erpTransactionsCount: this.erpTransactions.length,
                            bankTransactionsCount: this.bankTransactions.length,
                            matchedErpCount,
                            unmatchedErpCount,
                            unmatchedBankCount
                        });

                        // Fallback: save to localStorage for debugging
                        const fallbackData = {
                            timestamp: new Date().toISOString(),
                            error: error.message,
                            data: reconciliationData,
                            debugInfo: {
                                erpTransactions: this.erpTransactions.length,
                                bankTransactions: this.bankTransactions.length,
                                unmatchedErp: this.unmatchedErpTransactions.length,
                                unmatchedBank: this.unmatchedBankTransactions.length,
                                selectedAccount: this.selectedAccount,
                                selectedPeriod: this.selectedPeriod
                            }
                        };

                        try {
                            localStorage.setItem('reconciliation_backup', JSON.stringify(fallbackData));
                            console.log('💾 Data disimpan ke localStorage sebagai backup');
                        } catch (storageError) {
                            console.error('Gagal menyimpan backup:', storageError);
                        }

                        let errorMessage = `❌ Gagal menyimpan rekonsiliasi: ${error.message}\n\n`;

                        // Provide specific error guidance
                        if (error.message.includes('validation')) {
                            errorMessage += `🔍 Kemungkinan masalah validasi data.\n`;
                        } else if (error.message.includes('network') || error.message.includes('fetch')) {
                            errorMessage += `🌐 Masalah koneksi jaringan.\n`;
                        } else if (error.message.includes('500')) {
                            errorMessage += `🔧 Masalah server internal.\n`;
                        }

                        errorMessage += `Data telah disimpan sebagai backup di browser. ` +
                            `Silakan hubungi administrator atau coba lagi.\n\n` +
                            `🐛 Untuk debugging, buka Console browser dan periksa log detail.`;

                        alert(errorMessage);
                    } finally {
                        this.isLoading = false;
                    }
                },

                // Export reconciliation to Excel
                async exportReconciliation() {
                    if (!this.selectedAccount || this.erpTransactions.length === 0) {
                        alert(
                            'Tidak ada data untuk diekspor. Pilih rekening dan periode terlebih dahulu.');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        console.log('📊 Mengekspor data rekonsiliasi...');

                        // Prepare export data
                        const exportData = {
                            rekening_id: this.selectedAccount,
                            periode: this.selectedPeriod,
                            tahun: new Date().getFullYear(),
                            account_info: this.currentAccountData,
                            balances: {
                                erp_balance: this.erpBalance,
                                bank_balance: this.realBankBalance,
                                difference: this.difference
                            },
                            transactions: {
                                erp: this.erpTransactions,
                                bank: this.bankTransactions,
                                matched: this.erpTransactions.filter(t => t.matched),
                                unmatched_erp: this.unmatchedErp,
                                unmatched_bank: this.unmatchedBank
                            },
                            summary: {
                                total_erp_transactions: this.erpTransactions.length,
                                total_bank_transactions: this.bankTransactions.length,
                                matched_count: this.erpTransactions.filter(t => t.matched).length,
                                unmatched_erp_count: this.unmatchedErp.length,
                                unmatched_bank_count: this.unmatchedBank.length,
                                reconciliation_percentage: this.erpTransactions.length > 0 ?
                                    Math.round((this.erpTransactions.filter(t => t.matched).length /
                                        this
                                        .erpTransactions.length) * 100) : 0
                            },
                            generated_at: new Date().toISOString(),
                            generated_by: '{{ auth()->user()->name ?? 'System' }}'
                        };

                        // Try to call backend export API first
                        try {
                            const response = await fetch('/keuangan/rekonsiliasi/export', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(exportData)
                            });

                            if (response.ok) {
                                // If backend export is successful, download the file
                                const blob = await response.blob();
                                const filename =
                                    `Rekonsiliasi_${this.selectedAccount}_${this.selectedPeriod}.xlsx`;

                                const downloadLink = document.createElement('a');
                                downloadLink.href = window.URL.createObjectURL(blob);
                                downloadLink.download = filename;
                                downloadLink.click();

                                console.log('✅ Ekspor Excel berhasil dari backend');
                                alert('📊 File Excel berhasil diunduh!');
                                return;
                            }
                        } catch (backendError) {
                            console.warn(
                                'Backend export tidak tersedia, menggunakan client-side export:',
                                backendError);
                        }

                        // Fallback: Client-side export using simple CSV format
                        console.log('🔄 Menggunakan client-side export (CSV)...');

                        let csvContent = '';

                        // Header information
                        csvContent += `LAPORAN REKONSILIASI BANK\n`;
                        csvContent += `Rekening,${this.currentAccountData?.nama_bank || 'N/A'}\n`;
                        csvContent += `Periode,${this.selectedPeriod}/${new Date().getFullYear()}\n`;
                        csvContent += `Dibuat pada,${new Date().toLocaleString('id-ID')}\n`;
                        csvContent += `Dibuat oleh,{{ auth()->user()->name ?? 'System' }}\n\n`;

                        // Summary
                        csvContent += `RINGKASAN\n`;
                        csvContent += `Saldo ERP,${this.formatCurrency(this.erpBalance)}\n`;
                        csvContent +=
                            `Saldo Bank Statement,${this.formatCurrency(this.realBankBalance)}\n`;
                        csvContent += `Selisih,${this.formatCurrency(Math.abs(this.difference))}\n`;
                        csvContent += `Total Transaksi ERP,${this.erpTransactions.length}\n`;
                        csvContent += `Total Transaksi Bank,${this.bankTransactions.length}\n`;
                        csvContent +=
                            `Transaksi Cocok,${this.erpTransactions.filter(t => t.matched).length}\n`;
                        csvContent +=
                            `Persentase Kecocokan,${exportData.summary.reconciliation_percentage}%\n\n`;

                        // ERP Transactions
                        csvContent += `TRANSAKSI ERP\n`;
                        csvContent += `Tanggal,Referensi,Deskripsi,Jumlah,Status\n`;
                        this.erpTransactions.forEach(t => {
                            csvContent +=
                                `${t.date},"${t.reference}","${t.description}",${t.amount},${t.matched ? 'Cocok' : 'Belum Cocok'}\n`;
                        });
                        csvContent += `\n`;

                        // Bank Transactions
                        csvContent += `TRANSAKSI BANK\n`;
                        csvContent += `Tanggal,Referensi,Deskripsi,Jumlah,Status\n`;
                        this.bankTransactions.forEach(t => {
                            csvContent +=
                                `${t.date},"${t.reference}","${t.description}",${t.amount},${t.matched ? 'Cocok' : 'Belum Cocok'}\n`;
                        });

                        // Create and download file
                        const blob = new Blob([csvContent], {
                            type: 'text/csv;charset=utf-8;'
                        });
                        const filename =
                            `Rekonsiliasi_${this.selectedAccount}_${this.selectedPeriod}.csv`;

                        const downloadLink = document.createElement('a');
                        downloadLink.href = window.URL.createObjectURL(blob);
                        downloadLink.download = filename;
                        downloadLink.click();

                        console.log('✅ Ekspor CSV berhasil (client-side)');
                        alert(
                            '📊 File CSV berhasil diunduh!\n\nCatatan: Untuk format Excel yang lebih baik, pastikan backend tersedia.'
                        );

                    } catch (error) {
                        console.error('❌ Error exporting reconciliation:', error);
                        alert(
                            `❌ Gagal mengekspor data: ${error.message}\n\nSilakan coba lagi atau hubungi administrator.`
                        );
                    } finally {
                        this.isLoading = false;
                    }
                },

                // Show reconciliation history
                async showReconciliationHistory() {
                    try {
                        console.log('📚 Memuat riwayat rekonsiliasi...');

                        // Try to load from backend first
                        try {
                            const response = await fetch('/keuangan/rekonsiliasi/history', {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]')
                                        .getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            });

                            if (response.ok) {
                                const historyData = await response.json();
                                this.displayHistoryModal(historyData);
                                return;
                            }
                        } catch (backendError) {
                            console.warn('Backend history tidak tersedia:', backendError);
                        }

                        // Fallback: Load from localStorage
                        const localHistory = localStorage.getItem('reconciliation_backup');
                        if (localHistory) {
                            const backup = JSON.parse(localHistory);
                            this.displayHistoryModal([{
                                id: 'backup',
                                created_at: backup.timestamp,
                                periode: backup.data.periode,
                                status: backup.data.status,
                                erp_balance: backup.data.erp_balance,
                                bank_balance: backup.data.bank_balance,
                                difference: backup.data.difference,
                                summary: backup.data.summary
                            }]);
                        } else {
                            alert(
                                '📚 Belum ada riwayat rekonsiliasi yang tersimpan.\n\nLakukan rekonsiliasi terlebih dahulu untuk melihat riwayat.'
                            );
                        }

                    } catch (error) {
                        console.error('❌ Error loading history:', error);
                        alert('❌ Gagal memuat riwayat rekonsiliasi.');
                    }
                },

                // Display history modal
                displayHistoryModal(historyData) {
                    let historyHtml = `
                        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-500 bg-opacity-75">
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[80vh] overflow-y-auto">
                                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Riwayat Rekonsiliasi Bank</h3>
                                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Periode</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Saldo ERP</th>
                                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Saldo Bank</th>
                                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Selisih</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dibuat</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    `;

                    if (historyData.length === 0) {
                        historyHtml += `
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada riwayat rekonsiliasi
                                </td>
                            </tr>
                        `;
                    } else {
                        historyData.forEach(item => {
                            const statusBadge = item.status === 'balanced' ?
                                '<span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full">Seimbang</span>' :
                                '<span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 rounded-full">Pending</span>';

                            historyHtml += `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">${item.periode || 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right">${this.formatCurrency(item.erp_balance || 0)}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right">${this.formatCurrency(item.bank_balance || 0)}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        <span class="${Math.abs(item.difference || 0) === 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}">${this.formatCurrency(Math.abs(item.difference || 0))}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">${new Date(item.created_at).toLocaleString('id-ID')}</td>
                                </tr>
                            `;
                        });
                    }

                    historyHtml += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                        <div class="flex justify-end">
                                            <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                                                Tutup
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Add modal to DOM
                    document.body.insertAdjacentHTML('beforeend', historyHtml);
                },

                // Reset form for new reconciliation
                resetForm() {
                    this.selectedAccount = '';
                    this.selectedPeriod = '';
                    this.uploadedFile = null;
                    this.erpTransactions = [];
                    this.bankTransactions = [];
                    this.erpBalance = 0;
                    this.realBankBalance = 0;
                    console.log('🔄 Form direset untuk rekonsiliasi baru');
                },

                updateMatching() {
                    console.log('Transaction matching updated');
                },

                // Export reconciliation data
                async exportReconciliation() {
                    if (!this.selectedAccount || !this.selectedPeriod) {
                        alert('Pilih rekening dan periode terlebih dahulu');
                        return;
                    }

                    try {
                        console.log('🗂️ Exporting reconciliation data...');

                        // Prepare export data
                        const exportData = {
                            rekening_id: this.selectedAccount,
                            periode: this.selectedPeriod,
                            balances: {
                                erp_balance: this.erpBalance,
                                bank_balance: this.realBankBalance || this.bankBalance,
                                difference: this.difference
                            },
                            transactions: {
                                erp: this.erpTransactions,
                                bank: this.bankTransactions,
                                unmatched_erp: this.unmatchedErp,
                                unmatched_bank: this.unmatchedBank
                            },
                            summary: {
                                total_erp_transactions: this.erpTransactions.length,
                                total_bank_transactions: this.bankTransactions.length,
                                matched_count: this.erpTransactions.filter(t => t.matched)
                                    .length,
                                reconciliation_percentage: this.erpTransactions.length > 0 ?
                                    Math.round((this.erpTransactions.filter(t => t.matched)
                                        .length / this
                                        .erpTransactions.length) * 100) : 0
                            }
                        };

                        // Try backend export first
                        const response = await fetch('/keuangan/rekonsiliasi/export', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(exportData)
                        });

                        if (response.ok) {
                            // Get CSV data from backend
                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;

                            // Get filename from response headers or generate one
                            const contentDisposition = response.headers.get(
                                'Content-Disposition');
                            let filename = 'rekonsiliasi_export.csv';
                            if (contentDisposition) {
                                const matches = contentDisposition.match(/filename="(.+)"/);
                                if (matches) filename = matches[1];
                            }

                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);

                            console.log('✅ Export successful via backend');

                        } else {
                            // Fallback to client-side CSV generation
                            console.log('⚠️ Backend export failed, using client-side fallback');
                            this.generateClientSideCSV(exportData);
                        }

                    } catch (error) {
                        console.error('❌ Export error:', error);
                        // Fallback to client-side CSV generation
                        this.generateClientSideCSV({
                            rekening_id: this.selectedAccount,
                            periode: this.selectedPeriod,
                            balances: {
                                erp_balance: this.erpBalance,
                                bank_balance: this.realBankBalance || this.bankBalance,
                                difference: this.difference
                            },
                            transactions: {
                                erp: this.erpTransactions,
                                bank: this.bankTransactions
                            }
                        });
                    }
                },

                // Generate CSV on client-side as fallback
                generateClientSideCSV(data) {
                    const csv = [];

                    // Header
                    csv.push(['LAPORAN REKONSILIASI BANK']);
                    csv.push(['']);
                    csv.push(['Periode', data.periode]);
                    csv.push(['Saldo ERP', this.formatCurrency(data.balances?.erp_balance ||
                        0)]);
                    csv.push(['Saldo Bank', this.formatCurrency(data.balances?.bank_balance ||
                        0)]);
                    csv.push(['Selisih', this.formatCurrency(Math.abs(data.balances
                        ?.difference || 0))]);
                    csv.push(['']);

                    // ERP Transactions
                    if (data.transactions?.erp?.length > 0) {
                        csv.push(['TRANSAKSI ERP']);
                        csv.push(['Tanggal', 'Referensi', 'Deskripsi', 'Jumlah', 'Status']);

                        data.transactions.erp.forEach(t => {
                            csv.push([
                                t.date,
                                t.reference,
                                t.description,
                                this.formatCurrency(t.amount),
                                t.matched ? 'Cocok' : 'Belum Cocok'
                            ]);
                        });
                        csv.push(['']);
                    }

                    // Bank Transactions
                    if (data.transactions?.bank?.length > 0) {
                        csv.push(['TRANSAKSI BANK']);
                        csv.push(['Tanggal', 'Referensi', 'Deskripsi', 'Jumlah', 'Status']);

                        data.transactions.bank.forEach(t => {
                            csv.push([
                                t.date,
                                t.reference,
                                t.description,
                                this.formatCurrency(t.amount),
                                t.matched ? 'Cocok' : 'Belum Cocok'
                            ]);
                        });
                    }

                    // Convert to CSV string
                    const csvContent = csv.map(row =>
                        row.map(cell => `"${cell}"`).join(',')
                    ).join('\n');

                    // Download
                    const blob = new Blob([csvContent], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `rekonsiliasi_${data.periode}_${new Date().getTime()}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);

                    console.log('📥 CSV exported successfully (client-side)');
                },

                // Show reconciliation history
                async showReconciliationHistory() {
                    try {
                        console.log('📋 Loading reconciliation history...');

                        // Prepare query parameters
                        const params = new URLSearchParams();
                        if (this.selectedAccount) params.append('rekening_id', this
                            .selectedAccount);
                        if (this.selectedPeriod) params.append('periode', this
                            .selectedPeriod);
                        params.append('per_page', '20');

                        const response = await fetch(
                            `/keuangan/rekonsiliasi/history/data?${params}`, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute(
                                        'content'),
                                    'Accept': 'application/json'
                                }
                            });

                        if (response.ok) {
                            const result = await response.json();

                            if (result.success) {
                                this.reconciliationHistory = result.data || [];
                                this.displayHistoryModal(result.data, result.pagination);
                            } else {
                                console.error('Failed to load history:', result.message);
                                this.showSampleHistory();
                            }
                        } else {
                            console.error('HTTP error:', response.status);
                            this.showSampleHistory();
                        }

                    } catch (error) {
                        console.error('❌ Error loading history:', error);
                        this.showSampleHistory();
                    }
                },

                // Display history modal
                displayHistoryModal(historyData, pagination) {
                    const modal = document.createElement('div');
                    modal.className =
                        'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                    modal.innerHTML = `
                        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    Riwayat Rekonsiliasi Bank
                                </h3>
                                <button onclick="this.closest('.fixed').remove()" 
                                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto">
                                <table class="min-w-full table-auto">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                ID Rekonsiliasi
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Rekening
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Periode
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Selisih
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Tanggal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        ${historyData.map(item => `
                                                                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                                                                        ${item.reconciliation_id || 'N/A'}
                                                                                                    </td>
                                                                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                                                                        ${item.rekening_bank?.nama_bank || 'N/A'}
                                                                                                    </td>
                                                                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                                                                        ${item.periode || 'N/A'}
                                                                                                    </td>
                                                                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                                                                        ${this.formatCurrency(Math.abs(item.difference || 0))}
                                                                                                    </td>
                                                                                                    <td class="px-4 py-2">
                                                                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${this.getStatusClass(item.status)}">
                                                                                                            ${this.getStatusText(item.status)}
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">
                                                                                                        ${item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : 'N/A'}
                                                                                                    </td>
                                                                                                </tr>
                                                                                            `).join('')}
                                    </tbody>
                                </table>
                                
                                ${pagination ? `
                                                                                        <div class="mt-4 flex justify-between items-center text-sm text-gray-500 dark:text-gray-400">
                                                                                            <span>Menampilkan ${pagination.current_page} dari ${pagination.last_page} halaman</span>
                                                                                            <span>Total: ${pagination.total} rekonsiliasi</span>
                                                                                        </div>
                                                                                    ` : ''}
                            </div>
                            
                            <div class="mt-4 text-center">
                                <button onclick="this.closest('.fixed').remove()" 
                                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    `;

                    document.body.appendChild(modal);
                },

                // Show sample history as fallback
                showSampleHistory() {
                    const sampleData = [{
                            reconciliation_id: 'REC-20240115-001',
                            rekening_bank: {
                                nama_bank: 'Bank Mandiri'
                            },
                            periode: '2024-01',
                            difference: -250000,
                            status: 'approved',
                            created_at: '2024-01-15T10:30:00Z'
                        },
                        {
                            reconciliation_id: 'REC-20240201-002',
                            rekening_bank: {
                                nama_bank: 'Bank BCA'
                            },
                            periode: '2024-02',
                            difference: 0,
                            status: 'balanced',
                            created_at: '2024-02-01T09:15:00Z'
                        }
                    ];

                    this.displayHistoryModal(sampleData, null);
                },

                // Helper methods for status display
                getStatusClass(status) {
                    const classes = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'balanced': 'bg-green-100 text-green-800',
                        'reviewed': 'bg-blue-100 text-blue-800',
                        'approved': 'bg-green-100 text-green-800',
                        'rejected': 'bg-red-100 text-red-800'
                    };
                    return classes[status] || 'bg-gray-100 text-gray-800';
                },

                getStatusText(status) {
                    const texts = {
                        'pending': 'Pending',
                        'balanced': 'Seimbang',
                        'reviewed': 'Reviewed',
                        'approved': 'Approved',
                        'rejected': 'Rejected'
                    };
                    return texts[status] || 'Unknown';
                }
            };
        }
    </script>
    </div>
    </div>

</x-app-layout>
