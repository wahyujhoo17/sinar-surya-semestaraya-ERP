<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Delivery Order', 'url' => route('penjualan.delivery-order.index')],
    ['label' => 'Tambah'],
]" :currentPage="'Tambah Delivery Order'">
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
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                font-size: 0.875rem;
            }

            .select2-container--default .select2-selection--single:focus,
            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            }

            .select2-dropdown {
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                z-index: 9999;
            }

            .dark .select2-container--default .select2-selection--single {
                background-color: #374151;
                border-color: #4B5563;
                color: #F9FAFB;
            }

            .dark .select2-dropdown {
                background-color: #1F2937;
                border-color: #4B5563;
            }

            /* Hide number input arrows */
            input[type=number]::-webkit-inner-spin-button,
            input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type=number] {
                -moz-appearance: textfield;
            }

            /* Loading animation */
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

            /* Enhanced modern UI elements */
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
                /* Force display as table row */
                visibility: visible !important;
                /* Force visibility */
                opacity: 1;
                /* Default opacity */
                position: static !important;
                /* Prevent position changes */
            }

            /* Override any CSS framework rules that might hide rows */
            #productTableBody tr.product-row {
                display: table-row !important;
                visibility: visible !important;
                position: static !important;
            }

            tr.product-row td {
                position: static !important;
            }

            .product-row.opacity-70 {
                opacity: 0.7;
                /* Only reduce opacity for dimmed rows, don't hide */
            }

            .product-row:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }
        </style>
    @endpush

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">

                        Tambah Delivery Order
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Buat delivery order baru dari sales order
                        yang sudah ada</p>
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

        <form action="{{ route('penjualan.delivery-order.store') }}" method="POST" id="formDeliveryOrder">
            @csrf
            @if (isset($permintaanBarang))
                <input type="hidden" name="permintaan_barang_id" value="{{ $permintaanBarang->id }}">
            @endif

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
                                        value="{{ old('nomor', $nomor) }}" required
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
                                        value="{{ old('tanggal', date('Y-m-d')) }}" required
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
                                                {{ old('sales_order_id', $salesOrder?->id) == $so->id ? 'selected' : '' }}>
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
                                                {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
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
                                            {{ old('customer_id', $salesOrder?->customer_id) == $customer->id ? 'selected' : '' }}>
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
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm resize-vertical @error('alamat_pengiriman') border-red-500 @enderror">{{ old('alamat_pengiriman', $salesOrder?->alamat_pengiriman) }}</textarea>
                                @error('alamat_pengiriman')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Catatan
                                </label>
                                <textarea id="catatan" name="catatan" rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm resize-vertical @error('catatan') border-red-500 @enderror"
                                    placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                                @error('catatan')
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
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Produk
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                        Satuan
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-24">
                                        Stok
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                        Qty SO
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                        Terkirim
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-20">
                                        Sisa
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-28">
                                        Dikirim
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                        Keterangan
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody"
                                class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr id="emptyRow">
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center max-w-md mx-auto">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Mulai
                                                Buat Delivery Order</h3>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm text-center mb-4">
                                                Pilih Sales Order dan Gudang terlebih dahulu untuk melihat daftar produk
                                                yang dapat dikirim.
                                            </p>
                                            <div class="flex space-x-3">
                                                <button type="button" onClick="$('#sales_order_id').select2('open');"
                                                    class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900 border border-transparent rounded-md font-medium text-sm text-indigo-700 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                    <i class="fas fa-clipboard-check mr-2"></i> Pilih Sales Order
                                                </button>
                                                <button type="button" onClick="$('#gudang_id').select2('open');"
                                                    class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-transparent rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                                    <i class="fas fa-warehouse mr-2"></i> Pilih Gudang
                                                </button>
                                            </div>
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
                    Simpan Delivery Order
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <!-- jQuery (must be loaded first) -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <!-- Select2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize Select2
                $('.select2').select2({
                    theme: 'default',
                    width: '100%',
                    placeholder: 'Pilih...',
                });

                // If coming from permintaan barang, pre-fill data
                @if (isset($permintaanBarang))
                    // Set gudang if coming from permintaan barang
                    $('#gudang_id').val('{{ $permintaanBarang->gudang_id }}').trigger('change');
                @endif

                // Add event listener for after page fully loads
                $(window).on('load', function() {
                    // Force product visibility after page fully loads
                    setTimeout(ensureAllProductsVisible, 500);
                    setTimeout(debugAndForceVisibility, 1000);
                });

                // Variable untuk menyimpan data produk dari Sales Order
                let salesOrderProducts = [];
                let productStockInfo = {};

                // Function untuk update tampilan stok dan validasi
                function updateStockDisplay(row, stockQty) {
                    const productId = $(row).data('product-id');
                    const sisaSO = parseFloat($(row).data('sisa-so')) || 0;
                    const stockCell = $(row).find('.stock-cell');
                    const inputQty = $(row).find('input[name="quantity[]"]');
                    const currentQty = parseFloat(inputQty.val()) || 0;

                    // Ensure stockQty is a number
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
                        } else if (stockNum < sisaSO * 0.5) {
                            badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                            icon = 'fas fa-exclamation-triangle';
                            text = `${stockNum.toFixed(2)}`;
                        } else if (stockNum < sisaSO) {
                            badgeClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                            icon = 'fas fa-check-circle';
                            text = `${stockNum.toFixed(2)}`;
                        } else {
                            badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                            icon = 'fas fa-check-circle';
                            text = `${stockNum.toFixed(2)}`;
                        }

                        stockCell.html(`
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-xs font-medium ${badgeClass} stock-badge">
                                <i class="${icon} mr-1"></i> ${text}
                            </span>
                        `);
                    }

                    // Reset styling
                    $(row).removeClass(
                        'bg-yellow-50 dark:bg-yellow-900 bg-red-50 dark:bg-red-900 border-l-4 border-yellow-400 border-red-400'
                    );
                    inputQty.removeClass('border-red-500');

                    // Cek stok dan set batasan
                    if (stockNum <= 0) {
                        // Tidak ada stok, set nilai default ke 0
                        inputQty.prop('disabled', false);
                        $(row).addClass('bg-red-50 dark:bg-red-900 border-l-4 border-red-400');

                        // Set nilai ke 0 jika belum diubah sebelumnya
                        if (currentQty > 0) {
                            inputQty.val(0);
                        }

                        // Set max ke 0 karena stok kosong
                        inputQty.attr('max', 0);

                        // Min juga set ke 0 untuk memungkinkan tidak ada pengiriman untuk produk ini
                        inputQty.attr('min', 0);

                        // IMPORTANT: Ensure the row is still visible using multiple methods
                        $(row).css('display', 'table-row');
                        $(row).show();
                        $(row).removeClass('hidden d-none');
                        $(row).attr('style', $(row).attr('style') + '; display: table-row !important;');
                    } else {
                        // Ada stok, enable input
                        inputQty.prop('disabled', false);

                        // Jika nilai saat ini melebihi stok, batasi dan beri warning
                        if (currentQty > stockNum) {
                            inputQty.val(stockNum); // Batasi nilai ke stok maksimum
                            $(row).addClass('bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400');
                            inputQty.addClass('border-red-500');
                        }

                        // Set max ke nilai stok yang tersedia
                        inputQty.attr('max', Math.min(stockNum, sisaSO));

                        // Min set ke 0 untuk memungkinkan tidak ada pengiriman untuk produk ini
                        inputQty.attr('min', 0);
                    }

                    // IMPORTANT: Always ensure row is not hidden - Force visibility with multiple approaches
                    $(row).removeClass('hidden d-none');
                    $(row).css('display', 'table-row');
                    $(row).show();
                    $(row).attr('style', 'display: table-row !important; visibility: visible !important;');

                    // Simpan info stok
                    productStockInfo[productId] = stockNum;
                }

                // Function untuk get stok dari gudang untuk semua produk
                function getStockInfo() {
                    const gudangId = $('#gudang_id').val();
                    const productRows = document.querySelectorAll('#productTableBody tr[data-product-id]');

                    if (!gudangId || productRows.length === 0) {
                        return;
                    }

                    // Show loading state for all stock cells
                    productRows.forEach(function(row) {
                        // Force row visibility while we're updating it
                        row.style.display = 'table-row';
                        row.style.visibility = 'visible';

                        const stockCell = row.querySelector('.stock-cell');
                        if (stockCell) {
                            stockCell.innerHTML =
                                '<div class="animate-pulse-custom bg-gray-200 dark:bg-gray-600 h-6 w-16 rounded mx-auto"></div>';
                        }
                    });

                    // Collect product IDs
                    let productIds = [];
                    productRows.forEach(function(row) {
                        const productId = row.dataset.productId;
                        if (productId) {
                            productIds.push(productId);
                        }
                    });

                    if (productIds.length === 0) {
                        return;
                    }

                    // Get stock info from server
                    $.ajax({
                        url: "{{ route('penjualan.delivery-order.get-stock-info') }}",
                        type: 'GET',
                        data: {
                            gudang_id: gudangId,
                            product_ids: productIds
                        },
                        success: function(response) {
                            if (response.success && response.stocks) {
                                // Verify all expected products are in the table
                                const tableProductIds = Array.from(document.querySelectorAll(
                                        '#productTableBody tr[data-product-id]'))
                                    .map(row => row.dataset.productId);

                                // Check if any products are missing from the table
                                const salesOrderProductIds = salesOrderProducts.map(p => p.produk_id);
                                const missingProducts = salesOrderProductIds.filter(id => !tableProductIds
                                    .includes(id));

                                // If any products are missing, rebuild the table
                                if (missingProducts.length > 0) {
                                    rebuildProductTable();

                                    // Update with a slight delay to ensure the table is rebuilt
                                    setTimeout(function() {
                                        // Get fresh references to product rows after rebuilding
                                        const updatedProductRows = document.querySelectorAll(
                                            '#productTableBody tr[data-product-id]');

                                        // Update stock display for each product
                                        updatedProductRows.forEach(function(row) {
                                            const productId = row.dataset.productId;
                                            // Always update the display, with 0 stock if not found in response
                                            if (productId && response.stocks[productId] !==
                                                undefined) {
                                                updateStockDisplay(row, response.stocks[
                                                    productId]);
                                            } else {
                                                updateStockDisplay(row, 0);
                                            }

                                            // Force visibility on each row
                                            row.style.display = 'table-row';
                                            row.style.visibility = 'visible';
                                            row.style.opacity = '1';
                                        });
                                    }, 50);
                                } else {
                                    // If all products are in the table, just update the stock display
                                    const productRows = document.querySelectorAll(
                                        '#productTableBody tr[data-product-id]');
                                    productRows.forEach(function(row) {
                                        const productId = row.dataset.productId;
                                        // Update stock display for each product
                                        if (productId && response.stocks[productId] !== undefined) {
                                            updateStockDisplay(row, response.stocks[productId]);
                                        } else {
                                            updateStockDisplay(row, 0);
                                        }

                                        // Force visibility on each row
                                        row.style.display = 'table-row';
                                        row.style.visibility = 'visible';
                                        row.style.opacity = '1';
                                    });
                                }

                                // Final verification that all products are present
                                setTimeout(function() {
                                    const visibleRowsCount = document.querySelectorAll(
                                        '#productTableBody tr[data-product-id]').length;

                                    if (visibleRowsCount < salesOrderProducts.length) {
                                        // Force a complete rebuild and get stock info again if products are missing
                                        rebuildProductTable();

                                        // Get stock info again after a delay to ensure the table is rebuilt
                                        setTimeout(getStockInfo, 100);
                                    }
                                }, 200);
                            }
                        },
                        error: function(xhr, status, error) {
                            document.getElementById('productTableBody').innerHTML = `
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center max-w-md mx-auto">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Terjadi Kesalahan</h3>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm text-center mb-4">
                                                Gagal memuat data Sales Order. Silakan coba lagi atau pilih Sales Order yang berbeda.
                                            </p>
                                            <div class="flex space-x-3">
                                                <button type="button" onClick="$('#sales_order_id').trigger('change');"
                                                    class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900 border border-transparent rounded-md font-medium text-sm text-indigo-700 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                    <i class="fas fa-sync-alt mr-2"></i> Coba Lagi
                                                </button>
                                                <button type="button" onClick="$('#sales_order_id').val('').trigger('change'); $('#sales_order_id').select2('open');"
                                                    class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-transparent rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                                    <i class="fas fa-exchange-alt mr-2"></i> Pilih SO Lain
                                                </button>
                                            </div>
                                            <div class="mt-4 text-xs text-red-500 dark:text-red-400 text-left bg-red-50 dark:bg-red-900/20 p-2 rounded">
                                                <strong>Detail Error:</strong> ${error}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                            alert("Gagal mengambil informasi stok. Silakan coba lagi.");
                        }
                    });
                }

                // Function to ensure all products are visible
                function ensureAllProductsVisible() {
                    // Get the count of product rows that should be displayed
                    const expectedProducts = salesOrderProducts.length;
                    const visibleRows = $('#productTableBody tr:visible').not('#emptyRow').length;

                    // Force all product rows to be visible
                    $('#productTableBody tr').each(function() {
                        if ($(this).data('product-id')) {
                            $(this).css('display', 'table-row').show();
                            $(this).removeClass('hidden d-none');
                        }
                    });

                    // Recheck visibility after forcing display
                    const newVisibleRows = $('#productTableBody tr:visible').not('#emptyRow').length;
                    if (visibleRows < expectedProducts || newVisibleRows < expectedProducts) {
                        // Repopulate the product table to ensure all products are shown
                        populateProductTable();

                        // If we have stock info already, reapply it
                        if (Object.keys(productStockInfo).length > 0) {
                            $('#productTableBody tr').each(function() {
                                const productId = $(this).data('product-id');
                                if (productId && productStockInfo[productId] !== undefined) {
                                    updateStockDisplay(this, productStockInfo[productId]);
                                }
                            });
                        }

                        // Force all product rows to be visible again
                        $('#productTableBody tr').each(function() {
                            if ($(this).data('product-id')) {
                                $(this).css('display', 'table-row').show();
                                $(this).removeClass('hidden d-none');
                            }
                        });

                        return true; // Fixed some visibility issues
                    }

                    return false; // No issues found
                }

                // Add periodic check to ensure all products remain visible
                setInterval(ensureAllProductsVisible, 2000);

                // Function to debug and force product visibility
                function debugAndForceVisibility() {
                    // Force this row to be visible
                    $('#productTableBody tr').each(function() {
                        const productId = $(this).data('product-id');
                        if (productId) {
                            $(this).css('display', 'table-row');
                            $(this).show();
                        }
                    });

                    // Re-check and log all product rows again
                    const visibleRows = $('#productTableBody tr:visible').not('#emptyRow').length;
                }

                // Setup MutationObserver to replace deprecated DOMNodeInserted
                const productTableObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                            // Check if any added nodes are table rows with product IDs
                            for (let i = 0; i < mutation.addedNodes.length; i++) {
                                const node = mutation.addedNodes[i];
                                if (node.nodeType === 1 && node.tagName === 'TR' && $(node).data(
                                        'product-id')) {
                                    setTimeout(debugAndForceVisibility, 100);
                                    break;
                                }
                            }
                        }
                    });
                });

                // Start observing the target node for configured mutations
                productTableObserver.observe(document.getElementById('productTableBody') || document.body, {
                    childList: true,
                    subtree: true
                });

                // Run debug function periodically
                setInterval(debugAndForceVisibility, 3000);

                // Load Sales Order details when Sales Order is selected
                $('#sales_order_id').change(function() {
                    const salesOrderId = $(this).val();
                    if (!salesOrderId) {
                        document.getElementById('productTableBody').innerHTML = `
                            <tr id="emptyRow">
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center max-w-md mx-auto">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Mulai Buat Delivery Order</h3>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center mb-4">
                                            Pilih Sales Order dan Gudang terlebih dahulu untuk melihat daftar produk yang dapat dikirim.
                                        </p>
                                        <div class="flex space-x-3">
                                            <button type="button" onClick="$('#sales_order_id').select2('open');"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900 border border-transparent rounded-md font-medium text-sm text-indigo-700 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                <i class="fas fa-clipboard-check mr-2"></i> Pilih Sales Order
                                            </button>
                                            <button type="button" onClick="$('#gudang_id').select2('open');"
                                                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-transparent rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                                <i class="fas fa-warehouse mr-2"></i> Pilih Gudang
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                        $('#customer_id').val('').trigger('change');
                        $('#alamat_pengiriman').val('');
                        salesOrderProducts = [];
                        return;
                    }

                    // Show loading
                    document.getElementById('productTableBody').innerHTML = `
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">Memuat data Sales Order...</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Mengambil informasi produk dan status stok</p>
                                </div>
                            </td>
                        </tr>
                    `;

                    // Fetch sales order data
                    $.ajax({
                        url: "{{ url('penjualan/delivery-order/get-sales-order-data') }}/" +
                            salesOrderId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            // Update customer and address
                            $('#customer_id').val(response.customer.id).trigger('change');
                            $('#alamat_pengiriman').val(response.salesOrder.alamat_pengiriman);

                            // Clear existing products and save new products
                            salesOrderProducts = response.details || [];

                            // Reset product stock info when changing sales order
                            productStockInfo = {};

                            // Use a small delay to ensure the DOM is ready before populating
                            setTimeout(function() {
                                // Populate product table with the new approach
                                populateProductTable();

                                // Get stock info if gudang is already selected
                                if ($('#gudang_id').val()) {
                                    // Use a small delay to ensure rows are created before fetching stock info
                                    setTimeout(function() {
                                        getStockInfo();
                                    }, 50);
                                }

                                // Additional check to ensure products are visible after a delay
                                setTimeout(function() {
                                    const productRows = document.querySelectorAll(
                                        '#productTableBody tr[data-product-id]');
                                    if (productRows.length !== salesOrderProducts
                                        .length) {
                                        // If row count doesn't match expected count, rebuild
                                        rebuildProductTable();

                                        // Update stock info on the rebuilt table
                                        if ($('#gudang_id').val()) {
                                            setTimeout(getStockInfo, 50);
                                        }
                                    }
                                }, 200);
                            }, 10);
                        },
                        error: function(xhr, status, error) {
                            document.getElementById('productTableBody').innerHTML = `
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center max-w-md mx-auto">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Terjadi Kesalahan</h3>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm text-center mb-4">
                                                Gagal memuat data Sales Order. Silakan coba lagi atau pilih Sales Order yang berbeda.
                                            </p>
                                            <div class="flex space-x-3">
                                                <button type="button" onClick="$('#sales_order_id').trigger('change');"
                                                    class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900 border border-transparent rounded-md font-medium text-sm text-indigo-700 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                                    <i class="fas fa-sync-alt mr-2"></i> Coba Lagi
                                                </button>
                                                <button type="button" onClick="$('#sales_order_id').val('').trigger('change'); $('#sales_order_id').select2('open');"
                                                    class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-transparent rounded-md font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                                                    <i class="fas fa-exchange-alt mr-2"></i> Pilih SO Lain
                                                </button>
                                            </div>
                                            <div class="mt-4 text-xs text-red-500 dark:text-red-400 text-left bg-red-50 dark:bg-red-900/20 p-2 rounded">
                                                <strong>Detail Error:</strong> ${error}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    });
                });

                // Function to populate product table
                function populateProductTable() {
                    // Use direct DOM manipulation for better performance and visibility
                    const tableBody = document.getElementById('productTableBody');

                    // Clear all existing content
                    tableBody.innerHTML = '';

                    // Add an empty state message with more visual appeal
                    if (salesOrderProducts.length === 0) {
                        tableBody.innerHTML = `
                            <tr id="emptyRow">
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center max-w-md mx-auto">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada produk</h3>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center mb-4">
                                            Tidak ada produk dalam Sales Order ini atau semua produk sudah terkirim.
                                        </p>
                                        <button type="button" onClick="$('#sales_order_id').val('').trigger('change'); $('#sales_order_id').select2('open');"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900 border border-transparent rounded-md font-medium text-sm text-indigo-700 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                            <i class="fas fa-sync-alt mr-2"></i> Pilih Sales Order Lain
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    // Add product rows using direct DOM manipulation for better performance
                    // Create a document fragment to batch all DOM operations
                    const fragment = document.createDocumentFragment();

                    // Add all products at once
                    salesOrderProducts.forEach(function(detail) {
                        const hasRemainingQty = detail.quantity_sisa > 0;
                        const rowClass = hasRemainingQty ?
                            "hover:bg-gray-50 dark:hover:bg-gray-800 product-row" :
                            "hover:bg-gray-50 dark:hover:bg-gray-800 product-row bg-gray-50 dark:bg-gray-700";

                        // Use normal quantity value if there's remaining quantity, otherwise use 0
                        const inputValue = hasRemainingQty ? detail.quantity_sisa : 0;
                        const inputDisabled = !hasRemainingQty;

                        // Create a new row element
                        const row = document.createElement('tr');
                        row.className = rowClass;
                        row.dataset.productId = detail.produk_id;
                        row.dataset.sisaSo = detail.quantity_sisa;

                        // Set important style properties directly to ensure visibility
                        row.style.display = 'table-row';
                        row.style.visibility = 'visible';
                        row.style.opacity = '1';

                        // Set row HTML content
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">${detail.produk_kode}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">${detail.produk_nama}</div>
                                        ${!hasRemainingQty ? '<div class="text-xs text-red-500 mt-1"><i class="fas fa-info-circle mr-1"></i> Semua item sudah terkirim</div>' : ''}
                                    </div>
                                </div>
                                <input type="hidden" name="produk_id[]" value="${detail.produk_id}">
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    ${detail.satuan_nama}
                                </span>
                                <input type="hidden" name="satuan_id[]" value="${detail.satuan_id}">
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center stock-cell">
                                ${document.getElementById('gudang_id').value ? 
                                    '<div class="animate-pulse-custom bg-gray-200 dark:bg-gray-600 h-6 w-16 rounded mx-auto"></div>' : 
                                    '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400"><i class="fas fa-exclamation-circle mr-1"></i> Pilih Gudang</span>'}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    ${detail.quantity}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    ${detail.quantity_terkirim}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${hasRemainingQty ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'} font-bold">
                                    ${detail.quantity_sisa}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="relative">
                                    <input type="number" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm text-center quantity-input" 
                                        name="quantity[]" 
                                        min="0" 
                                        max="${detail.quantity_sisa}" 
                                        step="0.01" 
                                        value="${inputValue}" 
                                        ${inputDisabled ? 'disabled' : ''}
                                        required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-edit text-xs text-gray-400"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="relative">
                                    <input type="text" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm" 
                                        name="keterangan[]" 
                                        placeholder="Keterangan"
                                        style="min-width: 200px;">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-comment-alt text-xs text-gray-400"></i>
                                    </div>
                                </div>
                            </td>
                        `;

                        // Add the row to fragment
                        fragment.appendChild(row);
                    });

                    // Append all rows at once to the DOM
                    tableBody.appendChild(fragment);

                    // Force immediate rendering - requestAnimationFrame ensures we're working with the painted DOM
                    requestAnimationFrame(function() {
                        // Force product rows to be visible 
                        const productRows = document.querySelectorAll('#productTableBody tr[data-product-id]');
                        productRows.forEach(function(row) {
                            row.style.display = 'table-row';
                            row.style.visibility = 'visible';
                            row.style.opacity = '1';
                        });
                    });

                    // Make sure all rows are visible
                    $('#productTableBody tr').each(function() {
                        const productId = $(this).data('product-id');
                        if (productId) {
                            $(this).css('display', 'table-row');
                            $(this).show();
                            $(this).removeClass('hidden d-none');
                        }
                    });
                }

                // Function to completely rebuild the product table from scratch
                function rebuildProductTable() {
                    // Get a reference to the existing table body
                    const existingTableBody = document.getElementById('productTableBody');

                    // Create a new table body element
                    const newTableBody = document.createElement('tbody');
                    newTableBody.id = 'productTableBody';

                    // Replace the entire table body
                    if (existingTableBody && existingTableBody.parentNode) {
                        existingTableBody.parentNode.replaceChild(newTableBody, existingTableBody);
                    }

                    // Add empty state if needed
                    if (salesOrderProducts.length === 0) {
                        newTableBody.innerHTML = `
                            <tr id="emptyRow">
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center max-w-md mx-auto">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada produk</h3>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center mb-4">
                                            Tidak ada produk dalam Sales Order ini atau semua produk sudah terkirim.
                                        </p>
                                        <button type="button" onClick="$('#sales_order_id').val('').trigger('change'); $('#sales_order_id').select2('open');"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900 border border-transparent rounded-md font-medium text-sm text-indigo-700 dark:text-indigo-300 hover:bg-indigo-200 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                            <i class="fas fa-sync-alt mr-2"></i> Pilih Sales Order Lain
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    // Create a document fragment to batch DOM operations
                    const fragment = document.createDocumentFragment();

                    // Add all products
                    salesOrderProducts.forEach(function(detail) {
                        const hasRemainingQty = detail.quantity_sisa > 0;
                        const rowClass = "product-row"; // Always use standard class for visibility

                        // Use normal quantity value if there's remaining quantity, otherwise use 0
                        const inputValue = hasRemainingQty ? detail.quantity_sisa : 0;
                        const inputDisabled = !hasRemainingQty;

                        // Create new row element
                        const row = document.createElement('tr');
                        row.className = rowClass;
                        row.dataset.productId = detail.produk_id;
                        row.dataset.sisaSo = detail.quantity_sisa;

                        // Force visibility with inline styles
                        row.style.display = 'table-row';
                        row.style.visibility = 'visible';
                        row.style.opacity = '1';

                        // Set HTML content
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">${detail.produk_kode}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">${detail.produk_nama}</div>
                                        ${!hasRemainingQty ? '<div class="text-xs text-red-500 mt-1"><i class="fas fa-info-circle mr-1"></i> Semua item sudah terkirim</div>' : ''}
                                    </div>
                                </div>
                                <input type="hidden" name="produk_id[]" value="${detail.produk_id}">
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    ${detail.satuan_nama}
                                </span>
                                <input type="hidden" name="satuan_id[]" value="${detail.satuan_id}">
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center stock-cell">
                                ${document.getElementById('gudang_id').value ? 
                                    '<div class="animate-pulse-custom bg-gray-200 dark:bg-gray-600 h-6 w-16 rounded mx-auto"></div>' : 
                                    '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400"><i class="fas fa-exclamation-circle mr-1"></i> Pilih Gudang</span>'}
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    ${detail.quantity}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    ${detail.quantity_terkirim}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${hasRemainingQty ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'} font-bold">
                                    ${detail.quantity_sisa}
                                </span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="relative">
                                    <input type="number" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm text-center quantity-input" 
                                        name="quantity[]" 
                                        min="0" 
                                        max="${detail.quantity_sisa}" 
                                        step="0.01" 
                                        value="${inputValue}" 
                                        ${inputDisabled ? 'disabled' : ''}
                                        required>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-edit text-xs text-gray-400"></i>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-normal">
                                <div class="relative">
                                    <input type="text" 
                                        class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm" 
                                        name="keterangan[]" 
                                        placeholder="Keterangan"
                                        style="min-width: 200px;">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                        <i class="fas fa-comment-alt text-xs text-gray-400"></i>
                                    </div>
                                </div>
                            </td>
                        `;

                        // Add to fragment
                        fragment.appendChild(row);
                    });

                    // Append all rows at once to the table body
                    newTableBody.appendChild(fragment);

                    // Apply stock information if available after a slight delay to ensure rows are visible
                    setTimeout(function() {
                        if (Object.keys(productStockInfo).length > 0 && document.getElementById('gudang_id')
                            .value) {
                            const rows = document.querySelectorAll('#productTableBody tr[data-product-id]');
                            rows.forEach(function(row) {
                                const productId = row.dataset.productId;
                                if (productId && productStockInfo[productId] !== undefined) {
                                    updateStockDisplay(row, productStockInfo[productId]);
                                }
                            });
                        }
                    }, 50);
                }

                // Update stok ketika gudang berubah
                $('#gudang_id').change(function() {
                    if (salesOrderProducts.length > 0) {
                        // Count products before warehouse change
                        const beforeCount = $('#productTableBody tr:visible').not('#emptyRow').length;

                        // If the count doesn't match, rebuild the table completely first
                        if (beforeCount !== salesOrderProducts.length) {
                            rebuildProductTable();
                        } else {
                            // Make sure all products are visible before getting stock info
                            $('#productTableBody tr').each(function() {
                                const productId = $(this).data('product-id');
                                if (productId) {
                                    $(this).css('display', 'table-row');
                                    $(this).show();
                                    $(this).removeClass('hidden d-none');
                                    $(this).attr('style',
                                        'display: table-row !important; visibility: visible !important;'
                                    );
                                }
                            });
                        }

                        // Get stock information
                        getStockInfo();

                        // Additional checks after a delay
                        setTimeout(function() {
                            const afterCount = $('#productTableBody tr:visible').not('#emptyRow')
                                .length;

                            if (afterCount < salesOrderProducts.length) {
                                rebuildProductTable();

                                // Update stock info again on the freshly rebuilt table
                                setTimeout(function() {
                                    getStockInfo();
                                }, 100);
                            }
                        }, 200);

                        // Final verification after all updates
                        setTimeout(function() {
                            const finalCount = $('#productTableBody tr:visible').not('#emptyRow')
                                .length;

                            if (finalCount !== salesOrderProducts.length) {
                                // Last resort: force a complete rebuild and reapply everything
                                rebuildProductTable();

                                // Update stock info on the rebuilt table after a delay
                                setTimeout(function() {
                                    getStockInfo();
                                }, 200);
                            }
                        }, 500);
                    }
                });

                // Listen untuk perubahan di input kuantitas
                $(document).on('input', '.quantity-input', function() {
                    const row = $(this).closest('tr');
                    const productId = row.data('product-id');
                    const sisaSO = parseFloat(row.data('sisa-so')) || 0;
                    const stockQty = productStockInfo[productId] || 0;
                    const qty = parseFloat($(this).val()) || 0;

                    // Reset styling
                    row.removeClass('bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400');
                    $(this).removeClass('border-red-500 border-yellow-500');

                    // Batasi nilai maksimum sesuai stok gudang
                    if (qty > stockQty && stockQty > 0) {
                        $(this).val(stockQty);
                        row.addClass('bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400');
                        $(this).addClass('border-yellow-500');
                        alert(`Stok tidak cukup! Maksimal pengiriman untuk produk ini adalah ${stockQty}`);
                    } else if (qty > sisaSO) {
                        $(this).val(sisaSO);
                        $(this).addClass('border-red-500');
                        alert(`Kuantitas tidak boleh melebihi sisa SO (${sisaSO})`);
                    }
                });

                // Trigger change if sales order is already selected (e.g. when returning with validation errors)
                if ($('#sales_order_id').val()) {
                    $('#sales_order_id').trigger('change');
                }

                // Form validation
                $('#formDeliveryOrder').submit(function(e) {
                    const salesOrderId = $('#sales_order_id').val();
                    const gudangId = $('#gudang_id').val();
                    let isValid = true;
                    let errorMessage = '';
                    let errorCount = 0;

                    // Show loading on submit button
                    $('#submitBtn').prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');

                    // Validate required fields
                    const requiredFields = [{
                            id: 'nomor',
                            label: 'Nomor Delivery Order'
                        },
                        {
                            id: 'tanggal',
                            label: 'Tanggal'
                        },
                        {
                            id: 'sales_order_id',
                            label: 'Sales Order'
                        },
                        {
                            id: 'gudang_id',
                            label: 'Gudang Asal'
                        },
                        {
                            id: 'customer_id',
                            label: 'Customer'
                        },
                        {
                            id: 'alamat_pengiriman',
                            label: 'Alamat Pengiriman'
                        }
                    ];

                    requiredFields.forEach(field => {
                        const value = $(`#${field.id}`).val();
                        if (!value || value.trim() === '') {
                            isValid = false;
                            errorCount++;
                            $(`#${field.id}`).addClass('border-red-500');
                            errorMessage += `- ${field.label} wajib diisi\n`;
                        } else {
                            $(`#${field.id}`).removeClass('border-red-500');
                        }
                    });

                    // Check if there are product rows
                    if ($('#emptyRow').length > 0) {
                        isValid = false;
                        errorCount++;
                        errorMessage += "- Tidak ada produk yang dapat dikirim\n";
                    }

                    // Check if any quantity is invalid
                    let validItemCount = 0;
                    let totalItems = 0;

                    $('input[name="quantity[]"]').each(function() {
                        totalItems++;
                        const row = $(this).closest('tr');
                        const productId = row.data('product-id');
                        const stockQty = productStockInfo[productId] || 0;
                        const sisaSO = parseFloat(row.data('sisa-so')) || 0;
                        const qty = parseFloat($(this).val()) || 0;
                        const productName = row.find('td:first .text-sm.font-medium').text().trim();

                        // Reset styling first
                        $(this).removeClass('border-red-500');

                        if (qty <= 0) {
                            // Jika qty 0, tidak invalid tapi juga tidak dihitung sebagai valid item
                            // Kita akan periksa di akhir apakah semua item qty 0 atau tidak
                        } else if (qty > stockQty) {
                            // Jika qty melebihi stok, tidak valid
                            isValid = false;
                            errorCount++;
                            errorMessage +=
                                `- Kuantitas untuk ${productName} (${qty}) melebihi stok gudang (${stockQty})\n`;
                            $(this).addClass('border-red-500');
                        } else if (qty > sisaSO) {
                            isValid = false;
                            errorCount++;
                            errorMessage +=
                                `- Kuantitas untuk ${productName} (${qty}) melebihi sisa SO (${sisaSO})\n`;
                            $(this).addClass('border-red-500');
                        } else {
                            validItemCount++;
                        }
                    });

                    // Check if at least one product has a valid quantity
                    let nonZeroItems = 0;
                    $('input[name="quantity[]"]').each(function() {
                        const qty = parseFloat($(this).val()) || 0;
                        if (qty > 0) {
                            nonZeroItems++;
                        }
                    });

                    // Jika semua produk bernilai 0, tidak valid
                    if (totalItems > 0 && nonZeroItems === 0) {
                        isValid = false;
                        errorMessage +=
                            "- Minimal satu produk harus memiliki kuantitas lebih dari 0 untuk dikirim\n";
                    }

                    // Jika hanya ada 1 produk, nilainya tidak boleh 0
                    if (totalItems === 1 && nonZeroItems === 0) {
                        isValid = false;
                        errorMessage += "- Dengan hanya satu produk, kuantitas tidak boleh 0\n";
                    }

                    // Check for products with insufficient stock but still valid for submission
                    const lowStockProducts = [];
                    $('input[name="quantity[]"]').each(function() {
                        const row = $(this).closest('tr');
                        const productId = row.data('product-id');
                        const productName = row.find('td:first .text-sm.font-medium').text().trim();
                        const stockQty = productStockInfo[productId] || 0;
                        const qty = parseFloat($(this).val()) || 0;

                        // Jika kuantitas lebih dari 0 tapi melebihi stok, tampilkan peringatan
                        if (qty > 0 && qty > stockQty) {
                            lowStockProducts.push({
                                name: productName,
                                requested: qty,
                                available: stockQty
                            });
                        }
                    });

                    // If there are low stock products but the form is otherwise valid
                    if (isValid && lowStockProducts.length > 0) {
                        e.preventDefault();
                        $('#submitBtn').prop('disabled', false).html(
                            '<i class="fas fa-save mr-2"></i>Simpan Delivery Order');

                        // Show confirmation dialog
                        const productList = lowStockProducts.map(p =>
                            `- ${p.name}: Diminta ${p.requested}, Stok tersedia ${p.available}`
                        ).join('\n');

                        const confirmMessage =
                            `PERHATIAN: ${lowStockProducts.length} produk memiliki stok yang tidak mencukupi:\n\n${productList}\n\nApakah Anda tetap ingin melanjutkan?`;

                        if (confirm(confirmMessage)) {
                            // User confirmed, submit the form
                            $(this).off('submit').submit();
                        }

                        return false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        $('#submitBtn').prop('disabled', false).html(
                            '<i class="fas fa-save mr-2"></i>Simpan Delivery Order');

                        // Show error in a more user-friendly way
                        let alertMessage =
                            `Terdapat ${errorCount} kesalahan yang perlu diperbaiki:\n\n${errorMessage}`;

                        // Create and show a modern error notification
                        const errorNotification = `
                            <div id="errorAlert" class="fixed top-4 right-4 z-50 bg-red-50 dark:bg-red-900 border-l-4 border-red-500 p-4 rounded shadow-lg max-w-md transition-all duration-500 transform translate-x-full">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-lg"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Form tidak valid</h3>
                                        <div class="mt-2 text-sm text-red-700 dark:text-red-300 max-h-48 overflow-y-auto">
                                            <ul class="list-disc pl-5 space-y-1">
                                                ${errorMessage.split('\n').filter(Boolean).map(err => `<li>${err.replace(/^- /, '')}</li>`).join('')}
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="ml-auto pl-3">
                                        <button type="button" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" onclick="document.getElementById('errorAlert').remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Add to body and animate in
                        $('body').append(errorNotification);
                        setTimeout(() => {
                            $('#errorAlert').removeClass('translate-x-full');
                        }, 10);

                        // Auto-remove after 10 seconds
                        setTimeout(() => {
                            $('#errorAlert').addClass('translate-x-full');
                            setTimeout(() => {
                                $('#errorAlert').remove();
                            }, 500);
                        }, 10000);

                        // Also show standard alert for browsers without good CSS support
                        alert(alertMessage);
                    } else {
                        // All valid, show loading state
                        $('body').append(`
                            <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm mx-auto text-center">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                                    <p class="mt-4 text-gray-700 dark:text-gray-300">Menyimpan Delivery Order...</p>
                                </div>
                            </div>
                        `);
                    }
                });
            });
        </script>
    @endpush

</x-app-layout>
