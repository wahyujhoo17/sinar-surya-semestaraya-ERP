<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan', 'url' => route('penjualan.delivery-order.index')],
    ['label' => 'Delivery Order', 'url' => route('penjualan.delivery-order.index')],
    ['label' => 'Edit'],
]" :currentPage="'Edit Delivery Order'">
    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Minimal Select2 customization to match Tailwind */
            .select2-container {
                width: 100% !important;
            }

            .select2-container--default .select2-selection--single {
                height: 42px;
                /* Match create form */
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                /* Match create form */
                display: flex;
                align-items: center;
            }

            .select2-container--default .select2-selection--single:focus,
            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                /* Match create form */
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px;
                /* Adjusted to align with new height */
                display: flex;
                align-items: center;
            }

            .select2-dropdown {
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                z-index: 9999;
                /* Match create form */
            }

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

            .dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #9CA3AF;
            }

            /* Form and input styling */
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }

            /* Loading animation from create.blade.php */
            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.5;
                }
            }

            .animate-pulse-custom {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            /* Enhanced modern UI elements from create.blade.php */
            .form-card {
                transition: all 0.3s ease;
            }

            .form-card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            .item-row {
                transition: all 0.2s ease;
                border-radius: 8px;
            }

            .item-row:hover {
                background-color: rgba(99, 102, 241, 0.05);
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
                transform: translateY(-1px);
            }

            .stock-badge {
                transition: all 0.3s ease;
            }

            .product-row {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                display: table-row !important;
                visibility: visible !important;
                opacity: 1;
                position: static !important;
            }

            #produkContainer tr.product-row {
                display: table-row !important;
                visibility: visible !important;
                position: static !important;
            }

            tr.product-row td {
                position: static !important;
            }

            .product-row.opacity-70 {
                opacity: 0.7;
            }

            .product-row:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            /* Stock warning styles */
            .insufficient-stock-warning {
                background-color: #fef3c7;
                /* yellow-100 */
            }

            .dark .insufficient-stock-warning {
                background-color: #78350f;
                /* amber-800 */
            }

            .critical-stock-error {
                background-color: #fee2e2;
                /* red-100 */
            }

            .dark .critical-stock-error {
                background-color: #7f1d1d;
                /* red-900 */
            }

            .input-error {
                border-color: #ef4444 !important;
                /* Tailwind class for red border */
            }
        </style>
    @endpush

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-truck text-indigo-600 dark:text-indigo-400 mr-3"></i>
                        Edit Delivery Order
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Perbarui detail delivery order yang sudah
                        ada.</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('penjualan.delivery-order.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('penjualan.delivery-order.update', $deliveryOrder->id) }}" method="POST"
            id="formDeliveryOrder">
            @csrf
            @method('PUT')

            <!-- Form Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Main Information -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 form-card">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-indigo-600 dark:text-indigo-400 mr-3"></i>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Delivery
                                        Order</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Data utama delivery order</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nomor"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nomor Delivery Order <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nomor" name="nomor"
                                        value="{{ old('nomor', $deliveryOrder->nomor) }}" required
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('nomor') border-red-500 @enderror">
                                    @error('nomor')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tanggal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="tanggal" name="tanggal"
                                        value="{{ old('tanggal', $deliveryOrder->tanggal) }}" required
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('tanggal') border-red-500 @enderror">
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="sales_order_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Sales Order <span class="text-red-500">*</span>
                                    </label>
                                    <select id="sales_order_id" name="sales_order_id" required
                                        class="select2 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('sales_order_id') border-red-500 @enderror">
                                        <option value="">Pilih Sales Order</option>
                                        @foreach ($salesOrders as $so)
                                            <option value="{{ $so->id }}"
                                                {{ old('sales_order_id', $deliveryOrder->sales_order_id) == $so->id ? 'selected' : '' }}>
                                                {{ $so->nomor }} -
                                                {{ $so->customer->company ?? ($so->customer->nama ?? 'Customer tidak ditemukan') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sales_order_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="gudang_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Gudang Asal <span class="text-red-500">*</span>
                                    </label>
                                    <select id="gudang_id" name="gudang_id" required
                                        class="select2 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('gudang_id') border-red-500 @enderror">
                                        <option value="">Pilih Gudang</option>
                                        @foreach ($gudangs as $gudang)
                                            <option value="{{ $gudang->id }}"
                                                {{ old('gudang_id', $deliveryOrder->gudang_id) == $gudang->id ? 'selected' : '' }}>
                                                {{ $gudang->nama }} - {{ $gudang->alamat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gudang_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div>
                    <div
                        class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 form-card">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-user text-green-600 dark:text-green-400 mr-3"></i>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Customer
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Data penerima</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <div>
                                <label for="customer_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Customer <span class="text-red-500">*</span>
                                </label>
                                <select id="customer_id" name="customer_id" required
                                    class="select2 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('customer_id') border-red-500 @enderror">
                                    <option value="">Pilih Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ old('customer_id', $deliveryOrder->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->nama }} - {{ $customer->company ?? 'Personal' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="alamat_pengiriman"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Alamat Pengiriman <span class="text-red-500">*</span>
                                </label>
                                <textarea id="alamat_pengiriman" name="alamat_pengiriman" rows="4" required
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm resize-vertical @error('alamat_pengiriman') border-red-500 @enderror">{{ old('alamat_pengiriman', $deliveryOrder->alamat_pengiriman) }}</textarea>
                                @error('alamat_pengiriman')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="keterangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Catatan
                                </label>
                                <textarea id="keterangan" name="keterangan" rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm resize-vertical @error('keterangan') border-red-500 @enderror"
                                    placeholder="Catatan tambahan...">{{ old('keterangan', $deliveryOrder->keterangan) }}</textarea>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Table -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-boxes text-blue-600 dark:text-blue-400 mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Detail Produk</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Daftar produk yang akan dikirim</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-64">
                                        Produk</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                        Satuan</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                        Stok</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                        Qty SO</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                        Terkirim</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                        Sisa</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28">
                                        Dikirim</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-64">
                                        Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="produkContainer"
                                class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($deliveryOrder->deliveryOrderDetail as $index => $detail)
                                    <tr class="product-row item-row" data-product-id="{{ $detail->produk_id }}"
                                        data-detail-id="{{ $detail->id }}"
                                        data-qty-so="{{ $detail->salesOrderDetail ? $detail->salesOrderDetail->quantity : 0 }}"
                                        data-terkirim-so="{{ $detail->salesOrderDetail ? $detail->salesOrderDetail->quantity_terkirim : 0 }}"
                                        data-sisa-so="{{ $detail->salesOrderDetail ? $detail->salesOrderDetail->quantity - $detail->salesOrderDetail->quantity_terkirim : 0 }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span
                                                    class="text-sm font-medium text-gray-900 dark:text-white">{{ $detail->produk->nama ?? 'N/A' }}</span>
                                                <span
                                                    class="text-xs text-gray-500 dark:text-gray-400 ml-2">({{ $detail->produk->kode ?? '-' }})</span>
                                            </div>
                                            <input type="hidden"
                                                name="items[{{ $index }}][delivery_order_detail_id]"
                                                value="{{ $detail->id }}">
                                            <input type="hidden" name="items[{{ $index }}][produk_id]"
                                                value="{{ $detail->produk_id }}">
                                            <input type="hidden"
                                                name="items[{{ $index }}][sales_order_detail_id]"
                                                value="{{ $detail->sales_order_detail_id }}">
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-center">
                                            <span
                                                class="text-sm text-gray-900 dark:text-white">{{ $detail->satuan->nama ?? 'N/A' }}</span>
                                            <input type="hidden" name="items[{{ $index }}][satuan_id]"
                                                value="{{ $detail->satuan_id }}">
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-center stock-cell">
                                            <span
                                                class="text-sm text-gray-500 dark:text-gray-400 stock-text animate-pulse-custom">Memuat...</span>
                                        </td>
                                        <td
                                            class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400 qty-so-cell">
                                            {{ $detail->salesOrderDetail ? number_format($detail->salesOrderDetail->quantity, 2, ',', '.') : 'N/A' }}
                                        </td>
                                        <td
                                            class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400 terkirim-so-cell">
                                            {{ $detail->salesOrderDetail ? number_format($detail->salesOrderDetail->quantity_terkirim, 2, ',', '.') : 'N/A' }}
                                        </td>
                                        <td
                                            class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400 sisa-so-cell">
                                            @if ($detail->salesOrderDetail)
                                                {{ number_format($detail->salesOrderDetail->quantity - $detail->salesOrderDetail->quantity_terkirim, 2, ',', '.') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 whitespace-nowrap text-center">
                                            <input type="number" name="items[{{ $index }}][quantity]"
                                                value="{{ old('items.' . $index . '.quantity', number_format($detail->quantity, 2, '.', '')) }}"
                                                class="w-24 text-center form-input text-sm dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-600 rounded-md shadow-sm quantity-input"
                                                min="0" step="any" required
                                                data-original-qty="{{ number_format($detail->quantity, 2, '.', '') }}">
                                            <p
                                                class="text-xs text-red-500 dark:text-red-400 mt-1 error-message hidden">
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" name="items[{{ $index }}][keterangan]"
                                                value="{{ old('items.' . $index . '.keterangan', $detail->keterangan) }}"
                                                class="w-full form-input text-sm dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-600 rounded-md shadow-sm"
                                                placeholder="Keterangan item">
                                        </td>
                                    </tr>
                                @endforeach
                                <tr id="emptyRow"
                                    style="display: {{ count($deliveryOrder->deliveryOrderDetail) > 0 ? 'none' : 'table-row' }};">
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-box-open fa-3x text-gray-400 dark:text-gray-500 mb-4"></i>
                                            <p class="text-gray-500 dark:text-gray-400 text-lg">
                                                Tidak ada item produk dalam delivery order ini.
                                            </p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500">
                                                Item akan muncul di sini setelah Sales Order dipilih dan produk
                                                ditambahkan.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mb-8 mr-8">
                <a href="{{ route('penjualan.delivery-order.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-lg shadow-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" id="submitBtn"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('.select2').select2({
                    theme: 'default', // Changed from classic to default to match create.blade.php
                    width: '100%',
                    placeholder: 'Pilih...',
                    // allowClear: true, // allowClear is default with placeholder
                });

                let productStockInfo = {};
                let salesOrderProducts = []; // To store products from the selected SO

                // Function to update stock display and input validation (adapted from create.blade.php)
                function updateStockDisplay(row, stockQty) {
                    const productId = $(row).data('product-id');
                    const sisaSO = parseFloat($(row).data('sisa-so')) || 0;
                    const stockCell = $(row).find('.stock-cell');
                    const inputQty = $(row).find('input[name^="items"][name$="[quantity]"]'); // Adjusted selector
                    const currentQty = parseFloat(inputQty.val()) || 0;

                    let stockNum = Number(stockQty);
                    if (isNaN(stockNum)) {
                        stockCell.html(
                            '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200"><i class="fas fa-exclamation-triangle mr-1"></i> Tidak tersedia</span>'
                        );
                        stockNum = 0;
                    } else {
                        let badgeClass, icon, text;
                        if (stockNum <= 0) {
                            badgeClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                            icon = 'fas fa-times-circle';
                            text = 'Stok kosong';
                        } else if (stockNum < sisaSO * 0.5 && sisaSO >
                            0) { // Check sisaSO > 0 to avoid issues if sisaSO is 0
                            badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                            icon = 'fas fa-exclamation-triangle';
                            text = `${stockNum.toFixed(2)}`;
                        } else if (stockNum < sisaSO && sisaSO > 0) {
                            badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                            icon = 'fas fa-check-circle';
                            text = `${stockNum.toFixed(2)}`;
                        } else {
                            badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                            icon = 'fas fa-check-circle';
                            text = `${stockNum.toFixed(2)}`;
                        }
                        stockCell.html(
                            `<span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-xs font-medium ${badgeClass} stock-badge"><i class="${icon} mr-1"></i> ${text}</span>`
                        );
                    }

                    productStockInfo[productId] = stockNum; // Store numeric stock

                    // Reset styling
                    $(row).removeClass(
                        'bg-yellow-50 dark:bg-yellow-900 bg-red-50 dark:bg-red-900 border-l-4 border-yellow-400 dark:border-yellow-600 border-red-400 dark:border-red-600'
                        );
                    inputQty.removeClass('border-red-500 dark:border-red-500');
                    inputQty.closest('td').find('.text-xs.text-red-500').remove(); // Remove previous error message

                    // Validation logic
                    if (currentQty > 0) {
                        if (stockNum <= 0) {
                            $(row).addClass('bg-red-50 dark:bg-red-900 border-l-4 border-red-400 dark:border-red-600');
                            inputQty.addClass('border-red-500 dark:border-red-500');
                            inputQty.closest('td').append(
                                '<p class="text-xs text-red-500 dark:text-red-400 mt-1">Stok kosong.</p>');
                        } else if (currentQty > stockNum) {
                            $(row).addClass(
                                'bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600'
                                );
                            inputQty.addClass('border-red-500 dark:border-red-500');
                            inputQty.closest('td').append(
                                '<p class="text-xs text-red-500 dark:text-red-400 mt-1">Melebihi stok.</p>');
                        }
                        if (currentQty > sisaSO) {
                            // Add warning, but not error for exceeding SO Sisa, as it might be intentional
                            if (!$(row).hasClass('bg-red-50')) { // Don't override critical stock error styling
                                $(row).addClass(
                                    'bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600'
                                    );
                            }
                            // Add a specific message if not already showing a stock error
                            if (!inputQty.closest('td').find('.text-xs.text-red-500').length) {
                                inputQty.closest('td').append(
                                    '<p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">Qty > Sisa SO.</p>'
                                    );
                            }
                        }
                    }
                    inputQty.attr('max', Math.min(stockNum, sisaSO > 0 ? sisaSO : stockNum));
                }

                // Function to fetch stock info (adapted from create.blade.php)
                function getStockInfo() {
                    const gudangId = $('#gudang_id').val();
                    if (!gudangId) {
                        $('.product-row').each(function() {
                            $(this).find('.stock-cell').html(
                                '<span class="text-xs text-gray-500 dark:text-gray-400">Pilih Gudang</span>'
                                );
                            $(this).find('input[name^="items"][name$="[quantity]"]').prop('disabled', true);
                        });
                        return;
                    }

                    let productIds = [];
                    $('.product-row').each(function() {
                        const productId = $(this).data('product-id');
                        if (productId) {
                            productIds.push(productId);
                        }
                    });

                    if (productIds.length === 0) {
                        return;
                    }

                    $('.product-row').each(function() {
                        $(this).find('.stock-cell').html(
                            '<span class="animate-pulse-custom text-xs text-gray-500 dark:text-gray-400">Memuat stok...</span>'
                            );
                        $(this).find('input[name^="items"][name$="[quantity]"]').prop('disabled',
                        false); // Enable while loading
                    });

                    $.ajax({
                        url: "{{ route('penjualan.delivery-order.get-stock-info') }}",
                        type: 'GET',
                        data: {
                            gudang_id: gudangId,
                            product_ids: productIds
                        },
                        success: function(response) {
                            if (response.success && response.stocks) {
                                productStockInfo = response.stocks;
                                $('.product-row').each(function() {
                                    const productId = $(this).data('product-id');
                                    const stock = productStockInfo[productId] !== undefined ?
                                        productStockInfo[productId] : 0;
                                    updateStockDisplay(this, stock);
                                });
                            } else {
                                console.error("Failed to parse stock info or success false:", response);
                                $('.product-row').each(function() {
                                    updateStockDisplay(this, 0); // Treat as 0 stock on failure
                                    $(this).find('.stock-cell').html(
                                        '<span class="text-xs text-red-500 dark:text-red-400">Error stok</span>'
                                        );
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching stock info:", error);
                            alert("Gagal mengambil informasi stok. Silakan coba lagi.");
                            $('.product-row').each(function() {
                                updateStockDisplay(this, 0);
                                $(this).find('.stock-cell').html(
                                    '<span class="text-xs text-red-500 dark:text-red-400">Error stok</span>'
                                    );
                            });
                        }
                    });
                }

                // Event listener for quantity input changes
                $(document).on('input', 'input[name^="items"][name$="[quantity]"]', function() {
                    const row = $(this).closest('.product-row');
                    const productId = row.data('product-id');
                    const stockQty = productStockInfo[productId] || 0;
                    updateStockDisplay(row, stockQty); // Re-run display logic which includes validation
                });

                // Gudang change event
                $('#gudang_id').change(function() {
                    getStockInfo();
                });

                // Sales Order change event - Load SO details (adapted from create.blade.php)
                // This is simplified for edit as products are already loaded.
                // We mainly need to update customer and address if SO changes, though this is less common in edit.
                // For edit, the primary function is to re-fetch stock if SO implies different product constraints (not typical).
                $('#sales_order_id').on('change', function() {
                    const salesOrderId = $(this).val();
                    if (salesOrderId) {
                        // Fetch SO data to update customer and alamat pengiriman if necessary
                        $.ajax({
                            url: `{{ url('penjualan/delivery-order/get-sales-order-data') }}/${salesOrderId}`,
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response.customer) {
                                    // Update customer if it's different - might need to re-init select2 if it's a select
                                    // For now, assuming customer_id is a hidden or non-editable field post-load in edit
                                    // Or, if it's a select, update and trigger change
                                    if ($('#customer_id').is('select')) {
                                        $('#customer_id').val(response.customer.id).trigger(
                                            'change.select2');
                                    } else {
                                        // If it's just a display or hidden input, update its value
                                    }
                                }
                                if (response.salesOrder && response.salesOrder.alamat_pengiriman) {
                                    $('#alamat_pengiriman').val(response.salesOrder
                                        .alamat_pengiriman);
                                } else if (response.customer && response.customer
                                    .alamat_pengiriman) {
                                    $('#alamat_pengiriman').val(response.customer
                                    .alamat_pengiriman);
                                }
                                // Potentially re-fetch products or update existing ones if SO change means different items
                                // For edit, this is complex. Usually, DO items are fixed. If SO change *can* alter items,
                                // then a more complex product table refresh is needed here.
                                // For now, assume items are based on initial $deliveryOrder and we just update stock.
                                getStockInfo
                            (); // Refresh stock based on potentially new SO context (if gudang is also set)
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching sales order data for edit page:",
                                    error);
                            }
                        });
                    } else {
                        // Clear customer, address, and product table if SO is deselected
                        // This might be too destructive for an edit page. Consider implications.
                        // $('#customer_id').val('').trigger('change.select2');
                        // $('#alamat_pengiriman').val('');
                        // $('#produkContainer').empty().append($('#emptyRow').clone().show());
                    }
                });

                // Form validation on submit (adapted from create.blade.php)
                $('#formDeliveryOrder').submit(function(e) {
                    let isValid = true;
                    let errorMessages = [];
                    // Clear previous global errors
                    $('#form-errors').remove();

                    const gudangId = $('#gudang_id').val();
                    if (!gudangId) {
                        isValid = false;
                        errorMessages.push("Gudang asal harus dipilih.");
                        $('#gudang_id').next('.select2-container').find('.select2-selection').addClass(
                            'border-red-500 dark:border-red-500');
                    }

                    if ($('.product-row[data-product-id]').length === 0) {
                        isValid = false;
                        errorMessages.push("Minimal ada satu produk untuk dikirim.");
                    }

                    $('input[name^="items"][name$="[quantity]"]').each(function() {
                        const row = $(this).closest('.product-row');
                        const productName = row.find('.text-sm.font-medium').text()
                    .trim(); // Get product name
                        const qty = parseFloat($(this).val()) || 0;
                        const productId = row.data('product-id');
                        const stockQty = productStockInfo[productId] || 0;
                        const sisaSO = parseFloat(row.data('sisa-so')) || 0;

                        $(this).removeClass('border-red-500 dark:border-red-500');
                        row.find('.text-xs.text-red-500.mt-1').remove(); // Clear previous item errors

                        if (qty <= 0) {
                            isValid = false;
                            errorMessages.push(`Kuantitas untuk ${productName} harus lebih dari 0.`);
                            $(this).addClass('border-red-500 dark:border-red-500');
                            $(this).closest('td').append(
                                '<p class="text-xs text-red-500 dark:text-red-400 mt-1">Qty > 0</p>'
                                );
                        } else {
                            if (stockQty <= 0) {
                                isValid = false;
                                errorMessages.push(
                                    `Stok untuk ${productName} kosong, tidak bisa dikirim.`);
                                $(this).addClass('border-red-500 dark:border-red-500');
                                $(this).closest('td').append(
                                    '<p class="text-xs text-red-500 dark:text-red-400 mt-1">Stok kosong</p>'
                                    );
                            } else if (qty > stockQty) {
                                isValid = false;
                                errorMessages.push(
                                    `Kuantitas ${productName} (${qty}) melebihi stok (${stockQty}).`
                                    );
                                $(this).addClass('border-red-500 dark:border-red-500');
                                $(this).closest('td').append(
                                    '<p class="text-xs text-red-500 dark:text-red-400 mt-1">Melebihi stok</p>'
                                    );
                            }
                            // Warning for exceeding Sisa SO, but not a validation blocker unless specified
                            if (qty > sisaSO) {
                                // errorMessages.push(`Kuantitas ${productName} (${qty}) melebihi sisa SO (${sisaSO}).`);
                                // $(this).addClass('border-yellow-500'); // Or some other visual cue
                            }
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        // Display a summary of errors
                        let alertMessage = 'Terdapat kesalahan pada input Anda:\n' + errorMessages.join('\n');
                        // Optionally, add a global error display area at the top of the form
                        $(this).prepend(
                            '<div id="form-errors" class="mb-4 p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 border border-red-400 dark:border-red-600 rounded-md"><ul>' +
                            errorMessages.map(err => `<li>${err}</li>`).join('') + '</ul></div>');
                        alert(alertMessage); // Simple alert, consider a more integrated UI notification
                    } else {
                        $('#submitBtn').prop('disabled', true).html(
                            '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...');
                    }
                });

                // Initial actions on page load for edit page
                function initializeEditPage() {
                    // Ensure all product rows are visible by default
                    $('#produkContainer .product-row').show();
                    $('#emptyRow').hide();

                    if ($('#gudang_id').val()) {
                        getStockInfo(); // Fetch stock for initially selected gudang
                    } else {
                        // If no gudang selected, disable quantity inputs and show "Pilih Gudang"
                        $('.product-row').each(function() {
                            $(this).find('.stock-cell').html(
                                '<span class="text-xs text-gray-500 dark:text-gray-400">Pilih Gudang</span>'
                                );
                            $(this).find('input[name^="items"][name$="[quantity]"]').prop('disabled', true);
                        });
                    }

                    // Trigger initial validation display for existing quantities
                    $('input[name^="items"][name$="[quantity]"]').each(function() {
                        const row = $(this).closest('.product-row');
                        const productId = row.data('product-id');
                        // Stock info might not be loaded yet, so this call might be premature
                        // or rely on stock info being embedded or fetched quickly.
                        // For now, getStockInfo() should handle the initial display after it fetches.
                    });
                    // If a sales order is pre-selected, trigger change to load its data (customer, address)
                    if ($('#sales_order_id').val()) {
                        $('#sales_order_id').trigger('change');
                    }
                }

                initializeEditPage();

            });
        </script>
    @endpush
</x-app-layout>
