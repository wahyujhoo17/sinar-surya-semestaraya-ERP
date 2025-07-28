<x-app-layout :breadcrumbs="$breadcrumbs ?? []" :currentPage="$currentPage ?? 'Penggajian Karyawan'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Penggajian Karyawan</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Kelola data penggajian karyawan PT Sinar Surya Semestaraya
            </p>
        </div>

        <!-- Pesan Sukses/Error -->
        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div
            class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Daftar Penggajian
                </h3>
                <div class="flex space-x-2">
                    <a href="{{ route('hr.penggajian.create', [
                        'bulan' => request('bulan', $currentMonth),
                        'tahun' => request('tahun', $currentYear),
                    ]) }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Penggajian
                    </a>
                </div>
            </div>
            <div class="p-6">
                <!-- Filter -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6">
                    <div class="md:col-span-8">
                        <div class="flex">
                            <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                <input type="text"
                                    class="block w-full rounded-none rounded-l-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm search-input"
                                    placeholder="Cari nama karyawan atau NIP..." value="{{ request('search') }}">
                                <!-- Clear search button (hidden by default) -->
                                <button type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center clear-search-btn {{ request('search') ? '' : 'hidden' }}"
                                    title="Hapus pencarian">
                                    <svg class="h-4 w-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <button
                                class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 search-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <div class="flex space-x-2">
                            <select
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm filter-status">
                                <option value="">Semua Status</option>
                                <option value="belum_dibayar"
                                    {{ request('status') == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui</option>
                                <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar
                                </option>
                            </select>
                            <button
                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 reset-filter-btn"
                                title="Reset Filter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Filter Bulan -->
                    <div>
                        <label for="filter-bulan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Bulan
                        </label>
                        <select id="filter-bulan"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm filter-bulan">
                            <option value="">Semua Bulan</option>
                            @php
                                $months = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                ];
                                $selectedMonth = request('bulan', $currentMonth);
                            @endphp
                            @foreach ($months as $value => $name)
                                <option value="{{ $value }}" {{ $selectedMonth == $value ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Tahun -->
                    <div>
                        <label for="filter-tahun"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tahun
                        </label>
                        <div class="relative">
                            <input type="number" id="filter-tahun"
                                class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm filter-tahun-input"
                                placeholder="Masukkan tahun..." min="2000" max="2099"
                                value="{{ request('tahun', $currentYear) }}" style="padding-right: 3rem;">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <!-- Quick year buttons -->
                        <div class="flex flex-wrap gap-1 mt-2">
                            @php
                                $currentYearValue = date('Y');
                                $quickYears = [$currentYearValue - 1, $currentYearValue, $currentYearValue + 1];
                            @endphp
                            @foreach ($quickYears as $quickYear)
                                <button type="button"
                                    class="quick-year-btn px-2 py-1 text-xs rounded border {{ request('tahun', $currentYear) == $quickYear ? 'bg-primary-100 border-primary-300 text-primary-700 dark:bg-primary-900/20 dark:border-primary-600 dark:text-primary-400' : 'bg-gray-100 border-gray-300 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600' }}"
                                    data-year="{{ $quickYear }}">
                                    {{ $quickYear }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Items per page -->
                    <div>
                        <label for="per-page-select"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Items per halaman
                        </label>
                        <select id="per-page-select"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm per-page-select">
                            <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 per halaman
                            </option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 per halaman
                            </option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 per halaman
                            </option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 per
                                halaman</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    No.</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 sortable cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                                    data-sort="karyawan_nama">
                                    <div class="flex items-center justify-between">
                                        <span>Karyawan</span>
                                        <div class="ml-2 flex flex-col">
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-asc {{ request('sort') === 'karyawan_nama' && request('direction') === 'asc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-desc {{ request('sort') === 'karyawan_nama' && request('direction') === 'desc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 sortable cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                                    data-sort="bulan">
                                    <div class="flex items-center justify-between">
                                        <span>Bulan</span>
                                        <div class="ml-2 flex flex-col">
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-asc {{ request('sort') === 'bulan' && request('direction') === 'asc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-desc {{ request('sort') === 'bulan' && request('direction') === 'desc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 sortable cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                                    data-sort="tahun">
                                    <div class="flex items-center justify-between">
                                        <span>Tahun</span>
                                        <div class="ml-2 flex flex-col">
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-asc {{ request('sort') === 'tahun' && request('direction') === 'asc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-desc {{ request('sort') === 'tahun' && request('direction') === 'desc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 sortable cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                                    data-sort="total_gaji">
                                    <div class="flex items-center justify-between">
                                        <span>Total Gaji</span>
                                        <div class="ml-2 flex flex-col">
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-asc {{ request('sort') === 'total_gaji' && request('direction') === 'asc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-desc {{ request('sort') === 'total_gaji' && request('direction') === 'desc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 sortable cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                                    data-sort="status">
                                    <div class="flex items-center justify-between">
                                        <span>Status</span>
                                        <div class="ml-2 flex flex-col">
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-asc {{ request('sort') === 'status' && request('direction') === 'asc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-desc {{ request('sort') === 'status' && request('direction') === 'desc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400 sortable cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                                    data-sort="tanggal_bayar">
                                    <div class="flex items-center justify-between">
                                        <span>Tanggal Bayar</span>
                                        <div class="ml-2 flex flex-col">
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-asc {{ request('sort') === 'tanggal_bayar' && request('direction') === 'asc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                            <svg class="w-3 h-3 text-gray-400 sort-arrow sort-desc {{ request('sort') === 'tanggal_bayar' && request('direction') === 'desc' ? 'text-primary-600' : '' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 table-body">
                            @include('hr_karyawan.penggajian_dan_tunjangan._table_body')
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0 pagination-container">
                    <div class="text-sm text-gray-500 dark:text-gray-400 pagination-info">
                        <!-- JavaScript will populate this -->
                    </div>
                    <div class="pagination-links">
                        {{ $penggajian->links('vendor.pagination.tailwind-custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-2xl rounded-xl bg-white dark:bg-gray-800 dark:border-gray-700 transform transition-all duration-300 scale-95 opacity-0"
            id="deleteModalContent">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-900/20 rounded-full mr-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    Konfirmasi Hapus
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-200"
                    id="closeModalBtn">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mb-6">
                <div class="text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/20 mb-4">
                        <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Hapus Data Penggajian?</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        Anda akan menghapus data penggajian untuk:
                    </p>
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-4 border border-gray-200 dark:border-gray-600">
                        <p class="font-semibold text-gray-900 dark:text-white text-base" id="deleteEmployeeName"></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1" id="deletePeriod"></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1" id="deleteSalary"></p>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-3 rounded">
                        <p class="text-sm text-red-700 dark:text-red-400 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Tindakan ini tidak dapat dibatalkan!
                        </p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                <button type="button"
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600 dark:focus:ring-gray-800 transition-all duration-200"
                    id="cancelDeleteBtn">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </button>
                <button type="button" id="confirmDeleteBtn"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Ya, Hapus Data
                </button>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Global modal functions
    let deleteForm = null;

    // Global pagination function for Alpine.js
    function handlePaginationClick(url) {
        loadTable(url);
    }

    // Global state for navigation tracking
    window.isNavigating = false;

    // Global loadTable function for AJAX pagination
    function loadTable(url) {
        // Get current URL parameters
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;

        // Prevent AJAX during browser navigation
        if (window.isNavigating) {
            window.location.href = url;
            return;
        }

        // Add ajax_request parameter to ensure we get JSON response
        const urlObj = new URL(url, window.location.origin);
        urlObj.searchParams.set('ajax_request', '1');
        const ajaxUrl = urlObj.toString();

        // Debug log untuk tracking request (uncomment jika diperlukan)
        console.log('Loading table with URL:', ajaxUrl);
        console.log('Current filters:', {
            search: params.get('search'),
            status: params.get('status'),
            bulan: params.get('bulan'),
            tahun: params.get('tahun'),
            sort: params.get('sort'),
            direction: params.get('direction'),
            per_page: params.get('per_page')
        });

        // Add loading state to pagination
        $('.pagination-links').addClass('opacity-50 pointer-events-none');

        // Show loading indicator with better styling
        $('.table-body').html(`
            <tr>
                <td colspan="8" class="px-3 py-12 text-center">
                    <div class="flex flex-col items-center justify-center space-y-3">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Memuat data penggajian...</p>
                    </div>
                </td>
            </tr>
        `);

        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                // console.log("Response received:", response); // Debug log
                if (response.success) {
                    $('.table-body').html(response.html);
                    $('.pagination-links').html(response.pagination);

                    // Update pagination info with more detailed information
                    const totalData = response.total || 0;
                    const firstItem = response.first_item || 0;
                    const lastItem = response.last_item || 0;

                    // Update pagination info text
                    if (totalData > 0) {
                        $('.pagination-info').text(
                            `Menampilkan ${firstItem} sampai ${lastItem} dari ${totalData} data`
                        );
                    } else {
                        $('.pagination-info').text('Tidak ada data yang ditemukan');
                    }

                    // Update URL without refreshing page (remove ajax_request parameter from URL)
                    const cleanUrl = new URL(url, window.location.origin);
                    cleanUrl.searchParams.delete('ajax_request');
                    window.history.pushState({}, '', cleanUrl.toString());
                    currentUrl = new URL(window.location);
                    params = currentUrl.searchParams;

                    // Ensure the correct values are selected in the dropdowns and inputs
                    $('.filter-status').val(params.get('status') || '');
                    $('.filter-bulan').val(params.get('bulan') || response.selectedMonth ||
                        response.currentMonth || '');
                    $('.filter-tahun-input').val(params.get('tahun') || response.selectedYear ||
                        response.currentYear || '');
                    $('.per-page-select').val(params.get('per_page') || '10');
                    $('.search-input').val(params.get('search') || '');

                    // Update search clear button visibility
                    if (window.toggleClearSearchButton) {
                        window.toggleClearSearchButton();
                    }

                    // Update quick year buttons
                    if (window.updateQuickYearButtons) {
                        window.updateQuickYearButtons();
                    }

                    // Update sorting indicators
                    if (window.updateSortingIndicators) {
                        window.updateSortingIndicators();
                    }

                    // Remove loading state
                    $('.pagination-links').removeClass('opacity-50 pointer-events-none');

                    // Log successful update for debugging
                    console.log('Table updated successfully:', {
                        total: totalData,
                        first: firstItem,
                        last: lastItem,
                        pagination_visible: $('.pagination-links').children().length > 0
                    });
                } else if (response.error) {
                    // Handle error from server
                    // console.error('Server error:', response.error);
                    $('.table-body').html(`
                        <tr>
                            <td colspan="8" class="px-3 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-medium text-gray-900 dark:text-white mb-1">Terjadi Kesalahan</p>
                                        <p class="text-sm text-red-600 dark:text-red-400 font-medium">${response.error}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Silakan coba refresh halaman atau hubungi administrator</p>
                                    </div>
                                    <button class="mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 retry-btn transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Coba Lagi
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);

                    // Retry button handler
                    $('.retry-btn').on('click', function() {
                        loadTable(url);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading data:', {
                    xhr,
                    status,
                    error,
                    responseText: xhr.responseText
                });

                // Try to parse the error response
                let errorMessage = 'Terjadi kesalahan saat memuat data.';
                let errorDetails = '';

                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.error) {
                        errorMessage = errorResponse.error;

                        if (errorResponse.file && errorResponse.line) {
                            errorDetails =
                                `File: ${errorResponse.file}, Line: ${errorResponse.line}`;
                        }
                    }
                } catch (e) {
                    // If we can't parse the JSON, use the status text
                    errorMessage = xhr.statusText || 'Unknown error';
                }

                // Show error message in table body
                $('.table-body').html(`
                    <tr>
                        <td colspan="8" class="px-3 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-1">Gagal Memuat Data</p>
                                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">${errorMessage}</p>
                                    ${errorDetails ? `<p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${errorDetails}</p>` : ''}
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Silakan coba refresh halaman atau gunakan filter yang berbeda</p>
                                </div>
                                <button class="mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 retry-btn transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Coba Lagi
                                </button>
                            </div>
                        </td>
                    </tr>
                `);

                // Clear pagination
                $('.pagination-links').html('').removeClass('opacity-50 pointer-events-none');
                $('.pagination-info').text('Error memuat data');

                // Retry button handler
                $('.retry-btn').on('click', function() {
                    loadTable(url);
                });
            }
        });
    }

    // Global helper functions for AJAX functionality
    window.toggleClearSearchButton = function() {
        const searchValue = $('.search-input').val();
        if (searchValue && searchValue.length > 0) {
            $('.clear-search-btn').removeClass('hidden');
        } else {
            $('.clear-search-btn').addClass('hidden');
        }
    }

    window.updateQuickYearButtons = function() {
        const currentSelectedYear = $('.filter-tahun-input').val();
        $('.quick-year-btn').each(function() {
            const btnYear = $(this).data('year');
            if (btnYear == currentSelectedYear) {
                $(this).removeClass(
                        'bg-gray-100 border-gray-300 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600'
                    )
                    .addClass(
                        'bg-primary-100 border-primary-300 text-primary-700 dark:bg-primary-900/20 dark:border-primary-600 dark:text-primary-400'
                    );
            } else {
                $(this).removeClass(
                        'bg-primary-100 border-primary-300 text-primary-700 dark:bg-primary-900/20 dark:border-primary-600 dark:text-primary-400'
                    )
                    .addClass(
                        'bg-gray-100 border-gray-300 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600'
                    );
            }
        });
    }

    window.updateSortingIndicators = function() {
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;
        const currentSort = params.get('sort');
        const currentDirection = params.get('direction');

        // Reset all sort arrows
        $('.sort-arrow').removeClass('text-primary-600').addClass('text-gray-400');

        // Highlight current sort direction
        if (currentSort && currentDirection) {
            $(`.sortable[data-sort="${currentSort}"] .sort-${currentDirection}`)
                .removeClass('text-gray-400').addClass('text-primary-600');
        }
    }

    function showDeleteModal() {
        const modal = $('#deleteModal');
        const modalContent = $('#deleteModalContent');

        modal.removeClass('hidden');
        $('body').addClass('overflow-hidden');

        // Trigger animation
        setTimeout(function() {
            modal.removeClass('opacity-0');
            modalContent.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = $('#deleteModal');
        const modalContent = $('#deleteModalContent');

        // Start closing animation
        modal.addClass('opacity-0');
        modalContent.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');

        // Hide modal after animation
        setTimeout(function() {
            modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            deleteForm = null;

            // Reset modal content
            $('#deleteEmployeeName').empty();
            $('#deletePeriod').empty();
            $('#deleteSalary').empty();

            // Reset button state
            const confirmBtn = $('#confirmDeleteBtn');
            confirmBtn.html(`
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Ya, Hapus Data
            `).prop('disabled', false);
        }, 300);
    }

    document.addEventListener('DOMContentLoaded', function() {
        let currentUrl = new URL(window.location);
        let params = currentUrl.searchParams;

        // If ajax_request parameter exists in URL (from page refresh), redirect to clean URL
        if (params.has('ajax_request')) {
            params.delete('ajax_request');
            currentUrl.search = params.toString();
            window.location.href = currentUrl.toString();
            return;
        }

        // Handle browser back/forward navigation
        window.addEventListener('popstate', function(event) {
            // For browser navigation, do a full page reload instead of AJAX
            window.isNavigating = true;
            // console.log('Browser navigation detected, reloading page...');
            window.location.reload();
        });

        // Also handle page unload to prevent AJAX during navigation
        window.addEventListener('beforeunload', function() {
            window.isNavigating = true;
        });

        // Set default filter to current month and year if not already set
        const currentMonth = {{ $currentMonth }};
        const currentYear = {{ $currentYear }};

        // If no month or year is selected in the URL, set them to current values and reload
        let needsReload = false;

        if (!params.has('bulan')) {
            params.set('bulan', currentMonth);
            needsReload = true;
        }

        if (!params.has('tahun')) {
            params.set('tahun', currentYear);
            needsReload = true;
        }

        // Set default per_page if not present
        if (!params.has('per_page')) {
            params.set('per_page', '10');
            needsReload = true;
        }

        if (needsReload) {
            currentUrl.search = params.toString();
            window.history.pushState({}, '', currentUrl.toString());
            // console.log('Setting default filters and reloading with URL:', currentUrl.toString());
            // For initial load with default filters, do a regular page redirect instead of AJAX
            window.location.href = currentUrl.toString();
            return;
        }

        // Pre-select the values in the dropdowns
        setTimeout(function() {
            // console.log('Setting dropdown values - bulan:', params.get('bulan'), 'tahun:', params.get(
            //     'tahun'));
            $('.filter-bulan').val(params.get('bulan') || '');
            $('.filter-tahun-input').val(params.get('tahun') || '');
            $('.filter-status').val(params.get('status') || '');
            $('.per-page-select').val(params.get('per_page') || '10');

            // Update sorting indicators on page load
            window.updateSortingIndicators();

            // Update clear search button on page load
            window.toggleClearSearchButton();

            // Set initial pagination info from server data
            const totalData = {{ $penggajian->total() }};
            const firstItem = {{ $penggajian->firstItem() ?? 0 }};
            const lastItem = {{ $penggajian->lastItem() ?? 0 }};

            if (totalData > 0) {
                $('.pagination-info').text(
                    `Menampilkan ${firstItem} sampai ${lastItem} dari ${totalData} data`
                );
            } else {
                $('.pagination-info').text('Tidak ada data yang ditemukan');
            }
        }, 100);

        // Filter functions
        $('.search-btn').on('click', function() {
            params.set('search', $('.search-input').val());
            params.delete('page'); // Reset to first page when searching
            currentUrl.search = params.toString();
            loadTable(currentUrl.toString());
        });

        $('.search-input').on('keypress', function(e) {
            if (e.which === 13) {
                params.set('search', $(this).val());
                params.delete('page'); // Reset to first page when searching
                currentUrl.search = params.toString();
                loadTable(currentUrl.toString());
            }
        });

        // Clear search functionality
        $('.clear-search-btn').on('click', function() {
            $('.search-input').val('');
            params.delete('search');
            params.delete('page');
            currentUrl.search = params.toString();
            loadTable(currentUrl.toString());
            // Use global function
            window.toggleClearSearchButton();
        });

        // Show/hide clear search button based on input
        $('.search-input').on('input', function() {
            window.toggleClearSearchButton();
        });

        // Handle month and status dropdown changes
        $('.filter-status, .filter-bulan, .per-page-select').on('change', function() {
            const name = $(this).hasClass('filter-status') ? 'status' :
                $(this).hasClass('filter-bulan') ? 'bulan' : 'per_page';

            // console.log(`Filter changed: ${name} = ${$(this).val()}`);

            if ($(this).val()) {
                params.set(name, $(this).val());
            } else {
                params.delete(name);
            }

            // Reset to first page when filtering (except for per_page)
            if (name !== 'per_page') {
                params.delete('page');
            }

            currentUrl.search = params.toString();
            // console.log('Loading with new URL after filter change:', currentUrl.toString());
            loadTable(currentUrl.toString());
        });

        // Handle year input changes
        $('.filter-tahun-input').on('change blur', function() {
            const yearValue = $(this).val();

            if (yearValue && yearValue >= 2000 && yearValue <= 2099) {
                params.set('tahun', yearValue);
            } else if (!yearValue) {
                params.delete('tahun');
            } else {
                // Invalid year, reset to current year
                $(this).val(currentYear);
                params.set('tahun', currentYear);
            }

            params.delete('page'); // Reset to first page when changing year
            currentUrl.search = params.toString();
            loadTable(currentUrl.toString());
            updateQuickYearButtons();
        });

        // Handle year input keypress (Enter key)
        $('.filter-tahun-input').on('keypress', function(e) {
            if (e.which === 13) {
                $(this).trigger('blur');
            }
        });

        // Handle quick year buttons
        $(document).on('click', '.quick-year-btn', function() {
            const year = $(this).data('year');
            $('.filter-tahun-input').val(year);
            params.set('tahun', year);
            params.delete('page'); // Reset to first page when changing year
            currentUrl.search = params.toString();
            loadTable(currentUrl.toString());
            window.updateQuickYearButtons();
        });

        $('.reset-filter-btn').on('click', function() {
            params.delete('search');
            params.delete('status');
            params.delete('bulan');
            params.delete('tahun');
            params.delete('sort');
            params.delete('direction');
            params.delete('page');
            // Keep per_page setting when resetting other filters
            // params.delete('per_page');

            // Reset form elements
            $('.search-input').val('');
            $('.filter-status').val('');
            $('.filter-bulan').val('');
            $('.filter-tahun-input').val('');
            // Don't reset per_page selector
            // $('.per-page-select').val('10');

            currentUrl.search = params.toString();
            loadTable(currentUrl.toString());
            window.updateQuickYearButtons();
            window.updateSortingIndicators();
        });

        // Sorting
        $('.sortable').on('click', function() {
            const sort = $(this).data('sort');
            let direction = 'asc';

            if (params.get('sort') === sort) {
                direction = params.get('direction') === 'asc' ? 'desc' : 'asc';
            }

            params.set('sort', sort);
            params.set('direction', direction);
            params.delete('page'); // Reset to first page when sorting
            currentUrl.search = params.toString();
            loadTable(currentUrl.toString());
        });

        // Pagination
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            loadTable($(this).attr('href'));
        });

        // Delete confirmation
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            deleteForm = $(this).closest('form');

            // Get data from the row
            const row = $(this).closest('tr');
            const employeeName = row.find('td:eq(1) .text-sm.font-medium').text().trim();
            const employeeNip = row.find('td:eq(1) .text-sm.text-gray-500').text().trim();
            const month = row.find('td:eq(2)').text().trim();
            const year = row.find('td:eq(3)').text().trim();
            const salary = row.find('td:eq(4)').text().trim();

            // Populate modal with data
            $('#deleteEmployeeName').text(employeeName);
            $('#deletePeriod').text(`${month} ${year}`);
            $('#deleteSalary').html(`<strong>Total Gaji:</strong> ${salary}`);

            // Add NIP if available
            if (employeeNip) {
                $('#deleteEmployeeName').append(
                    `<br><small class="text-sm text-gray-500 dark:text-gray-400">${employeeNip}</small>`
                );
            }

            // Show modal
            showDeleteModal();
        });

        // Modal button event handlers
        $('#closeModalBtn, #cancelDeleteBtn').on('click', function() {
            closeDeleteModal();
        });

        // Confirm delete action
        $('#confirmDeleteBtn').on('click', function() {
            if (deleteForm) {
                const button = $(this);
                const originalText = button.html();

                // Show loading state
                button.html(`
                    <svg class="animate-spin w-4 h-4 mr-2 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menghapus...
                `).prop('disabled', true);

                // Submit form
                deleteForm.submit();
            }
        });

        // Close modal when clicking outside
        $(document).on('click', '#deleteModal', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal with Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$('#deleteModal').hasClass('hidden')) {
                closeDeleteModal();
            }
        });

        // Ajax Table loading function is now global
        // The loadTable function has been moved to global scope above
    });
</script>

<style>
    /* Sorting arrows styling */
    .sortable .sort-arrow {
        transition: color 0.2s ease-in-out;
        opacity: 0.6;
    }

    .sortable:hover .sort-arrow {
        opacity: 0.8;
    }

    .sortable .sort-arrow.text-primary-600 {
        opacity: 1;
    }

    /* Spacing for sort arrows */
    .sort-arrow+.sort-arrow {
        margin-top: -2px;
    }

    /* Responsive table improvements */
    @media (max-width: 768px) {
        .sortable {
            font-size: 0.75rem;
            padding: 0.5rem;
        }

        .sort-arrow {
            width: 0.75rem;
            height: 0.75rem;
        }
    }

    /* Smooth transitions for all interactive elements */
    .pagination-links a,
    .sortable,
    .filter-bulan,
    .filter-status,
    .per-page-select,
    .search-input {
        transition: all 0.2s ease-in-out;
    }

    /* Hide pagination info from vendor template to avoid duplication */
    .pagination-links .flex.items-center.justify-between>div.text-sm p {
        display: none !important;
    }
</style>
