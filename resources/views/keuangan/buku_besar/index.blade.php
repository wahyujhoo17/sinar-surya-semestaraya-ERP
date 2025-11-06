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

        {{-- Filter Section --}}
        <div
            class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden mb-6">
            <form method="GET" action="{{ route('keuangan.buku-besar.index') }}" id="filterForm">
                <div class="p-5">
                    {{-- Row 1: Account Filter --}}
                    <div class="grid grid-cols-1 gap-4 mb-4">
                        {{-- Account Multi-Select Dropdown --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="inline w-4 h-4 mr-1.5 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                Filter Akun
                            </label>

                            {{-- Custom Dropdown Button --}}
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <span id="dropdown-label" class="text-gray-500 dark:text-gray-400">Pilih
                                        Akun...</span>
                                    <svg class="w-5 h-5 ml-2 transition-transform" :class="{ 'rotate-180': open }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Menu --}}
                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-96 overflow-y-auto"
                                    style="display: none;">

                                    {{-- Search Box --}}
                                    <div
                                        class="sticky top-0 bg-white dark:bg-gray-800 p-3 border-b border-gray-200 dark:border-gray-700 z-10">
                                        <input type="text" id="account-search"
                                            placeholder="Cari kode atau nama akun..."
                                            class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            @input="filterAccounts($event.target.value)">
                                    </div>

                                    {{-- Select All / Clear All --}}
                                    <div
                                        class="sticky top-[57px] bg-white dark:bg-gray-800 px-3 py-2 border-b border-gray-200 dark:border-gray-700 flex gap-2 z-10">
                                        <button type="button" onclick="selectAllAccounts()"
                                            class="flex-1 px-3 py-1.5 text-xs font-medium bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                                            ✓ Pilih Semua
                                        </button>
                                        <button type="button" onclick="clearAllAccounts()"
                                            class="flex-1 px-3 py-1.5 text-xs font-medium bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                            ✕ Bersihkan
                                        </button>
                                    </div>

                                    {{-- Account List with Checkboxes --}}
                                    <div class="py-2">
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
                                            $selectedAkunIds = request('akun_ids', []);
                                            if (!is_array($selectedAkunIds)) {
                                                $selectedAkunIds = [$selectedAkunIds];
                                            }
                                            if (request('akun_id')) {
                                                $selectedAkunIds[] = request('akun_id');
                                            }
                                        @endphp

                                        @foreach (['asset', 'liability', 'equity', 'income', 'expense', 'other'] as $category)
                                            @if (isset($groupedAccounts[$category]) && $groupedAccounts[$category]->count() > 0)
                                                {{-- Category Header --}}
                                                <div
                                                    class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 sticky top-[113px] border-b border-gray-100 dark:border-gray-800">
                                                    {{ $categoryLabels[$category] }}
                                                </div>

                                                {{-- Account Items --}}
                                                @foreach ($groupedAccounts[$category] as $akun)
                                                    <label
                                                        class="account-item flex items-center px-4 py-2.5 hover:bg-blue-50 dark:hover:bg-blue-900/20 cursor-pointer transition-colors border-b border-gray-50 dark:border-gray-800/50 last:border-0"
                                                        data-search="{{ strtolower($akun->kode . ' ' . $akun->nama) }}">
                                                        <input type="checkbox" name="akun_ids[]"
                                                            value="{{ $akun->id }}"
                                                            {{ in_array($akun->id, $selectedAkunIds) ? 'checked' : '' }}
                                                            class="account-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                            onchange="updateDropdownLabel()">
                                                        <span
                                                            class="ml-3 text-sm text-gray-700 dark:text-gray-300 flex-1">
                                                            <span
                                                                class="font-semibold text-gray-900 dark:text-white">{{ $akun->kode }}</span>
                                                            <span class="text-gray-500 dark:text-gray-400 mx-1">•</span>
                                                            <span>{{ $akun->nama }}</span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Selected Accounts Tags --}}
                            <div id="selected-accounts-tags" class="mt-2.5 flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    {{-- Row 2: Period, Date Range & Actions --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                        {{-- Period Dropdown --}}
                        <div class="lg:col-span-4">
                            <label for="periode_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Periode Akuntansi
                            </label>
                            <select name="periode_id" id="periode_id"
                                class="w-full px-4 py-2.5 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <option value="">Gunakan Tanggal Custom</option>
                                @foreach ($periodes as $periode)
                                    <option value="{{ $periode->id }}"
                                        {{ request('periode_id') == $periode->id ? 'selected' : '' }}>
                                        {{ $periode->nama }} ({{ $periode->tanggal_mulai->format('d/m/Y') }} -
                                        {{ $periode->tanggal_akhir->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date Range --}}
                        <div class="lg:col-span-3">
                            <label for="tanggal_awal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Dari Tanggal
                            </label>
                            <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}"
                                class="w-full px-4 py-2.5 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="lg:col-span-3">
                            <label for="tanggal_akhir"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sampai Tanggal
                            </label>
                            <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                value="{{ $tanggalAkhir }}"
                                class="w-full px-4 py-2.5 text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Action Buttons --}}
                        <div class="lg:col-span-2 flex gap-2">
                            <button type="submit" title="Tampilkan Data"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors shadow-sm font-medium flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span class="hidden xl:inline">Filter</span>
                            </button>

                            <button type="button" onclick="resetFilters()" title="Reset Semua Filter"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2.5 rounded-lg transition-colors shadow-sm flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Row 3: Additional Options --}}
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-4 border-t border-gray-200 dark:border-gray-700 mt-4">
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="include_drafts" id="include_drafts" value="1"
                                    {{ request()->get('include_drafts', '0') == '1' ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Tampilkan Draft
                                    Jurnal</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Quick Period:</span>
                            <button type="button" onclick="setQuickPeriod('today')"
                                class="px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors">
                                Hari Ini
                            </button>
                            <button type="button" onclick="setQuickPeriod('thisMonth')"
                                class="px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors">
                                Bulan Ini
                            </button>
                            <button type="button" onclick="setQuickPeriod('thisYear')"
                                class="px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors">
                                Tahun Ini
                            </button>
                        </div>
                    </div>
            </form>
        </div>

        @if (!empty($multipleAccountsData))
            {{-- Multiple Accounts View --}}
            <div class="mb-8">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2 ml-5">Detail Multiple Akun</h2>
                    <p class="text-gray-600 dark:text-gray-400 ml-5">
                        Menampilkan {{ count($multipleAccountsData) }} akun terpilih untuk periode
                        {{ \Carbon\Carbon::parse($tanggalAwal)->format('d F Y') }} -
                        {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d F Y') }}
                    </p>
                </div>

                {{-- Accounts List --}}
                @foreach ($multipleAccountsData as $index => $bukuBesarData)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-4 ml-5 mr-5">
                        {{-- Account Header --}}
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $bukuBesarData['akun']->kode }} - {{ $bukuBesarData['akun']->nama }}
                                    </h3>
                                    <div class="mt-1 flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($bukuBesarData['akun']->kategori == 'asset') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($bukuBesarData['akun']->kategori == 'liability') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($bukuBesarData['akun']->kategori == 'equity') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                            @elseif($bukuBesarData['akun']->kategori == 'income') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($bukuBesarData['akun']->kategori == 'expense') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                            @elseif($bukuBesarData['akun']->kategori == 'purchase') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($bukuBesarData['akun']->kategori == 'other_income') bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200
                                            @elseif($bukuBesarData['akun']->kategori == 'other_expense') bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                            @if ($bukuBesarData['akun']->kategori == 'other_income')
                                                Pendapatan di Luar Usaha
                                            @elseif($bukuBesarData['akun']->kategori == 'other_expense')
                                                Biaya di Luar Usaha
                                            @elseif($bukuBesarData['akun']->kategori == 'purchase')
                                                Pembelian
                                            @else
                                                {{ ucfirst($bukuBesarData['akun']->kategori) }}
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $bukuBesarData['total_transaksi'] }} transaksi
                                        </span>
                                    </div>
                                </div>
                                <button onclick="toggleMultiAccountDetail({{ $index }})"
                                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                    <svg class="w-6 h-6 multi-chevron-{{ $index }} transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Summary Cards --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 dark:bg-gray-900/50">
                            <div class="text-center">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Saldo Awal</div>
                                <div
                                    class="text-base font-bold {{ $bukuBesarData['opening_balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                    Rp {{ number_format(abs($bukuBesarData['opening_balance']), 0, ',', '.') }}
                                    {{ $bukuBesarData['opening_balance'] < 0 ? '(-)' : '' }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Total Debit</div>
                                <div class="text-base font-bold text-green-600">
                                    Rp {{ number_format($bukuBesarData['period_debit'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Total Kredit</div>
                                <div class="text-base font-bold text-red-600">
                                    Rp {{ number_format($bukuBesarData['period_kredit'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Saldo Akhir</div>
                                <div
                                    class="text-base font-bold {{ $bukuBesarData['ending_balance'] >= 0 ? 'text-purple-600' : 'text-red-600' }}">
                                    Rp {{ number_format(abs($bukuBesarData['ending_balance']), 0, ',', '.') }}
                                    {{ $bukuBesarData['ending_balance'] < 0 ? '(-)' : '' }}
                                </div>
                            </div>
                        </div>

                        {{-- Transaction Detail (Collapsible) --}}
                        <div id="multi-detail-{{ $index }}" class="hidden">
                            @include('keuangan.buku_besar._transaction_detail', [
                                'bukuBesarData' => $bukuBesarData,
                            ])
                        </div>
                    </div>
                @endforeach
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
        @elseif ($allAccountsData)
            {{-- All Accounts Table (No Summary Cards) --}}
            <div class="mb-8">
                <div class="mb-6 px-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3 ml-4">Daftar Semua Akun</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed ml-4">
                        Menampilkan semua akun yang memiliki saldo per
                        <span
                            class="font-medium text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($tanggalAkhir)->format('d F Y') }}</span>
                    </p>
                </div>

                {{-- Accounts Table with Toggle --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mt-4">
                    <div
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Daftar Akun dengan Saldo
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">
                                ({{ count($allAccountsData['accounts']) }} akun)
                            </span>
                        </h3>
                        <div class="flex gap-2">
                            <button onclick="expandAllAccounts()"
                                class="text-xs px-3 py-1.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-md transition-colors font-medium border border-blue-200 dark:border-blue-700">
                                <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                                Expand All
                            </button>
                            <button onclick="collapseAllAccounts()"
                                class="text-xs px-3 py-1.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md transition-colors font-medium border border-gray-200 dark:border-gray-600">
                                <svg class="inline w-4 h-4 mr-1 -mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                                Collapse All
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider w-12">
                                    </th>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Kode Akun
                                    </th>
                                    <th
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Nama Akun
                                    </th>
                                    <th
                                        class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Kategori
                                    </th>
                                    <th
                                        class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Total Transaksi
                                    </th>
                                    <th
                                        class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Total Debit
                                    </th>
                                    <th
                                        class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Total Kredit
                                    </th>
                                    <th
                                        class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Saldo
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($allAccountsData['accounts'] as $accountData)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 cursor-pointer"
                                        onclick="toggleAccountDetail({{ $accountData['account']->id }})">
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button type="button"
                                                class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                                <svg class="w-5 h-5 account-chevron-{{ $accountData['account']->id }} transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $accountData['account']->kode }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
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
                                                @elseif($accountData['account']->kategori == 'purchase') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($accountData['account']->kategori == 'other_income') bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200
                                                @elseif($accountData['account']->kategori == 'other_expense') bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                                                @if ($accountData['account']->kategori == 'other_income')
                                                    Pendapatan di Luar Usaha
                                                @elseif($accountData['account']->kategori == 'other_expense')
                                                    Biaya di Luar Usaha
                                                @elseif($accountData['account']->kategori == 'purchase')
                                                    Pembelian
                                                @else
                                                    {{ ucfirst($accountData['account']->kategori) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                {{ number_format($accountData['transaction_count'] ?? 0) }} transaksi
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                            Rp {{ number_format($accountData['total_debit'], 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 dark:text-white">
                                            Rp {{ number_format($accountData['total_kredit'], 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold {{ $accountData['balance'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $accountData['formatted_balance'] }}
                                        </td>
                                    </tr>
                                    {{-- Detail Row (Hidden by default) --}}
                                    <tr id="account-detail-{{ $accountData['account']->id }}"
                                        class="hidden bg-gray-50 dark:bg-gray-900/50">
                                        <td colspan="8" class="px-6 py-6">
                                            <div class="text-center py-4">
                                                <div
                                                    class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                    <svg class="animate-spin h-5 w-5 mr-2"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    Loading detail transaksi...
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
            // Wrap in IIFE to avoid global scope pollution
            (function() {
                'use strict';

                // Store loaded account details to avoid repeated API calls
                const loadedAccounts = new Set();

                // Clear account selection
                window.clearAccountSelection = function() {
                    const select = document.getElementById('akun_ids');
                    Array.from(select.options).forEach(option => {
                        option.selected = false;
                    });
                    updateSelectedAccountsDisplay();
                };

                // Update selected accounts display
                function updateSelectedAccountsDisplay() {
                    const select = document.getElementById('akun_ids');
                    const display = document.getElementById('selected-accounts-display');
                    const selectedOptions = Array.from(select.selectedOptions);

                    display.innerHTML = '';

                    if (selectedOptions.length > 0) {
                        selectedOptions.forEach(option => {
                            const tag = document.createElement('span');
                            tag.className =
                                'inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800';
                            tag.innerHTML = `
                                ${option.text}
                                <button type="button" onclick="removeAccountSelection(${option.value})" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            `;
                            display.appendChild(tag);
                        });
                    }
                }

                // Remove individual account selection
                window.removeAccountSelection = function(accountId) {
                    const select = document.getElementById('akun_ids');
                    const option = select.querySelector(`option[value="${accountId}"]`);
                    if (option) {
                        option.selected = false;
                    }
                    updateSelectedAccountsDisplay();
                };

                // Update dropdown label and tags for checkbox version
                window.updateDropdownLabel = function() {
                    const checkboxes = document.querySelectorAll('.account-checkbox:checked');
                    const label = document.getElementById('dropdown-label');
                    const tagsContainer = document.getElementById('selected-accounts-tags');

                    if (checkboxes.length === 0) {
                        label.textContent = 'Pilih Akun...';
                        label.classList.add('text-gray-500', 'dark:text-gray-400');
                        label.classList.remove('text-gray-900', 'dark:text-white', 'font-semibold');
                        tagsContainer.innerHTML = '';
                    } else {
                        label.textContent = `${checkboxes.length} Akun Terpilih`;
                        label.classList.remove('text-gray-500', 'dark:text-gray-400');
                        label.classList.add('text-gray-900', 'dark:text-white', 'font-semibold');

                        // Update tags - only show account codes for clean look
                        tagsContainer.innerHTML = '';
                        checkboxes.forEach(checkbox => {
                            const labelElement = checkbox.parentElement.querySelector('span');
                            const fullText = labelElement.textContent.trim();
                            // Extract code (before "•")
                            const code = fullText.split('•')[0].trim();

                            const tag = document.createElement('span');
                            tag.className =
                                'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors';
                            tag.innerHTML = `
                                <span class="font-semibold">${code}</span>
                                <button type="button" onclick="removeAccountTag(${checkbox.value})" 
                                    class="inline-flex items-center justify-center w-4 h-4 rounded hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors"
                                    title="Hapus ${code}">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            `;
                            tagsContainer.appendChild(tag);
                        });
                    }
                };

                // Remove account tag (checkbox version)
                window.removeAccountTag = function(accountId) {
                    const checkbox = document.querySelector(`.account-checkbox[value="${accountId}"]`);
                    if (checkbox) {
                        checkbox.checked = false;
                        updateDropdownLabel();
                    }
                };

                // Select all accounts (checkbox version)
                window.selectAllAccounts = function() {
                    const visibleCheckboxes = document.querySelectorAll(
                        '.account-item:not([style*="display: none"]) .account-checkbox');
                    visibleCheckboxes.forEach(cb => cb.checked = true);
                    updateDropdownLabel();
                };

                // Clear all accounts (checkbox version)
                window.clearAllAccounts = function() {
                    const checkboxes = document.querySelectorAll('.account-checkbox');
                    checkboxes.forEach(cb => cb.checked = false);
                    updateDropdownLabel();
                };

                // Filter accounts
                window.filterAccounts = function(searchText) {
                    const items = document.querySelectorAll('.account-item');
                    const search = searchText.toLowerCase();

                    items.forEach(item => {
                        const text = item.getAttribute('data-search');
                        if (text.includes(search)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                };

                // Listen to select changes
                document.addEventListener('DOMContentLoaded', function() {
                    const select = document.getElementById('akun_ids');
                    if (select) {
                        select.addEventListener('change', updateSelectedAccountsDisplay);
                        // Initialize display
                        updateSelectedAccountsDisplay();
                    }

                    // Initialize checkbox dropdown if exists
                    if (document.getElementById('dropdown-label')) {
                        updateDropdownLabel();
                    }
                });

                // Toggle multiple account detail
                window.toggleMultiAccountDetail = function(index) {
                    const detailDiv = document.getElementById(`multi-detail-${index}`);
                    const chevron = document.querySelector(`.multi-chevron-${index}`);

                    if (detailDiv.classList.contains('hidden')) {
                        detailDiv.classList.remove('hidden');
                        chevron.style.transform = 'rotate(180deg)';
                    } else {
                        detailDiv.classList.add('hidden');
                        chevron.style.transform = 'rotate(0deg)';
                    }
                };

                // Toggle account detail
                window.toggleAccountDetail = function(accountId) {
                    const detailRow = document.getElementById(`account-detail-${accountId}`);
                    const chevron = document.querySelector(`.account-chevron-${accountId}`);

                    if (detailRow.classList.contains('hidden')) {
                        // Show detail
                        detailRow.classList.remove('hidden');
                        chevron.style.transform = 'rotate(90deg)';

                        // Load data if not already loaded
                        if (!loadedAccounts.has(accountId)) {
                            loadAccountDetail(accountId);
                        }
                    } else {
                        // Hide detail
                        detailRow.classList.add('hidden');
                        chevron.style.transform = 'rotate(0deg)';
                    }
                };

                // Load account detail via AJAX
                function loadAccountDetail(accountId) {
                    const detailRow = document.getElementById(`account-detail-${accountId}`);
                    const tanggalAwal = document.getElementById('tanggal_awal').value;
                    const tanggalAkhir = document.getElementById('tanggal_akhir').value;
                    const includeDrafts = document.getElementById('include_drafts').checked ? '1' : '0';

                    // Build URL with parameters
                    const url = new URL(window.location.origin + '/keuangan/buku-besar');
                    url.searchParams.append('akun_id', accountId);
                    url.searchParams.append('tanggal_awal', tanggalAwal);
                    url.searchParams.append('tanggal_akhir', tanggalAkhir);
                    url.searchParams.append('include_drafts', includeDrafts);
                    url.searchParams.append('ajax', '1');

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.html) {
                                detailRow.querySelector('td').innerHTML = data.html;
                                loadedAccounts.add(accountId);
                            } else {
                                detailRow.querySelector('td').innerHTML = `
                                    <div class="text-center py-4 text-red-600 dark:text-red-400">
                                        <p>Gagal memuat detail transaksi</p>
                                    </div>
                                `;
                            }
                        })
                        .catch(error => {
                            console.error('Error loading account detail:', error);
                            detailRow.querySelector('td').innerHTML = `
                                <div class="text-center py-4 text-red-600 dark:text-red-400">
                                    <p>Error: ${error.message}</p>
                                </div>
                            `;
                        });
                }

                // Expand all accounts
                window.expandAllAccounts = function() {
                    document.querySelectorAll('[id^="account-detail-"]').forEach(row => {
                        const accountId = row.id.replace('account-detail-', '');
                        const chevron = document.querySelector(`.account-chevron-${accountId}`);

                        if (row.classList.contains('hidden')) {
                            row.classList.remove('hidden');
                            chevron.style.transform = 'rotate(90deg)';

                            if (!loadedAccounts.has(parseInt(accountId))) {
                                loadAccountDetail(parseInt(accountId));
                            }
                        }
                    });
                };

                // Collapse all accounts
                window.collapseAllAccounts = function() {
                    document.querySelectorAll('[id^="account-detail-"]').forEach(row => {
                        const accountId = row.id.replace('account-detail-', '');
                        const chevron = document.querySelector(`.account-chevron-${accountId}`);

                        row.classList.add('hidden');
                        chevron.style.transform = 'rotate(0deg)';
                    });
                };

                // Set quick period functions
                window.setQuickPeriod = function(period) {
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
                };

                // Format date for input
                function formatDate(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }

                // Reset filters
                window.resetFilters = function() {
                    // Reset account selection (both old select and new checkbox version)
                    if (document.getElementById('akun_ids')) {
                        clearAccountSelection();
                    }
                    if (document.querySelectorAll('.account-checkbox').length > 0) {
                        clearAllAccounts();
                    }

                    // Reset periode
                    if (document.getElementById('periode_id')) {
                        document.getElementById('periode_id').selectedIndex = 0;
                    }

                    // Reset drafts checkbox
                    if (document.getElementById('include_drafts')) {
                        document.getElementById('include_drafts').checked = false;
                    }

                    // Reset date to current month
                    const today = new Date();
                    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                    document.getElementById('tanggal_awal').value = formatDate(startOfMonth);
                    document.getElementById('tanggal_akhir').value = formatDate(endOfMonth);
                };

                // Export functions (for compatibility)
                window.exportToExcel = function() {
                    const params = new URLSearchParams(window.location.search);
                    params.set('export', 'excel');
                    window.location.href = '{{ route('keuangan.buku-besar.export') }}?' + params.toString();
                };

                window.printReport = function() {
                    window.print();
                };
            })();
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

            /* Smooth transition for chevron rotation */
            .transition-transform {
                transition: transform 0.2s ease-in-out;
            }

            /* Multi-select styling with better UX */
            #akun_ids {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e0 #f7fafc;
            }

            #akun_ids::-webkit-scrollbar {
                width: 8px;
            }

            #akun_ids::-webkit-scrollbar-track {
                background: #f7fafc;
                border-radius: 4px;
            }

            #akun_ids::-webkit-scrollbar-thumb {
                background: #cbd5e0;
                border-radius: 4px;
            }

            #akun_ids::-webkit-scrollbar-thumb:hover {
                background: #a0aec0;
            }

            #akun_ids option {
                padding: 8px 12px;
                border-radius: 4px;
                margin: 2px 4px;
            }

            #akun_ids option:checked {
                background: linear-gradient(to right, #3b82f6, #2563eb) !important;
                color: white !important;
            }

            #akun_ids optgroup {
                font-weight: 700;
                font-size: 0.75rem;
                color: #6b7280;
                padding: 8px 12px;
                background: #f3f4f6;
            }

            /* Dark mode styles */
            .dark #akun_ids {
                scrollbar-color: #4b5563 #1f2937;
            }

            .dark #akun_ids::-webkit-scrollbar-track {
                background: #1f2937;
            }

            .dark #akun_ids::-webkit-scrollbar-thumb {
                background: #4b5563;
            }

            .dark #akun_ids::-webkit-scrollbar-thumb:hover {
                background: #6b7280;
            }

            .dark #akun_ids optgroup {
                background: #374151;
                color: #9ca3af;
            }
        </style>
    @endpush
</x-app-layout>
