<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead>
        <tr class="bg-gray-50 dark:bg-gray-700/50">
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                No. GR</th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                Tanggal</th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                No. PO</th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                Supplier</th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                Gudang</th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                User</th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                Status</th>
            <th
                class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($penerimaanBarangs as $row)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="font-medium text-sky-600 dark:text-sky-400">{{ $row->nomor }}</div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-700 dark:text-gray-200">
                        {{ $row->tanggal ? date('d-m-Y', strtotime($row->tanggal)) : '-' }}
                    </div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @if ($row->purchaseOrder)
                        <div class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $row->purchaseOrder->nomor }}
                        </div>
                    @else
                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @if ($row->supplier)
                        <div class="text-sm text-gray-700 dark:text-gray-200">
                            {{ $row->supplier->nama }}
                        </div>
                    @else
                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @if ($row->gudang)
                        <div class="text-sm text-gray-700 dark:text-gray-200">
                            {{ $row->gudang->nama }}
                        </div>
                    @else
                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @if ($row->user)
                        <div class="text-sm text-gray-700 dark:text-gray-200">
                            {{ $row->user->name }}
                        </div>
                    @else
                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @php
                        $statusColors = [
                            'draft' => 'gray',
                            'parsial' => 'amber',
                            'selesai' => 'emerald',
                            'dibatalkan' => 'red',
                        ];
                        $color = $statusColors[$row->status] ?? 'gray';
                    @endphp
                    <span
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 dark:bg-{{ $color }}-900/20 text-{{ $color }}-700 dark:text-{{ $color }}-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-{{ $color }}-500"></span>
                        {{ ucfirst($row->status) }}
                    </span>
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('pembelian.penerimaan-barang.show', $row->id) }}"
                        class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-5 py-12">
                    <div class="flex flex-col items-center justify-center">
                        <div
                            class="flex items-center justify-center h-24 w-24 rounded-full bg-gray-50 dark:bg-gray-700/30 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 dark:text-gray-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum Ada Data Penerimaan
                            Barang</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-md mb-6">
                            Belum ada penerimaan barang yang direkam. Penerimaan barang akan muncul di sini setelah Anda
                            membuat data baru.
                        </p>
                        <a href="{{ route('pembelian.penerimaan-barang.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Penerimaan Barang
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
