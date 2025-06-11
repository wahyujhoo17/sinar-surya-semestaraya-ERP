<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Perencanaan Produksi', 'url' => route('produksi.perencanaan-produksi.index')],
    ['label' => 'Buat Baru'],
]" :currentPage="'Buat Perencanaan Produksi'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    Buat Perencanaan Produksi
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

        <form action="{{ route('produksi.perencanaan-produksi.store') }}" method="POST" id="form-perencanaan">
            @csrf
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Perencanaan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nomor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor
                                Perencanaan <span class="text-red-500">*</span></label>
                            <input type="text" id="nomor" name="nomor" value="{{ $nomor }}" readonly
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
                                value="{{ old('tanggal', date('Y-m-d')) }}" required
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
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih Sales Order --</option>
                                @foreach ($salesOrders as $so)
                                    <option value="{{ $so->id }}"
                                        {{ old('sales_order_id') == $so->id ? 'selected' : '' }}>
                                        {{ $so->nomor }} -
                                        {{ $so->customer->nama ?? ($so->customer->company ?? 'Customer tidak ditemukan') }}
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
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach ($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}"
                                        {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
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
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan') }}</textarea>
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
                                <tr id="empty-row">
                                    <td colspan="6"
                                        class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        Pilih Sales Order terlebih dahulu
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

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
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Log untuk debugging
            console.log('DOM loaded');

            // Event saat sales order dipilih
            document.getElementById('sales_order_id').addEventListener('change', function() {
                console.log('Sales Order changed');
                const soId = this.value;
                const gudangId = document.getElementById('gudang_id').value;

                if (soId && gudangId) {
                    loadSalesOrderItems(soId, gudangId);
                } else {
                    document.getElementById('items-container').innerHTML =
                        '<tr id="empty-row"><td colspan="6" class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">Pilih Sales Order dan Gudang terlebih dahulu</td></tr>';
                }
            });

            // Event saat gudang dipilih
            document.getElementById('gudang_id').addEventListener('change', function() {
                console.log('Gudang changed');
                const soId = document.getElementById('sales_order_id').value;
                const gudangId = this.value;

                if (soId && gudangId) {
                    loadSalesOrderItems(soId, gudangId);
                }
            });

            function loadSalesOrderItems(soId, gudangId) {
                console.log(`Loading items for SO: ${soId}, Gudang: ${gudangId}`);

                // Tampilkan loading state
                document.getElementById('items-container').innerHTML =
                    '<tr><td colspan="6" class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">Loading data...</td></tr>';

                const url =
                    `{{ route('produksi.perencanaan-produksi.get-so-items') }}?sales_order_id=${soId}&gudang_id=${gudangId}`;
                console.log('Fetching URL:', url);

                fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data received:', data);

                        if (data.items && data.items.length > 0) {
                            let html = '';
                            data.items.forEach((item, index) => {
                                // Pastikan semua properti tersedia dengan nilai default jika tidak ada
                                const produkId = item.produk_id || '';
                                const produkNama = item.produk_nama || 'Produk tidak diketahui';
                                const satuanId = item.satuan_id || '';
                                const satuanNama = item.satuan_nama || 'Satuan';
                                const quantity = item.quantity || 0;
                                const stokTersedia = item.stok_tersedia || 0;
                                const jumlahProduksi = Math.max(0, quantity - stokTersedia);

                                html += `
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    ${index + 1}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    ${produkNama}
                                    <input type="hidden" name="detail[${index}][produk_id]" value="${produkId}">
                                    <input type="hidden" name="detail[${index}][satuan_id]" value="${satuanId}">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    ${quantity} ${satuanNama}
                                    <input type="hidden" name="detail[${index}][jumlah]" value="${quantity}">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    ${stokTersedia} ${satuanNama}
                                    <input type="hidden" name="detail[${index}][stok_tersedia]" value="${stokTersedia}">
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <input type="number" class="w-full focus:ring-primary-500 focus:border-primary-500 shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" name="detail[${index}][jumlah_produksi]" value="${jumlahProduksi}" min="0" step="0.01" required>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <input type="text" class="w-full focus:ring-primary-500 focus:border-primary-500 shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" name="detail[${index}][keterangan]" value="${jumlahProduksi != 0 ? 'Stok tidak mencukupi' : 'Stok mencukupi'}">
                                </td>
                            </tr>
                            `;
                            });

                            document.getElementById('items-container').innerHTML = html;
                        } else {
                            console.warn('No items returned from API or empty array received');
                            document.getElementById('items-container').innerHTML =
                                '<tr><td colspan="6" class="px-4 py-4 whitespace-nowrap text-sm text-yellow-500 dark:text-yellow-400 text-center">Tidak ada item yang ditemukan untuk Sales Order ini</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('items-container').innerHTML =
                            `<tr><td colspan="6" class="px-4 py-4 whitespace-nowrap text-sm text-red-500 dark:text-red-400 text-center">
                                Error loading items: ${error.message}. Please check the browser console for details.
                            </td></tr>`;
                    });
            }

            // Validasi form sebelum submit
            document.getElementById('form-perencanaan').addEventListener('submit', function(e) {
                console.log('Form submitted');
                const soId = document.getElementById('sales_order_id').value;
                const gudangId = document.getElementById('gudang_id').value;

                if (!soId) {
                    e.preventDefault();
                    alert('Pilih Sales Order terlebih dahulu!');
                    console.log('Sales Order not selected');
                    return false;
                }

                if (!gudangId) {
                    e.preventDefault();
                    alert('Pilih Gudang terlebih dahulu!');
                    console.log('Gudang not selected');
                    return false;
                }

                // Cek apakah masih ada empty row yang menandakan tidak ada item
                const emptyRow = document.getElementById('empty-row');
                if (emptyRow) {
                    e.preventDefault();
                    alert('Tidak ada item yang dapat diproses!');
                    console.log('No items to process');
                    return false;
                }

                return true;
            });

            // Inisialisasi - cek apakah keduanya sudah terpilih saat halaman dimuat
            const initialSoId = document.getElementById('sales_order_id').value;
            const initialGudangId = document.getElementById('gudang_id').value;
            if (initialSoId && initialGudangId) {
                console.log('Initial load with values');
                loadSalesOrderItems(initialSoId, initialGudangId);
            }
        });
    </script>
</x-app-layout>
