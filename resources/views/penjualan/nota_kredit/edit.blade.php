<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                Edit Nota Kredit: {{ $notaKredit->nomor }}
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Edit data nota kredit yang sudah dibuat sebelumnya
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('penjualan.nota-kredit.show', $notaKredit->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Batal
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('penjualan.nota-kredit.update', $notaKredit->id) }}" method="POST"
            id="editNotaKreditForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                {{-- Nota Kredit Information --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Nota Kredit</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nomor"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor
                                    Nota Kredit</label>
                                <input type="text" name="nomor" id="nomor" value="{{ $notaKredit->nomor }}"
                                    readonly
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                            </div>
                            <div>
                                <label for="tanggal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal"
                                    value="{{ $notaKredit->tanggal ? date('Y-m-d', strtotime($notaKredit->tanggal)) : date('Y-m-d') }}"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                            </div>
                        </div>

                        <div>
                            <label for="customer_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer</label>
                            <select name="customer_id" id="customer_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ $notaKredit->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->company ? $customer->company . ' - ' . $customer->nama : $customer->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sales_order_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sales
                                Order</label>
                            <input type="hidden" name="sales_order_id" value="{{ $notaKredit->sales_order_id }}">
                            <input type="text" value="{{ $notaKredit->salesOrder?->nomor ?? 'N/A' }}" readonly
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                        </div>

                        <div>
                            <label for="retur_penjualan_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Retur
                                Penjualan</label>
                            <input type="hidden" name="retur_penjualan_id"
                                value="{{ $notaKredit->retur_penjualan_id }}">
                            <input type="text" value="{{ $notaKredit->returPenjualan?->nomor ?? 'N/A' }}" readonly
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                        </div>

                        <div>
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">{{ $notaKredit->catatan }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Detail Items Table --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Item</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="detailsTable">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Quantity</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Satuan</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Harga</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($notaKredit->details as $index => $detail)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="details[{{ $index }}][produk_id]"
                                                value="{{ $detail->produk_id }}">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $detail->produk->nama ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $detail->produk->kode ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <input type="number" step="0.01"
                                                name="details[{{ $index }}][quantity]"
                                                value="{{ $detail->quantity }}" readonly
                                                class="w-24 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md text-right">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="details[{{ $index }}][satuan_id]"
                                                value="{{ $detail->satuan_id }}">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $detail->satuan->nama ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <input type="number" step="1"
                                                name="details[{{ $index }}][harga]"
                                                value="{{ $detail->harga }}" readonly
                                                class="w-32 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md text-right">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <input type="number" step="1"
                                                name="details[{{ $index }}][subtotal]"
                                                value="{{ $detail->subtotal }}" readonly
                                                class="w-32 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md text-right">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th colspan="4"
                                        class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">
                                        Total:
                                    </th>
                                    <th class="px-6 py-3 text-right">
                                        <input type="number" name="total" id="total"
                                            value="{{ $notaKredit->total }}" readonly
                                            class="w-32 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md text-right font-bold">
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end">
                    <a href="{{ route('penjualan.nota-kredit.show', $notaKredit->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
