<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Perintah Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Detail', 'url' => route('produksi.work-order.show', $workOrder->id)],
    ['label' => 'Pengembalian Material'],
]" :currentPage="'Pengembalian Material'">

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-primary-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                        </svg>
                        Pengembalian Material: <span class="text-primary-600 ml-2">{{ $workOrder->nomor }}</span>
                    </h1>
                    <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="col-span-1 lg:col-span-2">
                        <div
                            class="p-5 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg border border-blue-200 dark:border-blue-700 shadow-sm">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-base font-semibold text-blue-800 dark:text-blue-200">Informasi
                                        Pengembalian Material</h3>
                                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300 space-y-2">
                                        <p>
                                            Form ini digunakan untuk mengembalikan material sisa produksi ke gudang
                                            asal.
                                            Jumlah yang dapat dikembalikan adalah selisih antara jumlah yang diambil
                                            dengan
                                            jumlah yang terpakai.
                                        </p>
                                        <p class="font-medium">
                                            Material yang dikembalikan akan ditambahkan ke stok gudang <span
                                                class="text-blue-800 dark:text-blue-200 font-bold">{{ $workOrder->gudangProduksi->nama }}</span>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-600 shadow-sm">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-3 text-base flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Detail Work Order
                        </h3>
                        <div class="grid grid-cols-1 gap-3 text-sm">
                            <div
                                class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-600">
                                <div class="text-gray-600 dark:text-gray-400">Nomor WO:</div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $workOrder->nomor }}</div>
                            </div>

                            <div
                                class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-600">
                                <div class="text-gray-600 dark:text-gray-400">Produk:</div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $workOrder->produk->nama }}
                                </div>
                            </div>

                            <div
                                class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-600">
                                <div class="text-gray-600 dark:text-gray-400">Kuantitas:</div>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($workOrder->quantity, 2) }} {{ $workOrder->satuan->nama }}</div>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="text-gray-600 dark:text-gray-400">Status:</div>
                                <div class="font-medium">
                                    <span
                                        class="px-2.5 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-700 dark:text-indigo-200 font-semibold">
                                        {{ ucwords(str_replace('_', ' ', $workOrder->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($materials->isEmpty())
                    <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg mb-6">
                        <div
                            class="px-4 py-3 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900 dark:to-amber-900 border-b border-yellow-200 dark:border-yellow-800">
                            <h3 class="text-base font-medium text-yellow-800 dark:text-yellow-200 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-yellow-600 dark:text-yellow-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Material yang Tersisa
                            </h3>
                        </div>
                        <div
                            class="bg-gradient-to-b from-amber-50 to-yellow-50 dark:from-amber-900 dark:to-yellow-900 p-10 flex flex-col items-center justify-center text-center">
                            <div
                                class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-full inline-flex items-center justify-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-12 w-12 text-yellow-600 dark:text-yellow-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Tidak ada
                                material yang tersisa untuk dikembalikan</h4>
                            <p class="text-base text-yellow-700 dark:text-yellow-300 mb-6 max-w-md">Semua material sudah
                                digunakan dalam proses produksi atau telah dikembalikan sebelumnya.</p>
                            <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                                class="inline-flex items-center px-5 py-2.5 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring focus:ring-primary-300 disabled:opacity-25 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali ke Work Order
                            </a>
                        </div>
                    </div>
                @else
                    @if (auth()->user()->hasPermission('work_order.edit'))
                        <form action="{{ route('produksi.work-order.store-pengembalian', $workOrder->id) }}"
                            method="POST">
                            @csrf

                            <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg mb-6">
                                <div
                                    class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900 dark:to-blue-900 border-b border-indigo-200 dark:border-indigo-800">
                                    <h3
                                        class="text-base font-medium text-indigo-800 dark:text-indigo-200 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Material yang Tersisa
                                    </h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead
                                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-750">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Produk
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Jumlah Awal
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Jumlah Terpakai
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Tersisa
                                                </th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Jumlah Dikembalikan
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($materials as $material)
                                                <tr
                                                    class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div>
                                                                <div
                                                                    class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $material->produk->nama }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                    {{ $material->produk->kode }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="material_id[]"
                                                            value="{{ $material->id }}">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white">
                                                            {{ number_format($material->quantity, 2) }}
                                                            <span
                                                                class="text-xs text-gray-500 dark:text-gray-400">{{ $material->satuan->nama }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900 dark:text-white">
                                                            {{ number_format($material->quantity_terpakai, 2) }}
                                                            <span
                                                                class="text-xs text-gray-500 dark:text-gray-400">{{ $material->satuan->nama }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div
                                                            class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                                            {{ number_format($material->quantity - $material->quantity_terpakai, 2) }}
                                                            <span
                                                                class="text-xs text-gray-500 dark:text-gray-400">{{ $material->satuan->nama }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center space-x-2">
                                                            <input type="number" name="quantity_return[]"
                                                                min="0"
                                                                max="{{ $material->quantity - $material->quantity_terpakai }}"
                                                                step="0.01" value="0"
                                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                                            <span
                                                                class="text-sm text-gray-500 dark:text-gray-400">{{ $material->satuan->nama }}</span>
                                                            <button type="button"
                                                                onclick="this.previousElementSibling.previousElementSibling.value='{{ $material->quantity - $material->quantity_terpakai }}'"
                                                                class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800 transition-colors duration-150">
                                                                Max
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg mb-6">
                                <div
                                    class="px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-750 border-b border-gray-200 dark:border-gray-600">
                                    <h3 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5 mr-2 text-gray-600 dark:text-gray-300" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Catatan Pengembalian
                                    </h3>
                                </div>
                                <div class="p-5 bg-white dark:bg-gray-800">
                                    <label for="catatan"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tambahkan catatan mengenai pengembalian material (opsional)
                                    </label>
                                    <textarea id="catatan" name="catatan" rows="3"
                                        placeholder="Misal: Material dikembalikan dalam kondisi baik, dapat digunakan kembali untuk produksi lain"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">{{ old('catatan') }}</textarea>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-colors duration-150 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Batal
                                </a>
                                @if (auth()->user()->hasPermission('work_order.edit'))
                                    <button type="submit"
                                        class="inline-flex items-center px-5 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring focus:ring-primary-300 disabled:opacity-25 transition-colors duration-150 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Simpan Pengembalian
                                    </button>
                                @else
                                    <div
                                        class="inline-flex items-center px-5 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Akses Terbatas
                                    </div>
                                @endif
                            </div>
                        </form>
                    @else
                        <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg mb-6">
                            <div
                                class="px-4 py-3 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 border-b border-red-200 dark:border-red-700">
                                <h3 class="text-base font-medium text-red-800 dark:text-red-200 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 mr-2 text-red-600 dark:text-red-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Akses Terbatas
                                </h3>
                            </div>
                            <div class="p-10 flex flex-col items-center justify-center text-center">
                                <div
                                    class="bg-red-100 dark:bg-red-800 p-4 rounded-full inline-flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 text-red-600 dark:text-red-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-red-800 dark:text-red-200 mb-2">Akses Ditolak
                                </h4>
                                <p class="text-base text-red-700 dark:text-red-300 mb-6 max-w-md">Anda tidak memiliki
                                    izin
                                    untuk melakukan pengembalian material. Hubungi administrator untuk mendapatkan
                                    akses.
                                </p>
                                <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                                    class="inline-flex items-center px-5 py-2.5 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring focus:ring-gray-300 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali ke Work Order
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
