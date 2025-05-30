{{-- Partial for displaying replacement products --}}
<div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Barang Pengganti</h3>
        @if ($returPenjualan->status === 'menunggu_barang_pengganti')
            <span
                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1"></span>
                Menunggu Barang Pengganti
            </span>
        @endif
    </div>
    <div class="p-6">
        @if ($returPenjualan->barangPengganti->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Produk
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Satuan
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Gudang
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal Pengiriman
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($returPenjualan->barangPengganti as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                        {{ $item->produk->kode ?? 'Kode tidak tersedia' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ number_format($item->quantity, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $item->satuan->nama ?? 'Satuan tidak ditemukan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $item->gudang->nama ?? 'Gudang tidak ditemukan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if ($item->tanggal_pengiriman)
                                        {{ $item->tanggal_pengiriman->format('d F Y') }}
                                    @else
                                        <span class="text-amber-600 dark:text-amber-400">Belum dikirim</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($returPenjualan->barangPengganti->first()->no_referensi || $returPenjualan->barangPengganti->first()->catatan)
                <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if ($returPenjualan->barangPengganti->first()->no_referensi)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Referensi:</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $returPenjualan->barangPengganti->first()->no_referensi }}</p>
                            </div>
                        @endif

                        @if ($returPenjualan->barangPengganti->first()->catatan)
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Catatan:</p>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $returPenjualan->barangPengganti->first()->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="py-6 text-center">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z">
                        </path>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mb-2">Belum ada data barang pengganti</p>
                @if ($returPenjualan->status === 'menunggu_barang_pengganti')
                    <p class="text-sm text-gray-400 dark:text-gray-500">
                        Pengiriman barang pengganti masih dalam proses
                    </p>
                @endif
            </div>
        @endif
    </div>
</div>
