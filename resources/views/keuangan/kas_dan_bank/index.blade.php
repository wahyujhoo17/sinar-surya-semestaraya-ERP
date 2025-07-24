<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Manajemen Kas & Bank</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Kelola kas dan rekening bank untuk operasional keuangan PT Sinar Surya Semestaraya
                    </p>
                </div>
                {{-- Quick Transaction Button --}}
                <div class="mt-4 sm:mt-0">
                    <button type="button"
                        onclick="console.log('Button clicked, dispatching event'); window.dispatchEvent(new CustomEvent('open-transaksi-modal', {detail: {}}));"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="mr-2 -ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Transaksi Cepat
                    </button>
                </div>
            </div>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 mb-8">
            {{-- Total Kas Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Saldo Kas
                            </dt>
                            <dd class="mt-2">
                                <div class="flex items-baseline text-xl font-semibold text-gray-900 dark:text-white">
                                    <span class="truncate">Rp {{ number_format($totalKas, 0, ',', '.') }}</span>
                                    <span class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">IDR</span>
                                </div>
                            </dd>
                        </div>
                        <div class="flex-shrink-0 rounded-lg bg-green-100 dark:bg-green-900/30 p-2">
                            <svg class="h-4 w-4 text-green-500 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Rekening Bank Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Saldo Bank
                            </dt>
                            <dd class="mt-2">
                                <div class="flex items-baseline text-xl font-semibold text-gray-900 dark:text-white">
                                    <span class="truncate">Rp {{ number_format($totalRekening, 0, ',', '.') }}</span>
                                    <span class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">IDR</span>
                                </div>
                            </dd>
                        </div>
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-2">
                            <svg class="h-4 w-4 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jumlah Kas Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Jumlah Kas</dt>
                            <dd class="mt-2">
                                <div class="flex items-baseline text-xl font-semibold text-gray-900 dark:text-white">
                                    <span>{{ $kasAll->count() }}</span>
                                    <span class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">kas</span>
                                </div>
                            </dd>
                        </div>
                        <div class="flex-shrink-0 rounded-lg bg-amber-100 dark:bg-amber-900/30 p-2">
                            <svg class="h-4 w-4 text-amber-500 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jumlah Rekening Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Jumlah Rekening
                            </dt>
                            <dd class="mt-2">
                                <div class="flex items-baseline text-xl font-semibold text-gray-900 dark:text-white">
                                    <span>{{ $rekeningAll->count() }}</span>
                                    <span
                                        class="ml-1 text-xs font-normal text-gray-500 dark:text-gray-400">rekening</span>
                                </div>
                            </dd>
                        </div>
                        <div class="flex-shrink-0 rounded-lg bg-purple-100 dark:bg-purple-900/30 p-2">
                            <svg class="h-4 w-4 text-purple-500 dark:text-purple-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Area: Kas & Rekening Bank in Tabs --}}
        <div x-data="{ activeTab: 'kas' }">
            {{-- Tabs Navigation --}}
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-8 overflow-x-auto" aria-label="Tabs">
                    <button @click="activeTab = 'kas'"
                        class="py-4 px-1 inline-flex items-center font-medium text-sm border-b-2 whitespace-nowrap"
                        :class="activeTab === 'kas' ? 'border-primary-500 text-primary-600 dark:text-primary-400' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                        Kas & Uang Tunai
                    </button>
                    <button @click="activeTab = 'rekening'"
                        class="py-4 px-1 inline-flex items-center font-medium text-sm border-b-2 whitespace-nowrap"
                        :class="activeTab === 'rekening' ? 'border-primary-500 text-primary-600 dark:text-primary-400' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                        Rekening Bank
                    </button>
                    <button @click="activeTab = 'transaksi'"
                        class="py-4 px-1 inline-flex items-center font-medium text-sm border-b-2 whitespace-nowrap"
                        :class="activeTab === 'transaksi' ? 'border-primary-500 text-primary-600 dark:text-primary-400' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                        Riwayat Transaksi Terbaru
                    </button>
                    <button @click="activeTab = 'project'"
                        class="py-4 px-1 inline-flex items-center font-medium text-sm border-b-2 whitespace-nowrap"
                        :class="activeTab === 'project' ? 'border-primary-500 text-primary-600 dark:text-primary-400' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'">
                        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0c-.027.113-.064.224-.109.332l-.457.916a1.125 1.125 0 01-1.007.622H9.478a1.125 1.125 0 01-1.007-.622l-.457-.916c-.045-.108-.082-.219-.109-.332m7.5 0a48.394 48.394 0 00-7.5 0" />
                        </svg>
                        Manajemen Project
                    </button>
                </nav>
            </div>

            {{-- Tab Content - Kas --}}
            <div x-show="activeTab === 'kas'" class="mt-6" x-transition:enter="transition-opacity duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Kas</h3>
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-kas-modal'))"
                            data-open-kas-modal
                            class="mt-2 sm:mt-0 inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-primary-700 dark:hover:bg-primary-600">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Kas Baru
                        </button>
                    </div>

                    @if ($kasAll->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Belum ada data kas
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambahkan kas baru dengan
                                mengklik tombol di atas</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($kasAll as $kas)
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-200/50 dark:border-gray-700/50 overflow-hidden group cursor-pointer">
                                    {{-- Clickable Card Content --}}
                                    <a href="{{ route('keuangan.kas-dan-bank.kas', $kas->id) }}" class="block">
                                        <div class="p-5">
                                            <div class="flex justify-between items-center mb-4">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 rounded-lg bg-green-100 dark:bg-green-900/30 p-2.5 group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition-colors duration-200">
                                                        <svg class="h-5 w-5 text-green-500 dark:text-green-400"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125-1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                                        </svg>
                                                    </div>
                                                    <h4
                                                        class="text-base font-semibold text-gray-900 dark:text-white ml-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">
                                                        {{ $kas->nama }}</h4>
                                                </div>
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $kas->is_aktif ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                                    {{ $kas->is_aktif ? 'Aktif' : 'Non-aktif' }}
                                                </span>
                                            </div>

                                            <div class="flex flex-col space-y-2 mb-4">
                                                <div class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                                    {{ $kas->deskripsi ?: 'Tidak ada deskripsi' }}
                                                </div>
                                                <div class="flex items-baseline">
                                                    <span
                                                        class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">Rp
                                                        {{ number_format($kas->saldo, 0, ',', '.') }}</span>
                                                    <span
                                                        class="ml-1 text-xs text-gray-500 dark:text-gray-400">saldo</span>
                                                </div>
                                            </div>

                                            {{-- Hover indicator --}}
                                            <div
                                                class="flex items-center text-sm text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                </svg>
                                                Klik untuk melihat detail transaksi
                                            </div>
                                        </div>
                                    </a>

                                    {{-- Action Buttons (separate from clickable area) --}}
                                    <div class="px-5 pb-4">
                                        <div
                                            class="flex justify-end space-x-2 border-t dark:border-gray-700 pt-4 mt-2">
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-transaksi-modal', {detail: {account_type: 'kas', account_id: {{ $kas->id }}}}));"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                Transaksi
                                            </button>
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-transaksi-project-modal', {detail: {kas_id: {{ $kas->id }}, sumber_dana_type: 'kas'}}));"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-purple-700 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:hover:bg-purple-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0c-.027.113-.064.224-.109.332l-.457.916a1.125 1.125 0 01-1.007.622H9.478a1.125 1.125 0 01-1.007-.622l-.457-.916c-.045-.108-.082-.219-.109-.332m7.5 0a48.394 48.394 0 00-7.5 0" />
                                                </svg>
                                                Project
                                            </button>
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-kas-modal', {detail: {mode: 'edit', kas: {{ Js::from($kas) }}}}));"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button onclick="event.preventDefault(); event.stopPropagation();"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tab Content - Rekening Bank --}}
            <div x-show="activeTab === 'rekening'" class="mt-6"
                x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Daftar Rekening Bank</h3>
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-rekening-modal'))"
                            data-open-rekening-modal
                            class="mt-2 sm:mt-0 inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-primary-700 dark:hover:bg-primary-600">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Rekening Baru
                        </button>
                    </div>

                    @if ($rekeningAll->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Belum ada data
                                rekening bank</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambahkan rekening bank baru
                                dengan mengklik tombol di atas</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($rekeningAll as $rekening)
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-200/50 dark:border-gray-700/50 overflow-hidden group cursor-pointer">
                                    {{-- Clickable Card Content --}}
                                    <a href="{{ route('keuangan.kas-dan-bank.rekening', $rekening->id) }}"
                                        class="block">
                                        <div class="p-5">
                                            <div class="flex justify-between items-center mb-4">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-12 w-12 rounded-lg p-2 bg-blue-100 dark:bg-blue-900/30 group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors duration-200">
                                                        @php
                                                            $bankLogos = [
                                                                'BCA' =>
                                                                    'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png',
                                                                'BNI' =>
                                                                    'https://www.bni.co.id/Portals/1/BNI/Images/logo-bni-new.png',
                                                                'BRI' =>
                                                                    'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/2560px-BANK_BRI_logo.svg.png',
                                                                'Mandiri' =>
                                                                    'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/2560px-Bank_Mandiri_logo_2016.svg.png',
                                                                'default' =>
                                                                    'https://cdn-icons-png.flaticon.com/512/2830/2830289.png',
                                                            ];
                                                            $bankLogo = $bankLogos['default'];
                                                            foreach ($bankLogos as $bankName => $logo) {
                                                                if (
                                                                    stripos($rekening->nama_bank, $bankName) !== false
                                                                ) {
                                                                    $bankLogo = $logo;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp
                                                        <img src="{{ $bankLogo }}"
                                                            alt="{{ $rekening->nama_bank }}"
                                                            class="h-full w-full object-contain">
                                                    </div>
                                                    <div class="ml-3">
                                                        <h4
                                                            class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">
                                                            {{ $rekening->nama_bank }}
                                                        </h4>
                                                        <p
                                                            class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors duration-200">
                                                            {{ $rekening->atas_nama }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $rekening->is_aktif ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                                    {{ $rekening->is_aktif ? 'Aktif' : 'Non-aktif' }}
                                                </span>
                                            </div>

                                            <div class="flex flex-col space-y-3 mb-4">
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-500 dark:text-gray-400">No. Rekening</span>
                                                    <span
                                                        class="font-mono font-medium text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">{{ $rekening->nomor_rekening }}</span>
                                                </div>
                                                <div class="flex justify-between text-sm">
                                                    <span class="text-gray-500 dark:text-gray-400">Cabang</span>
                                                    <span
                                                        class="text-gray-900 dark:text-white">{{ $rekening->cabang ?: '-' }}</span>
                                                </div>
                                                <div class="flex items-baseline">
                                                    <span
                                                        class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">Rp
                                                        {{ number_format($rekening->saldo, 0, ',', '.') }}</span>
                                                    <span
                                                        class="ml-1 text-xs text-gray-500 dark:text-gray-400">saldo</span>
                                                </div>
                                            </div>

                                            {{-- Hover indicator --}}
                                            <div
                                                class="flex items-center text-sm text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                </svg>
                                                Klik untuk melihat detail transaksi
                                            </div>
                                        </div>
                                    </a>

                                    {{-- Action Buttons (separate from clickable area) --}}
                                    <div class="px-5 pb-4">
                                        <div
                                            class="flex justify-end space-x-2 border-t dark:border-gray-700 pt-4 mt-2">
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-transaksi-modal', {detail: {account_type: 'bank', account_id: {{ $rekening->id }}}}));"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                Transaksi
                                            </button>
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-transaksi-project-modal', {detail: {rekening_bank_id: {{ $rekening->id }}, sumber_dana_type: 'bank'}}));"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-purple-700 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:hover:bg-purple-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0c-.027.113-.064.224-.109.332l-.457.916a1.125 1.125 0 01-1.007.622H9.478a1.125 1.125 0 01-1.007-.622l-.457-.916c-.045-.108-.082-.219-.109-.332m7.5 0a48.394 48.394 0 00-7.5 0" />
                                                </svg>
                                                Project
                                            </button>
                                            <button type="button"
                                                onclick="event.preventDefault(); event.stopPropagation(); window.dispatchEvent(new CustomEvent('open-rekening-modal', {detail: {mode: 'edit', rekening: {{ Js::from($rekening) }}}}));"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button onclick="event.preventDefault(); event.stopPropagation();"
                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tab Content - Riwayat Transaksi --}}
            <div x-show="activeTab === 'transaksi'" class="mt-6"
                x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Transaksi Kas --}}
                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125-1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                </svg>
                                Transaksi Kas Terbaru
                            </h3>
                        </div>
                        <div class="p-6">
                            @if ($transaksiKas->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125-1.125 0 0113.5 7.125v-1.5a3.375-3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300">Belum ada
                                        transaksi kas</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Transaksi kas akan muncul
                                        di sini</p>
                                </div>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($transaksiKas as $trx)
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="flex-shrink-0 rounded-full p-2 {{ $trx->jenis == 'masuk' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                                    @if ($trx->jenis == 'masuk')
                                                        <svg class="h-5 w-5 {{ $trx->jenis == 'masuk' ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 {{ $trx->jenis == 'masuk' ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $trx->keterangan }}
                                                    </p>
                                                    <div class="flex mt-1">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $trx->kas->nama }} •
                                                            {{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="inline-flex items-center text-sm font-medium {{ $trx->jenis == 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $trx->jenis == 'masuk' ? '+' : '-' }} Rp
                                                    {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    {{-- Transaksi Bank --}}
                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <h3 class="text-base font-semibold text-gray-800 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500 dark:text-blue-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                </svg>
                                Transaksi Bank Terbaru
                            </h3>
                        </div>
                        <div class="p-6">
                            @if ($transaksiBank->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125-1.125 0 0113.5 7.125v-1.5a3.375-3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300">Belum ada
                                        transaksi bank</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Transaksi bank akan muncul
                                        di sini</p>
                                </div>
                            @else
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($transaksiBank as $trx)
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div
                                                    class="flex-shrink-0 rounded-full p-2 {{ $trx->jenis == 'masuk' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                                    @if ($trx->jenis == 'masuk')
                                                        <svg class="h-5 w-5 {{ $trx->jenis == 'masuk' ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 {{ $trx->jenis == 'masuk' ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $trx->keterangan }}
                                                    </p>
                                                    <div class="flex mt-1">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $trx->rekening->nama_bank }} •
                                                            {{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="inline-flex items-center text-sm font-medium {{ $trx->jenis == 'masuk' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                    {{ $trx->jenis == 'masuk' ? '+' : '-' }} Rp
                                                    {{ number_format($trx->jumlah, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Content - Manajemen Project --}}
            <div x-show="activeTab === 'project'" class="mt-6" x-transition:enter="transition-opacity duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Manajemen Project</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola alokasi dana dan keuangan
                                per project</p>
                        </div>
                        <div class="mt-2 sm:mt-0 flex space-x-2">
                            <button type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-project-modal', {detail: {}}))"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Tambah Project
                            </button>
                            <button type="button"
                                onclick="window.dispatchEvent(new CustomEvent('open-transaksi-project-modal', {detail: {}}))"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                </svg>
                                Transaksi Project
                            </button>
                        </div>
                    </div>

                    {{-- Project Overview Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm">Total Project</p>
                                    <p class="text-2xl font-bold">{{ $projects->count() ?? 0 }}</p>
                                </div>
                                <div class="bg-blue-400 bg-opacity-50 rounded-full p-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm">Total Budget</p>
                                    <p class="text-xl font-bold">Rp
                                        {{ number_format($totalBudget ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-green-400 bg-opacity-50 rounded-full p-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm">Total Alokasi</p>
                                    <p class="text-xl font-bold">Rp
                                        {{ number_format($totalAlokasi ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-yellow-400 bg-opacity-50 rounded-full p-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                            clip-rule="evenodd"></path>
                                        <path fill-rule="evenodd"
                                            d="M9.707 3.293a1 1 0 010 1.414L6.414 8H15a1 1 0 110 2H6.414l3.293 3.293a1 1 0 01-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm">Sisa Budget</p>
                                    <p class="text-xl font-bold">Rp {{ number_format($sisaBudget ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="bg-purple-400 bg-opacity-50 rounded-full p-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Project List --}}
                    @if (empty($projects) || (is_countable($projects) && count($projects) == 0))
                        <div class="flex flex-col items-center justify-center py-12">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3A2.25 2.25 0 008.25 5.25v.654m7.5 0c-.027.113-.064.224-.109.332l-.457.916a1.125 1.125 0 01-1.007.622H9.478a1.125 1.125 0 01-1.007-.622l-.457-.916c-.045-.108-.082-.219-.109-.332m7.5 0a48.394 48.394 0 00-7.5 0" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">Belum ada project</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tambahkan project baru dengan
                                mengklik tombol di atas</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($projects ?? [] as $project)
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 border border-gray-200/50 dark:border-gray-700/50 overflow-hidden">
                                    <div class="p-6">
                                        {{-- Project Header --}}
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $project->nama }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $project->kode }}</p>
                                            </div>
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full
                                                @if ($project->status == 'aktif') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @elseif($project->status == 'selesai') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </div>

                                        {{-- Project Details --}}
                                        <div class="space-y-3 mb-4">
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500 dark:text-gray-400">Budget</span>
                                                <span class="font-medium text-gray-900 dark:text-white">Rp
                                                    {{ number_format($project->budget, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500 dark:text-gray-400">Alokasi</span>
                                                <span class="font-medium text-green-600 dark:text-green-400">Rp
                                                    {{ number_format($project->total_alokasi, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500 dark:text-gray-400">Penggunaan</span>
                                                <span class="font-medium text-red-600 dark:text-red-400">Rp
                                                    {{ number_format($project->total_penggunaan, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-500 dark:text-gray-400">Saldo</span>
                                                <span class="font-medium text-gray-900 dark:text-white">Rp
                                                    {{ number_format($project->saldo, 0, ',', '.') }}</span>
                                            </div>
                                        </div>

                                        {{-- Progress Bar --}}
                                        <div class="mb-4">
                                            <div class="flex justify-between text-sm mb-1">
                                                <span class="text-gray-500 dark:text-gray-400">Progress Budget</span>
                                                <span
                                                    class="text-gray-700 dark:text-gray-300">{{ $project->persentase_penggunaan }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-primary-600 h-2 rounded-full"
                                                    style="width: {{ min($project->persentase_penggunaan, 100) }}%">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Action Buttons --}}
                                        <div class="flex justify-between space-x-2">
                                            <button type="button"
                                                onclick="window.dispatchEvent(new CustomEvent('open-detail-transaksi-project', {detail: {project: {{ Js::from($project) }}}}))"
                                                class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-xs font-medium rounded text-purple-700 bg-purple-100 hover:bg-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:hover:bg-purple-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                                Detail
                                            </button>
                                            <button type="button"
                                                onclick="window.dispatchEvent(new CustomEvent('open-transaksi-project-modal', {detail: {project_id: {{ $project->id }}}}))"
                                                class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                                <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                                </svg>
                                                Transaksi
                                            </button>
                                            <button type="button"
                                                onclick="window.dispatchEvent(new CustomEvent('open-project-modal', {detail: {mode: 'edit', project: {{ Js::from($project) }}}}))"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </button>
                                            <button onclick="event.preventDefault(); event.stopPropagation();"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modal Components --}}
    @include('keuangan.kas_dan_bank.modal-kas')
    @include('keuangan.kas_dan_bank.modal-rekening')
    @include('keuangan.kas_dan_bank.modal-transaksi')
    @include('keuangan.kas_dan_bank.modal-project')
    @include('keuangan.kas_dan_bank.modal-transaksi-project')
    @include('keuangan.kas_dan_bank.modal-detail-transaksi-project')
    
    {{-- Toast Notifications --}}
    @include('components.toast')

    {{-- Initialize Transaction Modal --}}
    <script>
        // console.log('Script section loaded');

        // Listen for custom events and log them
        document.addEventListener('DOMContentLoaded', () => {
            // console.log('DOM loaded, setting up event listeners');

            // Listen for transaction modal events
            window.addEventListener('open-transaksi-modal', (event) => {
                // console.log('Global open-transaksi-modal event received:', event.detail);
            });
        });
    </script>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // console.log('DOM loaded for kas dan bank page');
            });
        </script>
    @endpush
</x-app-layout>
