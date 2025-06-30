<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Perintah Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Detail', 'url' => route('produksi.work-order.show', $workOrder->id)],
    ['label' => 'Pengambilan Bahan Baku'],
]" :currentPage="'Pengambilan Bahan Baku'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                    </svg>
                    Pengambilan Bahan Baku
                </h1>
                <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            {{-- Informasi WO --}}
            <div
                class="lg:col-span-1 bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Work Order</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Work Order</dt>
                            <dd class="mt-1 text-base font-medium text-gray-900 dark:text-white">{{ $workOrder->nomor }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Produk</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->produk->nama ?? 'Produk tidak ditemukan' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Produksi</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->quantity }} {{ $workOrder->satuan->nama ?? '' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gudang Produksi</dt>
                            <dd class="mt-1 text-base text-gray-900 dark:text-white">
                                {{ $workOrder->gudangProduksi->nama ?? '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Form Pengambilan --}}
            <div class="lg:col-span-2">
                <form action="{{ route('produksi.work-order.store-pengambilan', $workOrder->id) }}" method="POST"
                    id="form-pengambilan">
                    @csrf
                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Form Pengambilan Bahan Baku
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" name="tanggal" id="tanggal"
                                            value="{{ old('tanggal', date('d/m/Y')) }}"
                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md datepicker"
                                            placeholder="Pilih tanggal">
                                    </div>
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan
                                </label>
                                <textarea name="catatan" id="catatan" rows="3"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                        <div
                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Detail Material</h2>
                        </div>
                        <div class="p-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Material
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Kebutuhan
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Stok Tersedia
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jumlah Ambil
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($workOrder->materials as $index => $material)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $material->produk->nama ?? 'Produk tidak ditemukan' }}
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $material->produk->kode ?? '-' }}
                                                </div>
                                                <input type="hidden" name="detail[{{ $index }}][produk_id]"
                                                    value="{{ $material->produk_id }}">
                                                <input type="hidden" name="detail[{{ $index }}][satuan_id]"
                                                    value="{{ $material->satuan_id }}">
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">
                                                {{ number_format($material->quantity, 2, ',', '.') }}
                                                {{ $material->satuan->nama ?? '-' }}
                                                <input type="hidden"
                                                    name="detail[{{ $index }}][jumlah_diminta]"
                                                    value="{{ number_format($material->quantity, 2, '.', '') }}">
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-right 
                                                {{ $material->stok_tersedia < $material->quantity ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }}">
                                                {{ number_format($material->stok_tersedia, 2, ',', '.') }}
                                                {{ $material->satuan->nama ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <input type="number" step="0.01" min="0"
                                                    name="detail[{{ $index }}][jumlah_diambil]"
                                                    id="jumlah-diminta-{{ $index }}"
                                                    value="{{ old('detail.' . $index . '.jumlah_diambil', min($material->quantity, $material->stok_tersedia)) }}"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    {{ $material->stok_tersedia == 0 ? 'disabled' : '' }}>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <input type="text" name="detail[{{ $index }}][keterangan]"
                                                    id="keterangan-{{ $index }}"
                                                    value="{{ old('detail.' . $index . '.keterangan', $material->stok_tersedia < $material->quantity ? 'Stok tidak mencukupi' : '') }}"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                    placeholder="Keterangan (opsional)">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Simpan Pengambilan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Datepicker
                flatpickr('.datepicker', {
                    dateFormat: 'd/m/Y',
                    locale: 'id'
                });

                // Form validation
                document.getElementById('form-pengambilan').addEventListener('submit', function(e) {
                    // Check if at least one material has a non-zero quantity
                    let hasNonZeroQuantity = false;
                    const quantityInputs = document.querySelectorAll(
                        'input[name^="detail"][name$="[jumlah_diminta]"]');

                    for (const input of quantityInputs) {
                        if (parseFloat(input.value) > 0) {
                            hasNonZeroQuantity = true;
                            break;
                        }
                    }

                    if (!hasNonZeroQuantity) {
                        e.preventDefault();
                        alert('Setidaknya satu material harus memiliki jumlah pengambilan lebih dari 0.');
                        return false;
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
