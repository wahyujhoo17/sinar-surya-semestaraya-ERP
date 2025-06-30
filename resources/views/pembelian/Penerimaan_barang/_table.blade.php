<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead>
        <tr class="bg-gray-50 dark:bg-gray-700/50">
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nomor', 'sort_direction' => request('sort_by') == 'nomor' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center group">
                    No. GR
                    @if (request('sort_by') == 'nomor')
                        @if (request('sort_direction') == 'asc')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 ml-1 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z" />
                            <path
                                d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    @endif
                </a>
            </th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'tanggal', 'sort_direction' => request('sort_by') == 'tanggal' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center group">
                    Tanggal
                    @if (request('sort_by') == 'tanggal')
                        @if (request('sort_direction') == 'asc')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 ml-1 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z" />
                            <path
                                d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    @endif
                </a>
            </th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'po_number', 'sort_direction' => request('sort_by') == 'po_number' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center group">
                    No. PO
                    @if (request('sort_by') == 'po_number')
                        @if (request('sort_direction') == 'asc')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 ml-1 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z" />
                            <path
                                d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    @endif
                </a>
            </th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'supplier', 'sort_direction' => request('sort_by') == 'supplier' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center group">
                    Supplier
                    @if (request('sort_by') == 'supplier')
                        @if (request('sort_direction') == 'asc')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 ml-1 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z" />
                            <path
                                d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    @endif
                </a>
            </th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'gudang', 'sort_direction' => request('sort_by') == 'gudang' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center group">
                    Gudang
                    @if (request('sort_by') == 'gudang')
                        @if (request('sort_direction') == 'asc')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 ml-1 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z" />
                            <path
                                d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    @endif
                </a>
            </th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                User
            </th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_direction' => request('sort_by') == 'status' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center group">
                    Status
                    @if (request('sort_by') == 'status')
                        @if (request('sort_direction') == 'asc')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 text-gray-400"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 ml-1 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12z" />
                            <path
                                d="M15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                        </svg>
                    @endif
                </a>
            </th>
            <th
                class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                Aksi
            </th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($penerimaanBarangs as $row)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                <td class="px-5 py-4">
                    {{-- PERLU DISESUAIKAN --}}
                    <a href="{{ route('pembelian.purchasing-order.show', $row->id) }}"
                        class="text-sm font-medium text-primary-600 dark:text-primary-400">
                        {{ $row->nomor }}
                    </a>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $row->catatan ?? 'Tidak ada catatan' }}
                    </div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-700 dark:text-gray-200">
                        {{ $row->tanggal ? date('d-m-Y', strtotime($row->tanggal)) : '-' }}
                    </div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @if ($row->purchaseOrder)
                        <a href="{{ route('pembelian.purchasing-order.show', $row->purchaseOrder->id) }}"
                            class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $row->purchaseOrder->nomor }}
                        </a>
                    @else
                        <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                    @endif
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @if ($row->supplier)
                        <div class="text-sm text-gray-700 dark:text-gray-200 flex items-center">
                            <span class="h-2 w-2 rounded-full bg-green-500 mr-2"></span>
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
                    <div class="flex justify-end space-x-2">
                        @if (auth()->user()->hasPermission('penerimaan_barang.view'))
                            <a href="/pembelian/penerimaan-barang/{{ $row->id }}"
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
                        @endif
                    </div>
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
                        @if (auth()->user()->hasPermission('penerimaan_barang.create'))
                            <a href="{{ route('pembelian.penerimaan-barang.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Buat Penerimaan Barang
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
