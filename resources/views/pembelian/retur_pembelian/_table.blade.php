<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead>
        <tr class="bg-gray-50 dark:bg-gray-700/50">
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'nomor', 'sort_direction' => request('sort_by') == 'nomor' && request('sort_direction') == 'asc' ? 'desc' : 'asc']) }}"
                    class="flex items-center group">
                    No. Retur
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
                PO
            </th>
            <th
                class="px-5 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                Status
            </th>
            <th
                class="px-5 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                Aksi
            </th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
        @forelse($returPembelian as $retur)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="ml-1">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $retur->nomor }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($retur->tanggal)->format('d/m/Y') }}
                    </div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ optional($retur->supplier)->nama ?? 'Tidak Tersedia' }}
                    </div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        {{ optional($retur->purchaseOrder)->nomor ?? 'Tidak Tersedia' }}
                    </div>
                </td>
                <td class="px-5 py-4 whitespace-nowrap">
                    @php
                        $statusColor = '';
                        $statusText = '';

                        switch ($retur->status) {
                            case 'draft':
                                $statusColor = 'gray';
                                $statusText = 'Draft';
                                break;
                            case 'diproses':
                                $statusColor = 'blue';
                                $statusText = 'Diproses';
                                break;
                            case 'selesai':
                                $statusColor = 'emerald';
                                $statusText = 'Selesai';
                                break;
                            default:
                                $statusColor = 'gray';
                                $statusText = ucfirst($retur->status);
                        }
                    @endphp
                    <span
                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 dark:bg-{{ $statusColor }}-900/30 dark:text-{{ $statusColor }}-400 border border-{{ $statusColor }}-200 dark:border-{{ $statusColor }}-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusColor }}-500 mr-1.5 my-auto"></span>
                        {{ $statusText }}
                    </span>
                </td>
                <td class="px-5 py-4 whitespace-nowrap text-right">
                    <div class="flex justify-end space-x-2">
                        <!-- Show button - always visible -->
                        <a href="{{ route('pembelian.retur-pembelian.show', $retur) }}"
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

                        <!-- Edit button - only visible for draft status -->
                        @if ($retur->status === 'draft')
                            <a href="{{ route('pembelian.retur-pembelian.edit', $retur) }}"
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

                            <!-- Delete button - only visible for draft status -->
                            <form action="{{ route('pembelian.retur-pembelian.destroy', $retur) }}" method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-gray-700 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300"
                                    title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="w-4 h-4">
                                        <path fill-rule="evenodd"
                                            d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <span>Belum ada data Retur Pembelian.</span>
                        <a href="{{ route('pembelian.retur-pembelian.create') }}"
                            class="mt-2 text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                            Buat Retur Pembelian
                        </a>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Pagination -->
<div class="bg-white dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-800 sm:px-6"
    id="pagination-container">
    {{ $returPembelian->links('vendor.pagination.tailwind-custom') }}
</div>
