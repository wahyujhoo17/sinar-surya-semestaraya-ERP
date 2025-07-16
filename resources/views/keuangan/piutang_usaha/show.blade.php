<x-app-layout>
    <div class="w-full max-w-none py-6 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-none mx-auto">
            {{-- Header Section --}}
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-violet-500 to-indigo-600 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detail Piutang Usaha</h1>
                            <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">
                                Invoice: <span
                                    class="font-semibold text-blue-600 dark:text-blue-400">{{ $invoice->nomor }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                        <a href="{{ route('keuangan.piutang-usaha.index') }}"
                            class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 lg:gap-8">
                {{-- Left Column - Invoice Details --}}
                <div class="xl:col-span-3 space-y-6">
                    {{-- Invoice Information Card --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Informasi Invoice
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                            Invoice</dt>
                                        <dd class="text-base font-semibold text-gray-900 dark:text-white">
                                            @if ($invoice->nomor)
                                                <a href="{{ route('penjualan.invoice.show', $invoice->id) }}"
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium hover:underline">
                                                    {{ $invoice->nomor }}
                                                    <svg class="w-4 h-4 inline ml-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                            Invoice</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($invoice->tanggal)->translatedFormat('d F Y') }}
                                        </dd>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer
                                        </dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            <div class="font-semibold">
                                                {{ $invoice->customer->company ?? $invoice->customer->nama }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                ({{ $invoice->customer->kode }})</div>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jatuh
                                            Tempo</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            @if ($invoice->jatuh_tempo)
                                                {{ \Carbon\Carbon::parse($invoice->jatuh_tempo)->translatedFormat('d F Y') }}
                                                @php
                                                    $daysOverdue = \Carbon\Carbon::parse(
                                                        $invoice->jatuh_tempo,
                                                    )->diffInDays(now(), false);
                                                    $daysOverdue = round($daysOverdue);
                                                @endphp
                                                @if ($daysOverdue > 0 && $invoice->sisa_piutang > 0)
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                        Terlambat {{ $daysOverdue }} hari
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Referensi
                                            Sales Order</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            @if ($invoice->salesOrder)
                                                <a href="{{ route('penjualan.sales-order.show', $invoice->sales_order_id) }}"
                                                    target="_blank"
                                                    class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium hover:underline">
                                                    {{ $invoice->salesOrder->nomor }}
                                                    <svg class="w-4 h-4 inline ml-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                        </path>
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat
                                            Oleh</dt>
                                        <dd class="text-base text-gray-900 dark:text-white">
                                            {{ $invoice->user->name ?? 'N/A' }}</dd>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total
                                            Invoice</dt>
                                        <dd class="text-xl font-bold text-blue-600 dark:text-blue-400 font-mono">
                                            Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Sisa
                                            Piutang</dt>
                                        <dd
                                            class="text-xl font-bold {{ $invoice->sisa_piutang > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} font-mono">
                                            Rp {{ number_format($invoice->sisa_piutang, 0, ',', '.') }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Invoice Items --}}
                    @if ($invoice->details && $invoice->details->count() > 0)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    Detail Produk Invoice
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
                                                Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach ($invoice->details as $index => $detail)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $index + 1 }}</td>
                                                <td class="px-4 lg:px-6 py-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $detail->produk->nama_produk ?? ($detail->produk->nama ?? 'Produk Tidak Ditemukan') }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right">
                                                    <span class="font-medium">{{ $detail->quantity }}</span>
                                                    <span
                                                        class="text-gray-500 ml-1">{{ $detail->produk->satuan->nama_satuan ?? ($detail->produk->satuan->nama ?? '') }}</span>
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-mono">
                                                    Rp
                                                    {{ number_format($detail->harga_satuan ?? $detail->harga, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-mono font-semibold">
                                                    Rp
                                                    {{ number_format($detail->sub_total ?? $detail->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <td colspan="4"
                                                class="px-4 lg:px-6 py-4 text-right text-base font-semibold text-gray-700 dark:text-gray-200">
                                                Total Invoice</td>
                                            <td
                                                class="px-4 lg:px-6 py-4 text-right text-lg font-bold text-gray-900 dark:text-gray-100 font-mono">
                                                Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif

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
                        @if ($payments && $payments->count() > 0)
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
                                                Dicatat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach ($payments as $payment)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($payment->tanggal)->translatedFormat('d M Y') }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $payment->nomor_pembayaran ?? ($payment->nomor ?? '-') }}
                                                </td>
                                                <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    <div>
                                                        {{ $payment->akunAkuntansi->nama ?? ($payment->metode_pembayaran ?? 'N/A') }}
                                                    </div>
                                                    @if ($payment->rekeningBank)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $payment->rekeningBank->nama_bank }} -
                                                            {{ $payment->rekeningBank->nomor_rekening }}</div>
                                                    @elseif($payment->kas)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $payment->kas->nama }}</div>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-mono font-semibold">
                                                    Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $payment->user->name ?? 'N/A' }}
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
                                                class="px-4 lg:px-6 py-4 text-right text-lg font-bold text-green-600 dark:text-green-400 font-mono">
                                                Rp {{ number_format($totalPaymentsForInvoice, 0, ',', '.') }}
                                            </td>
                                            <td></td>
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
                                    invoice ini.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Uang Muka History --}}
                    @if ($invoice->uangMukaAplikasi && $invoice->uangMukaAplikasi->count() > 0)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Riwayat Aplikasi Uang Muka
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Tanggal Aplikasi</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Nomor Uang Muka</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Jumlah Diterapkan</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach ($invoice->uangMukaAplikasi as $aplikasi)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                    {{ \Carbon\Carbon::parse($aplikasi->created_at)->translatedFormat('d M Y') }}
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    @if ($aplikasi->uangMukaPenjualan)
                                                        <a href="{{ route('keuangan.uang-muka-penjualan.show', $aplikasi->uang_muka_penjualan_id) }}"
                                                            target="_blank"
                                                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:underline">
                                                            {{ $aplikasi->uangMukaPenjualan->nomor }}
                                                            <svg class="w-3 h-3 inline ml-1" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">Data uang muka tidak
                                                            ditemukan</span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-mono font-semibold">
                                                    Rp {{ number_format($aplikasi->jumlah_aplikasi, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 lg:px-6 py-4">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        Diterapkan
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <td colspan="2"
                                                class="px-4 lg:px-6 py-4 text-right text-base font-semibold text-gray-700 dark:text-gray-200">
                                                Total Uang Muka Diterapkan</td>
                                            <td
                                                class="px-4 lg:px-6 py-4 text-right text-lg font-bold text-blue-600 dark:text-blue-400 font-mono">
                                                Rp {{ number_format($totalUangMukaApplied, 0, ',', '.') }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Return History --}}
                    @if ($invoice->salesOrder && $returnsRelatedToSO && $returnsRelatedToSO->count() > 0)
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
                                    Riwayat Retur (SO: {{ $invoice->salesOrder->nomor }})
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-full">
                                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                                        <tr>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Nomor Retur</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Nilai Retur</th>
                                            <th
                                                class="px-4 lg:px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach ($returnsRelatedToSO as $return)
                                            @php
                                                $currentReturValue = 0;
                                                if ($return->salesOrder && $return->salesOrder->details) {
                                                    $soDetailsOfReturn = $return->salesOrder->details;
                                                    foreach ($return->details as $returDetail) {
                                                        $matchingSoDetail = $soDetailsOfReturn
                                                            ->where('produk_id', $returDetail->produk_id)
                                                            ->first();
                                                        if ($matchingSoDetail) {
                                                            $currentReturValue +=
                                                                ($matchingSoDetail->harga ?? 0) *
                                                                $returDetail->quantity;
                                                        }
                                                    }
                                                } elseif (isset($return->total_nilai_retur)) {
                                                    $currentReturValue = $return->total_nilai_retur;
                                                }
                                            @endphp
                                            @if ($return->tipe_retur == 'pengembalian_dana')
                                                <tr
                                                    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                    <td
                                                        class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                        {{ \Carbon\Carbon::parse($return->tanggal)->translatedFormat('d M Y') }}
                                                    </td>
                                                    <td
                                                        class="px-4 lg:px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        <a href="{{ route('penjualan.retur.show', $return->id) }}"
                                                            target="_blank"
                                                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:underline">
                                                            {{ $return->nomor_retur ?? $return->nomor }}
                                                        </a>
                                                    </td>
                                                    <td
                                                        class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-gray-100 text-right font-mono">
                                                        Rp {{ number_format($currentReturValue, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 lg:px-6 py-4">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $return->status === 'selesai' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                                            {{ ucfirst($return->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right Column - Financial Summary --}}
                <div class="xl:col-span-1 space-y-6">
                    {{-- Financial Summary Card --}}
                    <div
                        class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-800 dark:to-gray-700 rounded-2xl shadow-lg border border-blue-200 dark:border-gray-600 overflow-hidden">
                        <div
                            class="px-6 py-4 bg-white/50 dark:bg-gray-800/50 border-b border-blue-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Ringkasan Keuangan
                            </h3>
                        </div>
                        <div class="p-6 space-y-6">
                            {{-- Total Invoice --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Invoice
                                        </p>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white font-mono">
                                            Rp {{ number_format($invoice->total, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Payments --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total
                                            Pembayaran</p>
                                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 font-mono">
                                            Rp {{ number_format($totalPaymentsForInvoice, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Uang Muka Applied --}}
                            @if ($totalUangMukaApplied > 0)
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Uang Muka
                                                Diterapkan</p>
                                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 font-mono">
                                                Rp {{ number_format($totalUangMukaApplied, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div
                                            class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Nota Kredit Applied --}}
                            @if (($invoice->kredit_terapkan ?? 0) > 0)
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nota Kredit
                                                Diterapkan</p>
                                            <p
                                                class="text-2xl font-bold text-purple-600 dark:text-purple-400 font-mono">
                                                Rp {{ number_format($invoice->kredit_terapkan, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div
                                            class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Outstanding Balance --}}
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-4 border-2 {{ $invoice->sisa_piutang > 0 ? 'border-red-200 dark:border-red-800' : 'border-green-200 dark:border-green-800' }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Piutang
                                        </p>
                                        <p
                                            class="text-3xl font-bold {{ $invoice->sisa_piutang > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} font-mono">
                                            Rp {{ number_format($invoice->sisa_piutang, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div
                                        class="w-12 h-12 {{ $invoice->sisa_piutang > 0 ? 'bg-red-100 dark:bg-red-900/30' : 'bg-green-100 dark:bg-green-900/30' }} rounded-lg flex items-center justify-center">
                                        @if ($invoice->sisa_piutang > 0)
                                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                </path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
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
                                @if ($invoice->status_display == 'Lunas')
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Lunas
                                    </span>
                                @elseif($invoice->status_display == 'Lunas Sebagian')
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Lunas Sebagian
                                    </span>
                                @elseif($invoice->status_display == 'Jatuh Tempo')
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Jatuh Tempo
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $invoice->status_display }}
                                    </span>
                                @endif

                            </div>

                            {{-- Payment Button --}}
                            @if ($invoice->sisa_piutang > 0)
                                <div class="pt-4">
                                    <a href="{{ route('keuangan.pembayaran-piutang.create', ['invoice_id' => $invoice->id]) }}"
                                        class="w-full inline-flex items-center justify-center px-6 py-4 border border-transparent rounded-xl shadow-lg text-base font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-[1.02]">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Proses Pembayaran
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statistik Singkat</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Persentase Terbayar</span>
                                <div class="flex items-center">
                                    <div class="w-20 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                        @php
                                            $totalSeluruhPembayaran =
                                                $totalPaymentsForInvoice +
                                                ($invoice->kredit_terapkan ?? 0) +
                                                ($totalUangMukaApplied ?? 0);
                                            $persentaseTerbayar =
                                                $invoice->total > 0
                                                    ? ($totalSeluruhPembayaran / $invoice->total) * 100
                                                    : 0;
                                            // Cap at 100%
                                            $persentaseTerbayar = min($persentaseTerbayar, 100);
                                        @endphp
                                        <div class="bg-green-500 h-2 rounded-full"
                                            style="width: {{ $persentaseTerbayar }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($persentaseTerbayar, 1) }}%
                                    </span>
                                </div>
                            </div>

                            {{-- Breakdown Detail --}}
                            <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Breakdown
                                    Pembayaran:</h5>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500 dark:text-gray-400">• Pembayaran Piutang</span>
                                        <span class="font-mono text-gray-900 dark:text-white">
                                            Rp {{ number_format($totalPaymentsForInvoice, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    @if ($totalUangMukaApplied > 0)
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-500 dark:text-gray-400">• Uang Muka</span>
                                            <span class="font-mono text-blue-600 dark:text-blue-400">
                                                Rp {{ number_format($totalUangMukaApplied, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif
                                    @if (($invoice->kredit_terapkan ?? 0) > 0)
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-500 dark:text-gray-400">• Nota Kredit</span>
                                            <span class="font-mono text-purple-600 dark:text-purple-400">
                                                Rp {{ number_format($invoice->kredit_terapkan, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif
                                    <div
                                        class="flex justify-between text-xs pt-1 border-t border-gray-100 dark:border-gray-700">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Total
                                            Terbayar</span>
                                        <span class="font-mono font-medium text-green-600 dark:text-green-400">
                                            Rp {{ number_format($totalSeluruhPembayaran, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if ($invoice->jatuh_tempo)
                                @php
                                    $daysLeft = \Carbon\Carbon::parse($invoice->jatuh_tempo)->diffInDays(now(), false);
                                    $daysLeft = round($daysLeft);
                                @endphp
                                <div
                                    class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Hari
                                        Tersisa/Terlambat</span>
                                    <span
                                        class="text-sm font-semibold {{ $daysLeft > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
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
</x-app-layout>
