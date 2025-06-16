<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Data Karyawan'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Data Karyawan</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola data karyawan perusahaan PT Sinar Surya Semestaraya
            </p>
        </div>

        {{-- Dashboard Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            {{-- Total Karyawan Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Karyawan</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $karyawan->total() }}
                                </p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Karyawan Aktif Card --}}
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
                                Karyawan Aktif</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Karyawan::where('status', 'aktif')->count() }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Karyawan Cuti Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3.5">
                            <svg class="h-7 w-7 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sedang Cuti</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                                    {{ \App\Models\Karyawan::where('status', 'cuti')->count() }}</p>
                                <p class="ml-1.5 text-sm font-medium text-gray-500 dark:text-gray-400">orang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Action Card --}}
            <a href="{{ route('hr.karyawan.create') }}"
                class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 overflow-hidden shadow-lg rounded-xl transition-all duration-300 hover:shadow-md hover:translate-y-[-2px] group">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white/80 uppercase tracking-wider">Aksi Cepat</p>
                            <p class="mt-1 text-lg font-semibold text-white">Tambah Karyawan Baru</p>
                        </div>
                        <div
                            class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/20 group-hover:bg-white/30 text-white transition-all duration-200">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Main Content Area --}}
        <div x-data="karyawanTableManager()"> {{-- Alpine component --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700 transition-all duration-300">
                <div class="p-6 sm:p-7 text-gray-900 dark:text-gray-100">

                    {{-- Header with Quick Search and Action Buttons --}}
                    <div class="mb-6 sm:mb-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="mb-4 sm:mb-0">
                                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white leading-tight">
                                    Data Karyawan</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola semua data karyawan
                                    perusahaan
                                    di sini</p>
                            </div>
                            <div
                                class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                                {{-- Dropdown Kolom Modular (pindah ke kiri search) --}}
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h5a2 2 0 002-2V7a2 2 0 00-2-2h-5a2 2 0 00-2 2m0 10l-3-3m3 3l3-3" />
                                        </svg>
                                        Kolom
                                        <svg class="ml-1.5 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.outside="open = false" x-transition
                                        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-40 max-h-96 overflow-y-auto"
                                        style="display: none;">
                                        <div class="py-1" role="menu" aria-orientation="vertical"
                                            aria-labelledby="options-menu">
                                            <template x-for="(isVisible, column) in visibleColumns"
                                                :key="column">
                                                <label
                                                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 cursor-pointer">
                                                    <input type="checkbox" x-model="visibleColumns[column]"
                                                        class="mr-2 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                                    <span
                                                        x-text="column.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                                                </label>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                {{-- Quick Search Form --}}
                                <div class="relative w-full sm:w-auto grow sm:grow-0">
                                    <div class="relative w-full">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                            </svg>
                                        </div>
                                        <input type="text" x-model.debounce.300ms="search"
                                            placeholder="Cari karyawan..."
                                            class="pl-10 pr-10 py-2 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white shadow-sm">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <button type="button" @click="search = ''; fetchData()" x-show="search"
                                                x-transition
                                                class="p-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-full">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    {{-- Export Button --}}
                                    <a href="#"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <svg class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Export
                                    </a>

                                    {{-- Import Button --}}
                                    <button type="button" @click="$dispatch('open-modal', 'import-modal')"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <svg class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Import
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Section --}}
                    <div
                        class="mb-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 transition-all duration-300">
                        <div class="p-4">
                            <form @submit.prevent="applyFilters">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                    {{-- Department Dropdown --}}
                                    <div x-data="{
                                        open: false,
                                        selectedDepartmentId: '',
                                        selectedDepartmentName: 'Semua Departemen'
                                    }" x-init="$watch('selectedDepartmentId', value => filters.department_id = value)" class="relative">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1">Departemen</label>
                                        <button type="button" @click="open = !open"
                                            class="relative w-full cursor-default rounded-md bg-white dark:bg-gray-700 py-1.5 pl-3 pr-10 text-left border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 text-sm shadow-sm">
                                            <span class="block truncate" x-text="selectedDepartmentName"></span>
                                            <span
                                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </button>
                                        <div x-cloak x-show="open" @click.away="open = false"
                                            class="absolute z-50 mt-1 max-h-48 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-sm shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                            <div @click="selectedDepartmentId = ''; selectedDepartmentName = 'Semua Departemen'; open = false"
                                                class="relative cursor-default select-none py-2 pl-10 pr-4 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                <span class="block truncate">Semua Departemen</span>
                                            </div>
                                            @foreach ($departments as $department)
                                                <div @click="selectedDepartmentId = '{{ $department->id }}'; selectedDepartmentName = '{{ $department->nama }}'; open = false"
                                                    class="relative cursor-default select-none py-2 pl-10 pr-4 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <span class="block truncate">{{ $department->nama }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Status Dropdown --}}
                                    <div class="relative">
                                        <label
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1">Status</label>
                                        <select x-model="filters.status"
                                            class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white py-1.5 px-3 shadow-sm">
                                            <option value="">Semua Status</option>
                                            <option value="aktif">Aktif</option>
                                            <option value="nonaktif">Nonaktif</option>
                                            <option value="cuti">Cuti</option>
                                            <option value="keluar">Keluar</option>
                                        </select>
                                    </div>

                                    {{-- Tanggal Masuk --}}
                                    <div class="relative">
                                        <label for="tanggal_masuk"
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1">Tanggal
                                            Masuk</label>
                                        <input type="date" name="tanggal_masuk" id="tanggal_masuk"
                                            x-model="filters.tanggal_masuk"
                                            class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white py-1.5 px-3 shadow-sm">
                                    </div>

                                    {{-- Sort By --}}
                                    <div class="relative">
                                        <label for="sort_by"
                                            class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1">Urutkan</label>
                                        <select id="sort_by" x-model="sortField" @change="sortDirection = 'asc';"
                                            class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white py-1.5 px-3 shadow-sm">
                                            <option value="nama_lengkap">Nama</option>
                                            <option value="nip">NIP</option>
                                            <option value="department_id">Departemen</option>
                                            <option value="tanggal_masuk">Tanggal Masuk</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Filter Buttons --}}
                                <div class="mt-3 flex justify-end space-x-2">
                                    <button type="button" @click="resetFilters()"
                                        class="px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-500 transition-all duration-200 shadow-sm">
                                        Reset
                                    </button>
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-primary-600 text-white text-xs font-medium rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-primary-500 transition-all duration-200 dark:bg-primary-700 dark:hover:bg-primary-600">
                                        <span class="flex items-center">
                                            Filter
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Container for Table and Pagination --}}
                    <div id="karyawan-table-container" class="relative">
                        {{-- Table --}}
                        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                            {{-- Per Page Selector --}}
                            <div
                                class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Menampilkan <span x-text="firstItem"></span> sampai <span
                                        x-text="lastItem"></span> dari <span x-text="totalResults"></span> hasil
                                </div>
                                <div>
                                    <label for="per-page"
                                        class="text-xs text-gray-500 dark:text-gray-400">Tampilkan:</label>
                                    <select id="per-page" x-model="perPage" @change="fetchData()"
                                        class="ml-1 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs border border-gray-300 dark:border-gray-600 rounded py-1 pl-2 pr-6 focus:outline-none focus:ring-1 focus:ring-primary-500">
                                        @foreach ([10, 25, 50, 100] as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 w-8">
                                            <input type="checkbox" :checked="allSelected" @click="toggleSelectAll()"
                                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
                                        </th>
                                        <template x-if="visibleColumns.karyawan">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span>Karyawan</span>
                                                    <button type="button" @click="sortBy('nama_lengkap')"
                                                        class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                        <svg :class="{ 'text-primary-600': sortField === 'nama_lengkap' }"
                                                            class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                :d="sortField === 'nama_lengkap' &&
                                                                    sortDirection === 'desc' ? 'M19 9l-7 7-7-7' :
                                                                    'M5 15l7-7 7 7'">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        <template x-if="visibleColumns.nip">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span>NIP</span>
                                                    <button type="button" @click="sortBy('nip')"
                                                        class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                        <svg :class="{ 'text-primary-600': sortField === 'nip' }"
                                                            class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                :d="sortField === 'nip' &&
                                                                    sortDirection === 'desc' ? 'M19 9l-7 7-7-7' :
                                                                    'M5 15l7-7 7 7'">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        <template x-if="visibleColumns.department">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span>Department</span>
                                                    <button type="button" @click="sortBy('department_id')"
                                                        class="ml-1 text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                                        <svg :class="{ 'text-primary-600': sortField === 'department_id' }"
                                                            class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                :d="sortField === 'department_id' &&
                                                                    sortDirection === 'desc' ? 'M19 9l-7 7-7-7' :
                                                                    'M5 15l7-7 7 7'">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </th>
                                        </template>
                                        <template x-if="visibleColumns.jabatan">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Jabatan
                                            </th>
                                        </template>
                                        {{-- <template x-if="visibleColumns.gaji_pokok">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Gaji Pokok
                                            </th>
                                        </template> --}}
                                        <template x-if="visibleColumns.status">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                <span>Status</span>
                                            </th>
                                        </template>
                                        <template x-if="visibleColumns.aksi">
                                            <th scope="col"
                                                class="px-5 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                                Aksi
                                            </th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody id="karyawan-table-body" x-html="tableHtml"></tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div id="pagination-container" x-html="paginationHtml"
                            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">
                            {{ $karyawan->links('vendor.pagination.tailwind-custom') }}
                        </div>

                        {{-- Loading Indicator --}}
                        <div x-show="loading" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="absolute inset-0 bg-white/70 dark:bg-gray-800/70 flex items-center justify-center z-30">
                            <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form untuk bulk delete --}}
    <form id="bulk-delete-form" action="{{ route('hr.karyawan.bulk-destroy') }}" method="POST">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
        <div id="selected-karyawan-container">
            {{-- Input akan dimasukkan secara dinamis saat submit --}}
        </div>
    </form>

    <script>
        function karyawanTableManager() {
            const loadVisibleColumns = () => {
                const stored = localStorage.getItem('karyawan_visible_columns');
                const defaults = {
                    karyawan: true, // nama, email, foto
                    nip: true,
                    department: true,
                    jabatan: true,
                    gaji_pokok: true,
                    status: true,
                    aksi: true,
                };
                try {
                    const parsed = stored ? JSON.parse(stored) : {};
                    const finalColumns = {
                        ...defaults
                    };
                    for (const key in defaults) {
                        if (parsed.hasOwnProperty(key)) {
                            finalColumns[key] = parsed[key];
                        }
                    }
                    return finalColumns;
                } catch (e) {
                    return defaults;
                }
            };
            return {
                search: '',
                filters: {
                    department_id: '',
                    tanggal_masuk: '',
                    status: '',
                },
                perPage: 10,
                currentPage: 1,
                sortField: 'nama_lengkap',
                sortDirection: 'asc',
                tableHtml: '',
                paginationHtml: '',
                loading: true,
                totalResults: {{ $karyawan->total() }},
                firstItem: {{ $karyawan->firstItem() ?? 0 }},
                lastItem: {{ $karyawan->lastItem() ?? 0 }},
                selectedKaryawan: [],
                allSelected: false,
                visibleColumns: loadVisibleColumns(),

                init() {
                    this.tableHtml = document.getElementById('karyawan-table-body').innerHTML;
                    this.fetchData();
                    this.$watch('visibleColumns', (newValue) => {
                        localStorage.setItem('karyawan_visible_columns', JSON.stringify(newValue));
                        this.fetchData();
                    }, {
                        deep: true
                    });
                },

                toggleSelectAll() {
                    const checkboxes = document.querySelectorAll('input[name="karyawan_ids[]"]');
                    if (this.allSelected) {
                        this.selectedKaryawan = [];
                        checkboxes.forEach(cb => {
                            cb.checked = false;
                        });
                    } else {
                        this.selectedKaryawan = Array.from(checkboxes).map(cb => cb.value);
                        checkboxes.forEach(cb => {
                            cb.checked = true;
                        });
                    }
                    this.allSelected = !this.allSelected;
                },

                updateSelectedKaryawan(event, id) {
                    if (event.target.checked) {
                        if (!this.selectedKaryawan.includes(id)) {
                            this.selectedKaryawan.push(id);
                        }
                    } else {
                        this.selectedKaryawan = this.selectedKaryawan.filter(kId => kId !== id);
                    }
                    const checkboxes = document.querySelectorAll('input[name="karyawan_ids[]"]');
                    this.allSelected = checkboxes.length > 0 && this.selectedKaryawan.length === checkboxes.length;
                },

                confirmDeleteSelected() {
                    if (this.selectedKaryawan.length === 0) {
                        alert('Silakan pilih minimal 1 karyawan untuk dihapus.');
                        return;
                    }
                    if (confirm(
                            `Apakah Anda yakin ingin menghapus ${this.selectedKaryawan.length} karyawan yang dipilih?`)) {
                        const container = document.getElementById('selected-karyawan-container');
                        container.innerHTML = '';
                        this.selectedKaryawan.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            container.appendChild(input);
                        });
                        document.getElementById('bulk-delete-form').submit();
                    }
                },

                confirmDelete(event, id) {
                    event.preventDefault();
                    if (confirm('Apakah Anda yakin ingin menghapus data karyawan ini?')) {
                        const form = event.target.closest('form');
                        form.submit();
                    }
                },

                attachPaginationListener() {
                    const links = document.querySelectorAll('#pagination-container a');
                    links.forEach(link => {
                        link.addEventListener('click', e => {
                            e.preventDefault();
                            const url = new URL(link.href);
                            this.currentPage = url.searchParams.get('page') || 1;
                            this.fetchData();
                        });
                    });
                },

                buildQueryString() {
                    const params = new URLSearchParams();
                    params.append('page', this.currentPage);
                    params.append('per_page', this.perPage);
                    if (this.search) params.append('search', this.search);
                    if (this.filters.department_id) params.append('department_id', this.filters.department_id);
                    if (this.filters.tanggal_masuk) params.append('tanggal_masuk', this.filters.tanggal_masuk);
                    if (this.filters.status) params.append('status', this.filters.status);
                    if (this.sortField) params.append('sort', this.sortField);
                    if (this.sortDirection) params.append('direction', this.sortDirection);
                    return params.toString();
                },

                fetchData() {
                    this.loading = true;
                    const queryString = this.buildQueryString();
                    const url = `{{ route('hr.karyawan.index') }}?${queryString}`;

                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error(`Network response error: ${response.status}`);
                            return response.json();
                        })
                        .then(data => {
                            this.tableHtml = data.html;
                            this.paginationHtml = data.pagination;
                            this.totalResults = data.total;
                            this.firstItem = data.first_item || 0;
                            this.lastItem = data.last_item || 0;
                            this.loading = false;
                            this.selectedKaryawan = [];
                            this.allSelected = false;
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                            this.loading = false;
                        });
                },

                applyFilters() {
                    this.currentPage = 1;
                    this.fetchData();
                },

                resetFilters() {
                    this.filters = {
                        department_id: '',
                        tanggal_masuk: '',
                        status: '',
                    };
                    this.fetchData();
                },

                sortBy(field) {
                    if (this.sortField === field) {
                        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortDirection = 'asc';
                    }
                    this.fetchData();
                },
            };
        }
    </script>
</x-app-layout>
