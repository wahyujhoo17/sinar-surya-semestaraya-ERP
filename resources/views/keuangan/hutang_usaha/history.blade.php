<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Navigation --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-indigo-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Riwayat Pembayaran
                                Hutang</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $purchaseOrder->nomor }} - {{ $purchaseOrder->supplier->nama }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('keuangan.hutang-usaha.show', $purchaseOrder->id) }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Detail PO
                    </a>
                    <a href="{{ route('keuangan.hutang-usaha.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Daftar Hutang
                    </a>
                    @if ($purchaseOrder->status_pembayaran != 'lunas')
                        <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $purchaseOrder->id]) }}"
                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Bayar
                        </a>
                    @endif
                    <a href="{{ route('keuangan.hutang-usaha.print', $purchaseOrder->id) }}" target="_blank"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                        <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Print PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Status Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- Status Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg p-3
                            {{ $purchaseOrder->status_pembayaran == 'lunas'
                                ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'
                                : ($purchaseOrder->status_pembayaran == 'sebagian'
                                    ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400'
                                    : 'bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400') }}">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                @if ($purchaseOrder->status_pembayaran == 'lunas')
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
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</p>
                            <div class="mt-1 flex items-baseline">
                                <p
                                    class="text-lg font-semibold
                                    {{ $purchaseOrder->status_pembayaran == 'lunas'
                                        ? 'text-emerald-600 dark:text-emerald-400'
                                        : ($purchaseOrder->status_pembayaran == 'sebagian'
                                            ? 'text-amber-600 dark:text-amber-400'
                                            : 'text-rose-600 dark:text-rose-400') }}">
                                    @if ($purchaseOrder->status_pembayaran == 'belum_bayar')
                                        Belum Bayar
                                    @elseif($purchaseOrder->status_pembayaran == 'sebagian')
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

            {{-- Total PO Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3">
                            <svg class="h-7 w-7 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total PO</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">Rp
                                    {{ number_format($purchaseOrder->total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pembayaran Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 p-3">
                            <svg class="h-7 w-7 text-emerald-600 dark:text-emerald-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Bayar</p>
                            <div class="mt-1 flex items-baseline">
                                <p class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">Rp
                                    {{ number_format($purchaseOrder->total_pembayaran, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sisa Hutang Card --}}
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg hover:shadow-xl rounded-xl border border-gray-200/50 dark:border-gray-700/50 transition-all duration-300 transform hover:-translate-y-1">
                <div class="p-5">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 rounded-lg {{ $purchaseOrder->sisa_hutang > 0 ? 'bg-rose-100 dark:bg-rose-900/30' : 'bg-emerald-100 dark:bg-emerald-900/30' }} p-3">
                            <svg class="h-7 w-7 {{ $purchaseOrder->sisa_hutang > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sisa Hutang</p>
                            <div class="mt-1 flex items-baseline">
                                <p
                                    class="text-lg font-semibold {{ $purchaseOrder->sisa_hutang > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                    Rp {{ number_format($purchaseOrder->sisa_hutang, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Timeline Section - Takes 2/3 of the space on large screens --}}
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Timeline Transaksi
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="relative border-l-4 border-blue-200 dark:border-blue-800 ml-3">
                            <!-- Initial PO -->
                            <div class="mb-8 flex items-start relative">
                                <div class="absolute -left-7 mt-1.5">
                                    <div
                                        class="bg-blue-600 dark:bg-blue-500 h-5 w-5 rounded-full border-4 border-white dark:border-gray-800 shadow">
                                    </div>
                                </div>
                                <div
                                    class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 ml-6 w-full shadow-sm hover:shadow transition-shadow duration-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                                        <div class="flex items-center">
                                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-lg font-semibold text-blue-600 dark:text-blue-400">Purchase
                                                Order Dibuat</span>
                                        </div>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full shadow-inner">
                                            {{ date('d M Y', strtotime($purchaseOrder->tanggal)) }}
                                        </span>
                                    </div>
                                    <div class="ml-8">
                                        <p class="text-gray-700 dark:text-gray-300 mb-1"><span
                                                class="font-semibold">No. PO:</span>
                                            {{ $purchaseOrder->nomor }}</p>
                                        <p class="text-gray-700 dark:text-gray-300 mb-1"><span
                                                class="font-semibold">Supplier:</span>
                                            {{ $purchaseOrder->supplier->nama }}</p>
                                        <p class="text-gray-700 dark:text-gray-300"><span
                                                class="font-semibold">Total:</span> Rp
                                            {{ number_format($purchaseOrder->total, 0, ',', '.') }}</p>
                                        @if ($purchaseOrder->keterangan)
                                            <p
                                                class="text-gray-700 dark:text-gray-300 mt-2 border-t border-gray-200 dark:border-gray-700 pt-2">
                                                <span class="font-semibold">Keterangan:</span>
                                                {{ $purchaseOrder->keterangan }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Combine payments and returns in chronological order -->
                            @php
                                $allTransactions = [];

                                // Add payments to the combined array
                                foreach ($pembayaran as $bayar) {
                                    $allTransactions[] = [
                                        'type' => 'payment',
                                        'date' => $bayar->tanggal,
                                        'data' => $bayar,
                                    ];
                                }

                                // Add returns to the combined array
                                foreach ($retur as $r) {
                                    $allTransactions[] = [
                                        'type' => 'return',
                                        'date' => $r->tanggal,
                                        'data' => $r,
                                    ];
                                }

                                // Sort by date in descending order (newest first)
                                usort($allTransactions, function ($a, $b) {
                                    return strtotime($b['date']) - strtotime($a['date']);
                                });
                            @endphp

                            @forelse($allTransactions as $transaction)
                                @if ($transaction['type'] == 'payment')
                                    @php $bayar = $transaction['data']; @endphp
                                    <div class="mb-8 flex items-start relative">
                                        <div class="absolute -left-7 mt-1.5">
                                            <div
                                                class="bg-emerald-600 dark:bg-emerald-500 h-5 w-5 rounded-full border-4 border-white dark:border-gray-800 shadow">
                                            </div>
                                        </div>
                                        <div
                                            class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-4 ml-6 w-full shadow-sm hover:shadow transition-shadow duration-200">
                                            <div
                                                class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                                                <div class="flex items-center">
                                                    <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400 mr-2"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </path>
                                                    </svg>
                                                    <span
                                                        class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">Pembayaran</span>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full shadow-inner">
                                                    {{ date('d M Y', strtotime($bayar->tanggal)) }}
                                                </span>
                                            </div>
                                            <div class="ml-8">
                                                <p class="text-gray-700 dark:text-gray-300 mb-1"><span
                                                        class="font-semibold">No. Ref:</span>
                                                    {{ $bayar->nomor }}</p>
                                                <p class="text-gray-700 dark:text-gray-300 mb-1"><span
                                                        class="font-semibold">Jumlah:</span> Rp
                                                    {{ number_format($bayar->jumlah, 0, ',', '.') }}</p>
                                                <p class="text-gray-700 dark:text-gray-300"><span
                                                        class="font-semibold">Metode:</span>
                                                    {{ $bayar->metode_pembayaran }}</p>
                                                @if ($bayar->keterangan)
                                                    <p
                                                        class="text-gray-700 dark:text-gray-300 mt-2 border-t border-gray-200 dark:border-gray-700 pt-2">
                                                        <span class="font-semibold">Keterangan:</span>
                                                        {{ $bayar->keterangan }}
                                                    </p>
                                                @endif
                                                @if ($bayar->user)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Dibuat
                                                        oleh:
                                                        {{ $bayar->user->name }}</p>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                @else
                                    @php $r = $transaction['data']; @endphp
                                    @php
                                        $totalReturValue = 0;
                                        foreach ($r->details as $detail) {
                                            $totalReturValue += $detail->harga * $detail->qty;
                                        }
                                    @endphp
                                    <div class="mb-8 flex items-start relative">
                                        <div class="absolute -left-7 mt-1.5">
                                            <div
                                                class="bg-indigo-600 dark:bg-indigo-500 h-5 w-5 rounded-full border-4 border-white dark:border-gray-800 shadow">
                                            </div>
                                        </div>
                                        <div
                                            class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 ml-6 w-full shadow-sm hover:shadow transition-shadow duration-200">
                                            <div
                                                class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-2">
                                                <div class="flex items-center">
                                                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400 mr-2"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z">
                                                        </path>
                                                    </svg>
                                                    <span
                                                        class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">Retur</span>
                                                </div>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full shadow-inner">
                                                    {{ date('d M Y', strtotime($r->tanggal)) }}
                                                </span>
                                            </div>
                                            <div class="ml-8">
                                                <p class="text-gray-700 dark:text-gray-300 mb-1"><span
                                                        class="font-semibold">No. Retur:</span>
                                                    {{ $r->nomor }}</p>
                                                <p class="text-gray-700 dark:text-gray-300 mb-1"><span
                                                        class="font-semibold">Nilai Retur:</span>
                                                    Rp {{ number_format($totalReturValue, 0, ',', '.') }}</p>
                                                <p class="text-gray-700 dark:text-gray-300">
                                                    <span class="font-semibold">Status:</span>
                                                    @if ($r->status == 'draft')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                            <span
                                                                class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-500"></span>
                                                            Draft
                                                        </span>
                                                    @elseif($r->status == 'proses')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                            <span
                                                                class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span>
                                                            Proses
                                                        </span>
                                                    @elseif($r->status == 'selesai')
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                                            <span
                                                                class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span>
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300">
                                                            <span
                                                                class="w-1.5 h-1.5 mr-1.5 rounded-full bg-rose-500"></span>
                                                            Batal
                                                        </span>
                                                    @endif
                                                </p>
                                                @if ($r->keterangan)
                                                    <p
                                                        class="text-gray-700 dark:text-gray-300 mt-2 border-t border-gray-200 dark:border-gray-700 pt-2">
                                                        <span class="font-semibold">Keterangan:</span>
                                                        {{ $r->keterangan }}
                                                    </p>
                                                @endif
                                                @if ($r->user)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Dibuat
                                                        oleh:
                                                        {{ $r->user->name }}</p>
                                                @endif
                                            </div>
                                            <div class="mt-4 ml-8">
                                                <a href="{{ route('inventory.retur-pembelian.show', $r->id) }}"
                                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 transition-colors"
                                                    title="Lihat Detail">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor" class="w-4 h-4">
                                                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                                        <path fill-rule="evenodd"
                                                            d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="mb-8 flex items-start relative">
                                    <div class="absolute -left-7 mt-1.5">
                                        <div
                                            class="bg-gray-400 dark:bg-gray-600 h-5 w-5 rounded-full border-4 border-white dark:border-gray-800 shadow">
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-10 ml-6 w-full flex justify-center items-center">
                                        <div class="text-center">
                                            <svg class="h-16 w-16 text-gray-400 dark:text-gray-500 mx-auto mb-4 opacity-75"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium mb-2">Belum ada
                                                transaksi pembayaran atau retur</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-sm mb-6">Tambahkan
                                                transaksi pembayaran untuk melihat history</p>
                                            @if ($purchaseOrder->status_pembayaran != 'lunas')
                                                <div class="mt-4">
                                                    <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $purchaseOrder->id]) }}"
                                                        class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 border border-transparent rounded-md font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                                        <svg class="w-4 h-4 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                        Tambah Pembayaran
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary Panel - Takes 1/3 of the space on large screens --}}
            <div>
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700 mb-6">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Ringkasan
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div
                                class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-300 font-medium">Total Nilai PO</span>
                                <span class="text-gray-900 dark:text-white font-semibold">Rp
                                    {{ number_format($purchaseOrder->total, 0, ',', '.') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-300 font-medium">Total Pembayaran</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Rp
                                    {{ number_format($purchaseOrder->total_pembayaran, 0, ',', '.') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-300 font-medium">Total Retur</span>
                                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Rp
                                    {{ number_format($purchaseOrder->total_retur, 0, ',', '.') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-800 dark:text-gray-100 font-bold">Sisa Hutang</span>
                                <span
                                    class="text-lg font-bold {{ $purchaseOrder->sisa_hutang > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                    Rp {{ number_format($purchaseOrder->sisa_hutang, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Status Card --}}
                        <div class="mt-6 bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl">
                            <div class="flex flex-col items-center text-center">
                                @if ($purchaseOrder->status_pembayaran == 'lunas')
                                    <div
                                        class="p-3.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mb-4 shadow-inner">
                                        <svg class="h-10 w-10" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-xl text-emerald-600 dark:text-emerald-400">Hutang Lunas
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Pembayaran telah selesai
                                    </p>
                                @elseif($purchaseOrder->status_pembayaran == 'sebagian')
                                    <div
                                        class="p-3.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mb-4 shadow-inner">
                                        <svg class="h-10 w-10" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-xl text-amber-600 dark:text-amber-400">Pembayaran
                                        Sebagian</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                        Progres:
                                        {{ number_format((($purchaseOrder->total_pembayaran + $purchaseOrder->total_retur) / $purchaseOrder->total) * 100, 0) }}%
                                    </p>
                                    {{-- Progress bar --}}
                                    <div
                                        class="w-full bg-gray-200 dark:bg-gray-600 h-2.5 mt-3 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-amber-400 to-amber-500 dark:from-amber-500 dark:to-amber-600 h-2.5"
                                            style="width: {{ number_format((($purchaseOrder->total_pembayaran + $purchaseOrder->total_retur) / $purchaseOrder->total) * 100, 0) }}%">
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="p-3.5 rounded-full bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 mb-4 shadow-inner">
                                        <svg class="h-10 w-10" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-bold text-xl text-rose-600 dark:text-rose-400">Belum Bayar</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Belum ada pembayaran</p>
                                @endif

                                @if ($purchaseOrder->status_pembayaran != 'lunas')
                                    <div class="mt-6">
                                        <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $purchaseOrder->id]) }}"
                                            class="inline-flex items-center justify-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            Lakukan Pembayaran
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Additional Info Card --}}
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="h-5 w-5 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Detail
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <span
                                    class="block text-xs uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                    PO</span>
                                <p class="text-gray-900 dark:text-white flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ date('d F Y', strtotime($purchaseOrder->tanggal)) }}
                                </p>
                            </div>
                            @if (isset($purchaseOrder->jatuh_tempo))
                                <div>
                                    <span
                                        class="block text-xs uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400 mb-1">Jatuh
                                        Tempo</span>
                                    @php
                                        $jatuhtempo = \Carbon\Carbon::parse($purchaseOrder->jatuh_tempo);
                                        $today = \Carbon\Carbon::now();
                                        $isOverdue =
                                            $jatuhtempo->lt($today) && $purchaseOrder->status_pembayaran != 'lunas';
                                        $almostDue =
                                            $jatuhtempo->diffInDays($today) <= 7 &&
                                            $jatuhtempo->gt($today) &&
                                            $purchaseOrder->status_pembayaran != 'lunas';
                                    @endphp
                                    <p
                                        class="{{ $isOverdue ? 'text-rose-600 dark:text-rose-400 font-semibold' : ($almostDue ? 'text-amber-600 dark:text-amber-400 font-semibold' : 'text-gray-900 dark:text-white') }} flex items-center">
                                        <svg class="h-4 w-4 mr-2 {{ $isOverdue ? 'text-rose-500' : ($almostDue ? 'text-amber-500' : 'text-blue-500') }}"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $jatuhtempo->format('d F Y') }}
                                        @if ($isOverdue)
                                            <span
                                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300">
                                                <span
                                                    class="w-1.5 h-1.5 mr-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                                Telat {{ $jatuhtempo->diffInDays($today) }} hari
                                            </span>
                                        @elseif($almostDue)
                                            <span
                                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span>
                                                {{ $jatuhtempo->diffInDays($today) }} hari lagi
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                            <div>
                                <span
                                    class="block text-xs uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400 mb-1">Dibuat
                                    Oleh</span>
                                <p class="text-gray-900 dark:text-white flex items-center">
                                    <svg class="h-4 w-4 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $purchaseOrder->user->name ?? 'N/A' }}
                                </p>
                            </div>
                            @if ($purchaseOrder->syarat_ketentuan)
                                <div>
                                    <span
                                        class="block text-xs uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400 mb-1">Syarat
                                        & Ketentuan</span>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-md">
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $purchaseOrder->syarat_ketentuan }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Add any JavaScript enhancements here
        </script>
    @endpush
</x-app-layout>
