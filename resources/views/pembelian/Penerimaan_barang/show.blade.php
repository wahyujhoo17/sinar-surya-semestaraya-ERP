<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Modern Header with Status Badge and Actions --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="md:flex md:items-center md:justify-between p-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-sky-600 h-12 w-1.5 rounded-full mr-4">
                        </div>
                        <div>
                            <div class="flex items-center">
                                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl">
                                    {{ $penerimaan->nomor }}
                                </h2>
                                <span
                                    class="ml-4 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if ($penerimaan->status == 'selesai') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($penerimaan->status == 'parsial')
                                        bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                    @elseif($penerimaan->status == 'batal')
                                        bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @else
                                        bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                    {{ ucfirst($penerimaan->status) }}
                                </span>
                            </div>
                            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($penerimaan->tanggal)->format('d F Y') }}
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    PO: <a
                                        href="{{ route('pembelian.purchasing-order.show', $penerimaan->purchaseOrder->id) }}"
                                        class="ml-1 font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                        {{ $penerimaan->purchaseOrder->nomor }}
                                    </a>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Supplier: <span
                                        class="ml-1 font-medium text-gray-800 dark:text-gray-300">{{ $penerimaan->supplier->nama }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex lg:mt-0 md:mt-0">
                    <a href="{{ route('pembelian.penerimaan-barang.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                        </svg>
                        Kembali
                    </a>
                    <a href="#" onclick="printPage()"
                        class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak
                    </a>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Summary Panel --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Penerimaan Barang Info --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                    clip-rule="evenodd" />
                            </svg>
                            Informasi Penerimaan
                        </h3>
                    </div>
                    <div class="p-4">
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->nomor }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ \Carbon\Carbon::parse($penerimaan->tanggal)->format('d F Y') }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->gudang->nama ?? '-' }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="col-span-2">
                                    <span
                                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($penerimaan->status == 'selesai') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($penerimaan->status == 'parsial')
                                            bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                        @elseif($penerimaan->status == 'batal')
                                            bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @else
                                            bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                        {{ ucfirst($penerimaan->status) }}
                                    </span>
                                </dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->user->name ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Purchase Order Info --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                    clip-rule="evenodd" />
                            </svg>
                            Referensi Purchase Order
                        </h3>
                    </div>
                    <div class="p-4">
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor PO</dt>
                                <dd class="col-span-2">
                                    <a href="{{ route('pembelian.purchasing-order.show', $penerimaan->purchaseOrder->id) }}"
                                        class="text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 flex items-center">
                                        {{ $penerimaan->purchaseOrder->nomor }}
                                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal PO</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ \Carbon\Carbon::parse($penerimaan->purchaseOrder->tanggal)->format('d F Y') }}
                                </dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status PO</dt>
                                <dd class="col-span-2">
                                    <span
                                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($penerimaan->purchaseOrder->status == 'selesai') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($penerimaan->purchaseOrder->status == 'dikirim')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @elseif($penerimaan->purchaseOrder->status == 'dibatalkan')
                                            bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @else
                                            bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 @endif">
                                        {{ ucfirst($penerimaan->purchaseOrder->status) }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Dokumen Terkait --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div
                        class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Dokumen Terkait
                        </h3>
                    </div>
                    <div class="p-4">
                        <dl class="space-y-3">
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Surat Jalan</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->nomor_surat_jalan ?? '-' }}</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tgl. Surat Jalan</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->tanggal_surat_jalan ? \Carbon\Carbon::parse($penerimaan->tanggal_surat_jalan)->format('d F Y') : '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Detail Items Section --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z"
                                clip-rule="evenodd" />
                            <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z" />
                        </svg>
                        Detail Item Diterima
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/60">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Produk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Deskripsi
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Jumlah
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Satuan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">
                                    Lokasi Rak
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($penerimaan->details as $detail)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="flex-shrink-0 h-9 w-9 flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $detail->produk ? $detail->produk->nama : $detail->nama_item }}
                                                </div>
                                                @if ($detail->produk)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $detail->produk->kode }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-200 line-clamp-2">
                                            {{ $detail->deskripsi ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ number_format($detail->quantity, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            dari {{ number_format($detail->poDetail->quantity ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $detail->satuan->nama ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if (
                                            $detail->produk &&
                                                $detail->produk->stokProduk &&
                                                $detail->produk->stokProduk->where('gudang_id', $penerimaan->gudang_id)->first())
                                            @php
                                                $stokProduk = $detail->produk->stokProduk
                                                    ->where('gudang_id', $penerimaan->gudang_id)
                                                    ->first();
                                            @endphp
                                            <span
                                                class="px-2.5 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                {{ $stokProduk->lokasi_rak }}
                                            </span>
                                            @if ($stokProduk->batch_number)
                                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    Batch: {{ $stokProduk->batch_number }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-8 w-8 mx-auto mb-2 text-gray-400 dark:text-gray-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <span class="block">Tidak ada data item</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Supplier Info --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path
                                d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            <path
                                d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                        </svg>
                        Informasi Supplier
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dl class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->supplier->nama }}</dd>
                            </dl>
                            <dl class="grid grid-cols-3 gap-2 mt-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->supplier->email ?? '-' }}</dd>
                            </dl>
                            <dl class="grid grid-cols-3 gap-2 mt-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telepon</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->supplier->telepon ?? '-' }}</dd>
                            </dl>
                        </div>
                        <div>
                            <dl class="grid grid-cols-3 gap-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2 whitespace-pre-wrap">
                                    {{ $penerimaan->supplier->alamat ?? '-' }}</dd>
                            </dl>
                            <dl class="grid grid-cols-3 gap-2 mt-3">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NPWP</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $penerimaan->supplier->npwp ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            @if ($penerimaan->catatan)
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div
                        class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Catatan
                        </h3>
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">
                            {{ $penerimaan->catatan }}</p>
                    </div>
                </div>
            @endif

            {{-- Riwayat Aktivitas --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700/30 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 mr-2 text-primary-500 dark:text-primary-400" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        Riwayat Aktivitas
                    </h3>
                </div>
                <div class="p-4">
                    <div class="flow-root">
                        <ul class="space-y-6">
                            <li>
                                <div class="relative flex items-start space-x-3">
                                    <div class="relative">
                                        <div
                                            class="h-9 w-9 rounded-full bg-green-500 dark:bg-green-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-base font-medium text-gray-900 dark:text-white">Penerimaan
                                                dibuat</div>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Dibuat oleh {{ $penerimaan->user->name }} pada
                                                {{ $penerimaan->created_at->format('d M Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            @if ($penerimaan->updated_at && $penerimaan->updated_at->gt($penerimaan->created_at))
                                <li>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div
                                                class="h-9 w-9 rounded-full bg-blue-500 dark:bg-blue-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-base font-medium text-gray-900 dark:text-white">
                                                    Penerimaan diperbarui</div>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    Diperbarui pada {{ $penerimaan->updated_at->format('d M Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            @if ($penerimaan->status == 'selesai')
                                <li>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div
                                                class="h-9 w-9 rounded-full bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-base font-medium text-gray-900 dark:text-white">
                                                    Penerimaan selesai</div>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    Semua item telah diterima sepenuhnya
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @elseif($penerimaan->status == 'parsial')
                                <li>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div
                                                class="h-9 w-9 rounded-full bg-yellow-500 dark:bg-yellow-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-base font-medium text-gray-900 dark:text-white">
                                                    Penerimaan parsial</div>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    Beberapa item masih dalam proses pengiriman
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @elseif($penerimaan->status == 'batal')
                                <li>
                                    <div class="relative flex items-start space-x-3">
                                        <div class="relative">
                                            <div
                                                class="h-9 w-9 rounded-full bg-red-500 dark:bg-red-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-base font-medium text-gray-900 dark:text-white">
                                                    Penerimaan dibatalkan</div>
                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    Penerimaan barang telah dibatalkan
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function printPage() {
                // Add any pre-print logic here
                window.print();
            }
        </script>
    @endpush

    {{-- Print CSS --}}
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print-section,
            .print-section * {
                visibility: visible;
            }

            .print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print,
            button,
            a[onclick] {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
