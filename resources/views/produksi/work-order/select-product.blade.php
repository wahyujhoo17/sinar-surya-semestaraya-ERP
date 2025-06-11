<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Perintah Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Pilih Perencanaan', 'url' => route('produksi.work-order.create')],
    ['label' => 'Pilih Produk'],
]" :currentPage="'Pilih Produk untuk Perintah Produksi'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Pilih Produk untuk Perintah Produksi
                </h1>
                <a href="{{ route('produksi.work-order.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Perencanaan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nomor Perencanaan</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ $perencanaan->nomor }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">
                            {{ date('d/m/Y', strtotime($perencanaan->tanggal)) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sales Order</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">
                            {{ $perencanaan->salesOrder->nomor ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Customer</p>
                        <p class="text-base font-medium text-gray-900 dark:text-white">
                            {{ $perencanaan->salesOrder->customer->nama ?? ($perencanaan->salesOrder->customer->company ?? '-') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Pilih Produk</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    Pilih produk yang akan diproduksi dari daftar berikut:
                </p>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Produk
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Kode
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Jumlah Perencanaan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Satuan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Stok Tersedia
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($perencanaan->detailPerencanaan as $detail)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        @if ($detail && $detail->produk)
                                            {{ $detail->produk->nama }}
                                        @else
                                            <span class="text-red-500">Produk tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if ($detail && $detail->produk)
                                            {{ $detail->produk->kode ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if ($detail)
                                            {{ number_format($detail->jumlah_produksi, 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if ($detail && $detail->satuan)
                                            {{ $detail->satuan->nama }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        @if ($detail)
                                            {{ number_format($detail->stok_tersedia, 2, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $detail ? $detail->keterangan : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if ($detail && $detail->produk_id)
                                            <a href="{{ route('produksi.work-order.create', ['perencanaan_id' => $perencanaan->id, 'produk_id' => $detail->produk_id]) }}"
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Buat WO
                                            </a>
                                        @else
                                            <span class="text-red-500">Data produk tidak lengkap</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada detail produk dalam perencanaan ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
