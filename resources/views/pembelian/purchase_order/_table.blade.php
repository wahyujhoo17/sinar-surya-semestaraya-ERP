@php
    function poStatusColor($status)
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
    function paymentStatusLabel($status)
    {
        switch ($status) {
            case 'belum_bayar':
                return 'Belum Bayar';
            case 'sebagian':
                return 'Sebagian';
            case 'lunas':
                return 'Lunas';
            default:
                return '-';
        }
    }
@endphp

<div
    class="overflow-hidden rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm mt-5">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th
                        class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        No</th>
                    <th
                        class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Nomor</th>
                    <th
                        class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Tanggal</th>
                    <th
                        class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Supplier</th>
                    <th
                        class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Status</th>
                    <th
                        class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Pembayaran</th>
                    <th
                        class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Total</th>
                    <th
                        class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($purchaseOrders as $po)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150">
                        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ ($purchaseOrders->firstItem() ?? 1) + $loop->index }}
                        </td>
                        <td class="px-5 py-4">
                            <a href="{{ route('pembelian.purchasing-order.show', $po->id) }}"
                                class="text-sm font-medium text-primary-600 dark:text-primary-400">
                                {{ $po->nomor }}
                            </a>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $po->catatan ?? 'Tidak ada catatan' }}
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm text-gray-700 dark:text-gray-200">
                                {{ \Carbon\Carbon::parse($po->tanggal)->format('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($po->tanggal)->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ $po->supplier->nama ?? '-' }}
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-medium bg-{{ poStatusColor($po->status) }}-100 dark:bg-{{ poStatusColor($po->status) }}-900/20 text-{{ poStatusColor($po->status) }}-700 dark:text-{{ poStatusColor($po->status) }}-300">
                                <span class="h-1.5 w-1.5 rounded-full bg-{{ poStatusColor($po->status) }}-500"></span>
                                {{ ucfirst($po->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <span
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ paymentStatusLabel($po->status_pembayaran) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                            Rp {{ number_format($po->total, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('pembelian.purchasing-order.show', $po->id) }}"
                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                                title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="w-4 h-4">
                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    <path fill-rule="evenodd"
                                        d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                            @if ($po->status == 'draft')
                                <a href="{{ route('pembelian.purchasing-order.edit', $po->id) }}"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-gray-700 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="w-4 h-4">
                                        <path
                                            d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path
                                            d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-10 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div
                                    class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-gray-50 dark:bg-gray-700/50">
                                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <p class="text-base font-medium text-gray-500 dark:text-gray-400">Tidak ada data
                                        purchase order</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 max-w-md mx-auto">
                                        Belum ada purchase order dengan filter/status ini.
                                    </p>
                                </div>
                                <a href="{{ route('pembelian.purchasing-order.create') }}"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    Buat Purchase Order
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
