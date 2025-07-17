<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-primary-600 dark:text-primary-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Jurnal Umum
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Kelola pencatatan jurnal akuntansi untuk PT Sinar Surya Semestaraya
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('keuangan.jurnal-umum.export-excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-green-600 dark:text-green-400"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>
                <a href="{{ route('keuangan.jurnal-umum.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Jurnal Baru
                </a>
            </div>
        </div>

        {{-- Minimalist Filter Section --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('keuangan.jurnal-umum.index') }}" id="filterForm" class="space-y-3">
                {{-- Main Filter Row --}}
                <div class="flex flex-wrap items-end gap-3">
                    {{-- Date Inputs --}}
                    <div>
                        <label for="start_date"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Dari</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                    </div>

                    <div>
                        <label for="end_date"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Sampai</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                    </div>

                    {{-- Period Dropdown --}}
                    <div class="flex-1 min-w-48">
                        <label for="periode_id"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Periode
                            Akuntansi</label>
                        <select name="periode_id" id="periode_id"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                            <option value="">Semua Periode</option>
                            @foreach ($periodes as $periode)
                                <option value="{{ $periode->id }}"
                                    {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama }} ({{ $periode->tanggal_mulai->format('d/m/Y') }} -
                                    {{ $periode->tanggal_akhir->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Account Dropdown --}}
                    <div class="flex-1 min-w-64">
                        <label for="akun_id"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Pilih Akun</label>
                        <select name="akun_id" id="akun_id"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                            <option value="">Semua Akun</option>
                            @php
                                $groupedAccounts = $akuns->groupBy('kategori');
                                $categoryLabels = [
                                    'asset' => 'ASET',
                                    'liability' => 'KEWAJIBAN',
                                    'equity' => 'MODAL',
                                    'income' => 'PENDAPATAN',
                                    'expense' => 'BEBAN',
                                    'other' => 'LAINNYA',
                                ];
                            @endphp
                            @foreach (['asset', 'liability', 'equity', 'income', 'expense', 'other'] as $category)
                                @if (isset($groupedAccounts[$category]) && $groupedAccounts[$category]->count() > 0)
                                    <optgroup label="{{ $categoryLabels[$category] }}">
                                        @foreach ($groupedAccounts[$category] as $akun)
                                            <option value="{{ $akun->id }}"
                                                {{ request('akun_id') == $akun->id ? 'selected' : '' }}>
                                                {{ $akun->kode }} - {{ $akun->nama }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Reference Number Search --}}
                    <div class="flex-1 min-w-48">
                        <label for="no_referensi"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">No.
                            Referensi</label>
                        <input type="text" name="no_referensi" id="no_referensi"
                            value="{{ request('no_referensi') }}" placeholder="Cari nomor referensi..."
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                    </div>

                    {{-- Status Posting Filter --}}
                    <div class="flex-1 min-w-48">
                        <label for="status_posting"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Status
                            Posting</label>
                        <select name="status_posting" id="status_posting"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status_posting') === 'draft' ? 'selected' : '' }}>
                                Draft
                            </option>
                            <option value="posted" {{ request('status_posting') === 'posted' ? 'selected' : '' }}>
                                Posted
                            </option>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-2">
                        <button type="submit" title="Tampilkan Data"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md transition-colors text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>

                        <a href="{{ route('keuangan.jurnal-umum.index') }}" title="Tampilkan Semua Jurnal"
                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md transition-colors text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            All
                        </a>

                        <button type="button" onclick="resetFilters()" title="Reset Filter"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-md transition-colors text-sm flex items-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Quick Period Buttons --}}
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Quick:</span>
                    <button type="button" onclick="setQuickPeriod('today')"
                        class="px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                        Hari Ini
                    </button>
                    <button type="button" onclick="setQuickPeriod('thisMonth')"
                        class="px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                        Bulan Ini
                    </button>
                    <button type="button" onclick="setQuickPeriod('thisYear')"
                        class="px-2 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                        Tahun Ini
                    </button>
                </div>
            </form>
        </div>

        {{-- Journal Entries List --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
            <div
                class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-primary-600 dark:text-primary-400 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Daftar Jurnal Umum</h3>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <span id="total-count"
                        class="bg-primary-50 dark:bg-primary-900/30 px-2 py-1 rounded-md text-primary-700 dark:text-primary-400">
                        {{ $jurnals->total() }} entri jurnal
                    </span>
                </div>
            </div>

            {{-- Loading indicator --}}
            <div id="loading-indicator" class="hidden">
                <div class="px-6 py-8 text-center">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-primary-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-400">Memuat data...</span>
                    </div>
                </div>
            </div>

            <div id="table-container" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead
                        class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 border-b-2 border-gray-200 dark:border-gray-600">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <button type="button" onclick="sortTable('tanggal')"
                                        class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Tanggal
                                        @if (request('sort') === 'tanggal')
                                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                @if (request('direction') === 'asc')
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <button type="button" onclick="sortTable('no_referensi')"
                                        class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        No. Referensi
                                        @if (request('sort') === 'no_referensi')
                                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                @if (request('direction') === 'asc')
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Jumlah Entri
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <button type="button" onclick="sortTable('keterangan')"
                                        class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        Keterangan
                                        @if (request('sort') === 'keterangan')
                                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                @if (request('direction') === 'asc')
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-emerald-500 dark:text-emerald-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Akun Debit
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-blue-500 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                    Akun Kredit
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-purple-500 dark:text-purple-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Total & Status
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Status
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="jurnal-table-body"
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @include('keuangan.jurnal_umum._table', ['jurnals' => $jurnals])
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div id="pagination-info" class="mb-4 md:mb-0 text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan {{ $jurnals->firstItem() ?? 0 }} hingga {{ $jurnals->lastItem() ?? 0 }} dari
                        {{ $jurnals->total() }} entri jurnal
                    </div>
                    <div id="pagination-links">
                        {{ $jurnals->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"></div>
            </div>

            {{-- Modal panel --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                Konfirmasi Hapus Jurnal
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="modal-message">
                                    <!-- Dynamic message will be inserted here -->
                                </p>
                                <div
                                    class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-md border border-yellow-200 dark:border-yellow-800">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                <strong>Perhatian:</strong> Jika jurnal sudah diposting, sistem akan
                                                otomatis membatalkan posting terlebih dahulu untuk memulihkan saldo kas
                                                dan rekening bank, kemudian menghapus seluruh entri jurnal.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmDeleteBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Ya, Hapus Jurnal
                    </button>
                    <button type="button" id="cancelDeleteBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Post/Unpost Confirmation Modal --}}
    <div id="postModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"></div>
            </div>

            {{-- Modal panel --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div id="postModalIcon"
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Icon will be set dynamically -->
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                id="postModalTitle">
                                <!-- Title will be set dynamically -->
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="postModalMessage">
                                    <!-- Dynamic message will be inserted here -->
                                </p>
                                <div id="postModalWarning" class="mt-3 p-3 rounded-md border">
                                    <!-- Warning content will be set dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmPostBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <!-- Button text will be set dynamically -->
                    </button>
                    <button type="button" id="cancelPostBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentSort = '{{ request('sort', 'tanggal') }}';
        let currentDirection = '{{ request('direction', 'desc') }}';
        let loading = false;
        let currentDeleteId = null; // Store current delete ID for modal
        let currentPostForm = null; // Store current post/unpost form for modal

        // Initialize table content
        document.addEventListener('DOMContentLoaded', function() {
            initializeTable();
            setupPaginationListeners();
            setupModalEventListeners();
            setupPostModalEventListeners();
        });

        // Use event delegation for delete buttons - this works even after AJAX reload
        document.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('.btn-delete, [data-delete-id]');
            if (deleteButton) {
                e.preventDefault();
                e.stopPropagation();

                const id = deleteButton.getAttribute('data-delete-id');
                const noReferensi = deleteButton.getAttribute('data-no-referensi') || 'N/A';
                const tanggal = deleteButton.getAttribute('data-tanggal') || 'N/A';

                // console.log('Delete button clicked for ID:', id);
                showDeleteModal(id, noReferensi, tanggal);
            }
        });

        function setupModalEventListeners() {
            // Modal confirm button
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (currentDeleteId) {
                    // console.log('Confirm delete clicked for ID:', currentDeleteId);
                    executeDelete(currentDeleteId);
                } else {
                    // console.error('No currentDeleteId set');
                }
                hideDeleteModal();
            });

            // Modal cancel button
            document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
                // console.log('Delete cancelled');
                hideDeleteModal();
            });

            // Close modal when clicking outside
            document.getElementById('deleteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    // console.log('Modal closed by clicking outside');
                    hideDeleteModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains(
                        'hidden')) {
                    // console.log('Modal closed with Escape key');
                    hideDeleteModal();
                }
            });
        }

        function showDeleteModal(id, noReferensi = 'N/A', tanggal = 'N/A') {
            currentDeleteId = id;

            // Update modal message with detailed information
            const modal = document.getElementById('deleteModal');
            const message = document.getElementById('modal-message');
            message.innerHTML = `
                <div class="space-y-2">
                    <p>Apakah Anda yakin ingin menghapus jurnal berikut?</p>
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                        <div class="grid grid-cols-2 gap-2">
                            <div><strong>ID:</strong> ${id}</div>
                            <div><strong>Tanggal:</strong> ${tanggal}</div>
                            <div class="col-span-2"><strong>No. Referensi:</strong> ${noReferensi}</div>
                        </div>
                    </div>
                    <p class="text-red-600 dark:text-red-400 font-medium">Tindakan ini tidak dapat dibatalkan!</p>
                </div>
            `;

            // Show modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling

            // Focus on cancel button for accessibility
            document.getElementById('cancelDeleteBtn').focus();
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
            currentDeleteId = null;

            // Reset confirm button state
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Ya, Hapus Jurnal';
        }

        function executeDelete(id) {
            // console.log('Attempting to delete journal with ID:', id);

            // First try to find the form
            const form = document.getElementById('delete-form-' + id);

            if (form) {
                // console.log('Form found, submitting...');

                // Disable the confirm button to prevent double submission
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                const originalText = confirmBtn.textContent;
                confirmBtn.disabled = true;
                confirmBtn.textContent = 'Menghapus...';

                // Submit the form
                form.submit();
            } else {
                console.error('Form not found with id: delete-form-' + id);

                // Debug: List all delete forms that exist
                const allForms = document.querySelectorAll('form[id^="delete-form-"]');
                // console.log('Available delete forms:', Array.from(allForms).map(f => f.id));

                // Try alternative method: create and submit form dynamically
                // console.log('Trying alternative method: creating form dynamically');

                const dynamicForm = document.createElement('form');
                dynamicForm.method = 'POST';

                // Build the URL manually using the current page URL
                const baseUrl = window.location.origin + '/keuangan/jurnal-umum/' + id;
                dynamicForm.action = baseUrl;

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                dynamicForm.appendChild(csrfInput);

                // Add method field for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                dynamicForm.appendChild(methodInput);

                // Add to document and submit
                document.body.appendChild(dynamicForm);
                dynamicForm.submit();

                // console.log('Dynamic form created and submitted');
            }
        }

        // Legacy function for backward compatibility (not used anymore)
        function confirmDelete(id) {
            showDeleteModal(id);
        }

        function initializeTable() {
            const tableContainer = document.getElementById('table-container');
            tableContainer.innerHTML = `
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 border-b-2 border-gray-200 dark:border-gray-600">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <button type="button" onclick="sortTable('tanggal')" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Tanggal
                                        ${getSortIcon('tanggal')}
                                    </button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <button type="button" onclick="sortTable('no_referensi')" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        No. Referensi
                                        ${getSortIcon('no_referensi')}
                                    </button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Jumlah Entri
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <button type="button" onclick="sortTable('keterangan')" class="flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        Keterangan
                                        ${getSortIcon('keterangan')}
                                    </button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Akun Debit
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                    Akun Kredit
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-purple-500 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Total & Status
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Status
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                    </svg>
                                    Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="jurnal-table-body" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @include('keuangan.jurnal_umum._table', ['jurnals' => $jurnals])
                    </tbody>
                </table>`;
        }

        function getSortIcon(field) {
            if (currentSort === field) {
                return currentDirection === 'asc' ?
                    '<svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>' :
                    '<svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
            }
            return '';
        }

        function sortTable(field) {
            if (loading) return;

            // Toggle direction if same field, otherwise default to desc
            if (currentSort === field) {
                currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort = field;
                currentDirection = 'desc';
            }

            // Get current filter parameters from the form
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }

            // Add sort parameters
            params.set('sort', currentSort);
            params.set('direction', currentDirection);

            // Reload the page with new sort parameters
            window.location.search = params.toString();
        }

        function loadData(url = null) {
            if (loading) return;

            loading = true;
            showLoading();

            // Build URL
            const baseUrl = url || '{{ route('keuangan.jurnal-umum.index') }}';
            const params = new URLSearchParams();

            // Add current form data
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }

            // Add sort parameters
            params.append('sort', currentSort);
            params.append('direction', currentDirection);

            // Add page parameter if it's in the URL
            if (url && url.includes('page=')) {
                const urlParams = new URLSearchParams(url.split('?')[1]);
                const page = urlParams.get('page');
                if (page) params.append('page', page);
            }

            const fullUrl = `${baseUrl}?${params.toString()}`;

            fetch(fullUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateTable(data);
                    updatePagination(data);
                    updateURL(fullUrl);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data');
                })
                .finally(() => {
                    loading = false;
                    hideLoading();
                });
        }

        function updateTable(data) {
            const tbody = document.getElementById('jurnal-table-body');
            tbody.innerHTML = data.table_html;

            // Update total count
            const totalCount = document.getElementById('total-count');
            totalCount.textContent = `${data.total} entri jurnal`;

            // Debug: Log delete buttons after update
            // console.log('Table updated. Delete buttons found:', document.querySelectorAll('.btn-delete, [data-delete-id]')
            //     .length);
        }

        function updatePagination(data) {
            const paginationInfo = document.getElementById('pagination-info');
            const paginationLinks = document.getElementById('pagination-links');

            paginationInfo.innerHTML =
                `Menampilkan ${data.first_item} hingga ${data.last_item} dari ${data.total} entri jurnal`;
            paginationLinks.innerHTML = data.pagination_html;

            setupPaginationListeners();
        }

        function setupPaginationListeners() {
            const paginationLinks = document.querySelectorAll('#pagination-links a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadData(this.href);
                });
            });
        }

        function updateURL(url) {
            if (window.history && window.history.pushState) {
                window.history.pushState({}, '', url);
            }
        }

        function showLoading() {
            document.getElementById('loading-indicator').classList.remove('hidden');
            document.getElementById('table-container').style.opacity = '0.5';
        }

        function hideLoading() {
            document.getElementById('loading-indicator').classList.add('hidden');
            document.getElementById('table-container').style.opacity = '1';
        }

        // Filter form submission
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            loadData();
        });

        function setQuickPeriod(period) {
            const today = new Date();
            let startDate, endDate;

            switch (period) {
                case 'today':
                    startDate = endDate = today.toISOString().split('T')[0];
                    break;
                case 'thisMonth':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                    endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];
                    break;
                case 'thisYear':
                    startDate = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                    endDate = new Date(today.getFullYear(), 11, 31).toISOString().split('T')[0];
                    break;
            }

            document.getElementById('start_date').value = startDate;
            document.getElementById('end_date').value = endDate;
            // Reset other filters when using quick period
            document.getElementById('periode_id').selectedIndex = 0;
            loadData();
        }

        function resetFilters() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            document.getElementById('periode_id').selectedIndex = 0;
            document.getElementById('akun_id').selectedIndex = 0;
            document.getElementById('no_referensi').value = '';
            document.getElementById('status_posting').selectedIndex = 0;
            currentSort = 'tanggal';
            currentDirection = 'desc';
            loadData();
        }

        // Auto-fill dates when period is selected
        document.getElementById('periode_id').addEventListener('change', function() {
            if (this.value) {
                // Clear date inputs when period is selected
                document.getElementById('start_date').value = '';
                document.getElementById('end_date').value = '';
                loadData();
            }
        });

        // Clear period when dates are manually set
        document.getElementById('start_date').addEventListener('change', function() {
            if (this.value || document.getElementById('end_date').value) {
                document.getElementById('periode_id').selectedIndex = 0;
            }
        });

        document.getElementById('end_date').addEventListener('change', function() {
            if (this.value || document.getElementById('start_date').value) {
                document.getElementById('periode_id').selectedIndex = 0;
            }
        });

        // Handle browser back/forward button using pageshow event for bfcache compatibility
        window.addEventListener('pageshow', function(event) {
            // The pageshow event is fired every time the page is displayed.
            // The event.persisted property is false on initial load, and true if the page is from the bfcache.
            // If the page is restored from the back-forward cache, we need to reload it to
            // ensure we get the correct HTML content instead of a stale JSON response.
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Post/Unpost modal functionality
        // Use event delegation for post/unpost buttons
        document.addEventListener('click', function(e) {
            const postButton = e.target.closest('.post-unpost-btn');
            if (postButton) {
                e.preventDefault();
                e.stopPropagation();

                const action = postButton.getAttribute('data-action');
                const noReferensi = postButton.getAttribute('data-no-referensi') || 'N/A';
                const formAction = postButton.getAttribute('data-form-action');


                // Validate required data
                if (!action || !formAction) {
                    console.error('Missing required data for post/unpost action:', {
                        action,
                        formAction
                    });
                    return;
                }

                // Create a form for submission
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = formAction;

                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Add no_referensi
                const noRefInput = document.createElement('input');
                noRefInput.type = 'hidden';
                noRefInput.name = 'no_referensi';
                noRefInput.value = noReferensi;
                form.appendChild(noRefInput);

                // Add form to body temporarily (required for submission)
                document.body.appendChild(form);
                form.style.display = 'none';

                // console.log('Form created successfully, showing modal'); // Debug log
                showPostModal(action, form, noReferensi);
            }
        });

        function setupPostModalEventListeners() {
            document.getElementById('confirmPostBtn').addEventListener('click', function() {
                if (currentPostForm) {
                    // console.log('Submitting post form'); // Debug log

                    // Disable the confirm button to prevent double submission
                    const confirmBtn = document.getElementById('confirmPostBtn');
                    const originalText = confirmBtn.textContent;
                    confirmBtn.disabled = true;
                    confirmBtn.textContent = 'Memproses...';

                    currentPostForm.submit();

                    // Clean up the form after submission
                    setTimeout(() => {
                        if (currentPostForm && currentPostForm.parentNode) {
                            currentPostForm.parentNode.removeChild(currentPostForm);
                        }
                        closePostModal();
                    }, 100);
                } else {
                    console.error('No currentPostForm set');
                    closePostModal();
                }
            });

            document.getElementById('cancelPostBtn').addEventListener('click', function() {
                // console.log('Post/unpost cancelled');
                closePostModal();
            });

            document.getElementById('postModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    // console.log('Modal closed by clicking outside');
                    closePostModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('postModal').classList.contains(
                        'hidden')) {
                    // console.log('Modal closed with Escape key');
                    closePostModal();
                }
            });
        }

        function showPostModal(action, form, noReferensi = 'N/A') {


            currentPostForm = form;

            const modal = document.getElementById('postModal');
            const title = document.getElementById('postModalTitle');
            const message = document.getElementById('postModalMessage');
            const warning = document.getElementById('postModalWarning');
            const icon = document.getElementById('postModalIcon');
            const confirmBtn = document.getElementById('confirmPostBtn');

            if (!modal || !title || !message || !warning || !icon || !confirmBtn) {
                console.error('Modal elements not found:', {
                    modal,
                    title,
                    message,
                    warning,
                    icon,
                    confirmBtn
                });
                return;
            }

            if (action === 'post') {
                // Post configuration
                title.textContent = 'Konfirmasi Posting Jurnal';

                message.innerHTML = `
                    <div class="space-y-2">
                        <p>Apakah Anda yakin ingin memposting jurnal berikut?</p>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                            <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                        </div>
                    </div>
                `;

                icon.className =
                    'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10';
                icon.innerHTML = `
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                `;

                warning.className =
                    'mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800';
                warning.innerHTML = `
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-300">
                                <strong>Informasi:</strong> Setelah diposting, jurnal ini akan mempengaruhi saldo kas/bank dan tidak dapat diedit lagi. Pastikan semua entri sudah benar sebelum melanjutkan.
                            </p>
                        </div>
                    </div>
                `;

                confirmBtn.className =
                    'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 hover:bg-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
                confirmBtn.textContent = 'Ya, Posting Jurnal';

            } else {
                // Unpost configuration
                title.textContent = 'Konfirmasi Pembatalan Posting';

                message.innerHTML = `
                    <div class="space-y-2">
                        <p>Apakah Anda yakin ingin membatalkan posting jurnal berikut?</p>
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                            <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                        </div>
                    </div>
                `;

                icon.className =
                    'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900/30 sm:mx-0 sm:h-10 sm:w-10';
                icon.innerHTML = `
                    <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                `;

                warning.className =
                    'mt-3 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-md border border-orange-200 dark:border-orange-800';
                warning.innerHTML = `
                    <div class="flex">
                        <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-orange-700 dark:text-orange-300">
                                <strong>Perhatian:</strong> Pembatalan posting akan membalik perubahan saldo kas/bank yang telah dilakukan. Jurnal akan kembali ke status draft dan dapat diedit kembali.
                            </p>
                        </div>
                    </div>
                `;

                confirmBtn.className =
                    'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 hover:bg-orange-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
                confirmBtn.textContent = 'Ya, Unpost';
            }

            // Show modal
            // console.log('Showing modal...'); // Debug log
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
            confirmBtn.focus();
            // console.log('Modal should be visible now'); // Debug log
        }

        function closePostModal() {
            const modal = document.getElementById('postModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling

            // Clean up the form if it exists
            if (currentPostForm && currentPostForm.parentNode) {
                currentPostForm.parentNode.removeChild(currentPostForm);
            }
            currentPostForm = null;

            // Reset confirm button state
            const confirmBtn = document.getElementById('confirmPostBtn');
            confirmBtn.disabled = false;
            confirmBtn.textContent = confirmBtn.textContent.includes('Posting') ? 'Ya, Posting Jurnal' : 'Ya, Unpost';
        }
    </script>
</x-app-layout>
