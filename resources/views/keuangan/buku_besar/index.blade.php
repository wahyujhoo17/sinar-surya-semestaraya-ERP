<x-app-layout :breadcrumbs="[['label' => 'Keuangan', 'url' => '#'], ['label' => 'Buku Besar']]" :currentPage="'Buku Besar'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Buku Besar</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Rincian transaksi per akun akuntansi
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                <a href="{{ route('keuangan.buku-besar.export-excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>
                @if ($selectedAkun)
                    <button onclick="printReport()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak
                    </button>
                @endif
            </div>
        </div>

        {{-- Minimalist Filter Section --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('keuangan.buku-besar.index') }}" id="filterForm" class="space-y-3">
                {{-- Main Filter Row --}}
                <div class="flex flex-wrap items-end gap-3">
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

                    {{-- Period Dropdown --}}
                    <div class="flex-1 min-w-48">
                        <label for="periode_id"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Periode
                            Akuntansi</label>
                        <select name="periode_id" id="periode_id"
                            class="w-full text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                            <option value="">Pilih Periode (atau gunakan tanggal)</option>
                            @foreach ($periodes as $periode)
                                <option value="{{ $periode->id }}"
                                    {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama }} ({{ $periode->tanggal_mulai->format('d/m/Y') }} -
                                    {{ $periode->tanggal_akhir->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date Inputs --}}
                    <div>
                        <label for="tanggal_awal"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Dari</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}"
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                    </div>

                    <div>
                        <label for="tanggal_akhir"
                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Sampai</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}"
                            class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:border-blue-500 focus:ring-blue-500 focus:ring-1">
                    </div>

                    {{-- Draft Journals Toggle --}}
                    <div class="flex items-center gap-2">
                        <label for="include_drafts" class="text-xs font-medium text-gray-600 dark:text-gray-400">
                            Tampilkan Draft
                        </label>
                        <input type="checkbox" name="include_drafts" id="include_drafts" value="1"
                            {{ request()->get('include_drafts', '0') == '1' ? 'checked' : '' }}
                            class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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

                        <a href="{{ route('keuangan.buku-besar.index', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
                            title="Tampilkan Semua Akun"
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

        @if ($allAccountsData)
            {{-- All Accounts Summary --}}
            <div class="mb-8">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Ringkasan Semua Akun</h2>
                    <p class="text-gray-600 dark:text-gray-400">
                        Menampilkan semua akun yang memiliki saldo per
                        {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d F Y') }}
                    </p>
                </div>

                {{-- Category Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="text-blue-600 dark:text-blue-400 text-sm font-medium">Asset</div>
                        <div class="text-lg font-bold text-blue-900 dark:text-blue-100">
                            {{ number_format($allAccountsData['totals_by_category']['asset'], 0, ',', '.') >= 0 ? 'Rp ' . number_format($allAccountsData['totals_by_category']['asset'], 0, ',', '.') : '(Rp ' . number_format(abs($allAccountsData['totals_by_category']['asset']), 0, ',', '.') . ')' }}
                        </div>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="text-red-600 dark:text-red-400 text-sm font-medium">Liability</div>
                        <div class="text-lg font-bold text-red-900 dark:text-red-100">
                            {{ number_format($allAccountsData['totals_by_category']['liability'], 0, ',', '.') >= 0 ? 'Rp ' . number_format($allAccountsData['totals_by_category']['liability'], 0, ',', '.') : '(Rp ' . number_format(abs($allAccountsData['totals_by_category']['liability']), 0, ',', '.') . ')' }}
                        </div>
                    </div>
                    <div
                        class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                        <div class="text-purple-600 dark:text-purple-400 text-sm font-medium">Equity</div>
                        <div class="text-lg font-bold text-purple-900 dark:text-purple-100">
                            {{ number_format($allAccountsData['totals_by_category']['equity'], 0, ',', '.') >= 0 ? 'Rp ' . number_format($allAccountsData['totals_by_category']['equity'], 0, ',', '.') : '(Rp ' . number_format(abs($allAccountsData['totals_by_category']['equity']), 0, ',', '.') . ')' }}
                        </div>
                    </div>
                    <div
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="text-green-600 dark:text-green-400 text-sm font-medium">Income</div>
                        <div class="text-lg font-bold text-green-900 dark:text-green-100">
                            {{ number_format($allAccountsData['totals_by_category']['income'], 0, ',', '.') >= 0 ? 'Rp ' . number_format($allAccountsData['totals_by_category']['income'], 0, ',', '.') : '(Rp ' . number_format(abs($allAccountsData['totals_by_category']['income']), 0, ',', '.') . ')' }}
                        </div>
                    </div>
                    <div
                        class="bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg p-4">
                        <div class="text-orange-600 dark:text-orange-400 text-sm font-medium">Expense</div>
                        <div class="text-lg font-bold text-orange-900 dark:text-orange-100">
                            {{ number_format($allAccountsData['totals_by_category']['expense'], 0, ',', '.') >= 0 ? 'Rp ' . number_format($allAccountsData['totals_by_category']['expense'], 0, ',', '.') : '(Rp ' . number_format(abs($allAccountsData['totals_by_category']['expense']), 0, ',', '.') . ')' }}
                        </div>
                    </div>
                    <div
                        class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-800 rounded-lg p-4">
                        <div class="text-gray-600 dark:text-gray-400 text-sm font-medium">Other</div>
                        <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($allAccountsData['totals_by_category']['other'], 0, ',', '.') >= 0 ? 'Rp ' . number_format($allAccountsData['totals_by_category']['other'], 0, ',', '.') : '(Rp ' . number_format(abs($allAccountsData['totals_by_category']['other']), 0, ',', '.') . ')' }}
                        </div>
                    </div>
                </div>

                {{-- Accounts Table --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Daftar Akun dengan Saldo
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                ({{ count($allAccountsData['accounts']) }} akun)
                            </span>
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kode Akun
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nama Akun
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kategori
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total Debit
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total Kredit
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Saldo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($allAccountsData['accounts'] as $accountData)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $accountData['account']->kode }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $accountData['account']->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($accountData['account']->kategori == 'asset') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($accountData['account']->kategori == 'liability') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($accountData['account']->kategori == 'equity') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @elseif($accountData['account']->kategori == 'income') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($accountData['account']->kategori == 'expense') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                {{ ucfirst($accountData['account']->kategori) }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                            Rp {{ number_format($accountData['total_debit'], 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                            Rp {{ number_format($accountData['total_kredit'], 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $accountData['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $accountData['formatted_balance'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                            <a href="{{ route('keuangan.buku-besar.index', ['akun_id' => $accountData['account']->id, 'tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
                                                class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @elseif ($bukuBesarData)
            {{-- Account Info --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $bukuBesarData['akun']->kode }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Kode Akun</div>
                    </div>

                    <div class="text-center">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $bukuBesarData['akun']->nama }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Nama Akun</div>
                    </div>

                    <div class="text-center">
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ ucfirst($bukuBesarData['akun']->kategori) }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Kategori</div>
                    </div>

                    <div class="text-center">
                        <div
                            class="text-lg font-semibold {{ $bukuBesarData['ending_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}
                            {{ $bukuBesarData['ending_balance'] < 0 ? '(-)' : '' }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Saldo Akhir</div>
                    </div>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="text-blue-600 dark:text-blue-400 text-sm font-medium">Saldo Awal</div>
                    <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                        Rp {{ number_format(abs($bukuBesarData['opening_balance']), 0, ',', '.') }}
                        {{ $bukuBesarData['opening_balance'] < 0 ? '(-)' : '' }}
                    </div>
                </div>

                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="text-green-600 dark:text-green-400 text-sm font-medium">Total Debit</div>
                    <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                        Rp {{ number_format($bukuBesarData['period_debit'], 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="text-red-600 dark:text-red-400 text-sm font-medium">Total Kredit</div>
                    <div class="text-2xl font-bold text-red-900 dark:text-red-100">
                        Rp {{ number_format($bukuBesarData['period_kredit'], 0, ',', '.') }}
                    </div>
                </div>

                <div
                    class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                    <div class="text-purple-600 dark:text-purple-400 text-sm font-medium">Total Transaksi</div>
                    <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                        {{ number_format($bukuBesarData['total_transaksi']) }}
                    </div>
                </div>
            </div>

            {{-- Transaction Table --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Rincian Transaksi
                        <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            ({{ $tanggalAwal }} s.d {{ $tanggalAkhir }})
                        </span>
                    </h3>
                </div>

                @if (count($bukuBesarData['transaksi']) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No. Referensi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Debit
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Kredit
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Saldo
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                {{-- Opening Balance Row --}}
                                <tr class="bg-gray-50 dark:bg-gray-900">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($tanggalAwal)->subDay()->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        -
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        SALDO AWAL PERIODE
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                        -
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">
                                        -
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $bukuBesarData['opening_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format(abs($bukuBesarData['opening_balance']), 0, ',', '.') }}
                                        {{ $bukuBesarData['opening_balance'] < 0 ? '(-)' : '' }}
                                    </td>
                                </tr>

                                {{-- Transaction Rows --}}
                                @foreach ($bukuBesarData['transaksi'] as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($item['transaksi']->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $item['transaksi']->no_referensi }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $item['transaksi']->keterangan }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                            @if ($item['transaksi']->debit > 0)
                                                Rp {{ number_format($item['transaksi']->debit, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                            @if ($item['transaksi']->kredit > 0)
                                                Rp {{ number_format($item['transaksi']->kredit, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $item['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            Rp {{ number_format(abs($item['saldo']), 0, ',', '.') }}
                                            {{ $item['saldo'] < 0 ? '(-)' : '' }}
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- Summary Row --}}
                                <tr class="bg-gray-100 dark:bg-gray-900 font-semibold">
                                    <td colspan="3" class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        TOTAL PERIODE
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                        Rp {{ number_format($bukuBesarData['period_debit'], 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">
                                        Rp {{ number_format($bukuBesarData['period_kredit'], 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $bukuBesarData['ending_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}
                                        {{ $bukuBesarData['ending_balance'] < 0 ? '(-)' : '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada transaksi</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada transaksi untuk akun ini pada periode yang dipilih.
                        </p>
                    </div>
                @endif
            </div>
        @else
            {{-- Empty State --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Buku Besar</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    Klik "Tampilkan Semua Akun" untuk melihat ringkasan saldo semua akun, atau pilih akun spesifik untuk
                    melihat detail transaksi.
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('keuangan.buku-besar.index', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-sm transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Tampilkan Semua Akun
                    </a>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            // Set quick period functions
            function setQuickPeriod(period) {
                const today = new Date();
                let startDate, endDate;

                switch (period) {
                    case 'today':
                        startDate = new Date(today);
                        endDate = new Date(today);
                        break;
                    case 'thisMonth':
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        break;
                    case 'thisYear':
                        startDate = new Date(today.getFullYear(), 0, 1);
                        endDate = new Date(today.getFullYear(), 11, 31);
                        break;
                }

                document.getElementById('tanggal_awal').value = formatDate(startDate);
                document.getElementById('tanggal_akhir').value = formatDate(endDate);
            }

            // Format date for input
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Reset filters
            function resetFilters() {
                // Reset account selection
                document.getElementById('akun_id').selectedIndex = 0;

                // Reset date to current month
                const today = new Date();
                const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                document.getElementById('tanggal_awal').value = formatDate(startOfMonth);
                document.getElementById('tanggal_akhir').value = formatDate(endOfMonth);
            }

            // Export functions (for compatibility)
            function exportToExcel() {
                const params = new URLSearchParams(window.location.search);
                params.set('export', 'excel');
                window.location.href = '{{ route('keuangan.buku-besar.export') }}?' + params.toString();
            }

            function printReport() {
                window.print();
            }
        </script>

        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                body {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                }
            }
        </style>
    @endpush
</x-app-layout>
