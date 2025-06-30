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
                    <svg class="-ml-1 mr-2 h-5 w-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
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
                    <span
                        class="bg-primary-50 dark:bg-primary-900/30 px-2 py-1 rounded-md text-primary-700 dark:text-primary-400">
                        {{ $jurnals->total() }} entri jurnal
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead
                        class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 border-b-2 border-gray-200 dark:border-gray-600">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Tanggal
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    No. Referensi
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
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    Keterangan
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-end">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-emerald-500 dark:text-emerald-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Total Debit
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                <div class="flex items-center justify-end">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 mr-1.5 text-blue-500 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4" />
                                    </svg>
                                    Total Kredit
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
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @php
                            $totalDebit = 0;
                            $totalKredit = 0;
                            $groupedJurnals = [];

                            // Mengelompokkan jurnal berdasarkan no referensi dan tanggal
                            foreach ($jurnals as $jurnal) {
                                $key = $jurnal->tanggal . '-' . $jurnal->no_referensi;

                                if (!isset($groupedJurnals[$key])) {
                                    // Untuk setiap grup jurnal, ambil semua entri terkait (termasuk yang tidak terfilter)
                                    // untuk mendapatkan total debit/kredit yang benar
                                    $allRelatedEntries = \App\Models\JurnalUmum::where(
                                        'no_referensi',
                                        $jurnal->no_referensi,
                                    )
                                        ->where('tanggal', $jurnal->tanggal)
                                        ->get();

                                    $totalDebitForEntry = $allRelatedEntries->sum('debit');
                                    $totalKreditForEntry = $allRelatedEntries->sum('kredit');
                                    $countEntries = $allRelatedEntries->count();

                                    $groupedJurnals[$key] = [
                                        'id' => $jurnal->id,
                                        'tanggal' => $jurnal->tanggal,
                                        'no_referensi' => $jurnal->no_referensi,
                                        'keterangan' => $jurnal->keterangan,
                                        'total_debit' => $totalDebitForEntry,
                                        'total_kredit' => $totalKreditForEntry,
                                        'count' => $countEntries,
                                    ];

                                    // Tambahkan ke total keseluruhan hanya sekali per grup
                                    $totalDebit += $totalDebitForEntry;
                                    $totalKredit += $totalKreditForEntry;
                                }
                            }
                        @endphp

                        @forelse ($groupedJurnals as $jurnal)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($jurnal['tanggal'])->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-mono">{{ $jurnal['no_referensi'] }}</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs font-medium">
                                        {{ $jurnal['count'] }} entri
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    {{ Str::limit($jurnal['keterangan'], 40) }}
                                </td>
                                <td
                                    class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                        {{ number_format($jurnal['total_debit'], 2, ',', '.') }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                                    <span class="text-blue-600 dark:text-blue-400 font-medium">
                                        {{ number_format($jurnal['total_kredit'], 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-sm text-center">
                                    @if (abs($jurnal['total_debit'] - $jurnal['total_kredit']) < 0.01)
                                        <span
                                            class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded text-xs">
                                            Seimbang
                                        </span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 px-2 py-1 rounded text-xs">
                                            Tidak Seimbang
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('keuangan.jurnal-umum.show', $jurnal['id']) }}"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-blue-600 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300 dark:border-blue-700"
                                            title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('keuangan.jurnal-umum.edit', $jurnal['id']) }}"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-yellow-600 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300 dark:border-yellow-700"
                                            title="Edit Jurnal">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button" onclick="confirmDelete('{{ $jurnal['id'] }}')"
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-red-600 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300 dark:border-red-700"
                                            title="Hapus Jurnal">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        <form id="delete-form-{{ $jurnal['id'] }}"
                                            action="{{ route('keuangan.jurnal-umum.destroy', $jurnal['id']) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada
                                            data jurnal umum</span>
                                        <p class="text-gray-400 dark:text-gray-500 mt-1 max-w-md text-center">
                                            Tidak ada data jurnal umum yang ditemukan. Cobalah mengubah filter pencarian
                                            atau tambahkan jurnal baru.
                                        </p>
                                        <a href="{{ route('keuangan.jurnal-umum.create') }}"
                                            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Tambah Jurnal Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0 text-sm text-gray-600 dark:text-gray-400">
                        Menampilkan {{ $jurnals->firstItem() ?? 0 }} hingga {{ $jurnals->lastItem() ?? 0 }} dari
                        {{ $jurnals->total() }} entri jurnal
                    </div>
                    <div>
                        {{ $jurnals->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm(
                    'Apakah Anda yakin ingin menghapus jurnal ini? Tindakan ini akan menghapus seluruh entri jurnal dengan nomor referensi yang sama dan memulihkan saldo kas dan rekening bank yang terkait.'
                )) {
                document.getElementById('delete-form-' + id).submit();
            }
        }

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
            document.getElementById('filterForm').submit();
        }

        function resetFilters() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';
            document.getElementById('akun_id').selectedIndex = 0;
            document.getElementById('no_referensi').value = '';
            document.getElementById('filterForm').submit();
        }
    </script>
</x-app-layout>
