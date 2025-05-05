<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header Section with improved styling --}}
        <div
            class="mb-8 bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row">
                <!-- Supplier Info Section -->
                <div class="p-6 flex-grow">
                    <div class="flex items-start">
                        <div
                            class="h-16 w-16 flex-shrink-0 flex items-center justify-center rounded-lg bg-gradient-to-br from-primary-50 to-blue-50 dark:from-primary-900/30 dark:to-blue-900/30">
                            <svg class="h-10 w-10 text-primary-600 dark:text-primary-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-grow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $supplier->nama }}
                                    </h1>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Kode: <span
                                            class="font-medium text-gray-800 dark:text-gray-300">{{ $supplier->kode }}</span>
                                    </p>
                                </div>
                                @if ($supplier->is_active)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 ring-1 ring-inset ring-green-600/20 dark:ring-green-500/30">
                                        <span class="relative flex h-3 w-3 mr-2">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                        </span>
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 ring-1 ring-inset ring-red-600/20 dark:ring-red-500/30">
                                        <svg class="h-3 w-3 mr-2" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4 flex flex-wrap gap-3">
                                @if ($supplier->telepon)
                                    <div class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $supplier->telepon }}
                                    </div>
                                @endif

                                @if ($supplier->email)
                                    <div class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $supplier->email }}
                                    </div>
                                @endif

                                @if ($supplier->type_produksi)
                                    <div class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        {{ $supplier->type_produksi }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Section -->
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t md:border-t-0 md:border-l border-gray-200 dark:border-gray-700 flex flex-row md:flex-col justify-end gap-3 md:min-w-[200px]">
                    {{-- Edit Button --}}
                    <button
                        @click="window.dispatchEvent(new CustomEvent('open-supplier-modal', {detail: {mode: 'edit', supplier: {{ json_encode($supplier) }} }}))"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit
                    </button>

                    {{-- Back Button --}}
                    <a href="{{ route('master.supplier.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div class="mb-6" x-data="{ activeTab: 'details' }">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                    <button @click="activeTab = 'details'"
                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'details', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'details' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 -mt-0.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Detail Supplier
                    </button>
                    <button @click="activeTab = 'transactions'"
                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'transactions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'transactions' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 -mt-0.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Riwayat Transaksi
                    </button>
                    <button @click="activeTab = 'products'"
                        :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'products', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'products' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2 -mt-0.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Produk Supplier
                    </button>
                </nav>
            </div>

            {{-- Tab Content - Supplier Details --}}
            <div x-show="activeTab === 'details'" class="mt-6 animate-fade-in">
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            {{-- Contact Information Card --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                                <div
                                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                    <h3
                                        class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Informasi Kontak
                                    </h3>
                                </div>
                                <div class="px-4 py-5 sm:p-6 space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Kontak
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $supplier->nama_kontak ?: 'Tidak ada' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $supplier->telepon ?: 'Tidak ada' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $supplier->email ?: 'Tidak ada' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No. HP</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            {{ $supplier->no_hp ?: 'Tidak ada' }}
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            {{-- Address Card --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                                <div
                                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                    <h3
                                        class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Alamat
                                    </h3>
                                </div>
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 text-gray-400 dark:text-gray-500 mt-0.5 mr-3 flex-shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ $supplier->alamat ?: 'Tidak ada alamat yang tersimpan.' }}
                                        </p>
                                    </div>

                                    <!-- Optional: Map placeholder if you want to add a map in the future -->
                                    <div
                                        class="mt-4 bg-gray-100 dark:bg-gray-700 rounded-lg h-32 flex items-center justify-center">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Peta lokasi belum
                                            tersedia</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Business Details Card --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                                <div
                                    class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                                    <h3
                                        class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Detail Bisnis
                                    </h3>
                                </div>
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="space-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe
                                                Produksi</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                                {{ $supplier->type_produksi ?: 'Tidak ada' }}
                                            </dd>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status
                                                Supplier</dt>
                                            <dd class="mt-1">
                                                @if ($supplier->is_active)
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-md bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-md bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan
                                            </dt>
                                            <dd
                                                class="mt-2 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md border border-gray-200 dark:border-gray-700 max-h-32 overflow-y-auto">
                                                {!! nl2br(e($supplier->catatan ?: 'Tidak ada catatan.')) !!}
                                            </dd>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Content - Transaction History --}}
            <div x-show="activeTab === 'transactions'" class="mt-6 animate-fade-in" x-cloak>
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div
                        class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Riwayat Transaksi
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="flex justify-center items-center mb-4">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum Ada Transaksi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                            Data transaksi pembelian dari supplier ini akan muncul di sini ketika tersedia.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tab Content - Products --}}
            <div x-show="activeTab === 'products'" class="mt-6 animate-fade-in" x-cloak>
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div
                        class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Produk Supplier
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="flex justify-center items-center mb-4">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum Ada Produk</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                            Produk yang disediakan oleh supplier ini akan muncul di sini ketika tersedia.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modal Supplier Component for Edit --}}
    <x-modal-supplier />

    {{-- Add Alpine.js animation --}}
    <style>
        [x-cloak] {
            display: none !important;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</x-app-layout>
