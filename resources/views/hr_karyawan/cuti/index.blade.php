<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Manajemen Cuti & Izin') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Kelola pengajuan cuti dan izin karyawan
                </p>
            </div>
        </div>
    </x-slot>

    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border-color: #d1d5db !important;
            border-radius: 8px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px !important;
            padding-left: 12px !important;
            color: #111827 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            top: 1px !important;
            right: 1px !important;
        }

        .select2-dropdown {
            border-radius: 8px !important;
            border-color: #d1d5db !important;
        }

        .select2-search--dropdown .select2-search__field {
            border-radius: 6px !important;
            border-color: #d1d5db !important;
        }

        .select2-results__option--highlighted {
            background-color: #3b82f6 !important;
        }

        /* Dark mode styles */
        .dark .select2-container--default .select2-selection--single {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f9fafb !important;
        }

        .dark .select2-dropdown {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
        }

        .dark .select2-results__option {
            color: #f9fafb !important;
            background-color: #374151 !important;
        }

        .dark .select2-results__option--highlighted {
            background-color: #3b82f6 !important;
        }

        .dark .select2-search--dropdown .select2-search__field {
            background-color: #374151 !important;
            border-color: #4b5563 !important;
            color: #f9fafb !important;
        }
    </style>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6" x-data="cutiStats">
                {{-- Total Pengajuan --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                                <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0-1.125-.504-1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total Pengajuan</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"
                                        x-text="stats.total_pengajuan">{{ $stats['total_pengajuan'] }}</p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">cuti</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pending --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 p-3.5">
                                <svg class="h-7 w-7 text-yellow-500 dark:text-yellow-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Menunggu Persetujuan</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"
                                        x-text="stats.pending">{{ $stats['pending'] }}</p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">cuti</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Disetujui --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-green-100 dark:bg-green-900/30 p-3.5">
                                <svg class="h-7 w-7 text-green-500 dark:text-green-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Disetujui</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"
                                        x-text="stats.disetujui">{{ $stats['disetujui'] }}</p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">cuti</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ditolak --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-red-100 dark:bg-red-900/30 p-3.5">
                                <svg class="h-7 w-7 text-red-500 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Ditolak</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"
                                        x-text="stats.ditolak">{{ $stats['ditolak'] }}</p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">cuti</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sedang Cuti --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-lg bg-purple-100 dark:bg-purple-900/30 p-3.5">
                                <svg class="h-7 w-7 text-purple-500 dark:text-purple-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Sedang Cuti</p>
                                <div class="mt-1 flex items-baseline">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white"
                                        x-text="stats.sedang_cuti">{{ $stats['sedang_cuti'] }}</p>
                                    <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter dan Actions Panel --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 mb-8"
                x-data="cutiFilters">
                {{-- Filter Header --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filter & Pencarian</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Filter data cuti berdasarkan
                                    kriteria tertentu</p>
                            </div>
                        </div>
                        {{-- Mobile Filter Toggle --}}
                        <button @click="showFilters = !showFilters"
                            class="lg:hidden inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                            <svg x-show="!showFilters" class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                            <svg x-show="showFilters" class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>

                {{-- Filter Content --}}
                <div class="p-6" x-show="showFilters" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2">

                    {{-- Primary Filters Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                        {{-- Periode Cuti --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="inline h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                                </svg>
                                Periode Cuti
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    {{-- <label class="block text-xs text-gray-400 dark:text-gray-500 mb-1 ml-1">Dari</label> --}}
                                    <input type="date" x-model="filters.tanggal_mulai"
                                        class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:border-blue-400 dark:focus:ring-blue-800 transition-all duration-200"
                                        placeholder="Tanggal Mulai">
                                </div>
                                <div>
                                    {{-- <label class="block text-xs text-gray-400 dark:text-gray-500 mb-1 ml-1">Sampai</label> --}}
                                    <input type="date" x-model="filters.tanggal_akhir"
                                        class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:border-blue-400 dark:focus:ring-blue-800 transition-all duration-200"
                                        placeholder="Tanggal Akhir">
                                </div>
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="inline h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Status Persetujuan
                            </label>
                            <select x-model="filters.status"
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:border-blue-400 dark:focus:ring-blue-800 transition-all duration-200">
                                <option value="">Semua Status</option>
                                <option value="diajukan">Menunggu Persetujuan</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>

                        {{-- Jenis Cuti Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="inline h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                </svg>
                                Jenis Cuti
                            </label>
                            <select x-model="filters.jenis_cuti"
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:border-blue-400 dark:focus:ring-blue-800 transition-all duration-200">
                                <option value="">Semua Jenis</option>
                                <option value="tahunan">Cuti Tahunan</option>
                                <option value="sakit">Sakit</option>
                                <option value="melahirkan">Melahirkan</option>
                                <option value="penting">Keperluan Penting</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    {{-- Secondary Filters Row --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                        {{-- Department Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="inline h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 21h19.5m-18-18v18m2.25-18v18m13.5-18v18m2.25-18v18M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h18v18" />
                                </svg>
                                Departemen
                            </label>
                            <select x-model="filters.department_id"
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:border-blue-400 dark:focus:ring-blue-800 transition-all duration-200">
                                <option value="">Semua Departemen</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Employee Filter --}}
                        <div>
                            <label for="filter_karyawan_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="inline h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                Karyawan
                            </label>
                            <select id="filter_karyawan_id" x-model="filters.karyawan_id"
                                class="block w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-2.5 text-sm text-gray-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:border-blue-400 dark:focus:ring-blue-800 transition-all duration-200">
                                <option value="">Semua Karyawan</option>
                                @foreach ($karyawan as $emp)
                                    <option value="{{ $emp->id }}" data-name="{{ $emp->nama_lengkap }}"
                                        data-department="{{ $emp->department->nama ?? '' }}"
                                        data-nip="{{ $emp->nip }}">
                                        {{ $emp->nama_lengkap }} ({{ $emp->nip }}) -
                                        {{ $emp->department->nama ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Search --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="inline h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                Pencarian Global
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                    </svg>
                                </div>
                                <input type="text" x-model="filters.search" @input.debounce.300ms="applyFilters()"
                                    class="block w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:border-blue-400 dark:focus:ring-blue-800 transition-all duration-200"
                                    placeholder="Cari nama, NIP, keterangan...">
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        {{-- Filter Actions --}}
                        <div class="flex flex-wrap items-center gap-3">
                            <button @click="resetFilters()"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 transition-all duration-200 hover:shadow-sm">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Reset Filter
                            </button>

                            {{-- Active Filter Count --}}
                            <div x-show="getActiveFilterCount() > 0"
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                <span x-text="getActiveFilterCount()"></span> filter aktif
                            </div>
                        </div>

                        {{-- Main Actions --}}
                        <div class="flex flex-wrap items-center gap-3">
                            {{-- Add Cuti Button --}}
                            <button @click="openCreateModal()"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Pengajuan Cuti
                            </button>

                            {{-- Export Dropdown --}}
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white text-sm font-medium rounded-lg shadow-sm transition-all duration-200 hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Export
                                    <svg class="w-4 h-4 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false" x-cloak
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 border border-gray-200 dark:border-gray-700">
                                    <div class="py-1">
                                        <button @click="exportExcel(); open = false"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-3 text-green-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0-1.125-.504-1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            Export Excel
                                        </button>
                                        <button @click="exportPdf(); open = false"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center transition-colors duration-150">
                                            <svg class="w-4 h-4 mr-3 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0-1.125-.504-1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            Export PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-700/50"
                x-data="cutiTable">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Data Cuti & Izin</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftar pengajuan cuti dan izin karyawan
                    </p>
                </div>

                {{-- Loading State --}}
                <div x-show="loading" class="flex justify-center items-center py-12">
                    <div class="text-center">
                        <svg class="animate-spin h-8 w-8 text-primary-600 mx-auto" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Memuat data...</p>
                    </div>
                </div>

                {{-- Table Content --}}
                <div x-show="!loading">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Karyawan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jenis & Periode
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Durasi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <template x-for="item in data" :key="item.id">
                                    <tr
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-white"
                                                            x-text="item.karyawan?.nama_lengkap?.charAt(0) || 'N'"></span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white"
                                                        x-text="item.karyawan?.nama_lengkap || 'N/A'"></div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        <span x-text="item.karyawan?.nip || 'N/A'"></span>
                                                        <span x-show="item.karyawan?.department?.nama"> • </span>
                                                        <span x-text="item.karyawan?.department?.nama || ''"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                    :class="{
                                                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400': item
                                                            .jenis_cuti === 'tahunan',
                                                        'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': item
                                                            .jenis_cuti === 'sakit',
                                                        'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400': item
                                                            .jenis_cuti === 'melahirkan',
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': item
                                                            .jenis_cuti === 'penting',
                                                        'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400': item
                                                            .jenis_cuti === 'lainnya'
                                                    }"
                                                    x-text="getJenisCutiLabel(item.jenis_cuti)">
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <span x-text="formatDate(item.tanggal_mulai)"></span> -
                                                <span x-text="formatDate(item.tanggal_selesai)"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                <span x-text="item.jumlah_hari"></span> hari
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                :class="{
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400': item
                                                        .status === 'diajukan',
                                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': item
                                                        .status === 'disetujui',
                                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': item
                                                        .status === 'ditolak'
                                                }"
                                                x-text="getStatusLabel(item.status)">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 dark:text-white max-w-xs truncate"
                                                x-text="item.keterangan || '-'"></div>
                                            <div x-show="item.catatan_persetujuan"
                                                class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <strong>Catatan:</strong> <span
                                                    x-text="item.catatan_persetujuan"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2">
                                                {{-- View Button --}}
                                                <button @click="viewItem(item)"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </button>

                                                {{-- Edit Button (only for 'diajukan' status) --}}
                                                <button x-show="item.status === 'diajukan'" @click="editItem(item)"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </button>

                                                {{-- Approve Button (only for 'diajukan' status) --}}
                                                @if (auth()->user()->hasPermission('cuti.approve'))
                                                    <button x-show="item.status === 'diajukan'"
                                                        @click="approveItem(item)"
                                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>

                                                    {{-- Reject Button (only for 'diajukan' status) --}}
                                                    <button x-show="item.status === 'diajukan'"
                                                        @click="rejectItem(item)"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                @endif

                                                {{-- Delete Button (only for 'diajukan' status) --}}
                                                <button x-show="item.status === 'diajukan'" @click="deleteItem(item)"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.82 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- Empty State --}}
                    <div x-show="data.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada data cuti</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Belum ada pengajuan cuti yang dibuat.
                        </p>
                        <div class="mt-6">
                            <button @click="openCreateModal()"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Pengajuan Cuti Baru
                            </button>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div x-show="data.length > 0" id="cuti-pagination-container"
                        @click="handlePaginationEvent($event)"
                        class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6 px-5 py-3 min-w-full align-middle overflow-hidden">
                        <div x-html="paginationHtml" x-show="paginationHtml"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Modals --}}
    @include('hr_karyawan.cuti.modals.create')
    @include('hr_karyawan.cuti.modals.edit')
    @include('hr_karyawan.cuti.modals.view')
    @include('hr_karyawan.cuti.modals.approve')

    {{-- Notification Component --}}
    <div id="notification" class="fixed top-4 right-4 z-50 hidden">
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div id="notification-icon"></div>
                </div>
                <div class="ml-3">
                    <p id="notification-message" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="hideNotification()"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js Scripts --}}
    <script>
        // Global notification functions
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const messageElement = document.getElementById('notification-message');
            const iconElement = document.getElementById('notification-icon');

            messageElement.textContent = message;

            if (type === 'success') {
                iconElement.innerHTML =
                    '<svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            } else {
                iconElement.innerHTML =
                    '<svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
            }

            notification.classList.remove('hidden');
            setTimeout(() => hideNotification(), 5000);
        }

        function hideNotification() {
            document.getElementById('notification').classList.add('hidden');
        }

        document.addEventListener('alpine:init', () => {
            // Statistics Component
            Alpine.data('cutiStats', () => ({
                stats: {
                    total_pengajuan: {{ $stats['total_pengajuan'] }},
                    pending: {{ $stats['pending'] }},
                    disetujui: {{ $stats['disetujui'] }},
                    ditolak: {{ $stats['ditolak'] }},
                    sedang_cuti: {{ $stats['sedang_cuti'] }}
                },

                async loadStats() {
                    try {
                        const response = await fetch('{{ route('hr.cuti.index') }}?stats=1');
                        const result = await response.json();
                        if (result.success) {
                            this.stats = result.stats;
                        }
                    } catch (error) {
                        console.error('Error loading stats:', error);
                    }
                }
            }));

            // Filters Component
            Alpine.data('cutiFilters', () => ({
                showFilters: true, // Default show on desktop, will be controlled on mobile
                filters: {
                    tanggal_mulai: '',
                    tanggal_akhir: '',
                    status: '',
                    jenis_cuti: '',
                    department_id: '',
                    karyawan_id: '',
                    search: ''
                },

                init() {
                    // Hide filters by default on mobile
                    this.showFilters = window.innerWidth >= 1024;

                    // Listen for window resize
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.showFilters = true;
                        }
                    });

                    this.$watch('filters', () => {
                        this.applyFilters();
                    });
                },

                getActiveFilterCount() {
                    let count = 0;
                    if (this.filters.tanggal_mulai) count++;
                    if (this.filters.tanggal_akhir) count++;
                    if (this.filters.status) count++;
                    if (this.filters.jenis_cuti) count++;
                    if (this.filters.department_id) count++;
                    if (this.filters.karyawan_id) count++;
                    if (this.filters.search) count++;
                    return count;
                },

                applyFilters() {
                    this.$dispatch('filters-changed', this.filters);
                },

                resetFilters() {
                    this.filters = {
                        tanggal_mulai: '',
                        tanggal_akhir: '',
                        status: '',
                        jenis_cuti: '',
                        department_id: '',
                        karyawan_id: '',
                        search: ''
                    };
                },

                openCreateModal() {
                    this.$dispatch('open-create-modal');
                },

                exportExcel() {
                    const params = new URLSearchParams(this.filters);
                    window.location.href = `{{ route('hr.cuti.index') }}/export-excel?${params}`;
                },

                exportPdf() {
                    const params = new URLSearchParams(this.filters);
                    window.location.href = `{{ route('hr.cuti.index') }}/export-pdf?${params}`;
                }
            }));

            // Table Component
            Alpine.data('cutiTable', () => ({
                data: [],
                loading: false,
                paginationHtml: '',
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 15,
                    total: 0,
                    from: 0,
                    to: 0
                },
                currentFilters: {},

                init() {
                    this.loadData();

                    this.$watch('currentFilters', () => {
                        this.pagination.current_page = 1;
                        this.loadData();
                    });

                    window.addEventListener('filters-changed', (event) => {
                        this.currentFilters = event.detail;
                    });

                    window.addEventListener('cuti-updated', () => {
                        this.loadData();
                        Alpine.store('cutiStats').loadStats();
                    });
                },

                async loadData() {
                    this.loading = true;
                    try {
                        const params = new URLSearchParams({
                            ...this.currentFilters,
                            page: this.pagination.current_page
                        });

                        const response = await fetch(`{{ route('hr.cuti.index') }}?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.data = result.data || [];
                            this.pagination = result.pagination || {};
                            this.paginationHtml = result.pagination_html || '';
                        }
                    } catch (error) {
                        console.error('Error loading data:', error);
                        showNotification('Terjadi kesalahan saat memuat data', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                prevPage() {
                    if (this.pagination.current_page > 1) {
                        this.pagination.current_page--;
                        this.loadData();
                    }
                },

                nextPage() {
                    if (this.pagination.current_page < this.pagination.last_page) {
                        this.pagination.current_page++;
                        this.loadData();
                    }
                },

                handlePaginationClick(event) {
                    event.preventDefault();

                    const link = event.target.closest('a');
                    if (!link || !link.href) return;

                    const url = new URL(link.href);
                    const page = url.searchParams.get('page');

                    if (page && page !== this.pagination.current_page.toString()) {
                        this.pagination.current_page = parseInt(page);
                        this.loadData();

                        // Update URL without page reload
                        const currentUrl = new URL(window.location);
                        if (page === '1') {
                            currentUrl.searchParams.delete('page');
                        } else {
                            currentUrl.searchParams.set('page', page);
                        }
                        window.history.pushState({}, '', currentUrl);
                    }
                },

                handlePaginationEvent(event) {
                    const link = event.target.closest('a');
                    if (link && link.href && !link.href.includes('#')) {
                        this.handlePaginationClick(event);
                    }
                },

                viewItem(item) {
                    this.$dispatch('view-cuti', item);
                },

                editItem(item) {
                    this.$dispatch('edit-cuti', item);
                },

                approveItem(item) {
                    this.$dispatch('approve-cuti', {
                        item,
                        status: 'disetujui'
                    });
                },

                rejectItem(item) {
                    this.$dispatch('approve-cuti', {
                        item,
                        status: 'ditolak'
                    });
                },

                async deleteItem(item) {
                    if (!confirm(
                            `Apakah Anda yakin ingin menghapus pengajuan cuti ${item.karyawan?.nama_lengkap || 'ini'}?`
                        )) {
                        return;
                    }

                    try {
                        const response = await fetch(`{{ route('hr.cuti.index') }}/${item.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            showNotification(result.message, 'success');
                            this.loadData();
                            Alpine.store('cutiStats').loadStats();
                        } else {
                            showNotification(result.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting item:', error);
                        showNotification('Terjadi kesalahan saat menghapus data', 'error');
                    }
                },

                formatDate(date) {
                    if (!date) return '-';
                    return new Date(date).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });
                },

                getJenisCutiLabel(jenis) {
                    const labels = {
                        'tahunan': 'Cuti Tahunan',
                        'sakit': 'Sakit',
                        'melahirkan': 'Melahirkan',
                        'penting': 'Keperluan Penting',
                        'lainnya': 'Lainnya'
                    };
                    return labels[jenis] || jenis;
                },

                getStatusLabel(status) {
                    const labels = {
                        'diajukan': 'Menunggu Persetujuan',
                        'disetujui': 'Disetujui',
                        'ditolak': 'Ditolak'
                    };
                    return labels[status] || status;
                }
            }));
        });
    </script>

    {{-- Push Select2 script to stack to ensure proper loading order --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            // Wait for both jQuery and Select2 to be available
            function waitForLibraries(callback) {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    callback();
                } else {
                    setTimeout(() => waitForLibraries(callback), 50);
                }
            }

            // Initialize when everything is ready
            $(document).ready(function() {
                waitForLibraries(function() {
                    // console.log('Initializing Select2...');

                    // Initialize Select2 for filter karyawan
                    if ($('#filter_karyawan_id').length && !$('#filter_karyawan_id').hasClass(
                            'select2-hidden-accessible')) {
                        $('#filter_karyawan_id').select2({
                            placeholder: 'Semua Karyawan',
                            allowClear: true,
                            width: '100%',
                            templateResult: function(option) {
                                if (!option.id) return option.text;

                                const $option = $(option.element);
                                const name = $option.data('name') || '';
                                const nip = $option.data('nip') || '';
                                const department = $option.data('department') || '';

                                if (name && nip) {
                                    return $(`
                                    <div>
                                        <div class="font-medium">${name}</div>
                                        <div class="text-sm text-gray-500">NIP: ${nip} • ${department}</div>
                                    </div>
                                `);
                                }

                                return option.text;
                            },
                            templateSelection: function(option) {
                                if (!option.id) return option.text;

                                const $option = $(option.element);
                                const name = $option.data('name') || '';
                                const nip = $option.data('nip') || '';

                                if (name && nip) {
                                    return `${name} (${nip})`;
                                }

                                return option.text;
                            }
                        });

                        // Handle Select2 change event for filters
                        $('#filter_karyawan_id').on('change', function() {
                            // Update Alpine.js data
                            if (Alpine.store('cutiFilters')) {
                                Alpine.store('cutiFilters').filters.karyawan_id = $(this).val();
                                Alpine.store('cutiFilters').applyFilters();
                            }
                        });
                    }
                });
            });

            // Function to initialize modal Select2
            function initModalSelect2() {
                console.log('Initializing modal Select2...');

                // Initialize Select2 for create modal
                if ($('#create_karyawan_id').length && !$('#create_karyawan_id').hasClass('select2-hidden-accessible')) {
                    $('#create_karyawan_id').select2({
                        placeholder: 'Pilih Karyawan',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#create_karyawan_id').closest('.fixed'),
                        templateResult: function(option) {
                            if (!option.id) return option.text;

                            const $option = $(option.element);
                            const name = $option.data('name') || '';
                            const nip = $option.data('nip') || '';
                            const department = $option.data('department') || '';

                            if (name && nip) {
                                return $(`
                                <div>
                                    <div class="font-medium">${name}</div>
                                    <div class="text-sm text-gray-500">NIP: ${nip} • ${department}</div>
                                </div>
                            `);
                            }

                            return option.text;
                        },
                        templateSelection: function(option) {
                            if (!option.id) return option.text;

                            const $option = $(option.element);
                            const name = $option.data('name') || '';
                            const nip = $option.data('nip') || '';

                            if (name && nip) {
                                return `${name} (${nip})`;
                            }

                            return option.text;
                        }
                    });
                }

                // Initialize Select2 for edit modal
                if ($('#edit_karyawan_id').length && !$('#edit_karyawan_id').hasClass('select2-hidden-accessible')) {
                    $('#edit_karyawan_id').select2({
                        placeholder: 'Pilih Karyawan',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#edit_karyawan_id').closest('.fixed'),
                        templateResult: function(option) {
                            if (!option.id) return option.text;

                            const $option = $(option.element);
                            const name = $option.data('name') || '';
                            const nip = $option.data('nip') || '';
                            const department = $option.data('department') || '';

                            if (name && nip) {
                                return $(`
                                <div>
                                    <div class="font-medium">${name}</div>
                                    <div class="text-sm text-gray-500">NIP: ${nip} • ${department}</div>
                                </div>
                            `);
                            }

                            return option.text;
                        },
                        templateSelection: function(option) {
                            if (!option.id) return option.text;

                            const $option = $(option.element);
                            const name = $option.data('name') || '';
                            const nip = $option.data('nip') || '';

                            if (name && nip) {
                                return `${name} (${nip})`;
                            }

                            return option.text;
                        }
                    });
                }
            }

            // Initialize modal Select2 when modals are opened
            document.addEventListener('alpine:init', () => {
                // Listen for modal open events
                window.addEventListener('open-create-modal', () => {
                    setTimeout(() => {
                        waitForLibraries(() => {
                            initModalSelect2();
                        });
                    }, 100);
                });

                window.addEventListener('open-edit-modal', () => {
                    setTimeout(() => {
                        waitForLibraries(() => {
                            initModalSelect2();
                        });
                    }, 100);
                });
            });
        </script>
    @endpush
</x-app-layout>
