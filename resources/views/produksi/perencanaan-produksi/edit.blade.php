<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Perencanaan Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Edit'],
]" :currentPage="'Edit Perencanaan Produksi'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    Edit Perencanaan Produksi
                </h1>
                <a href="{{ route('produksi.perencanaan-produksi.index') }}"
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

        <form action="{{ route('produksi.perencanaan-produksi.update', $perencanaan->id) }}" method="POST"
            id="form-perencanaan">
            @csrf
            @method('PUT')
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Perencanaan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nomor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor
                                Perencanaan <span class="text-red-500">*</span></label>
                            <input type="text" id="nomor" name="nomor"
                                value="{{ old('nomor', $perencanaan->nomor) }}" readonly
                                class="bg-gray-100 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            @error('nomor')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" id="tanggal" name="tanggal"
                                value="{{ old('tanggal', $perencanaan->tanggal->format('Y-m-d')) }}" required
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sales_order_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sales Order
                                <span class="text-red-500">*</span></label>
                            <select id="sales_order_id" name="sales_order_id" required
                                {{ $perencanaan->status != 'draft' ? 'disabled' : '' }}
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md select2-sales-order">
                                <option value="">-- Pilih Sales Order --</option>
                                @foreach ($salesOrders as $so)
                                    <option value="{{ $so->id }}"
                                        {{ old('sales_order_id', $perencanaan->sales_order_id) == $so->id ? 'selected' : '' }}
                                        data-company="{{ $so->customer->company ?? ($so->customer->nama ?? 'Customer tidak ditemukan') }}"
                                        data-tanggal="{{ $so->tanggal ? $so->tanggal->format('d/m/Y') : '-' }}"
                                        data-total="{{ number_format($so->total ?? 0, 0, ',', '.') }}">
                                        {{ $so->nomor }} -
                                        {{ $so->customer->company ?? ($so->customer->nama ?? 'Customer tidak ditemukan') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sales_order_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gudang_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gudang untuk Cek
                                Stok <span class="text-red-500">*</span></label>
                            <select id="gudang_id" name="gudang_id" required
                                {{ $perencanaan->status != 'draft' ? 'disabled' : '' }}
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach ($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}"
                                        {{ old('gudang_id', $perencanaan->gudang_id) == $gudang->id ? 'selected' : '' }}>
                                        {{ $gudang->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gudang_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                            <textarea id="catatan" name="catatan" rows="3"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan', $perencanaan->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Detail Produk</h2>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="table-items">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        #
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah Order
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Stok Tersedia
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Jumlah Produksi
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                                id="items-container">
                                @foreach ($perencanaan->details as $index => $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $index + 1 }}
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $detail->produk->nama }}
                                            <input type="hidden" name="detail[{{ $index }}][id]"
                                                value="{{ $detail->id }}">
                                            <input type="hidden" name="detail[{{ $index }}][produk_id]"
                                                value="{{ $detail->produk_id }}">
                                            <input type="hidden" name="detail[{{ $index }}][satuan_id]"
                                                value="{{ $detail->satuan_id }}">
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $detail->jumlah }} {{ $detail->satuan->nama ?? '' }}
                                            <input type="hidden" name="detail[{{ $index }}][jumlah]"
                                                value="{{ $detail->jumlah }}">
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ $detail->stok_tersedia }} {{ $detail->satuan->nama ?? '' }}
                                            <input type="hidden" name="detail[{{ $index }}][stok_tersedia]"
                                                value="{{ $detail->stok_tersedia }}">
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <input type="number"
                                                class="w-full focus:ring-primary-500 focus:border-primary-500 shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                name="detail[{{ $index }}][jumlah_produksi]"
                                                value="{{ old('detail.' . $index . '.jumlah_produksi', $detail->jumlah_produksi) }}"
                                                min="0" step="0.01" required
                                                {{ $perencanaan->status != 'draft' ? 'readonly' : '' }}>
                                        </td>
                                        <td
                                            class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <input type="text"
                                                class="w-full focus:ring-primary-500 focus:border-primary-500 shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                                name="detail[{{ $index }}][keterangan]"
                                                value="{{ old('detail.' . $index . '.keterangan', $detail->keterangan) }}"
                                                {{ $perencanaan->status != 'draft' ? 'readonly' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($perencanaan->status == 'draft')
                <div class="flex justify-between items-center mb-8">
                    <div class="flex space-x-3">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan
                        </button>
                        <a href="{{ route('produksi.perencanaan-produksi.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                    </div>
                </div>
            @endif
        </form>
    </div>

    @push('scripts')
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for Sales Order
            $('.select2-sales-order').select2({
                placeholder: '-- Pilih Sales Order --',
                allowClear: true,
                width: '100%',
                disabled: {{ $perencanaan->status != 'draft' ? 'true' : 'false' }},
                templateResult: formatSalesOrder,
                templateSelection: formatSalesOrderSelection
            });

            function formatSalesOrder(option) {
                if (!option.id) {
                    return option.text;
                }

                var $option = $(option.element);
                var company = $option.data('company');

                // Check if dark mode is active
                var isDarkMode = document.documentElement.classList.contains('dark');
                var textColor = isDarkMode ? 'text-white' : 'text-gray-900';

                var $result = $(
                    '<div class="select2-result">' +
                    '<div class="font-medium ' + textColor + '">' + option.text + '</div>' +
                    '</div>'
                );

                return $result;
            }

            function formatSalesOrderSelection(option) {
                return option.text || '-- Pilih Sales Order --';
            }
        });
    </script>
</x-app-layout>
