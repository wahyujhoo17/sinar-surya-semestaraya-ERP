<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Navigation --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-blue-600 to-indigo-700 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                                Detail Hutang Usaha
                            </h1>
                            <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-400 flex items-center">
                                <span class="font-medium text-blue-600 dark:text-blue-400">{{ $po->nomor }}</span>
                                <span class="mx-2 text-gray-400">•</span>
                                <span>{{ $po->supplier->nama }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        {{-- Back Button --}}
                        <a href="{{ route('keuangan.hutang-usaha.index') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-500 transition-all hover:border-gray-300 hover:bg-gray-50 hover:text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-300">
                            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>

                        {{-- Primary Actions --}}
                        @if ($po->status_pembayaran != 'lunas')
                            <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $po->id]) }}"
                                class="group inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-blue-600 hover:to-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-1">
                                <svg class="h-4 w-4 transition-transform group-hover:scale-110"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Bayar Hutang
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Secondary Actions --}}
                        <a href="{{ route('keuangan.hutang-usaha.history', $po->id) }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-indigo-600 hover:to-indigo-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-1">
                            <svg class="h-4 w-4 transition-transform group-hover:rotate-12"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat Pembayaran
                        </a>

                        <a href="{{ route('keuangan.hutang-usaha.print', $po->id) }}" target="_blank"
                            class="group inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-purple-500 to-purple-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:from-purple-600 hover:to-purple-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-1">
                            <svg class="h-4 w-4 transition-transform group-hover:scale-110"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- Status Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 relative">
                <div
                    class="absolute top-0 left-0 w-full h-1 
                    {{ $po->status_pembayaran == 'lunas'
                        ? 'bg-gradient-to-r from-green-400 to-green-600'
                        : ($po->status_pembayaran == 'sebagian'
                            ? 'bg-gradient-to-r from-yellow-400 to-yellow-600'
                            : 'bg-gradient-to-r from-red-400 to-red-600') }}">
                </div>
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3.5
                            {{ $po->status_pembayaran == 'lunas'
                                ? 'bg-green-100/80 dark:bg-green-900/30 text-green-600 dark:text-green-400'
                                : ($po->status_pembayaran == 'sebagian'
                                    ? 'bg-yellow-100/80 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400'
                                    : 'bg-red-100/80 dark:bg-red-900/30 text-red-600 dark:text-red-400') }}">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                @if ($po->status_pembayaran == 'lunas')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                @endif
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status Pembayaran</p>
                            <div class="mt-1.5 flex items-baseline">
                                <p
                                    class="text-lg font-semibold {{ $po->status_pembayaran == 'lunas'
                                        ? 'text-green-600 dark:text-green-400'
                                        : ($po->status_pembayaran == 'sebagian'
                                            ? 'text-yellow-600 dark:text-yellow-400'
                                            : 'text-red-600 dark:text-red-400') }}">
                                    @if ($po->status_pembayaran == 'belum_bayar')
                                        Belum Bayar
                                    @elseif($po->status_pembayaran == 'sebagian')
                                        Dibayar Sebagian
                                    @else
                                        Lunas
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Debt Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600">
                </div>
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3.5 bg-blue-100/80 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Hutang</p>
                            <div class="mt-1.5 flex items-baseline">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($po->total, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Paid Amount Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600">
                </div>
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3.5 bg-emerald-100/80 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sudah Dibayar</p>
                            <div class="mt-1.5 flex items-baseline">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($po->total - $sisaHutang, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Remaining Amount Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-400 to-purple-600">
                </div>
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3.5 bg-purple-100/80 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400">
                            <svg class="h-7 w-7" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sisa Hutang</p>
                            <div class="mt-1.5 flex items-baseline">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($sisaHutang, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Sections --}}
        <div class="grid grid-cols-1 gap-8 mb-8">
            {{-- Purchase Order Information --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                <div
                    class="px-6 py-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/75 dark:border-gray-700/75">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Informasi Purchase Order
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Nomor PO</h4>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $po->nomor }}</p>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Tanggal PO</h4>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $po->tanggal_po ? $po->tanggal_po->format('d/m/Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Jatuh Tempo</h4>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $po->tanggal_jatuh_tempo ? $po->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}
                                @if ($po->status_pembayaran != 'lunas' && $po->tanggal_jatuh_tempo)
                                    <span
                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ now()->gt($po->tanggal_jatuh_tempo)
                                            ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'
                                            : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' }}">
                                        {{ now()->gt($po->tanggal_jatuh_tempo)
                                            ? 'Terlambat ' . now()->diffInDays($po->tanggal_jatuh_tempo) . ' hari'
                                            : 'Dalam ' . now()->diffInDays($po->tanggal_jatuh_tempo) . ' hari' }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Supplier</h4>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $po->supplier->nama }}
                            </p>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Telepon</h4>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $po->supplier->telepon ?? '-' }}</p>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Alamat</h4>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $po->supplier->alamat ?? '-' }}</p>
                        </div>
                    </div>

                    <hr class="my-6 border-gray-200 dark:border-gray-700">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Status PO</h4>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $po->status == 'selesai'
                                    ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                                    : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' }}">
                                {{ $po->status == 'selesai' ? 'Selesai' : 'Proses' }}
                            </span>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Keterangan</h4>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $po->keterangan ?? '-' }}</p>
                        </div>
                        <div>
                            <h4
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Dibuat Oleh</h4>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $po->user->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products Table --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                <div
                    class="px-6 py-4 bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/75 dark:border-gray-700/75">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Detail Produk
                        </h3>
                        @if ($po->status_pembayaran != 'lunas')
                            <div
                                class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                Sisa: Rp {{ number_format($po->total - $po->total_dibayar, 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/30">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nama Produk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Harga
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Diskon
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Subtotal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($po->details && count($po->details) > 0)
                                @foreach ($po->details as $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900/20 rounded-md flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-indigo-500 dark:text-indigo-400"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $detail->produk ? $detail->produk->nama : $detail->keterangan }}
                                                    </div>
                                                    @if ($detail->produk && $detail->produk->kode)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $detail->produk->kode }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span
                                                    class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 text-sm font-medium">
                                                    {{ $detail->quantity }}
                                                </span>
                                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                                    @php
                                                        // Handle the unit (satuan) from either a direct property or a relationship
                                                        $satuanName = '';
                                                        if (isset($detail->satuan) && is_object($detail->satuan)) {
                                                            $satuanName = $detail->satuan->nama;
                                                        } elseif (
                                                            isset($detail->satuan) &&
                                                            is_string($detail->satuan)
                                                        ) {
                                                            $satuanName = $detail->satuan;
                                                        } elseif (
                                                            isset($detail->satuan_id) &&
                                                            $detail->produk &&
                                                            $detail->produk->satuan
                                                        ) {
                                                            $satuanName = $detail->produk->satuan->nama;
                                                        }
                                                    @endphp
                                                    {{ $satuanName }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">Rp
                                                {{ number_format($detail->harga, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($detail->diskon && $detail->diskon > 0)
                                                <span
                                                    class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-400">
                                                    {{ $detail->diskon }}%
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Rp
                                                {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center py-5">
                                            <svg class="h-10 w-10 text-gray-400 dark:text-gray-600 mb-2"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                            <p class="text-sm">Tidak ada data produk</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900/30">
                            <tr>
                                <th colspan="4"
                                    class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                    Subtotal:
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($po->subtotal, 0, ',', '.') }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4"
                                    class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                    Diskon:
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $po->diskon ? $po->diskon . '%' : '-' }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4"
                                    class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                    PPN:
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $po->ppn ? $po->ppn . '%' : '-' }}
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4"
                                    class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                                    Total:
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($po->total, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Payment History --}}
            @if ($po->payments && count($po->payments) > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                    <div
                        class="px-6 py-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/75 dark:border-gray-700/75">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Riwayat Pembayaran
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/30">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Metode
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($po->payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $payment->tanggal_pembayaran ? $payment->tanggal_pembayaran->format('d/m/Y') : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Rp
                                                {{ number_format($payment->jumlah, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $payment->metode_pembayaran == 'tunai'
                                                    ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300'
                                                    : 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' }}">
                                                {{ $payment->metode_pembayaran == 'tunai' ? 'Tunai' : 'Transfer' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $payment->keterangan ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('keuangan.pembayaran-hutang.show', $payment->id) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-900/30">
                                <tr>
                                    <th colspan="1"
                                        class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">
                                        Total Pembayaran:
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($po->total_dibayar, 0, ',', '.') }}
                                    </th>
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Returns Section --}}
            @if (isset($returns) && count($returns) > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                    <div
                        class="px-6 py-4 bg-gradient-to-br from-rose-50 to-rose-100 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/75 dark:border-gray-700/75">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Retur Pembelian
                            </h3>
                            <div
                                class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300">
                                Total: Rp {{ number_format($totalReturValue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/30">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        No. Retur
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Nilai
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($returns as $return)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $return->nomor }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $return->tanggal ? $return->tanggal->format('d/m/Y') : '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $returValue = 0;
                                                foreach ($return->details as $detail) {
                                                    $returValue += $detail->harga * $detail->qty;
                                                }
                                            @endphp
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                Rp {{ number_format($returValue, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $return->keterangan ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('pembelian.retur.show', $return->id) }}"
                                                class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-300 font-medium">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Financial Summary Section --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200/50 dark:border-gray-700/50">
                <div
                    class="px-6 py-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750 border-b border-gray-200/75 dark:border-gray-700/75">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Ringkasan Keuangan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-hidden bg-gray-50 dark:bg-gray-900/30 sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-5">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Hutang</dt>
                                    <dd class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                        Rp {{ number_format($po->total, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembayaran
                                    </dt>
                                    <dd class="mt-1 text-base font-semibold text-emerald-600 dark:text-emerald-400">
                                        Rp {{ number_format($po->total - $sisaHutang, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Retur</dt>
                                    <dd class="mt-1 text-base font-semibold text-rose-600 dark:text-rose-400">
                                        Rp {{ number_format($totalReturValue, 0, ',', '.') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Hutang</dt>
                                    <dd class="mt-1 text-base font-bold text-indigo-600 dark:text-indigo-400">
                                        Rp {{ number_format($sisaHutang, 0, ',', '.') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add any JavaScript enhancements here

                // Initialize tooltips
                const tooltipTriggerList = document.querySelectorAll('[data-tooltip-target]');
                tooltipTriggerList.forEach(tooltipTriggerEl => {
                    // Your tooltip initialization code here if needed
                });

                // Add smooth transitions for hover states
                const hoverElements = document.querySelectorAll('.hover\\:bg-gray-50');
                hoverElements.forEach(el => {
                    el.addEventListener('mouseenter', () => {
                        el.style.transition = 'background-color 0.2s ease-in-out';
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
