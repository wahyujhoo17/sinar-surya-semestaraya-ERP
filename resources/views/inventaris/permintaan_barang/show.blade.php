<x-app-layout :breadcrumbs="[
    ['label' => 'Inventaris'],
    ['label' => 'Permintaan Barang', 'url' => route('inventaris.permintaan-barang.index')],
    ['label' => 'Detail'],
]" :currentPage="'Detail Permintaan Barang'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Enhanced Overview Header --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <span class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Detail Permintaan Barang
                            </span>
                        </h1>
                        @if ($permintaanBarang->status == 'menunggu')
                            <span
                                class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                Menunggu
                            </span>
                        @elseif ($permintaanBarang->status == 'diproses')
                            <span
                                class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                Diproses
                            </span>
                        @elseif ($permintaanBarang->status == 'selesai')
                            <span
                                class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                Selesai
                            </span>
                        @elseif ($permintaanBarang->status == 'dibatalkan')
                            <span
                                class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                Dibatalkan
                            </span>
                        @endif
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Nomor permintaan: {{ $permintaanBarang->nomor }}
                    </p>
                </div>
                <div class="mt-5 flex xl:ml-4 md:mt-0">
                    <span class="block md:ml-3">
                        <a href="{{ route('inventaris.permintaan-barang.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                    </span>

                    <span class="block ml-3">
                        <a href="{{ route('inventaris.permintaan-barang.pdf', $permintaanBarang->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download PDF
                        </a>
                    </span>

                    @if ($permintaanBarang->status == 'diproses')
                        <span class="block ml-3">
                            <a href="{{ route('inventaris.permintaan-barang.create-do', $permintaanBarang->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-white"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Buat Delivery Order
                            </a>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Left Column --}}
            <div class="space-y-6 md:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:p-6">
                        <h3
                            class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Permintaan
                        </h3>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor</dt>
                                    <dd class="text-sm font-semibold text-gray-900 dark:text-white ml-auto">
                                        {{ $permintaanBarang->nomor }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ \Carbon\Carbon::parse($permintaanBarang->tanggal)->format('d F Y') }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ $permintaanBarang->gudang->nama ?? '-' }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sales Order</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        @if ($permintaanBarang->salesOrder)
                                            <a class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300"
                                                href="{{ route('penjualan.sales-order.show', $permintaanBarang->sales_order_id) }}">
                                                {{ $permintaanBarang->salesOrder->nomor }}
                                            </a>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        @if ($permintaanBarang->customer)
                                            <span>{{ $permintaanBarang->customer->nama }}</span>
                                            @if ($permintaanBarang->customer->company)
                                                <span
                                                    class="block text-xs text-gray-500 dark:text-gray-400">{{ $permintaanBarang->customer->company }}</span>
                                            @endif
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat Oleh</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ $permintaanBarang->user->name ?? '-' }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="text-sm ml-auto">
                                        @if ($permintaanBarang->status == 'menunggu')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                Menunggu
                                            </span>
                                        @elseif ($permintaanBarang->status == 'diproses')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                Diproses
                                            </span>
                                        @elseif ($permintaanBarang->status == 'selesai')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                Selesai
                                            </span>
                                        @elseif ($permintaanBarang->status == 'dibatalkan')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                Dibatalkan
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Dibuat
                                    </dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ $permintaanBarang->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                <div class="py-3 flex justify-between flex-wrap">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Terakhir
                                        Diperbarui
                                    </dt>
                                    <dd class="text-sm text-gray-900 dark:text-white ml-auto">
                                        {{ $permintaanBarang->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:p-6">
                        <h3
                            class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Catatan
                        </h3>
                        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
                            <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-400">
                                {{ $permintaanBarang->catatan ?? 'Tidak ada catatan' }}
                            </div>
                        </div>
                    </div>
                </div>

                @if ($permintaanBarang->status != 'selesai' && $permintaanBarang->status != 'dibatalkan')
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-5 sm:p-6">
                            <h3
                                class="text-lg font-medium leading-6 text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tindakan
                            </h3>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="space-y-3">
                                    <button type="button" id="openStatusUpdateBtn"
                                        class="inline-flex items-center w-full justify-center px-4 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 transform hover:scale-105"
                                        data-modal-target="updateStatusModal" data-modal-toggle="updateStatusModal">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column - Item List --}}
            <div class="space-y-6 md:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <div>
                            <h3
                                class="text-lg leading-6 font-medium text-gray-900 dark:text-white flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Rincian Produk
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                Daftar produk yang diminta
                            </p>
                        </div>
                        <span
                            class="bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-400 text-xs font-medium py-1 px-2 rounded">
                            {{ $permintaanBarang->details->count() }} produk
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Satuan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Stok Tersedia
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($permintaanBarang->details as $index => $detail)
                                    <tr
                                        class="{{ $detail->jumlah_tersedia < $detail->jumlah }} ? 'bg-yellow-50 dark:bg-yellow-900/10' : 'hover:bg-gray-50 dark:hover:bg-gray-700/30'">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            <div class="font-medium">{{ $detail->produk->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $detail->produk->kode ?? '-' }}</div>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $detail->satuan->nama ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ number_format($detail->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ number_format($detail->jumlah_tersedia, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($detail->jumlah_tersedia < $detail->jumlah)
                                                <span class="text-red-600 dark:text-red-400">Stok kurang
                                                    {{ number_format($detail->jumlah - $detail->jumlah_tersedia, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span
                                                    class="text-gray-500 dark:text-gray-400">{{ $detail->keterangan ?? '-' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Tidak ada produk yang diminta
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Update Status -->
    <div id="updateStatusModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 items-center justify-center w-full md:inset-0 h-screen max-h-full bg-gray-900 bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Update Status Permintaan Barang
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="updateStatusModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form action="{{ route('inventaris.permintaan-barang.update-status', $permintaanBarang->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-4 md:p-5">
                        <div class="mb-4">
                            <label for="status"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status <span
                                    class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="menunggu"
                                    {{ $permintaanBarang->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="diproses"
                                    {{ $permintaanBarang->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai"
                                    {{ $permintaanBarang->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan"
                                    {{ $permintaanBarang->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="catatan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan</label>
                            <textarea id="catatan" name="catatan" rows="3"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">{{ $permintaanBarang->catatan }}</textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600 transition-all duration-300"
                                data-modal-hide="updateStatusModal">
                                Batal
                            </button>
                            <button type="submit" id="submitStatusBtn"
                                class="text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 transition-all duration-300 transform hover:scale-105">
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    /* Animation for modal */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideIn {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    #updateStatusModal.flex {
        animation: fadeIn 0.3s ease forwards;
    }

    #updateStatusModal.flex>div {
        animation: slideIn 0.3s ease forwards;
    }
</style>

<script>
    // Make sure modal toggle functionality works properly
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listener specifically to our update status button
        const updateStatusBtn = document.getElementById('openStatusUpdateBtn');
        const updateModal = document.getElementById('updateStatusModal');

        if (updateStatusBtn && updateModal) {
            updateStatusBtn.addEventListener('click', function() {
                updateModal.classList.remove('hidden');
                updateModal.classList.add('flex');
            });
        }

        // Handle all close buttons
        const closeButtons = document.querySelectorAll('[data-modal-hide]');

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal-hide');
                const modal = document.getElementById(modalId);

                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });

        // Handle background click to close modal
        updateModal?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
                this.classList.remove('flex');
            }
        });

        // Handle submit with confirmation
        const submitStatusBtn = document.getElementById('submitStatusBtn');
        if (submitStatusBtn) {
            submitStatusBtn.addEventListener('click', function(e) {
                e.preventDefault();

                const statusSelect = document.getElementById('status');
                const newStatus = statusSelect.options[statusSelect.selectedIndex].text;

                // Show detailed confirmation dialog
                let confirmMessage =
                    `Apakah Anda yakin ingin mengubah status permintaan barang menjadi "${newStatus}"?`;

                // Add additional context based on status
                if (newStatus === 'Selesai') {
                    confirmMessage +=
                        "\n\nPerubahan status menjadi 'Selesai' menandakan bahwa permintaan barang telah selesai diproses.";
                } else if (newStatus === 'Dibatalkan') {
                    confirmMessage +=
                        "\n\nPerubahan status menjadi 'Dibatalkan' akan membatalkan permintaan barang ini.";
                }

                // If confirmed, submit the form
                if (confirm(confirmMessage)) {
                    this.closest('form').submit();
                }
            });
        }
    });
</script>
