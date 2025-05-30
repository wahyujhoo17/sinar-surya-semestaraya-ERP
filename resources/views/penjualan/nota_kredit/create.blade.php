<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Buat Nota Kredit
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Untuk Retur Penjualan: {{ $returPenjualan->nomor }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('penjualan.retur.show', $returPenjualan->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Informasi Nota Kredit
                </h3>
            </div>

            <div class="p-6">
                <form action="{{ route('penjualan.nota-kredit.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="retur_penjualan_id" value="{{ $returPenjualan->id }}">
                    <input type="hidden" name="customer_id" value="{{ $returPenjualan->customer_id }}">
                    <input type="hidden" name="sales_order_id" value="{{ $returPenjualan->sales_order_id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Dokumen
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="nomor"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor
                                        Nota Kredit</label>
                                    <input type="text" name="nomor" id="nomor" value="{{ $nomorNotaKredit }}"
                                        readonly
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                </div>
                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal"
                                        value="{{ now()->format('Y-m-d') }}"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                                <textarea id="catatan" name="catatan" rows="3"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                    placeholder="Catatan untuk nota kredit..."></textarea>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Customer
                            </h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer</label>

                                    @if ($returPenjualan->customer->company)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $returPenjualan->customer->company }}</p>
                                    @else
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ $returPenjualan->customer->nama }}</p>
                                    @endif
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alamat</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $returPenjualan->customer->alamat }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Kontak</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $returPenjualan->customer->telepon ?? '-' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $returPenjualan->customer->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-base font-medium text-gray-700 dark:text-gray-300 mb-3">Detail Produk</h4>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Qty Retur
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Harga Satuan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @php
                                        $totalNilai = 0;
                                    @endphp
                                    @foreach ($returPenjualan->details as $detail)
                                        @php
                                            // Get the sales order detail to get the price
                                            $salesOrderDetail = \App\Models\SalesOrderDetail::where(
                                                'sales_order_id',
                                                $returPenjualan->sales_order_id,
                                            )
                                                ->where('produk_id', $detail->produk_id)
                                                ->first();

                                            $hargaSatuan = $salesOrderDetail ? $salesOrderDetail->harga : 0;
                                            $subtotal = $hargaSatuan * $detail->quantity;
                                            $totalNilai += $subtotal;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="hidden" name="details[{{ $loop->index }}][produk_id]"
                                                    value="{{ $detail->produk_id }}">
                                                <input type="hidden" name="details[{{ $loop->index }}][quantity]"
                                                    value="{{ $detail->quantity }}">
                                                <input type="hidden" name="details[{{ $loop->index }}][satuan_id]"
                                                    value="{{ $detail->satuan_id }}">
                                                <input type="hidden" name="details[{{ $loop->index }}][harga]"
                                                    value="{{ $hargaSatuan }}">
                                                <input type="hidden" name="details[{{ $loop->index }}][subtotal]"
                                                    value="{{ $subtotal }}">

                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $detail->produk->nama }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $detail->produk->kode }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $detail->quantity }} {{ $detail->satuan->nama }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <td colspan="3"
                                            class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Total Nota Kredit:
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-base font-bold text-primary-600 dark:text-primary-400">
                                                Rp {{ number_format($totalNilai, 0, ',', '.') }}
                                            </div>
                                            <input type="hidden" name="total" value="{{ $totalNilai }}">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Buat Nota Kredit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
