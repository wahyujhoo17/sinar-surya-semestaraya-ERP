{{-- Enhanced Responsive Table Layout --}}
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th scope="col"
                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">
                    No. Retur
                </th>
                <th scope="col"
                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                    Tanggal
                </th>
                <th scope="col"
                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Customer
                </th>
                <th scope="col"
                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">
                    No. SO
                </th>
                <th scope="col"
                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden sm:table-cell">
                    Total Retur
                </th>
                <th scope="col"
                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Status
                </th>
                <th scope="col"
                    class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                    Dibuat oleh
                </th>
                <th scope="col" class="relative px-3 sm:px-6 py-3">
                    <span class="sr-only">Aksi</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($returPenjualan as $retur)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    <td class="px-3 sm:px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                        <div class="flex flex-col">
                            <span class="whitespace-nowrap">{{ $retur->nomor }}</span>
                            <div class="mt-1 sm:hidden text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($retur->tanggal)->format('d/m/Y') }}
                            </div>
                        </div>
                    </td>
                    <td
                        class="px-3 sm:px-6 py-4 text-sm text-gray-500 dark:text-gray-300 hidden sm:table-cell whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($retur->tanggal)->format('d/m/Y') }}
                    </td>
                    <td class="px-3 sm:px-6 py-4">
                        <div class="flex flex-col">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $retur->salesOrder->customer->company ?? ($retur->salesOrder->customer->nama ?? '-') }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-300">
                                {{ $retur->salesOrder->customer->kode ?? '-' }}
                            </div>
                            <div class="md:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                                SO: {{ $retur->salesOrder->nomor ?? '-' }}
                            </div>
                            <div class="sm:hidden text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Rp {{ number_format($retur->total, 0, ',', '.') }}
                            </div>
                        </div>
                    </td>
                    <td
                        class="px-3 sm:px-6 py-4 text-sm text-gray-500 dark:text-gray-300 hidden md:table-cell whitespace-nowrap">
                        {{ $retur->salesOrder->nomor ?? '-' }}
                    </td>
                    <td
                        class="px-3 sm:px-6 py-4 text-sm font-medium text-gray-900 dark:text-white hidden sm:table-cell whitespace-nowrap">
                        Rp {{ number_format($retur->total, 0, ',', '.') }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                        @php
                            $statusColor = match ($retur->status) {
                                'draft' => 'gray',
                                'menunggu_persetujuan' => 'yellow',
                                'disetujui' => 'green',
                                'ditolak' => 'red',
                                'diproses' => 'blue',
                                'menunggu_barang_pengganti' => 'amber',
                                'selesai' => 'emerald',
                                default => 'gray',
                            };
                            $statusText = match ($retur->status) {
                                'draft' => 'Draft',
                                'menunggu_persetujuan' => 'Menunggu Persetujuan',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                                'diproses' => 'Diproses',
                                'menunggu_barang_pengganti' => 'Menunggu Barang Pengganti',
                                'selesai' => 'Selesai',
                                default => 'Unknown',
                            };
                            $statusShortText = match ($retur->status) {
                                'menunggu_persetujuan' => 'Menunggu',
                                'menunggu_barang_pengganti' => 'Menunggu Brg',
                                default => $statusText,
                            };
                        @endphp
                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium 
                            bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 
                            dark:bg-{{ $statusColor }}-900/30 dark:text-{{ $statusColor }}-300">
                            <span class="w-1.5 h-1.5 bg-{{ $statusColor }}-400 rounded-full"></span>
                            <span class="hidden sm:inline">{{ $statusText }}</span>
                            <span class="sm:hidden">{{ $statusShortText }}</span>
                        </span>
                    </td>
                    <td
                        class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 hidden lg:table-cell">
                        {{ $retur->user->name ?? '-' }}
                    </td>
                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('penjualan.retur.show', $retur) }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-150"
                                title="Lihat Detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">Detail</span>
                            </a>

                            @if ($retur->status === 'draft')
                                <a href="{{ route('penjualan.retur.edit', $retur) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-300 rounded-md hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-600 dark:hover:bg-blue-900/50 transition-colors duration-150"
                                    title="Edit Retur">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                            @endif

                            <a href="{{ route('penjualan.retur.pdf', $retur) }}" target="_blank"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-300 rounded-md hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:border-red-600 dark:hover:bg-red-900/50 transition-colors duration-150"
                                title="Cetak PDF">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span class="hidden sm:inline">PDF</span>
                            </a>

                            @if ($retur->status === 'draft')
                                <form action="{{ route('penjualan.retur.destroy', $retur) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Yakin ingin menghapus retur ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-300 rounded-md hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:border-red-600 dark:hover:bg-red-900/50 transition-colors duration-150"
                                        title="Hapus Retur">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        <span class="hidden sm:inline">Hapus</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-1">Tidak ada data retur
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada retur penjualan yang dibuat.
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
