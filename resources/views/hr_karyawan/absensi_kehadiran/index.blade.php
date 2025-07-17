<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Absensi & Kehadiran'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Absensi & Kehadiran</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola data absensi dan kehadiran karyawan PT Sinar Surya Semestara dengan mudah dan efisien.
            </p>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-8" x-data="attendanceStats">
            {{-- Total Hadir Hari Ini --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 p-3.5">
                            <svg class="h-7 w-7 text-emerald-500 dark:text-emerald-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Hadir Hari Ini</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" x-text="stats.hadir">-
                                </p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Terlambat Hari Ini --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-orange-100 dark:bg-orange-900/30 p-3.5">
                            <svg class="h-7 w-7 text-orange-500 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Terlambat Hari Ini</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white"
                                    x-text="stats.terlambat">-</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sakit Hari Ini --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 p-3.5">
                            <svg class="h-7 w-7 text-yellow-500 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sakit Hari Ini</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" x-text="stats.sakit">-
                                </p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alpha/Tidak Hadir --}}
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
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Alpha Hari Ini</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white" x-text="stats.alpha">-
                                </p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Izin/Cuti Hari Ini --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5a2.25 2.25 0 012.25 2.25v7.5" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Izin/Cuti Hari Ini</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white"
                                    x-text="stats.izin_cuti">-</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content will continue in next part --}}

        {{-- Filter dan Actions Panel --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-700/50 mb-8"
            x-data="attendanceFilters">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    {{-- Filter Section --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 flex-1">
                        {{-- Date Range Filter --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Periode
                                Tanggal</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" x-model="filters.tanggal_mulai" @change="applyFilters()"
                                    value="{{ date('Y-m-d') }}"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <input type="date" x-model="filters.tanggal_akhir" @change="applyFilters()"
                                    value="{{ date('Y-m-d') }}"
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                        </div>

                        {{-- Employee Filter --}}
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Karyawan</label>
                            <select id="filter_karyawan_id" x-model="filters.karyawan_id"
                                class="filter-employee-select block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Semua Karyawan</option>
                                @foreach ($karyawans as $karyawan)
                                    <option value="{{ $karyawan->id }}" data-name="{{ $karyawan->nama_lengkap }}"
                                        data-department="{{ $karyawan->department->nama ?? '' }}">
                                        {{ $karyawan->nama_lengkap }}
                                        @if ($karyawan->department)
                                            - {{ $karyawan->department->nama }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select x-model="filters.status" @change="applyFilters()"
                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="hadir">Hadir</option>
                                <option value="sakit">Sakit</option>
                                <option value="alpha">Alpha</option>
                                <option value="izin">Izin</option>
                                <option value="cuti">Cuti</option>
                            </select>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        {{-- Import Button --}}
                        <button @click="openImportModal()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            Import Data
                        </button>

                        {{-- Add Manual Entry Button --}}
                        <button @click="openCreateModal()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Manual
                        </button>

                        {{-- Export Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5a2.25 2.25 0 002.25-2.25V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Export
                                <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 z-10 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <button @click="exportExcel(); open = false"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
                                        <svg class="w-4 h-4 mr-3 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0-1.125-.504-1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        Export Excel
                                    </button>
                                    <button @click="exportPdf(); open = false"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">
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

                {{-- Search Bar --}}
                <div class="mt-4">
                    <div class="relative">
                        <input type="text" x-model="filters.search" @input.debounce.300ms="applyFilters()"
                            placeholder="Cari nama karyawan, departemen, atau keterangan..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200/50 dark:border-gray-700/50"
            x-data="attendanceTable">
            <div class="p-6">
                {{-- Loading State --}}
                <div x-show="loading" class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">Memuat data...</span>
                </div>

                {{-- Table --}}
                <div x-show="!loading" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Tanggal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nama Karyawan</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Departemen</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jam Masuk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jam Keluar</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Keterangan</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="item in data" :key="item.id">
                                <tr :class="getRowClass(item.status, item.keterangan)"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                        x-text="formatDate(item.tanggal)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white"
                                            x-text="item.karyawan?.nama || item.nama_karyawan"></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                        x-text="item.karyawan?.department?.nama || item.nama_department || '-'"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                        x-text="formatTime(item.jam_masuk)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                        x-text="formatTime(item.jam_keluar)"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="getStatusBadgeClass(item.status)"
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                            x-text="getStatusLabel(item.status)"></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span x-text="item.keterangan || '-'"
                                            :class="item.keterangan && item.keterangan.toLowerCase().includes('terlambat') ?
                                                'text-orange-600 dark:text-orange-400 font-medium' : ''"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button @click="editItem(item)"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </button>
                                            <button @click="deleteItem(item)"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    {{-- Empty State --}}
                    <div x-show="data.length === 0 && !loading" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h4.125m-6 0v-2.5m6 2.5v-11.25c0-.35.28-.625.625-.625H12a.625.625 0 01.625.625v11.25a.625.625 0 01-.625.625H6.625a.625.625 0 01-.625-.625z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada data absensi</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan data absensi
                            karyawan.</p>
                    </div>
                </div>

                {{-- Pagination --}}
                <div x-show="pagination.total > 0"
                    class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <button @click="prevPage()" :disabled="pagination.current_page <= 1"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>
                        <button @click="nextPage()" :disabled="pagination.current_page >= pagination.last_page"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Menampilkan
                                <span class="font-medium" x-text="pagination.from"></span>
                                sampai
                                <span class="font-medium" x-text="pagination.to"></span>
                                dari
                                <span class="font-medium" x-text="pagination.total"></span>
                                hasil
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <button @click="prevPage()" :disabled="pagination.current_page <= 1"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <template x-for="page in getPageNumbers()" :key="page">
                                    <button @click="goToPage(page)"
                                        :class="page === pagination.current_page ?
                                            'bg-blue-50 dark:bg-blue-900 border-blue-500 text-blue-600 dark:text-blue-400' :
                                            'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                        x-text="page">
                                    </button>
                                </template>

                                <button @click="nextPage()" :disabled="pagination.current_page >= pagination.last_page"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div x-data="toastNotification" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-x-full"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform translate-x-full"
        class="fixed top-4 right-4 z-50 max-w-sm w-full">
        <div :class="type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'"
            class="rounded-lg shadow-lg text-white p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg x-show="type === 'success'" class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    <svg x-show="type === 'error'" class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <svg x-show="type === 'info'" class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium" x-text="message"></p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button @click="hide()" class="inline-flex text-white hover:text-gray-200 focus:outline-none">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals will be included here --}}
    @include('hr_karyawan.absensi_kehadiran.modals.create')
    @include('hr_karyawan.absensi_kehadiran.modals.edit')
    @include('hr_karyawan.absensi_kehadiran.modals.import')

    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Select2 Dark Mode & Tailwind Integration */
            .select2-container--default .select2-selection--single {
                height: 42px !important;
                border: 1px solid #d1d5db !important;
                border-radius: 0.5rem !important;
                background-color: white !important;
                padding: 0 12px !important;
                display: flex !important;
                align-items: center !important;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: rgb(55 65 81) !important;
                border-color: rgb(75 85 99) !important;
                color: white !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 42px !important;
                padding-left: 0 !important;
                color: rgb(17 24 39) !important;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: white !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px !important;
                right: 12px !important;
            }

            .select2-dropdown {
                border: 1px solid #d1d5db !important;
                border-radius: 0.5rem !important;
                background-color: white !important;
            }

            .dark .select2-dropdown {
                background-color: rgb(55 65 81) !important;
                border-color: rgb(75 85 99) !important;
            }

            .select2-container--default .select2-results__option {
                color: rgb(17 24 39) !important;
                padding: 8px 12px !important;
            }

            .dark .select2-container--default .select2-results__option {
                color: white !important;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: rgb(59 130 246) !important;
                color: white !important;
            }

            .select2-container--default .select2-search--dropdown .select2-search__field {
                border: 1px solid #d1d5db !important;
                border-radius: 0.375rem !important;
                padding: 8px 12px !important;
                background-color: white !important;
                color: rgb(17 24 39) !important;
            }

            .dark .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: rgb(75 85 99) !important;
                border-color: rgb(107 114 128) !important;
                color: white !important;
            }

            .select2-container--default .select2-selection--single:focus {
                border-color: rgb(59 130 246) !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                // Toast Notification Component
                Alpine.data('toastNotification', () => ({
                    show: false,
                    type: 'success', // success, error, info
                    message: '',
                    timer: null,

                    init() {
                        this.$watch('show', (show) => {
                            if (show && this.timer) {
                                clearTimeout(this.timer);
                                this.timer = setTimeout(() => {
                                    this.hide();
                                }, 5000);
                            }
                        });

                        // Listen for global notification events
                        window.addEventListener('show-notification', (event) => {
                            this.showNotification(event.detail.message, event.detail.type ||
                                'info');
                        });
                    },

                    showNotification(message, type = 'info') {
                        this.message = message;
                        this.type = type;
                        this.show = true;
                    },

                    hide() {
                        this.show = false;
                        if (this.timer) {
                            clearTimeout(this.timer);
                            this.timer = null;
                        }
                    }
                }));

                // Attendance Statistics Component
                Alpine.data('attendanceStats', () => ({
                    stats: {
                        hadir: 0,
                        terlambat: 0,
                        sakit: 0,
                        alpha: 0,
                        izin_cuti: 0
                    },

                    init() {
                        this.loadStats();
                        // Refresh stats when data changes
                        window.addEventListener('data-updated', () => {
                            this.loadStats();
                        });
                    },

                    async loadStats() {
                        try {
                            const today = new Date().toISOString().split('T')[0];
                            const response = await fetch(
                                `{{ route('hr.absensi.index') }}?tanggal_mulai=${today}&tanggal_akhir=${today}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                }
                            );

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                console.warn('Stats response is not JSON, skipping stats update');
                                return;
                            }

                            const result = await response.json();

                            if (result.error) {
                                console.error('Stats error:', result.message);
                                return;
                            }

                            // Calculate stats from today's data
                            this.stats = {
                                hadir: result.data.filter(item => item.status === 'hadir').length,
                                terlambat: result.data.filter(item =>
                                    item.status === 'hadir' &&
                                    item.keterangan &&
                                    item.keterangan.toLowerCase().includes('terlambat')
                                ).length,
                                sakit: result.data.filter(item => item.status === 'sakit').length,
                                alpha: result.data.filter(item => item.status === 'alpha').length,
                                izin_cuti: result.data.filter(item => ['izin', 'cuti'].includes(item
                                    .status)).length
                            };
                        } catch (error) {
                            console.error('Error loading stats:', error);
                        }
                    }
                }));

                // Attendance Filters Component
                Alpine.data('attendanceFilters', () => ({
                    filters: {
                        tanggal_mulai: '',
                        tanggal_akhir: '',
                        karyawan_id: '',
                        status: '',
                        search: ''
                    },

                    init() {
                        // Set default date range to today
                        const today = new Date().toISOString().split('T')[0];

                        this.filters.tanggal_mulai = today;
                        this.filters.tanggal_akhir = today;

                        // Initialize Select2 for filter employee dropdown
                        this.$nextTick(() => {
                            this.initFilterSelect2();
                            // Apply initial filters after a short delay to ensure DOM is ready
                            setTimeout(() => {
                                this.applyFilters();
                            }, 1000);
                        });
                    },

                    initFilterSelect2() {
                        const $select = $('#filter_karyawan_id');

                        // Destroy existing Select2 if it exists
                        if ($select.hasClass('select2-hidden-accessible')) {
                            $select.select2('destroy');
                        }

                        // Initialize Select2
                        $select.select2({
                            placeholder: 'Semua Karyawan',
                            allowClear: true,
                            width: '100%',
                            templateResult: this.formatEmployeeOption,
                            templateSelection: this.formatEmployeeSelection,
                            escapeMarkup: function(markup) {
                                return markup;
                            }
                        });

                        // Sync Select2 changes with Alpine.js
                        $select.on('change', (e) => {
                            this.filters.karyawan_id = $select.val() || '';
                            this.applyFilters();
                        });
                    },

                    formatEmployeeOption(employee) {
                        if (!employee.id) {
                            return employee.text;
                        }

                        const $employee = $(employee.element);
                        const name = $employee.data('name') || '';
                        const department = $employee.data('department') || '';

                        return $(
                            '<div class="flex flex-col py-2">' +
                            '<div class="font-medium text-gray-900 dark:text-white">' + name +
                            '</div>' +
                            '<div class="text-sm text-gray-500 dark:text-gray-400">' +
                            (department ? department : 'Tidak ada departemen') +
                            '</div>' +
                            '</div>'
                        );
                    },

                    formatEmployeeSelection(employee) {
                        if (!employee.id) {
                            return 'Semua Karyawan';
                        }

                        const $employee = $(employee.element);
                        return $employee.data('name') || employee.text;
                    },

                    applyFilters() {
                        this.$dispatch('filters-changed', this.filters);
                    },

                    openImportModal() {
                        this.$dispatch('open-import-modal');
                    },

                    openCreateModal() {
                        this.$dispatch('open-create-modal');
                    },

                    exportExcel() {
                        const params = new URLSearchParams(this.filters);
                        window.location.href = `{{ route('hr.absensi.export-excel') }}?${params}`;
                    },

                    exportPdf() {
                        const params = new URLSearchParams(this.filters);
                        window.location.href = `{{ route('hr.absensi.export-pdf') }}?${params}`;
                    }
                }));

                // Attendance Table Component
                Alpine.data('attendanceTable', () => ({
                    data: [],
                    loading: false,
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
                        // Watch for filter changes
                        this.$watch('currentFilters', () => {
                            this.pagination.current_page = 1;
                            this.loadData();
                        });

                        // Listen for filter changes
                        window.addEventListener('filters-changed', (event) => {
                            this.currentFilters = {
                                ...event.detail
                            };
                        });

                        // Listen for data refresh events
                        window.addEventListener('refresh-data', () => {
                            this.loadData();
                        });
                    },

                    async loadData() {
                        this.loading = true;
                        try {
                            const params = new URLSearchParams({
                                ...this.currentFilters,
                                page: this.pagination.current_page
                            });

                            // Remove empty values
                            for (let [key, value] of [...params.entries()]) {
                                if (!value || value === '') {
                                    params.delete(key);
                                }
                            }

                            const response = await fetch(`{{ route('hr.absensi.index') }}?${params}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            });

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error('Response is not JSON');
                            }

                            const result = await response.json();

                            if (result.error) {
                                throw new Error(result.message || 'Unknown error');
                            }

                            this.data = result.data || [];
                            this.pagination = result.pagination || {
                                current_page: 1,
                                last_page: 1,
                                per_page: 15,
                                total: 0,
                                from: 0,
                                to: 0
                            };

                            // Dispatch event for stats update
                            window.dispatchEvent(new CustomEvent('data-updated'));
                        } catch (error) {
                            console.error('Error loading data:', error);
                            this.showNotification('Terjadi kesalahan saat memuat data', 'error');
                        } finally {
                            this.loading = false;
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

                    formatTime(time) {
                        if (!time) return '-';
                        return time.substring(0, 5); // HH:MM format
                    },

                    getStatusLabel(status) {
                        const labels = {
                            'hadir': 'Hadir',
                            'sakit': 'Sakit',
                            'alpha': 'Alpha',
                            'izin': 'Izin',
                            'cuti': 'Cuti'
                        };
                        return labels[status] || status;
                    },

                    getStatusBadgeClass(status) {
                        const classes = {
                            'hadir': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                            'sakit': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            'alpha': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            'izin': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'cuti': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
                        };
                        return classes[status] ||
                            'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
                    },

                    getRowClass(status, keterangan) {
                        if (status === 'hadir' && keterangan && keterangan.toLowerCase().includes(
                                'terlambat')) {
                            return 'bg-orange-50 dark:bg-orange-900/10 border-l-4 border-orange-400';
                        } else if (status === 'sakit') {
                            return 'bg-yellow-50 dark:bg-yellow-900/10';
                        } else if (status === 'alpha') {
                            return 'bg-red-50 dark:bg-red-900/10';
                        }
                        return '';
                    },

                    editItem(item) {
                        this.$dispatch('edit-attendance', item);
                    },

                    async deleteItem(item) {
                        if (!confirm(
                                `Apakah Anda yakin ingin menghapus data absensi ${item.karyawan?.nama || item.nama_karyawan} pada tanggal ${this.formatDate(item.tanggal)}?`
                            )) {
                            return;
                        }

                        try {
                            const response = await fetch(
                            `{{ route('hr.absensi.index') }}/${item.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            });

                            const result = await response.json();

                            if (result.success) {
                                this.showNotification(result.message, 'success');
                                this.loadData();
                            } else {
                                this.showNotification(result.message, 'error');
                            }
                        } catch (error) {
                            console.error('Error deleting item:', error);
                            this.showNotification('Terjadi kesalahan saat menghapus data', 'error');
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

                    goToPage(page) {
                        this.pagination.current_page = page;
                        this.loadData();
                    },

                    getPageNumbers() {
                        const current = this.pagination.current_page;
                        const last = this.pagination.last_page;
                        const pages = [];

                        // Show max 5 page numbers
                        let start = Math.max(1, current - 2);
                        let end = Math.min(last, start + 4);

                        if (end - start < 4) {
                            start = Math.max(1, end - 4);
                        }

                        for (let i = start; i <= end; i++) {
                            pages.push(i);
                        }

                        return pages;
                    },

                    showNotification(message, type = 'info') {
                        window.dispatchEvent(new CustomEvent('show-notification', {
                            detail: {
                                message,
                                type
                            }
                        }));
                    }
                }));
            });
        </script>
    @endpush
</x-app-layout>
