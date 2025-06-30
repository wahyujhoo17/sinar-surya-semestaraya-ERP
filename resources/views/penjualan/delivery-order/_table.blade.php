@php
    // Helper function to get status color
    function deliveryStatusColor($status)
    {
        switch ($status) {
            case 'draft':
                return 'gray';
            case 'dikirim':
                return 'amber';
            case 'diterima':
                return 'emerald';
            case 'dibatalkan':
                return 'red';
            default:
                return 'gray';
        }
    }

    function formatDeliveryStatus($status)
    {
        switch ($status) {
            case 'draft':
                return 'Draft';
            case 'dikirim':
                return 'Proses';
            case 'diterima':
                return 'Selesai';
            case 'dibatalkan':
                return 'Dibatalkan';
            default:
                return $status;
        }
    }
@endphp

@forelse($deliveryOrders as $deliveryOrder)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150">
        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
            {{ ($deliveryOrders->firstItem() ?? 1) + $loop->index }}
        </td>
        <td class="px-5 py-4">
            <a href="{{ route('penjualan.delivery-order.show', $deliveryOrder->id) }}" class="group">
                <div
                    class="text-sm font-medium text-primary-600 dark:text-primary-400 group-hover:text-primary-700 dark:group-hover:text-primary-300 transition duration-150">
                    {{ $deliveryOrder->nomor }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $deliveryOrder->catatan ?? 'Tidak ada catatan' }}
                </div>
            </a>
        </td>
        <td class="px-5 py-4">
            @php
                $tanggal = $deliveryOrder->tanggal;
                $formatted_date = 'N/A';
                $diff_for_humans = '';

                if ($tanggal) {
                    try {
                        if (is_string($tanggal)) {
                            $carbon_date = \Carbon\Carbon::parse($tanggal);
                        } else {
                            $carbon_date = $tanggal;
                        }
                        $formatted_date = $carbon_date->format('d M Y');
                        $diff_for_humans = $carbon_date->diffForHumans();
                    } catch (\Exception $e) {
                        $formatted_date = 'Tanggal tidak valid';
                    }
                }
            @endphp
            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                {{ $formatted_date }}
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                {{ $diff_for_humans }}
            </div>
        </td>
        <td class="px-5 py-4">
            @if ($deliveryOrder->salesOrder)
                <a href="{{ route('penjualan.sales-order.show', $deliveryOrder->salesOrder->id) }}" class="group">
                    <div
                        class="text-sm font-medium text-primary-600 dark:text-primary-400 group-hover:text-primary-700 dark:group-hover:text-primary-300 transition duration-150">
                        {{ $deliveryOrder->salesOrder->nomor }}
                    </div>
                </a>
            @else
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    -
                </div>
            @endif
        </td>
        <td class="px-5 py-4">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                {{ $deliveryOrder->customer ? $deliveryOrder->customer->company ?? $deliveryOrder->customer->nama : 'Customer tidak ditemukan' }}
            </div>
            @if ($deliveryOrder->customer && $deliveryOrder->customer->telepon)
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-3 h-3 mr-1">
                            <path fill-rule="evenodd"
                                d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $deliveryOrder->customer->telepon }}
                    </span>
                </div>
            @endif
        </td>
        <td class="px-5 py-4">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                {{ $deliveryOrder->gudang ? $deliveryOrder->gudang->nama : 'Gudang tidak ditemukan' }}
            </div>
        </td>
        <td class="px-5 py-4 text-center">
            @php
                $status = $deliveryOrder->status;
                $statusColor = deliveryStatusColor($status);
                $statusText = formatDeliveryStatus($status);
            @endphp
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 dark:bg-{{ $statusColor }}-900/30 dark:text-{{ $statusColor }}-500">
                {{ $statusText }}
            </span>
        </td>
        <td class="px-5 py-4 text-right">
            <div class="flex justify-end space-x-2">
                @if (auth()->user()->hasPermission('delivery_order.view'))
                    <a href="{{ route('penjualan.delivery-order.show', $deliveryOrder->id) }}"
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-gray-700 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300"
                        title="Detail">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                            <path fill-rule="evenodd"
                                d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                @if (auth()->user()->hasPermission('delivery_order.edit') && $deliveryOrder->status === 'draft')
                    <a href="{{ route('penjualan.delivery-order.edit', $deliveryOrder->id) }}"
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-gray-700 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300"
                        title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path
                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                            <path
                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                        </svg>
                    </a>
                @elseif (!auth()->user()->hasPermission('delivery_order.edit') || $deliveryOrder->status !== 'draft')
                    <button type="button" disabled
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed border border-dashed border-gray-300"
                        title="Hanya delivery order dengan status Draft yang dapat diedit atau Anda tidak memiliki akses">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path
                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                            <path
                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                        </svg>
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-5 py-10 text-center">
            <div class="flex flex-col items-center justify-center space-y-4">
                <div
                    class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-gray-50 dark:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-base font-medium text-gray-500 dark:text-gray-400">Tidak ada data delivery order</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 max-w-md mx-auto">
                        Belum ada delivery order dengan filter/status ini.
                    </p>
                </div>
                @if (auth()->user()->hasPermission('delivery_order.create'))
                    <a href="{{ route('penjualan.delivery-order.create') }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Delivery Order
                    </a>
                @endif
            </div>
        </td>
    </tr>
@endforelse
