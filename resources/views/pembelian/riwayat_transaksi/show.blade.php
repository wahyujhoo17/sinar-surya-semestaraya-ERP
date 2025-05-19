@php
    // Helper function untuk warna status transaksi
    function getStatusColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'gray';
            case 'diproses':
                return 'blue';
            case 'dikirim':
                return 'amber';
            case 'selesai':
                return 'emerald';
            case 'dibatalkan':
                return 'red';
            default:
                return 'primary';
        }
    }

    // Helper function untuk nama status pembayaran
    function getPaymentStatusName($status)
    {
        switch ($status) {
            case 'belum_bayar':
                return 'Belum Bayar';
            case 'sebagian':
                return 'Sebagian';
            case 'lunas':
                return 'Lunas';
            default:
                return $status;
        }
    }

    // Helper function untuk warna status pembayaran
    function getPaymentStatusColor($status)
    {
        switch ($status) {
            case 'belum_bayar':
                return 'red';
            case 'sebagian':
                return 'amber';
            case 'lunas':
                return 'emerald';
            default:
                return 'gray';
        }
    }
@endphp

<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center">
                    <a href="{{ route('pembelian.riwayat-transaksi.index') }}" class="mr-3">
                        <div
                            class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-900 dark:text-primary-300 hover:bg-primary-100 dark:hover:bg-primary-800 transition-colors">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </div>
                    </a>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Transaksi
                            {{ $transaction->nomor }}</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Tanggal: {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }} | Supplier:
                            {{ $transaction->supplier->nama ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('pembelian.purchasing-order.show', $transaction->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-primary-300 dark:border-primary-700 rounded-lg font-medium text-sm text-primary-700 dark:text-primary-400 bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Detail PO</span>
                </a>
                <a href="{{ route('pembelian.purchasing-order.pdf', $transaction->id) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span>Cetak PDF</span>
                </a>
            </div>
        </div>

        {{-- Content --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Left Panel - Transaction Details --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden xl:col-span-2 border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/80">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Informasi Transaksi
                        </h2>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Transaction Details --}}
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor PO</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">
                                    {{ $transaction->nomor }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($transaction->tanggal)->format('d M Y') }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $transaction->supplier->nama ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</div>
                                <div class="mt-1">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-{{ getStatusColor($transaction->status) }}-100 text-{{ getStatusColor($transaction->status) }}-800 dark:bg-{{ getStatusColor($transaction->status) }}-800/30 dark:text-{{ getStatusColor($transaction->status) }}-400">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Details --}}
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran
                                </div>
                                <div class="mt-1">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-100 text-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-800 dark:bg-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-800/30 dark:text-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-400">
                                        {{ getPaymentStatusName($transaction->status_pembayaran) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white font-semibold">Rp
                                    {{ number_format($transaction->total, 0, ',', '.') }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">PPN</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->ppn }}%</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</div>
                                <div class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $transaction->user->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    @if ($transaction->catatan)
                        <div class="mt-6">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan</div>
                            <div
                                class="mt-1 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm text-gray-900 dark:text-white">
                                {{ $transaction->catatan }}
                            </div>
                        </div>
                    @endif

                    @if ($transaction->syarat_ketentuan)
                        <div class="mt-6">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Syarat & Ketentuan</div>
                            <div
                                class="mt-1 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm text-gray-900 dark:text-white">
                                {{ $transaction->syarat_ketentuan }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Transaction Items --}}
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/80">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Item Pembelian
                        </h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800/80">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Item</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Quantity</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Harga</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Diskon</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($transaction->details as $item)
                                    <tr>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $item->produk->nama ?? ($item->nama_item ?? 'N/A') }}
                                            @if ($item->deskripsi)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $item->deskripsi }}</div>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                            {{ number_format($item->quantity, 0, ',', '.') }}
                                            {{ $item->satuan->nama ?? '' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                            @if ($item->diskon_nominal > 0)
                                                Rp {{ number_format($item->diskon_nominal, 0, ',', '.') }}
                                                @if ($item->diskon_persen > 0)
                                                    ({{ $item->diskon_persen }}%)
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right font-medium">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada item yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">
                                        Subtotal</td>
                                    <td class="px-6 py-3 text-right text-sm text-gray-900 dark:text-white font-medium">
                                        Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @if ($transaction->diskon_nominal > 0)
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Diskon
                                            {{ $transaction->diskon_persen > 0 ? '(' . $transaction->diskon_persen . '%)' : '' }}
                                        </td>
                                        <td
                                            class="px-6 py-3 text-right text-sm text-gray-900 dark:text-white font-medium">
                                            Rp {{ number_format($transaction->diskon_nominal, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                                @if ($transaction->ppn > 0)
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">
                                            PPN ({{ $transaction->ppn }}%)</td>
                                        <td
                                            class="px-6 py-3 text-right text-sm text-gray-900 dark:text-white font-medium">
                                            Rp
                                            {{ number_format($transaction->total - ($transaction->subtotal - $transaction->diskon_nominal), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                        Total</td>
                                    <td class="px-6 py-3 text-right text-base text-gray-900 dark:text-white font-bold">
                                        Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Panel - Receiving & Payment History --}}
            <div class="space-y-6">
                {{-- Delivery Status --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/80">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                                Status Penerimaan
                            </h2>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center mb-4">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</div>
                            <div class="ml-2">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full 
                                    @switch($transaction->status_penerimaan)
                                        @case('belum_diterima')
                                            bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                            @break
                                        @case('sebagian')
                                            bg-amber-100 text-amber-800 dark:bg-amber-800/30 dark:text-amber-400
                                            @break
                                        @case('diterima')
                                            bg-emerald-100 text-emerald-800 dark:bg-emerald-800/30 dark:text-emerald-400
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400
                                    @endswitch
                                ">
                                    @switch($transaction->status_penerimaan)
                                        @case('belum_diterima')
                                            Belum Diterima
                                        @break

                                        @case('sebagian')
                                            Diterima Sebagian
                                        @break

                                        @case('diterima')
                                            Diterima Penuh
                                        @break

                                        @default
                                            {{ $transaction->status_penerimaan }}
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        @if ($transaction->penerimaan->count() > 0)
                            <div class="space-y-3">
                                @foreach ($transaction->penerimaan as $penerimaan)
                                    <div
                                        class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                {{ $penerimaan->nomor }}</div>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($penerimaan->tanggal)->format('d M Y') }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <span>Gudang: {{ $penerimaan->gudang->nama ?? 'N/A' }}</span>
                                        </div>
                                        <div class="mt-2 text-xs">
                                            <a href="{{ route('pembelian.penerimaan-barang.show', $penerimaan->id) }}"
                                                class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada penerimaan barang untuk PO ini
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment History --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/80">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Riwayat Pembayaran
                            </h2>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</div>
                                <div class="ml-2">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-100 text-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-800 dark:bg-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-800/30 dark:text-{{ getPaymentStatusColor($transaction->status_pembayaran) }}-400">
                                        {{ getPaymentStatusName($transaction->status_pembayaran) }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('keuangan.pembayaran-hutang.create', ['po_id' => $transaction->id]) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 dark:text-primary-400 dark:bg-primary-900/30 dark:hover:bg-primary-900/50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1.5 h-3 w-3" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Pembayaran
                            </a>
                        </div>

                        @if ($payments->count() > 0)
                            <div class="space-y-3">
                                @foreach ($payments as $payment)
                                    <div
                                        class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center justify-between">
                                            <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                {{ $payment->nomor }}</div>
                                            <span
                                                class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($payment->tanggal)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                                    @if ($payment->metode_pembayaran == 'kas') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                    @elseif($payment->metode_pembayaran == 'bank')
                                                        bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 @endif">
                                                    {{ ucfirst($payment->metode_pembayaran) }}
                                                </span>
                                            </div>
                                            <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs">
                                            <a href="{{ route('keuangan.pembayaran-hutang.show', $payment->id) }}"
                                                class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada pembayaran untuk PO ini
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
