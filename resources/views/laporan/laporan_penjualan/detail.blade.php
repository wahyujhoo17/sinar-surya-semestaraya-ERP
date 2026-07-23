<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div
            class="mb-6 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/70 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-indigo-600 h-9 w-1.5 rounded-full">
                        </div>
                        Detail Penjualan #{{ $penjualan->nomor }}
                    </h1>
                    <p class="mt-2 text-sm md:text-base text-gray-600 dark:text-gray-400 max-w-3xl">
                        Detail transaksi penjualan lengkap dengan informasi customer, produk, dan pembayaran.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('laporan.penjualan.detail.pdf', $penjualan->id) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <path d="M12 18v-6"></path>
                            <path d="M8 15l4 4 4-4"></path>
                        </svg>
                        Unduh PDF
                    </a>
                    <a href="{{ route('laporan.penjualan.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5"></path>
                            <path d="M12 19l-7-7 7-7"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        @php
            $totalBayar = $penjualan->pembayaranPiutang->sum('jumlah');
            $sisaPembayaran = $penjualan->total - $totalBayar;
        @endphp

        <!-- Top 4 KPI Summary Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Card 1: Total Penjualan -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Penjualan</span>
                    <div class="p-2.5 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-2 text-xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($penjualan->total, 0, ',', '.') }}
                </div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Faktur: {{ $penjualan->nomor }}
                </div>
            </div>

            <!-- Card 2: Status & Dibayar -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Pembayaran</span>
                    <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="mt-2 flex items-center gap-2">
                    @if ($penjualan->status_pembayaran == 'lunas')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-400">LUNAS</span>
                    @elseif($penjualan->status_pembayaran == 'sebagian')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-400">DIBAYAR SEBAGIAN</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-400">BELUM DIBAYAR</span>
                    @endif
                </div>
                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Dibayar: <span class="font-medium text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Card 3: Total HPP (Modal) -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total HPP (Modal)</span>
                    <div class="p-2.5 rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                </div>
                <div class="mt-2 text-xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($marginInfo['total_hpp'] ?? 0, 0, ',', '.') }}
                </div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Biaya Pembelian / Produksi
                </div>
            </div>

            <!-- Card 4: Laba Kotor & Margin -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laba Kotor & Margin</span>
                    <div class="p-2.5 rounded-lg bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($marginInfo['laba_kotor'] ?? 0, 0, ',', '.') }}
                    </span>
                    <span class="px-2 py-0.5 rounded text-xs font-bold {{ ($marginInfo['margin_persen'] ?? 0) >= 20 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' }}">
                        {{ $marginInfo['margin_persen'] ?? 0 }}%
                    </span>
                </div>
                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Persentase keuntungan bersih
                </div>
            </div>
        </div>

        <!-- Mid Grid: Equal Height (items-stretch + h-full) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 items-stretch">
            <!-- Informasi Transaksi & Customer -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 h-full flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                        </svg>
                        Informasi Transaksi & Customer
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-700/60">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-0.5">Nomor Faktur</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $penjualan->nomor }}</span>
                        </div>
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-700/60">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-0.5">Tanggal Penjualan</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d/m/Y') }}</span>
                        </div>
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-700/60">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-0.5">Nama Customer</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white block truncate" title="{{ $penjualan->customer->nama ?? ($penjualan->customer->company ?? '-') }}">
                                {{ $penjualan->customer->nama ?? ($penjualan->customer->company ?? '-') }}
                            </span>
                        </div>
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-700/60">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-0.5">Kode Customer</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $penjualan->customer->kode ?? '-' }}</span>
                        </div>
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-700/60">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-0.5">Petugas Sales</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $penjualan->user->name ?? '-' }}</span>
                        </div>
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-700/60">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-0.5">Nomor SO / PO</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $penjualan->salesOrder->nomor ?? '-' }}
                                @if(!empty($penjualan->salesOrder->nomor_po))
                                    | PO: {{ $penjualan->salesOrder->nomor_po }}
                                @endif
                            </span>
                        </div>
                        <div class="p-2.5 bg-gray-50 dark:bg-gray-700/40 rounded-lg border border-gray-100 dark:border-gray-700/60 sm:col-span-2">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 block mb-0.5">Alamat / Kontak Customer</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ $penjualan->customer->alamat ?? 'Alamat tidak tersedia' }}
                                @if(!empty($penjualan->customer->telepon))
                                    | Telp: {{ $penjualan->customer->telepon }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                @if (!empty($penjualan->catatan))
                    <div class="mt-3 p-2.5 bg-amber-50/60 dark:bg-amber-900/20 rounded-lg border border-amber-200/60 dark:border-amber-700/50">
                        <span class="text-xs font-bold text-amber-800 dark:text-amber-400 block uppercase tracking-wider">Catatan Transaksi</span>
                        <p class="text-sm text-amber-900 dark:text-amber-200 mt-0.5 italic">{{ $penjualan->catatan }}</p>
                    </div>
                @endif
            </div>

            <!-- Ringkasan Keuangan & Billing -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 h-full flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                        </svg>
                        Rincian Pembayaran & Piutang
                    </h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-gray-50/80 dark:bg-gray-700/30">
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Subtotal Item</span>
                            <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($penjualan->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-gray-50/80 dark:bg-gray-700/30">
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Diskon</span>
                            <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-gray-50/80 dark:bg-gray-700/30">
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Pajak (PPN)</span>
                            <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($penjualan->pajak, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-gray-50/80 dark:bg-gray-700/30">
                            <span class="text-gray-600 dark:text-gray-400 font-medium">Ongkos Kirim</span>
                            <span class="font-bold text-gray-900 dark:text-white">Rp {{ number_format($penjualan->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-blue-50/80 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 font-bold">
                            <span class="text-blue-900 dark:text-blue-200">Total Faktur Penjualan</span>
                            <span class="text-base text-blue-900 dark:text-blue-200">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 px-3 rounded-lg bg-emerald-50/60 dark:bg-emerald-900/20">
                            <span class="text-emerald-800 dark:text-emerald-300 font-medium">Total Pembayaran Masuk</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 px-3 rounded-lg {{ $sisaPembayaran > 0 ? 'bg-red-50/80 dark:bg-red-900/30 border border-red-100 dark:border-red-800/50' : 'bg-emerald-50/80 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50' }} font-bold">
                            <span class="{{ $sisaPembayaran > 0 ? 'text-red-900 dark:text-red-200' : 'text-emerald-900 dark:text-emerald-200' }}">Sisa Tagihan (Piutang)</span>
                            <span class="text-base {{ $sisaPembayaran > 0 ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                Rp {{ number_format($sisaPembayaran, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Item Produk -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                        <path fill-rule="evenodd"
                            d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Daftar Item Produk & Analisis Margin
                </h2>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Harga Jual
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Subtotal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    HPP Satuan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Total HPP
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Laba Kotor
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Margin (%)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $detailMarginMap = collect($marginInfo['details'] ?? [])->keyBy('detail_id');
                            @endphp
                            @foreach ($penjualan->details as $index => $detail)
                                @php
                                    $itemMargin = $detailMarginMap->get($detail->id) ?? [];
                                    $hppSatuan = $itemMargin['hpp_satuan'] ?? 0;
                                    $totalHpp = $itemMargin['total_hpp'] ?? 0;
                                    $labaKotor = $itemMargin['laba_kotor'] ?? 0;
                                    $marginPersen = $itemMargin['margin_persen'] ?? 0;
                                @endphp
                                <tr class="{{ $index % 2 == 0 ? 'bg-gray-50 dark:bg-gray-700/50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $detail->nama_produk ?? $detail->produk->nama ?? 'Produk tidak tersedia' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->produk->kode ?? '-' }}
                                            @if ($detail->produk && $detail->produk->satuan)
                                                | Satuan: {{ $detail->produk->satuan->nama ?? '-' }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                        Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        {{ format_quantity($detail->quantity ?? $detail->qty ?? $detail->jumlah ?? 0) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white text-right">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                        Rp {{ number_format($hppSatuan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                        Rp {{ number_format($totalHpp, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right {{ $labaKotor >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        Rp {{ number_format($labaKotor, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-bold rounded-md {{ $marginPersen >= 20 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-400' : ($marginPersen >= 5 ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400') }}">
                                            {{ $marginPersen }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-200 dark:border-gray-600">
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-4 text-right text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Total Penjualan:
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white text-right">
                                    Rp {{ number_format($penjualan->subtotal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Total HPP:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white text-right">
                                    Rp {{ number_format($marginInfo['total_hpp'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right {{ ($marginInfo['laba_kotor'] ?? 0) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    Rp {{ number_format($marginInfo['laba_kotor'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-bold rounded-md bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-400">
                                        {{ $marginInfo['margin_persen'] ?? 0 }}%
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    Riwayat Pembayaran
                </h2>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Metode
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Petugas
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $allPayments = $penjualan->pembayaranPiutang;
                            @endphp
                            @if (count($allPayments) > 0)
                                @foreach ($allPayments as $index => $pembayaran)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-50 dark:bg-gray-700/50' : '' }}">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $pembayaran->metode_pembayaran }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $pembayaran->keterangan ?? '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $pembayaran->user->name ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6"
                                        class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada data pembayaran
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-200 dark:border-gray-600">
                            <tr>
                                <td colspan="2"
                                    class="px-6 py-4 text-right text-sm font-bold text-gray-700 dark:text-gray-300">
                                    Total:
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                    {{ number_format($allPayments->sum('jumlah'), 0, ',', '.') }}
                                </td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Retur Penjualan -->
        @if (count(($penjualan->salesOrder ? $penjualan->salesOrder->returPenjualan : collect())) > 0)
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        Retur Penjualan
                    </h2>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nomor Retur
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tipe Retur
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Dibuat Oleh
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach (($penjualan->salesOrder ? $penjualan->salesOrder->returPenjualan : collect()) as $index => $retur)
                                    <tr class="{{ $index % 2 == 0 ? 'bg-gray-50 dark:bg-gray-700/50' : '' }}">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $index + 1 }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            <a href="/penjualan/retur/{{ $retur->id }}">{{ $retur->nomor }}</a>

                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($retur->tanggal)->format('d/m/Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if ($retur->tipe_retur == 'pengembalian_dana')
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500">
                                                    Pengembalian Dana
                                                </span>
                                            @elseif($retur->tipe_retur == 'tukar_barang')
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                                                    Tukar Barang
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500">
                                                    {{ ucfirst(str_replace('_', ' ', $retur->tipe_retur)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @php
                                                $statusClass = [
                                                    'draft' =>
                                                        'bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500',
                                                    'menunggu_persetujuan' =>
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500',
                                                    'disetujui' =>
                                                        'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500',
                                                    'ditolak' =>
                                                        'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500',
                                                    'diproses' =>
                                                        'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500',
                                                    'menunggu_pengiriman' =>
                                                        'bg-indigo-100 text-indigo-800 dark:bg-indigo-800/30 dark:text-indigo-500',
                                                    'menunggu_barang_pengganti' =>
                                                        'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-500',
                                                    'selesai' =>
                                                        'bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-500',
                                                ];

                                                $statusText = [
                                                    'draft' => 'Draft',
                                                    'menunggu_persetujuan' => 'Menunggu Persetujuan',
                                                    'disetujui' => 'Disetujui',
                                                    'ditolak' => 'Ditolak',
                                                    'diproses' => 'Diproses',
                                                    'menunggu_pengiriman' => 'Menunggu Pengiriman',
                                                    'menunggu_barang_pengganti' => 'Menunggu Barang Pengganti',
                                                    'selesai' => 'Selesai',
                                                ];

                                                $status = $retur->status ?? 'draft';
                                                $class = $statusClass[$status] ?? $statusClass['draft'];
                                                $text = $statusText[$status] ?? ucfirst(str_replace('_', ' ', $status));
                                            @endphp
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                                {{ $text }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ number_format($retur->total, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $retur->user->name ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-200 dark:border-gray-600">
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-4 text-right text-sm font-bold text-gray-700 dark:text-gray-300">
                                        Total Retur:
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                        {{ number_format(($penjualan->salesOrder ? $penjualan->salesOrder->returPenjualan : collect())->sum('total'), 0, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Detail Item Retur -->
            @foreach (($penjualan->salesOrder ? $penjualan->salesOrder->returPenjualan : collect()) as $indexRetur => $retur)
                @if (count($retur->details) > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/60">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm11 1H6v8l4-2 4 2V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                Detail Item Retur #{{ $retur->nomor }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 pl-7">
                                Tanggal: {{ \Carbon\Carbon::parse($retur->tanggal)->format('d/m/Y') }} |
                                Status: {{ $statusText[$retur->status ?? 'draft'] }}
                            </p>
                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                No
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Produk
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Quantity
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Alasan
                                            </th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Keterangan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($retur->details as $index => $detail)
                                            <tr class="{{ $index % 2 == 0 ? 'bg-gray-50 dark:bg-gray-700/50' : '' }}">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $detail->produk->nama ?? 'Produk tidak tersedia' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $detail->produk->kode ?? '-' }}
                                                        @if ($detail->produk && $detail->produk->satuan)
                                                            | Satuan: {{ $detail->produk->satuan->nama ?? '-' }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $detail->quantity }}
                                                    @if ($detail->satuan)
                                                        {{ $detail->satuan->nama ?? '' }}
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $detail->alasan ?? '-' }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $detail->keterangan ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if (!empty($retur->catatan))
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan Retur:</p>
                                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $retur->catatan }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</x-app-layout>
