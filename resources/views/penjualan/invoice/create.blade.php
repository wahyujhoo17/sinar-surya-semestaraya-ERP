<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Invoice', 'url' => route('penjualan.invoice.index')],
    ['label' => 'Tambah'],
]" :currentPage="'Tambah Invoice'">
    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Custom Select2 styling */
            .select2-container {
                width: 100% !important;
            }

            .select2-container--default .select2-selection--single {
                height: 38px;
                padding: 4px 2px;
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                display: flex;
                align-items: center;
            }

            .select2-container--default .select2-selection--single:focus,
            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 38px;
                display: flex;
                align-items: center;
            }

            .select2-dropdown {
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                overflow: hidden;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #6366f1;
            }

            .select2-container--default .select2-search--dropdown .select2-search__field {
                border-color: #D1D5DB;
                border-radius: 0.25rem;
                padding: 0.4rem 0.75rem;
            }

            .select2-container--default .select2-search--dropdown .select2-search__field:focus {
                border-color: #6366f1;
                outline: none;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            }

            /* Dark mode styling */
            .dark .select2-container--default .select2-selection--single {
                background-color: #374151;
                border-color: #4B5563;
                color: #F9FAFB;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #F9FAFB;
            }

            .dark .select2-dropdown {
                background-color: #1F2937;
                border-color: #4B5563;
            }

            .dark .select2-container--default .select2-results__option {
                color: #F9FAFB;
            }

            .dark .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: #374151;
                border-color: #4B5563;
                color: #F9FAFB;
            }

            .dark .select2-container--default .select2-results__option[aria-selected=true] {
                background-color: #374151;
            }

            .dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #9CA3AF;
            }

            /* Base styles */
            .form-card {
                transition: all 0.3s ease;
                border-radius: 12px;
            }

            .form-card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            .input-group {
                position: relative;
                margin-bottom: 1rem;
            }

            .input-group label {
                font-weight: 500;
                margin-bottom: 0.5rem;
                display: inline-block;
            }

            .input-group input,
            .input-group select,
            .input-group textarea {
                transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            }

            .input-group input:focus,
            .input-group select:focus,
            .input-group textarea:focus {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            }

            .item-row {
                transition: all 0.2s ease;
                border-radius: 8px;
            }

            .item-row:hover {
                background-color: rgba(99, 102, 241, 0.05);
            }

            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }

            .btn {
                transition: all 0.2s ease;
                font-weight: 500;
            }

            .btn-primary {
                background-color: #6366f1;
                color: white;
            }

            .btn-primary:hover {
                background-color: #4F46E5;
            }

            .btn-secondary {
                background-color: #9CA3AF;
                color: white;
            }

            .btn-secondary:hover {
                background-color: #6B7280;
            }

            .btn-danger {
                background-color: #EF4444;
                color: white;
            }

            .btn-danger:hover {
                background-color: #DC2626;
            }

            .shadow-sm-hover:hover {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .summary-card {
                background: linear-gradient(to bottom right, #f9fafb, #f3f4f6);
                border-radius: 12px;
            }

            .dark .summary-card {
                background: linear-gradient(to bottom right, #1f2937, #111827);
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form id="invoice-form" action="{{ route('penjualan.invoice.store') }}" method="POST"
                        x-data="invoiceForm()" x-init="init()">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Bagian kiri -->
                            <div>
                                <div class="space-y-4">
                                    <!-- Nomor Invoice -->
                                    <div>
                                        <label for="nomor"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Nomor Invoice <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="nomor" id="nomor" value="{{ $nomor }}"
                                            readonly
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm">
                                    </div>

                                    <!-- Tanggal Invoice -->
                                    <div>
                                        <label for="tanggal"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Tanggal Invoice <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}"
                                            required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm">
                                    </div>

                                    <!-- Sales Order -->
                                    <div>
                                        <label for="sales_order_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Sales Order <span class="text-red-500">*</span>
                                        </label>
                                        <select name="sales_order_id" id="sales_order_id" required
                                            class="select2 mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm">
                                            <option value="">Pilih Sales Order</option>
                                            @foreach ($salesOrders as $salesOrder)
                                                <option value="{{ $salesOrder->id }}">{{ $salesOrder->nomor }} -
                                                    {{ $salesOrder->customer->company ?? $salesOrder->customer->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Customer (auto-filled) -->
                                    <div>
                                        <label for="customer_name"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Customer
                                        </label>
                                        <input type="text" id="customer_name" x-model="customerName" readonly
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm bg-gray-50 dark:bg-gray-800">
                                        <input type="hidden" name="customer_id" x-model="customerId">
                                    </div>

                                    <!-- Tanggal Jatuh Tempo -->
                                    <div>
                                        <label for="jatuh_tempo"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" name="jatuh_tempo" id="jatuh_tempo"
                                            :value="calculateDueDate()" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm">
                                        <!-- Payment Terms Info -->
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                                            x-show="termsPembayaran">
                                            <span class="font-medium text-primary-600 dark:text-primary-400">Terms
                                                Pembayaran:</span>
                                            <span x-text="termsPembayaran"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian kanan -->
                            <div>
                                <div class="space-y-4">
                                    <!-- Status -->
                                    <div>
                                        <label for="status"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Status Pembayaran <span class="text-red-500">*</span>
                                        </label>
                                        <select name="status" id="status" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm">
                                            <option value="belum_bayar">Belum Bayar</option>
                                            <option value="sebagian">Sebagian</option>
                                            <option value="lunas">Lunas</option>
                                        </select>
                                    </div>

                                    <!-- Catatan -->
                                    <div>
                                        <label for="catatan"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Catatan
                                        </label>
                                        <textarea name="catatan" id="catatan" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm"></textarea>
                                    </div>

                                    <!-- Syarat & Ketentuan -->
                                    <div>
                                        <label for="syarat_ketentuan"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Syarat & Ketentuan
                                        </label>
                                        <textarea name="syarat_ketentuan" id="syarat_ketentuan" rows="4"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm">1. Pembayaran dilakukan dengan transfer ke rekening perusahaan
2. Barang yang sudah dibeli tidak dapat dikembalikan
3. Harap melampirkan bukti transfer setelah melakukan pembayaran</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Barang Table -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Detail Barang
                            </h3>

                            <div
                                class="relative overflow-x-auto sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-5">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 w-5/12">Produk</th>
                                            <th scope="col" class="px-4 py-3 w-1/12 text-center">Satuan</th>
                                            <th scope="col" class="px-4 py-3 w-1/12 text-center">Qty</th>
                                            <th scope="col" class="px-4 py-3 w-2/12 text-right">Harga</th>
                                            <th scope="col" class="px-4 py-3 w-1/12 text-center">Diskon (%)</th>
                                            <th scope="col" class="px-4 py-3 w-2/12 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                <td class="px-4 py-3">
                                                    <span x-text="item.nama_produk"></span>
                                                    <input type="hidden" :name="'items[' + index + '][produk_id]'"
                                                        x-model="item.produk_id">
                                                    <input type="hidden" :name="'items[' + index + '][nama_produk]'"
                                                        x-model="item.nama_produk">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span x-text="item.satuan"></span>
                                                    <input type="hidden" :name="'items[' + index + '][satuan]'"
                                                        x-model="item.satuan">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span x-text="item.qty"></span>
                                                    <input type="hidden" :name="'items[' + index + '][qty]'"
                                                        x-model="item.qty">
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <span x-text="formatRupiah(item.harga)"></span>
                                                    <input type="hidden" :name="'items[' + index + '][harga]'"
                                                        x-model="item.harga">
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <span x-text="item.diskon + '%'"></span>
                                                    <input type="hidden" :name="'items[' + index + '][diskon]'"
                                                        x-model="item.diskon">
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <span x-text="formatRupiah(item.subtotal)"></span>
                                                    <input type="hidden" :name="'items[' + index + '][subtotal]'"
                                                        x-model="item.subtotal">
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="items.length === 0">
                                            <td colspan="6"
                                                class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                                                Pilih Sales Order untuk menampilkan item
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Total & Summary -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9h18M3 15h18" />
                                </svg>
                                Ringkasan Biaya
                            </h3>

                            <div class="flex flex-col md:flex-row md:justify-between gap-6">
                                <div class="md:w-1/2 flex-shrink-0">
                                    <!-- QR or Barcode could go here in the future -->
                                    <div
                                        class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-gray-800 dark:to-gray-900 p-4 rounded-lg border border-primary-200 dark:border-gray-700 shadow-sm">
                                        <div class="flex items-center mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5 text-primary-500 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium text-gray-800 dark:text-gray-200">Informasi
                                                Pembayaran</span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">Pembayaran dapat
                                            dilakukan melalui transfer bank ke rekening berikut:</p>
                                        <div
                                            class="bg-white dark:bg-gray-800 rounded p-3 mb-3 border border-gray-200 dark:border-gray-700">
                                            <div class="flex justify-between items-center mb-1">
                                                <span
                                                    class="text-xs font-medium text-gray-500 dark:text-gray-400">Bank:</span>
                                                <span class="text-xs font-medium text-gray-800 dark:text-gray-200">Bank
                                                    Central Asia (BCA)</span>
                                            </div>
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">No.
                                                    Rekening:</span>
                                                <span
                                                    class="text-xs font-medium text-gray-800 dark:text-gray-200">123-456-7890</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Atas
                                                    Nama:</span>
                                                <span
                                                    class="text-xs font-medium text-gray-800 dark:text-gray-200">{{ setting('company_name', 'PT. Sinar Surya') }}</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 italic">Harap sertakan nomor
                                            invoice dalam keterangan pembayaran</p>
                                    </div>
                                </div>
                                <div class="md:w-1/2">
                                    <div
                                        class="bg-white dark:bg-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-700 shadow-md">
                                        <h4
                                            class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">
                                            Detail Biaya</h4>
                                        <div class="space-y-3">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-gray-400 mr-2" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                    <span
                                                        class="text-sm text-gray-600 dark:text-gray-300">Subtotal</span>
                                                </div>
                                                <span class="text-sm font-medium text-gray-800 dark:text-gray-200"
                                                    x-text="formatRupiah(subtotal)"></span>
                                                <input type="hidden" name="subtotal" x-model="subtotal">
                                            </div>

                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-red-400 mr-2" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span
                                                        class="text-sm text-gray-600 dark:text-gray-300">Diskon</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <div class="relative w-20">
                                                        <input type="number" name="diskon_persen" min="0"
                                                            max="100" step="0.01" x-model="diskonPersen"
                                                            @input="calculateTotal"
                                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm 
                                                            focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm text-right pr-7">
                                                        <div
                                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                            <span
                                                                class="text-gray-500 dark:text-gray-400 text-xs">%</span>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm font-medium text-red-500 dark:text-red-400"
                                                        x-text="'- ' + formatRupiah(diskonNominal)"></span>
                                                    <input type="hidden" name="diskon_nominal"
                                                        x-model="diskonNominal">
                                                </div>
                                            </div>

                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-blue-400 mr-2" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">PPN</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <div class="relative w-20">
                                                        <input type="number" name="ppn_persen" min="0"
                                                            max="100" step="0.01" x-model="ppnPersen"
                                                            @input="calculateTotal"
                                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm 
                                                            focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm text-right pr-7">
                                                        <div
                                                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                            <span
                                                                class="text-gray-500 dark:text-gray-400 text-xs">%</span>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm font-medium text-blue-500 dark:text-blue-400"
                                                        x-text="'+ ' + formatRupiah(ppnNominal)"></span>
                                                    <input type="hidden" name="ppn" x-model="ppnNominal">
                                                </div>
                                            </div>

                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-green-400 mr-2" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                    </svg>
                                                    <span class="text-sm text-gray-600 dark:text-gray-300">Ongkos
                                                        Kirim</span>
                                                </div>
                                                <div class="relative w-36">
                                                    <div
                                                        class="absolute inset-y-0 left-0 flex items-center pl-2 pointer-events-none">
                                                        <span
                                                            class="text-gray-500 dark:text-gray-400 text-xs">Rp</span>
                                                    </div>
                                                    <input type="number" name="ongkos_kirim" min="0"
                                                        step="1000" x-model="ongkosKirim" @input="calculateTotal"
                                                        class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm 
                                                        focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm text-right pl-10">
                                                </div>
                                            </div>

                                            <div class="mt-4 pt-4 border-t-2 border-gray-200 dark:border-gray-700">
                                                <div class="flex justify-between items-center">
                                                    <span
                                                        class="text-base font-semibold text-gray-800 dark:text-white">Total</span>
                                                    <span
                                                        class="text-xl font-bold text-primary-600 dark:text-primary-400"
                                                        x-text="formatRupiah(total)"></span>
                                                    <input type="hidden" name="total" x-model="total">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('penjualan.invoice.index') }}"
                                class="inline-flex justify-center items-center py-2.5 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center items-center py-2.5 px-5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function invoiceForm() {
                return {
                    customerId: '',
                    customerName: '',
                    items: [],
                    subtotal: 0,
                    diskonPersen: 0,
                    diskonNominal: 0,
                    ppnPersen: {{ setting('tax_percentage', 11) }}, // Default PPN from settings
                    ppnNominal: 0,
                    ongkosKirim: 0,
                    total: 0,
                    termsPembayaran: '', // Track terms pembayaran from sales order

                    init() {
                        // Initialize select2
                        $(document).ready(() => {
                            $('.select2').select2();

                            // Bind the Select2 change event to call fetchSalesOrderData
                            $('#sales_order_id').on('change.select2', () => {
                                this.fetchSalesOrderData();
                            });

                            // Check if there's a preselected sales order (when coming from sales order detail page)
                            @if (isset($preselectedSalesOrder))
                                $('#sales_order_id').val('{{ $preselectedSalesOrder }}').trigger('change');
                            @endif
                        });
                    },

                    fetchSalesOrderData() {
                        console.log('Fetching sales order data...');
                        const salesOrderId = document.getElementById('sales_order_id').value;
                        if (!salesOrderId) {
                            this.resetForm();
                            return;
                        }

                        // Show loading
                        this.items = [];
                        this.subtotal = 0;
                        this.total = 0;

                        fetch(`/penjualan/invoice/get-sales-order/${salesOrderId}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Set customer data
                                    this.customerId = data.sales_order.customer_id;
                                    this.customerName = data.sales_order.customer ?
                                        (data.sales_order.customer.company ?
                                            ` (${data.sales_order.customer.company})` :
                                            data.sales_order.customer.nama) :
                                        'Unknown Customer';

                                    console.log('Sales Order Data:', data);

                                    // Get ongkos kirim from sales order
                                    this.ongkosKirim = data.sales_order.ongkos_kirim || 0;

                                    // Get diskon from sales order
                                    this.diskonPersen = data.sales_order.diskon_persen || 0;

                                    // Set jatuh tempo based on terms pembayaran from sales order
                                    if (data.jatuh_tempo) {
                                        document.getElementById('jatuh_tempo').value = data.jatuh_tempo;
                                        console.log('Setting due date from sales order terms:', data.jatuh_tempo);
                                    } else {
                                        document.getElementById('jatuh_tempo').value = this.calculateDueDate();
                                        console.log('No payment terms in sales order, using default 30 days');
                                    }

                                    // Save the terms_pembayaran for reference
                                    this.termsPembayaran = data.terms_pembayaran;

                                    // Set items data
                                    this.items = data.details.map(item => ({
                                        produk_id: item.produk_id,
                                        nama_produk: item.nama_produk,
                                        satuan: item.satuan,
                                        qty: item.qty,
                                        harga: item.harga,
                                        diskon: item.diskon || 0,
                                        subtotal: item.subtotal,
                                    }));

                                    // Calculate totals
                                    this.calculateSubtotal();
                                    this.calculateTotal();
                                } else {
                                    alert('Gagal mengambil data Sales Order');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat mengambil data');
                            });
                    },

                    resetForm() {
                        this.customerId = '';
                        this.customerName = '';
                        this.items = [];
                        this.subtotal = 0;
                        this.diskonPersen = 0;
                        this.diskonNominal = 0;
                        this.ppnPersen = {{ setting('tax_percentage', 11) }};
                        this.ppnNominal = 0;
                        this.ongkosKirim = 0;
                        this.total = 0;
                        this.termsPembayaran = '';
                        // Reset jatuh_tempo to default (30 days)
                        document.getElementById('jatuh_tempo').value = this.calculateDueDate();
                    },

                    calculateSubtotal() {
                        this.subtotal = this.items.reduce((sum, item) => sum + parseFloat(item.subtotal), 0);
                    },

                    calculateTotal() {
                        // Calculate diskon nominal
                        this.diskonNominal = this.subtotal * (this.diskonPersen / 100);

                        // Calculate PPN nominal (applied after discount)
                        const afterDiscount = this.subtotal - this.diskonNominal;
                        this.ppnNominal = afterDiscount * (this.ppnPersen / 100);

                        // Calculate total (including ongkos kirim)
                        this.total = afterDiscount + this.ppnNominal + parseFloat(this.ongkosKirim);
                    },

                    formatRupiah(value) {
                        return 'Rp ' + parseFloat(value).toLocaleString('id-ID');
                    },

                    calculateDueDate() {
                        // This is a fallback method that sets a default due date (30 days)
                        // Only used when no terms_pembayaran_hari is available from sales order
                        const today = new Date();
                        const dueDate = new Date(today);
                        dueDate.setDate(today.getDate() + 30);
                        return dueDate.toISOString().split('T')[0];
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
