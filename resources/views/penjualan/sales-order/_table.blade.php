@php
    // Helper function to get status color
    function paymentStatusColor($status)
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

    function deliveryStatusColor($status)
    {
        switch ($status) {
            case 'belum_dikirim':
                return 'red';
            case 'sebagian':
                return 'amber';
            case 'dikirim':
                return 'emerald';
            default:
                return 'gray';
        }
    }

    function formatStatusPembayaran($status)
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

    function formatStatusPengiriman($status)
    {
        switch ($status) {
            case 'belum_dikirim':
                return 'Belum Dikirim';
            case 'sebagian':
                return 'Sebagian';
            case 'dikirim':
                return 'Dikirim';
            default:
                return $status;
        }
    }
@endphp

@forelse($salesOrders as $salesOrder)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150">
        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">
            {{ ($salesOrders->firstItem() ?? 1) + $loop->index }}
        </td>
        <td class="px-5 py-4">
            <a href="{{ route('penjualan.sales-order.show', $salesOrder->id) }}" class="group">
                <div
                    class="text-sm font-medium text-primary-600 dark:text-primary-400 group-hover:text-primary-700 dark:group-hover:text-primary-300 transition duration-150">
                    {{ $salesOrder->nomor }}
                </div>
                @if ($salesOrder->nomor_po)
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        PO: {{ $salesOrder->nomor_po }}
                    </div>
                @endif
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $salesOrder->catatan ?? 'Tidak ada catatan' }}
                </div>
            </a>
        </td>
        <td class="px-5 py-4">
            @php
                $tanggal = $salesOrder->tanggal;
                $formatted_date = 'N/A';
                $diff_for_humans = '';
                $tanggal_kirim = $salesOrder->tanggal_kirim;
                $delivery_date = '';

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

                if ($tanggal_kirim) {
                    try {
                        if (is_string($tanggal_kirim)) {
                            $delivery_carbon_date = \Carbon\Carbon::parse($tanggal_kirim);
                        } else {
                            $delivery_carbon_date = $tanggal_kirim;
                        }
                        $delivery_date = $delivery_carbon_date->format('d M Y');
                    } catch (\Exception $e) {
                        $delivery_date = 'Tanggal tidak valid';
                    }
                }
            @endphp
            <div class="text-sm text-gray-700 dark:text-gray-200">
                {{ $formatted_date }}
            </div>
            @if ($diff_for_humans)
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $diff_for_humans }}
                </div>
            @endif
            @if ($delivery_date)
                <div class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                    <span class="bg-blue-50 dark:bg-blue-900/20 px-1.5 py-0.5 rounded text-xs">
                        Pengiriman: {{ $delivery_date }}
                    </span>
                </div>
            @endif
        </td>
        <td class="px-5 py-4">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                {{ $salesOrder->customer ? $salesOrder->customer->company ?? $salesOrder->customer->nama : 'Customer tidak ditemukan' }}
            </div>
            @if ($salesOrder->customer && $salesOrder->customer->telepon)
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-3 h-3 mr-1">
                            <path fill-rule="evenodd"
                                d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $salesOrder->customer->telepon }}
                    </span>
                </div>
            @endif
        </td>
        <td class="px-5 py-4">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                @if ($salesOrder->customer && $salesOrder->customer->kontak_person)
                    {{ $salesOrder->customer->kontak_person }}
                @else
                    <span class="text-gray-500 dark:text-gray-400">Tidak ada kontak person</span>
                @endif
            </div>
            @if ($salesOrder->customer && $salesOrder->customer->no_hp_kontak)
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-3 h-3 mr-1">
                            <path fill-rule="evenodd"
                                d="M10 2a8 8 0 00-8 8c0 1.422.368 2.756 1.006 3.912l-.943 3.405a.75.75 0 001.004.935l3.13-.935A8 8 0 1010 2zm0 12a4 4 0 01-2.39-.787l-.33-.244-.745.223a25.54 25.54 0 00-1.543.658l.663-2.375.188-.68-.502-.595A6.5 6.5 0 1110 14z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $salesOrder->customer->no_hp_kontak ?? 'Tidak ada nomor kontak' }}
                    </span>
                </div>
            @else
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <span class="inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-3 h-3 mr-1">
                            <path fill-rule="evenodd"
                                d="M10 2a8 8 0 00-8 8c0 1.422.368 2.756 1.006 3.912l-.943 3.405a.75.75 0 001.004.935l3.13-.935A8 8 0 1010 2zm0 12a4 4 0 01-2.39-.787l-.33-.244-.745.223a25.54 25.54 0 00-1.543.658l.663-2.375.188-.68-.502-.595A6.5 6.5 0 1110 14z"
                                clip-rule="evenodd" />
                        </svg>
                        Tidak ada nomor kontak
                    </span>
                </div>
            @endif
        </td>
        <td class="px-5 py-4">
            <div class="flex flex-col space-y-2">
                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-medium 
                    bg-{{ paymentStatusColor($salesOrder->status_pembayaran) }}-100 
                    dark:bg-{{ paymentStatusColor($salesOrder->status_pembayaran) }}-900/20 
                    text-{{ paymentStatusColor($salesOrder->status_pembayaran) }}-700 
                    dark:text-{{ paymentStatusColor($salesOrder->status_pembayaran) }}-300">
                    <span
                        class="h-1.5 w-1.5 rounded-full bg-{{ paymentStatusColor($salesOrder->status_pembayaran) }}-500"></span>
                    {{ formatStatusPembayaran($salesOrder->status_pembayaran) }}
                </span>

                <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-medium 
                    bg-{{ deliveryStatusColor($salesOrder->status_pengiriman) }}-100 
                    dark:bg-{{ deliveryStatusColor($salesOrder->status_pengiriman) }}-900/20 
                    text-{{ deliveryStatusColor($salesOrder->status_pengiriman) }}-700 
                    dark:text-{{ deliveryStatusColor($salesOrder->status_pengiriman) }}-300">
                    <span
                        class="h-1.5 w-1.5 rounded-full bg-{{ deliveryStatusColor($salesOrder->status_pengiriman) }}-500"></span>
                    {{ formatStatusPengiriman($salesOrder->status_pengiriman) }}
                </span>
            </div>
        </td>
        <td class="px-5 py-4 text-right">
            <div class="text-sm font-medium text-gray-900 dark:text-white">
                Rp {{ number_format($salesOrder->total, 0, ',', '.') }}
            </div>
            @if ($salesOrder->diskon_persen > 0)
                <div class="text-xs text-green-600 dark:text-green-400">
                    Diskon: {{ $salesOrder->diskon_persen }}%
                </div>
            @endif
        </td>
        <td class="px-5 py-4 text-right">
            <div class="flex justify-end space-x-2">
                @if (auth()->user()->hasPermission('sales_order.view'))
                    <a href="{{ route('penjualan.sales-order.show', $salesOrder->id) }}"
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

                @if (auth()->user()->hasPermission('sales_order.edit') &&
                        !$salesOrder->deliveryOrders()->exists() &&
                        !$salesOrder->invoices()->exists() &&
                        !$salesOrder->workOrders()->exists())
                    <a href="{{ route('penjualan.sales-order.edit', $salesOrder->id) }}"
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-gray-700 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300"
                        title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path
                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                            <path
                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                        </svg>
                    </a>
                @elseif (
                    !auth()->user()->hasPermission('sales_order.edit') ||
                        $salesOrder->deliveryOrders()->exists() ||
                        $salesOrder->invoices()->exists() ||
                        $salesOrder->workOrders()->exists())
                    <button type="button" disabled
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed border border-dashed border-gray-300"
                        title="Tidak dapat diedit karena sudah memiliki Delivery Order, Work Order, atau Invoice terkait atau Anda tidak memiliki akses">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path
                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                            <path
                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                        </svg>
                    </button>
                @endif

                @if (auth()->user()->hasPermission('sales_order.delete') &&
                        !$salesOrder->deliveryOrders()->exists() &&
                        !$salesOrder->invoices()->exists() &&
                        !$salesOrder->workOrders()->exists())
                    <div x-data="{ salesOrderId: {{ $salesOrder->id }}, salesOrderNo: '{{ $salesOrder->nomor }}' }">
                        <button type="button"
                            @click="confirmDelete(`Apakah Anda yakin ingin menghapus Sales Order <strong>${salesOrderNo}</strong>?<br><br>Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait dengan Sales Order ini.`, () => {
                            document.getElementById('delete-form-' + salesOrderId).submit();
                        })"
                            class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-gray-700 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300"
                            title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <form id="delete-form-{{ $salesOrder->id }}"
                            action="{{ route('penjualan.sales-order.destroy', $salesOrder->id) }}" method="POST"
                            class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                @elseif (
                    !auth()->user()->hasPermission('sales_order.delete') ||
                        $salesOrder->deliveryOrders()->exists() ||
                        $salesOrder->invoices()->exists() ||
                        $salesOrder->workOrders()->exists())
                    <button type="button" disabled
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed border border-dashed border-gray-300"
                        title="Tidak dapat dihapus karena sudah memiliki Delivery Order, Work Order, atau Invoice terkait atau Anda tidak memiliki akses">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd"
                                d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                clip-rule="evenodd" />
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
                    class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-gray-50 dark:bg-gray-700/50">
                    <svg class="h-12 w-12 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="text-center">
                    <p class="text-base font-medium text-gray-500 dark:text-gray-400">Tidak ada data sales order</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1 max-w-md mx-auto">
                        Belum ada sales order dengan filter/status ini.
                    </p>
                </div>
                @if (auth()->user()->hasPermission('sales_order.create'))
                    <a href="{{ route('penjualan.sales-order.create') }}"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Sales Order
                    </a>
                @endif
            </div>
        </td>
    </tr>
@endforelse
