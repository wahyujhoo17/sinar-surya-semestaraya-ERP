<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header Section with improved styling --}}
        <div
            class="mb-8 bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row">
                <!-- Customer Info Section -->
                <div class="p-6 flex-grow">
                    <div class="flex items-start">
                        <div
                            class="h-16 w-16 flex-shrink-0 flex items-center justify-center rounded-lg bg-gradient-to-br from-primary-50 to-blue-50 dark:from-primary-900/30 dark:to-blue-900/30">
                            <svg class="h-10 w-10 text-primary-600 dark:text-primary-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="ml-5 flex-grow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $customer->nama }}
                                    </h1>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Kode: <span
                                            class="font-medium text-gray-800 dark:text-gray-300">{{ $customer->kode }}</span>
                                    </p>
                                </div>
                                @if ($customer->is_active)
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
                                @if ($customer->telepon)
                                    <div class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $customer->telepon }}
                                    </div>
                                @endif

                                @if ($customer->email)
                                    <div class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $customer->email }}
                                    </div>
                                @endif

                                @if ($customer->company)
                                    <div class="inline-flex items-center text-sm text-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $customer->company }}
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
                        @click="window.dispatchEvent(new CustomEvent('open-pelanggan-modal', {detail: {mode: 'edit', customer: {{ json_encode($customer) }} }}))"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit
                    </button>

                    {{-- Back Button --}}
                    <a href="{{ route('master.pelanggan.index') }}"
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
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Detail Pelanggan
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
                </nav>
            </div>

            {{-- Tab Content - Customer Details --}}
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
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Informasi Kontak
                                    </h3>
                                </div>
                                <div class="px-4 py-5 sm:p-6 space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $customer->telepon ?: 'Tidak ada' }}
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
                                            {{ $customer->email ?: 'Tidak ada' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            {{ $customer->tipe ?: 'Tidak ada' }}
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
                                            {{ $customer->jalan ? $customer->jalan . ', ' : '' }}
                                            {{ $customer->kota ? $customer->kota . ', ' : '' }}
                                            {{ $customer->provinsi ? $customer->provinsi : '' }}
                                            {{ $customer->kode_pos ? ' ' . $customer->kode_pos : '' }}
                                            {{ $customer->negara ? ', ' . $customer->negara : '' }}
                                            @if (!$customer->jalan && !$customer->kota && !$customer->provinsi && !$customer->kode_pos && !$customer->negara)
                                                Tidak ada alamat yang tersimpan.
                                            @endif
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kota</dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                                {{ $customer->kota ?: 'Tidak ada' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Provinsi
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                                {{ $customer->provinsi ?: 'Tidak ada' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Pos
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                                {{ $customer->kode_pos ?: 'Tidak ada' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Negara
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                                {{ $customer->negara ?: 'Tidak ada' }}
                                            </dd>
                                        </div>
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
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Perusahaan
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                {{ $customer->company ?: 'Tidak ada' }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NPWP
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                {{ $customer->npwp ?: 'Tidak ada' }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sales
                                                Representative
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                @if ($customer->sales_id && $customer->sales)
                                                    {{ $customer->sales->name }}
                                                @elseif ($customer->sales_name)
                                                    {{ $customer->sales_name }}
                                                @else
                                                    Tidak ada
                                                @endif
                                            </dd>
                                        </div>

                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Kontak
                                                Person
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                {{ $customer->kontak_person ?: 'Tidak ada' }}
                                            </dd>
                                        </div>

                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No HP
                                                Kontak
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 dark:text-white flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500 mr-2"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                {{ $customer->no_hp_kontak ?: 'Tidak ada' }}
                                            </dd>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status
                                                Pelanggan</dt>
                                            <dd class="mt-1">
                                                @if ($customer->is_active)
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
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat
                                                Pengiriman
                                            </dt>
                                            <dd
                                                class="mt-2 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md border border-gray-200 dark:border-gray-700 max-h-32 overflow-y-auto">
                                                {!! nl2br(e($customer->alamat_pengiriman ?: 'Tidak ada alamat pengiriman.')) !!}
                                            </dd>
                                        </div>

                                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan
                                            </dt>
                                            <dd
                                                class="mt-2 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md border border-gray-200 dark:border-gray-700 max-h-32 overflow-y-auto">
                                                {!! nl2br(e($customer->catatan ?: 'Tidak ada catatan.')) !!}
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
                <!-- Quotations Table -->
                <div
                    class="mb-8 bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div
                        class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Quotation
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($customer->quotations->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                No. Quotation</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Tanggal</th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Total</th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Status</th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($customer->quotations as $quotation)
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $quotation->nomor }}</td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($quotation->tanggal_quotation)->format('d/m/Y') }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                                    Rp
                                                    {{ number_format($quotation->total ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                        {{ $quotation->status ?? 'Draft' }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium">
                                                    <a href="{{ route('penjualan.quotation.show', $quotation->id) }}"
                                                        class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <div class="flex justify-center items-center mb-4">
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum Ada
                                    Quotation</h3>
                                <p class="text-sm max-w-md mx-auto">
                                    Pelanggan ini belum memiliki riwayat quotation.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sales Orders Table -->
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
                            Sales Order
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($customer->salesOrders->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                No. SO</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Tanggal</th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Total</th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Total Pembayaran</th>
                                            <th
                                                class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Status</th>
                                            <th
                                                class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($customer->salesOrders as $salesOrder)
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $salesOrder->nomor }}</td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($salesOrder->tanggal_so)->format('d/m/Y') }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                                    Rp
                                                    {{ number_format($salesOrder->total ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                                    @if ($salesOrder->status_pembayaran == 'lunas')
                                                        Rp
                                                        {{ number_format($salesOrder->total ?? 0, 0, ',', '.') }}
                                                    @else
                                                        Rp
                                                        {{ number_format($salesOrder->total_pembayaran ?? 0, 0, ',', '.') }}
                                                    @endif

                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                        {{ $salesOrder->status_pembayaran ?? 'Open' }}
                                                    </span>
                                                </td>

                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-right font-medium">
                                                    <a href="{{ route('penjualan.sales-order.show', $salesOrder->id) }}"
                                                        class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">Detail</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Ringkasan Total Pembelian -->
                            <div
                                class="mt-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <h4
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Ringkasan Total Pembelian
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-md p-4 border border-gray-200 dark:border-gray-700">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Nilai Pesanan
                                        </p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Rp
                                            {{ number_format($customer->salesOrders->sum('total') ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-md p-4 border border-gray-200 dark:border-gray-700">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Pembayaran</p>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Rp
                                            {{ number_format($customer->salesOrders->sum('total_pembayaran') ?? 0, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="mt-4 bg-white dark:bg-gray-800 rounded-md p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Status Pembayaran
                                            </p>
                                            <div class="flex space-x-2 mt-1">
                                                @php
                                                    $totalLunas = $customer->salesOrders
                                                        ->where('status_pembayaran', 'lunas')
                                                        ->count();
                                                    $totalPending = $customer->salesOrders
                                                        ->whereNotIn('status_pembayaran', ['lunas'])
                                                        ->count();
                                                @endphp
                                                @if ($totalLunas > 0)
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-md bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                        {{ $totalLunas }} Lunas
                                                    </span>
                                                @endif
                                                @if ($totalPending > 0)
                                                    <span
                                                        class="px-2 py-1 text-xs font-medium rounded-md bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                        {{ $totalPending }} Belum Lunas
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Persentase
                                                Pembayaran</p>
                                            @php
                                                $totalNilai = $customer->salesOrders->sum('total');
                                                $totalBayar = $customer->salesOrders->sum('total_pembayaran');
                                                $persentase = $totalNilai > 0 ? ($totalBayar / $totalNilai) * 100 : 0;
                                            @endphp
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($persentase, 1) }}%
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <div class="flex justify-center items-center mb-4">
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum Ada Sales
                                    Order</h3>
                                <p class="text-sm max-w-md mx-auto">
                                    Pelanggan ini belum memiliki riwayat sales order.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modal Pelanggan Component for Edit --}}
    <x-modal-pelanggan />

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
