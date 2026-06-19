<x-app-layout>
    <div class="w-full max-w-none py-4 sm:py-6 px-3 sm:px-4 lg:px-6 xl:px-8">
        <div class="w-full max-w-none mx-auto">
            {{-- Header Section --}}
            <div class="mb-4 sm:mb-6 lg:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-violet-500 to-indigo-600 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Detail Hutang Usaha
                            </h1>
                            <p class="mt-1 text-base sm:text-lg text-gray-600 dark:text-gray-400">
                                PO: <span
                                    class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $po->nomor }}</span>
                                <span class="mx-2 text-gray-400">•</span>
                                <span class="text-gray-600 dark:text-gray-400">{{ $po->supplier->nama }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-2 sm:gap-3">
                        {{-- Back Button --}}
                        <a href="{{ route('keuangan.hutang-usaha.index') }}"
                            class="group inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>

                        {{-- History Button --}}
                        <a href="{{ route('keuangan.hutang-usaha.history', $po->id) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-sky-500 to-cyan-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:from-sky-600 hover:to-cyan-700 hover:shadow-lg hover:shadow-sky-500/30 focus:outline-none focus:ring-2 focus:ring-sky-500/50 focus:ring-offset-1 shadow-sm">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat
                        </a>

                        {{-- Print Button --}}
                        <a href="{{ route('keuangan.hutang-usaha.print', $po->id) }}" target="_blank"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-fuchsia-500 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:from-fuchsia-600 hover:to-purple-700 hover:shadow-lg hover:shadow-purple-500/30 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:ring-offset-1 shadow-sm">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print PDF
                        </a>

                        {{-- Payment / Refund Action Button --}}
                        @if ($po->status_pembayaran == 'kelebihan_bayar')
                            <a href="{{ route('keuangan.pengembalian-dana.create', ['po_id' => $po->id]) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 px-4 py-2.5 text-sm font-semibold text-white transition-all hover:from-blue-600 hover:to-cyan-700 hover:shadow-lg hover:shadow-blue-500/30 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:ring-offset-1 shadow-sm">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Proses Pengembalian
                            </a>
                        @elseif ($po->status_pembayaran != 'lunas')
                            <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $po->id]) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white transition-all hover:from-indigo-600 hover:to-violet-700 hover:shadow-lg hover:shadow-indigo-500/30 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:ring-offset-1 shadow-sm">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Bayar Hutang
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Main Content Grid: 2-column layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">

                {{-- Left Column - Main Details (2/3 width) --}}
                <div class="lg:col-span-2 lg:order-1 order-2 space-y-4 sm:space-y-6">

                    {{-- PO Information Card --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-4 sm:px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Informasi Purchase Order
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                                {{-- Column 1: Order Details --}}
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor PO
                                        </dt>
                                        <dd class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                            {{ $po->nomor }}
                                            <span
                                                class="px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300">
                                                #PO
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal PO
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $po->tanggal_po ? $po->tanggal_po->translatedFormat('d F Y') : '-' }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status PO
                                        </dt>
                                        <dd>
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                {{ $po->status == 'selesai'
                                                    ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 ring-1 ring-emerald-100/60 dark:ring-emerald-500/20'
                                                    : 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 ring-1 ring-amber-100/60 dark:ring-amber-500/20' }}">
                                                <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    @if ($po->status == 'selesai')
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    @endif
                                                </svg>
                                                {{ $po->status == 'selesai' ? 'Selesai' : 'Proses' }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>

                                {{-- Column 2: Supplier Details --}}
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Supplier
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            <div class="font-semibold">{{ $po->supplier->nama }}</div>
                                            @if ($po->supplier->telepon)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $po->supplier->telepon }}</div>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alamat
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $po->supplier->alamat ?? '-' }}
                                        </dd>
                                    </div>
                                </div>

                                {{-- Column 3: Finance Details --}}
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jatuh
                                            Tempo</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $po->tanggal_jatuh_tempo ? $po->tanggal_jatuh_tempo->translatedFormat('d F Y') : '-' }}
                                            @if ($po->status_pembayaran != 'lunas' && $po->tanggal_jatuh_tempo)
                                                <span
                                                    class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ now()->gt($po->tanggal_jatuh_tempo)
                                                        ? 'bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300'
                                                        : 'bg-sky-100 dark:bg-sky-900/30 text-sky-800 dark:text-sky-300' }}">
                                                    {{ now()->gt($po->tanggal_jatuh_tempo)
                                                        ? 'Terlambat ' . now()->diffInDays($po->tanggal_jatuh_tempo) . ' hari'
                                                        : 'Dalam ' . now()->diffInDays($po->tanggal_jatuh_tempo) . ' hari' }}
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total
                                            Hutang</dt>
                                        <dd class="text-xl font-bold text-indigo-600 dark:text-indigo-400 font-mono">
                                            Rp {{ number_format($po->total, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Sisa
                                            Hutang</dt>
                                        <dd
                                            class="text-xl font-bold {{ $sisaHutang > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} font-mono">
                                            Rp {{ number_format($sisaHutang, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            {{-- Dibuat Oleh & Keterangan --}}
                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat Oleh
                                    </dt>
                                    <dd class="flex items-center gap-2">
                                        <span
                                            class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30">
                                            <span
                                                class="text-xs font-medium text-indigo-700 dark:text-indigo-300">{{ $po->user ? strtoupper(substr($po->user->name, 0, 2)) : 'U' }}</span>
                                        </span>
                                        <span
                                            class="text-base text-gray-900 dark:text-white">{{ $po->user->name ?? '-' }}</span>
                                    </dd>
                                </div>
                                @if ($po->keterangan)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Keterangan
                                        </dt>
                                        <dd class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                            {{ $po->keterangan }}
                                        </dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Products Table --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-violet-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                Detail Produk PO
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th
                                            class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Produk</th>
                                        <th
                                            class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Qty</th>
                                        <th
                                            class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Harga Satuan</th>
                                        <th
                                            class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Diskon</th>
                                        <th
                                            class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @if ($po->details && count($po->details) > 0)
                                        @foreach ($po->details as $index => $detail)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $index + 1 }}</td>
                                                <td class="px-4 lg:px-6 py-4">
                                                    <div
                                                        class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $detail->produk ? $detail->produk->nama : $detail->keterangan }}
                                                    </div>
                                                    @if ($detail->produk && $detail->produk->kode)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $detail->produk->kode }}</div>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right">
                                                    @php
                                                        $satuanName = '';
                                                        if (isset($detail->satuan) && is_object($detail->satuan)) {
                                                            $satuanName = $detail->satuan->nama;
                                                        } elseif (isset($detail->satuan) && is_string($detail->satuan)) {
                                                            $satuanName = $detail->satuan;
                                                        } elseif (
                                                            isset($detail->satuan_id) &&
                                                            $detail->produk &&
                                                            $detail->produk->satuan
                                                        ) {
                                                            $satuanName = $detail->produk->satuan->nama;
                                                        }
                                                    @endphp
                                                    <span class="font-medium">{{ $detail->quantity }}</span>
                                                    <span
                                                        class="text-gray-500 ml-1">{{ $satuanName }}</span>
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-mono">
                                                    Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right">
                                                    @if ($detail->diskon && $detail->diskon > 0)
                                                        <span
                                                            class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full bg-teal-100 dark:bg-teal-900/20 text-teal-800 dark:text-teal-400">
                                                            {{ $detail->diskon }}%
                                                        </span>
                                                    @else
                                                        <span class="text-sm text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm font-semibold text-gray-900 dark:text-gray-100 text-right font-mono">
                                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600 mb-3"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1"
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data
                                                    produk</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 lg:px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                            Subtotal</td>
                                        <td
                                            class="px-4 lg:px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100 font-mono">
                                            Rp {{ number_format($po->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @if ($po->diskon)
                                        <tr>
                                            <td colspan="5"
                                                class="px-4 lg:px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                Diskon</td>
                                            <td
                                                class="px-4 lg:px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $po->diskon }}%</td>
                                        </tr>
                                    @endif
                                    @if ($po->ppn)
                                        <tr>
                                            <td colspan="5"
                                                class="px-4 lg:px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                PPN</td>
                                            <td
                                                class="px-4 lg:px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $po->ppn }}%</td>
                                        </tr>
                                    @endif
                                    @if ($po->ongkos_kirim > 0)
                                        <tr>
                                            <td colspan="5"
                                                class="px-4 lg:px-6 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-200">
                                                Ongkos Kirim</td>
                                            <td
                                                class="px-4 lg:px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100 font-mono">
                                                Rp {{ number_format($po->ongkos_kirim, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td colspan="5"
                                            class="px-4 lg:px-6 py-4 text-right text-base font-bold text-gray-700 dark:text-gray-200">
                                            Total</td>
                                        <td
                                            class="px-4 lg:px-6 py-4 text-right text-lg font-bold text-gray-900 dark:text-gray-100 font-mono">
                                            Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    {{-- Payment History --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                                Riwayat Pembayaran
                            </h3>
                        </div>
                        @if ($payments && count($payments) > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Nomor Pembayaran</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Metode</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Jumlah</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Keterangan</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach ($payments as $payment)
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $payment->tanggal ? \Carbon\Carbon::parse($payment->tanggal)->translatedFormat('d M Y') : '-' }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $payment->nomor_pembayaran ?? ($payment->nomor ?? '-') }}
                                                </td>
                                                <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    <span
                                                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        {{ $payment->metode_pembayaran == 'tunai'
                                                            ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300'
                                                            : 'bg-sky-100 dark:bg-sky-900/30 text-sky-800 dark:text-sky-300' }}">
                                                        {{ $payment->metode_pembayaran == 'tunai' ? 'Tunai' : 'Transfer' }}
                                                    </span>
                                                    @if ($payment->rekeningBank)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ $payment->rekeningBank->nama_bank }} -
                                                            {{ $payment->rekeningBank->nomor_rekening }}</div>
                                                    @elseif($payment->kas)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            {{ $payment->kas->nama }}</div>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-mono font-semibold">
                                                    Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $payment->keterangan ?? '-' }}
                                                </td>
                                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex items-center gap-3">
                                                        <a href="{{ route('keuangan.pembayaran-hutang.show', $payment->id) }}"
                                                            class="text-violet-600 dark:text-violet-400 hover:text-violet-900 dark:hover:text-violet-300 font-medium hover:underline">
                                                            Detail
                                                        </a>
                                                        @if (auth()->user()->hasRole('superadmin') ||
                                                                auth()->user()->hasRole('direktur_utama') ||
                                                                auth()->user()->hasRole('administrator') ||
                                                                auth()->user()->hasRole('admin'))
                                                            <a href="{{ route('keuangan.pembayaran-hutang.edit', $payment->id) }}"
                                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 font-medium hover:underline flex items-center gap-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3.5 w-3.5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                Edit
                                                            </a>
                                                            <form
                                                                action="{{ route('keuangan.pembayaran-hutang.destroy', $payment->id) }}"
                                                                method="POST" class="inline"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini? Jurnal dan saldo kas/bank akan disesuaikan otomatis.')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium hover:underline flex items-center gap-1">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-3.5 w-3.5" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <td colspan="3"
                                                class="px-4 lg:px-6 py-4 text-right text-base font-semibold text-gray-700 dark:text-gray-200">
                                                Total Pembayaran</td>
                                            <td
                                                class="px-4 lg:px-6 py-4 text-right text-lg font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                                                Rp {{ number_format($totalPayments, 0, ',', '.') }}
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-gray-200">Belum ada
                                    pembayaran</h3>
                                <p class="mt-2 text-gray-500 dark:text-gray-400">Tidak ada riwayat pembayaran untuk
                                    hutang ini.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Returns Section --}}
                    @if (isset($returns) && count($returns) > 0)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m5 14v-5a2 2 0 00-2-2H6a2 2 0 00-2 2v5a2 2 0 002 2h14a2 2 0 002-2z">
                                        </path>
                                    </svg>
                                    Retur Pembelian
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                No. Retur</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Nilai Retur</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Keterangan</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach ($returns as $return)
                                            <tr
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $return->nomor }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $return->tanggal }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-mono">
                                                    @php
                                                        $returValue = 0;
                                                        $poDetails = $return->purchaseOrder->details;
                                                        foreach ($return->details as $returDetail) {
                                                            $matchingPoDetail = $poDetails
                                                                ->where('produk_id', $returDetail->produk_id)
                                                                ->first();
                                                            if ($matchingPoDetail) {
                                                                $returValue +=
                                                                    $matchingPoDetail->harga * $returDetail->quantity;
                                                            }
                                                        }
                                                    @endphp
                                                    Rp {{ number_format($returValue, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $return->keterangan ?? '-' }}
                                                </td>
                                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm">
                                                    <a href="{{ route('pembelian.retur-pembelian.show', $return->id) }}"
                                                        class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-300 font-medium hover:underline">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <td colspan="2"
                                                class="px-4 lg:px-6 py-4 text-right text-base font-semibold text-gray-700 dark:text-gray-200">
                                                Total Retur</td>
                                            <td
                                                class="px-4 lg:px-6 py-4 text-right text-lg font-bold text-rose-600 dark:text-rose-400 font-mono">
                                                Rp {{ number_format($totalReturValue, 0, ',', '.') }}
                                            </td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Right Column - Financial Summary (1/3 width) --}}
                <div class="lg:col-span-1 lg:order-2 order-1 space-y-4 sm:space-y-6">

                    {{-- Financial Summary Card --}}
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-violet-100 dark:from-gray-800 dark:to-gray-700 rounded-2xl shadow-lg border border-indigo-200 dark:border-gray-600 overflow-hidden">
                        <div
                            class="px-4 sm:px-6 py-4 bg-white/50 dark:bg-gray-800/50 border-b border-indigo-200 dark:border-gray-600">
                            <h3
                                class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Ringkasan Keuangan
                            </h3>
                        </div>
                        <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">

                            {{-- Total Hutang --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Hutang
                                        </p>
                                        <p
                                            class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white font-mono">
                                            Rp {{ number_format($po->total, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Pembayaran --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total
                                            Pembayaran</p>
                                        <p
                                            class="text-xl sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                                            Rp {{ number_format($po->total - $sisaHutang, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Retur (if any) --}}
                            @if (isset($totalReturValue) && $totalReturValue > 0)
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total
                                                Retur</p>
                                            <p
                                                class="text-xl sm:text-2xl font-bold text-orange-600 dark:text-orange-400 font-mono">
                                                Rp {{ number_format($totalReturValue, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div
                                            class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Sisa Hutang --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border-2 {{ $sisaHutang > 0 ? 'border-rose-200 dark:border-rose-800' : 'border-emerald-200 dark:border-emerald-800' }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Hutang
                                        </p>
                                        <p
                                            class="text-2xl sm:text-3xl font-bold {{ $sisaHutang > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} font-mono">
                                            Rp {{ number_format($sisaHutang, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-12 h-12 {{ $sisaHutang > 0 ? 'bg-rose-100 dark:bg-rose-900/30' : 'bg-emerald-100 dark:bg-emerald-900/30' }} rounded-lg flex items-center justify-center">
                                        @if ($sisaHutang > 0)
                                            <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                </path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Status --}}
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status Pembayaran
                                </p>
                                @if ($po->status_pembayaran == 'lunas')
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Lunas
                                    </span>
                                @elseif ($po->status_pembayaran == 'sebagian')
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Dibayar Sebagian
                                    </span>
                                @elseif ($po->status_pembayaran == 'kelebihan_bayar')
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Kelebihan Bayar
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Belum Bayar
                                    </span>
                                @endif
                            </div>

                            {{-- Pay Button --}}
                            @if ($po->status_pembayaran != 'lunas')
                                <div class="pt-2">
                                    @if ($po->status_pembayaran == 'kelebihan_bayar')
                                        <a href="{{ route('keuangan.pengembalian-dana.create', ['po_id' => $po->id]) }}"
                                            class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent rounded-xl shadow-lg text-base font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            Proses Pengembalian Dana
                                        </a>
                                    @else
                                        <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $po->id]) }}"
                                            class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent rounded-xl shadow-lg text-base font-medium text-white bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-[1.02]">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Bayar Hutang Sekarang
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 sm:p-6">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik
                            Singkat</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Persentase Terbayar</span>
                                <div class="flex items-center">
                                    <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                        @php
                                            $totalTerbayar = $po->total - $sisaHutang;
                                            $persentaseTerbayar =
                                                $po->total > 0 ? ($totalTerbayar / $po->total) * 100 : 0;
                                            $persentaseTerbayar = min($persentaseTerbayar, 100);
                                        @endphp
                                        <div class="bg-indigo-500 h-2 rounded-full"
                                            style="width: {{ $persentaseTerbayar }}%">
                                        </div>
                                    </div>
                                    <span
                                        class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($persentaseTerbayar, 1) }}%</span>
                                </div>
                            </div>

                            {{-- Breakdown --}}
                            <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Breakdown
                                    Hutang:</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">• Total PO</span>
                                        <span
                                            class="font-mono text-gray-900 dark:text-white">Rp {{ number_format($po->total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">• Sudah Dibayar</span>
                                        <span
                                            class="font-mono text-emerald-600 dark:text-emerald-400">Rp {{ number_format($po->total - $sisaHutang, 0, ',', '.') }}</span>
                                    </div>
                                    @if (isset($totalReturValue) && $totalReturValue > 0)
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-500 dark:text-gray-400">• Total Retur</span>
                                            <span
                                                class="font-mono text-orange-600 dark:text-orange-400">Rp {{ number_format($totalReturValue, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <div
                                        class="flex justify-between text-xs pt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Sisa Hutang</span>
                                        <span
                                            class="font-mono font-medium {{ $sisaHutang > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">Rp {{ number_format($sisaHutang, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if ($po->tanggal_jatuh_tempo)
                                @php
                                    $daysLeft = $po->tanggal_jatuh_tempo->diffInDays(now(), false);
                                    $daysLeft = round($daysLeft);
                                @endphp
                                <div
                                    class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Hari Tersisa/Terlambat</span>
                                    <span
                                        class="text-sm font-semibold {{ $daysLeft > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                        {{ $daysLeft > 0 ? '+' . $daysLeft . ' hari (terlambat)' : abs($daysLeft) . ' hari tersisa' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Aktivitas / Log --}}
    @if (isset($logs) && $logs->isNotEmpty())
        <div class="mt-8 px-3 sm:px-4 lg:px-6 xl:px-8 pb-8">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Aktivitas Pembayaran
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($logs as $log)
                            <div
                                class="flex items-start gap-4 pb-4 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <div class="flex-shrink-0">
                                    @if ($log->aktivitas === 'ubah')
                                        <div
                                            class="h-9 w-9 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                    @elseif ($log->aktivitas === 'hapus')
                                        <div
                                            class="h-9 w-9 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-rose-600 dark:text-rose-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </div>
                                    @else
                                        <div
                                            class="h-9 w-9 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $log->user->name ?? 'System' }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->created_at->translatedFormat('d M Y H:i') }}
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        @if ($log->aktivitas === 'ubah')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300">
                                                Mengedit pembayaran #{{ $log->data_id }}
                                            </span>
                                            @if ($log->detail)
                                                @php $detail = is_string($log->detail) ? json_decode($log->detail, true) : $log->detail; @endphp
                                                @if (isset($detail['perubahan']) && count($detail['perubahan']) > 0)
                                                    <div
                                                        class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                                        @foreach ($detail['perubahan'] as $field => $change)
                                                            <div class="flex items-center gap-1">
                                                                <span
                                                                    class="font-medium">{{ $field }}:</span>
                                                                <span
                                                                    class="text-rose-500 line-through">{{ is_scalar($change['lama'] ?? '') ? $change['lama'] : '-' }}</span>
                                                                <svg class="h-3 w-3 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 5l7 7-7 7" />
                                                                </svg>
                                                                <span
                                                                    class="text-emerald-600 font-medium">{{ is_scalar($change['baru'] ?? '') ? $change['baru'] : '-' }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif
                                        @elseif ($log->aktivitas === 'hapus')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300">
                                                Menghapus pembayaran #{{ $log->data_id }}
                                            </span>
                                            @if ($log->detail)
                                                @php $detail = is_string($log->detail) ? json_decode($log->detail, true) : $log->detail; @endphp
                                                <div class="mt-2 text-xs text-gray-500 dark:text-gray-400 space-y-0.5">
                                                    @if (isset($detail['nomor']))
                                                        <div>Nomor: <span
                                                                class="font-medium">{{ $detail['nomor'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if (isset($detail['jumlah']))
                                                        <div>Jumlah: <span class="font-medium">Rp
                                                                {{ number_format($detail['jumlah'], 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                    @if (isset($detail['metode_pembayaran']))
                                                        <div>Metode: <span
                                                                class="font-medium">{{ $detail['metode_pembayaran'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if (isset($detail['tanggal']))
                                                        <div>Tanggal: <span
                                                                class="font-medium">{{ $detail['tanggal'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                {{ ucfirst($log->aktivitas) }}
                                            </span>
                                        @endif
                                    </div>
                                    @if ($log->ip_address)
                                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                            IP: {{ $log->ip_address }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
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
