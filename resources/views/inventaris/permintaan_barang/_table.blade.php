<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    No</th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    Nomor</th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    Tanggal</th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    Sales Order</th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    Customer</th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    Gudang</th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    Status</th>
                <th scope="col"
                    class="px-6 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky top-0 z-10 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($permintaanBarang as $index => $pb)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        {{ $permintaanBarang->firstItem() + $index }}</td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                        {{ $pb->nomor }}</td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                        {{ date('d/m/Y', strtotime($pb->tanggal)) }}</td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                        @if ($pb->salesOrder)
                            @if (auth()->user()->hasPermission('sales_order.view'))
                                <a href="{{ route('penjualan.sales-order.show', $pb->sales_order_id) }}"
                                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium hover:underline transition-all duration-300"
                                    data-tooltip="Lihat Sales Order">
                                    {{ $pb->salesOrder->nomor }}
                                </a>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 cursor-not-allowed" data-tooltip="Tidak Ada Akses">
                                    {{ $pb->salesOrder->nomor }}
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                        @endif
                    </td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                        @if ($pb->customer)
                            <div>{{ $pb->customer->nama }}</div>
                            @if ($pb->customer->company)
                                <div class="text-xs text-gray-400 dark:text-gray-500">{{ $pb->customer->company }}</div>
                            @endif
                        @else
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                        @endif
                    </td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-700">
                        {{ $pb->gudang->nama ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700">
                        @if ($pb->status == 'menunggu')
                            <span
                                class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-700/50 dark:text-yellow-100 border border-yellow-200 dark:border-yellow-600 shadow-sm">
                                Menunggu
                            </span>
                        @elseif($pb->status == 'diproses')
                            <span
                                class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-700/50 dark:text-blue-100 border border-blue-200 dark:border-blue-600 shadow-sm">
                                Diproses
                            </span>
                        @elseif($pb->status == 'selesai')
                            <span
                                class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-700/50 dark:text-green-100 border border-green-200 dark:border-green-600 shadow-sm">
                                Selesai
                            </span>
                        @elseif($pb->status == 'dibatalkan')
                            <span
                                class="px-2.5 py-0.5 inline-flex items-center justify-center text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-700/50 dark:text-red-100 border border-red-200 dark:border-red-600 shadow-sm">
                                Dibatalkan
                            </span>
                        @endif
                    </td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-end space-x-3">
                            @if (auth()->user()->hasPermission('permintaan_barang.view'))
                                <a href="{{ route('inventaris.permintaan-barang.show', $pb->id) }}"
                                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 flex items-center p-1.5 rounded-full hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-300"
                                    data-tooltip="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="flex items-center p-1.5 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed"
                                    data-tooltip="Tidak Ada Akses">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-3 4h6m-6 0h6m3-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                            @endif
                            @if ($pb->status == 'diproses')
                                @if (auth()->user()->hasPermission('permintaan_barang.create'))
                                    <a href="{{ route('inventaris.permintaan-barang.create-do', $pb->id) }}"
                                        class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 flex items-center p-1.5 rounded-full hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-300"
                                        data-tooltip="Buat Delivery Order">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="flex items-center p-1.5 rounded-full bg-gray-100 text-gray-400 cursor-not-allowed"
                                        data-tooltip="Tidak Ada Akses">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-3 4h6m-6 0h6m3-4a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8"
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center border-b border-gray-200 dark:border-gray-700">
                        Tidak ada data permintaan barang
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
