<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Sales Order', 'url' => route('penjualan.sales-order.index')],
    ['label' => 'Edit'],
]" :currentPage="'Edit Sales Order'">
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
        </style>
    @endpush

    <div class="py-8 max-w-full mx-auto sm:px-6 lg:px-8" x-data="$store.salesOrderEditForm" x-init="init()">>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Edit Sales Order
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Edit sales order untuk pelanggan.</p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('penjualan.sales-order.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 dark:focus:ring-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <form id="salesOrderForm" action="{{ route('penjualan.sales-order.update', $salesOrder->id) }}" method="POST"
            @submit="validateForm($event)">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/20 p-4 text-sm text-red-600 dark:text-red-400">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 mb-8">
                <!-- Card 1: Informasi Dasar -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 form-card">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Informasi Dasar
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="input-group">
                            <label for="nomor" class="text-gray-700 dark:text-gray-300">Nomor Sales Order <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nomor" id="nomor"
                                value="{{ old('nomor', $salesOrder->nomor) }}" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                        </div>

                        <div class="input-group">
                            <label for="nomor_po" class="text-gray-700 dark:text-gray-300">Nomor PO Customer</label>
                            <input type="text" name="nomor_po" id="nomor_po"
                                value="{{ old('nomor_po', $salesOrder->nomor_po) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                        </div>

                        <div class="input-group">
                            <label for="tanggal" class="text-gray-700 dark:text-gray-300">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', $salesOrder->tanggal ? \Carbon\Carbon::parse($salesOrder->tanggal)->format('Y-m-d') : '') }}"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                        </div>

                        <div class="input-group">
                            <label for="quotation_id" class="text-gray-700 dark:text-gray-300">Berdasarkan
                                Quotation</label>
                            <select name="quotation_id" id="quotation_id"
                                class="quotation-select mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white"
                                @change="loadQuotationData()">
                                <option value="">Pilih Quotation (Opsional)</option>
                                @if ($salesOrder->quotation)
                                    <option value="{{ $salesOrder->quotation->id }}" selected>
                                        {{ $salesOrder->quotation->nomor }} -
                                        {{ $salesOrder->quotation->customer->nama ?? $salesOrder->quotation->customer->company }}
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="customer_id" class="text-gray-700 dark:text-gray-300">Customer <span
                                    class="text-red-500">*</span></label>
                            <select name="customer_id" id="customer_id" x-model="customer_id" required
                                class="customer-select mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                <option value="">Pilih Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id', $salesOrder->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->company ? $customer->company . ' - ' . $customer->nama : $customer->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="tanggal_kirim" class="text-gray-700 dark:text-gray-300">Tanggal Kirim</label>
                            <input type="date" name="tanggal_kirim" id="tanggal_kirim" x-model="tanggal_kirim"
                                value="{{ old('tanggal_kirim', $salesOrder->tanggal_kirim ? \Carbon\Carbon::parse($salesOrder->tanggal_kirim)->format('Y-m-d') : '') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                        </div>

                        <div class="input-group">
                            <label for="status_pembayaran" class="text-gray-700 dark:text-gray-300">Status Pembayaran
                                <span class="text-red-500">*</span></label>
                            <select name="status_pembayaran" id="status_pembayaran" required
                                x-model="status_pembayaran"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                @foreach ($status_pembayaran as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status_pembayaran', $salesOrder->status_pembayaran) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="status_pengiriman" class="text-gray-700 dark:text-gray-300">Status Pengiriman
                                <span class="text-red-500">*</span></label>
                            <select name="status_pengiriman" id="status_pengiriman" required
                                x-model="status_pengiriman"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                @foreach ($status_pengiriman as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status_pengiriman', $salesOrder->status_pengiriman) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group md:col-span-2">
                            <label for="alamat_pengiriman" class="text-gray-700 dark:text-gray-300">Alamat
                                Pengiriman</label>
                            <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="3" x-model="alamat_pengiriman"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">{{ old('alamat_pengiriman', $salesOrder->alamat_pengiriman) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Item Produk -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 form-card mb-6">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                            </svg>
                            Detail Item
                        </h2>
                        <div class="flex gap-2">
                            <button type="button" @click="showBundleModal = true"
                                class="group inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 rounded-lg text-sm font-medium text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Tambah Bundle
                            </button>
                            <button type="button" @click="addItem()"
                                class="group inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 dark:hover:bg-primary-900/50 rounded-lg text-sm font-medium text-primary-700 dark:text-primary-300 border border-primary-200 dark:border-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Item
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-5">
                        <div x-show="!items.length" class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada item</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan item
                                untuk
                                sales order ini.</p>
                            <div class="mt-6 flex gap-3 justify-center">
                                <button type="button" @click="showBundleModal = true"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Tambah Bundle Produk
                                </button>
                                <button type="button" @click="addItem()"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Item Biasa
                                </button>
                            </div>
                        </div>

                        <div x-show="items.length > 0" class="overflow-x-auto">
                            <div class="space-y-3 mt-2" id="items-container">
                                <template x-for="(item, index) in items" :key="index">
                                    <div>
                                        <!-- BUNDLE HEADER - Simple & Clean -->
                                        <template x-if="item.is_bundle">
                                            <div
                                                class="border-l-4 border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 rounded-r-lg p-4">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <div
                                                            class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-white" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h4 class="font-semibold text-emerald-800 dark:text-emerald-200"
                                                                x-text="item.bundle_name"></h4>
                                                            <p class="text-sm text-emerald-600 dark:text-emerald-400"
                                                                x-text="item.bundle_code"></p>
                                                        </div>
                                                        <span
                                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                                            Bundle
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center space-x-3">
                                                        <!-- Bundle Quantity Input -->
                                                        <div class="flex items-center space-x-2">
                                                            <label
                                                                class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Qty:</label>
                                                            <input type="number" x-model.number="item.kuantitas"
                                                                @input="updateBundleCalculations(index)"
                                                                class="w-20 px-2 py-1 text-center border border-emerald-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                                                min="1" step="1">
                                                            <span
                                                                class="text-sm text-emerald-600 dark:text-emerald-400">paket</span>
                                                        </div>
                                                        <div class="text-right">
                                                            <p class="font-bold text-emerald-800 dark:text-emerald-200"
                                                                x-text="formatRupiah(item.subtotal)"></p>
                                                            <p class="text-xs text-emerald-600 dark:text-emerald-400">
                                                                <span x-text="item.kuantitas"></span> x <span
                                                                    x-text="formatRupiah(item.harga)"></span>
                                                            </p>
                                                        </div>
                                                        <button type="button" @click="removeItem(index)"
                                                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- Hidden inputs for bundle header only -->
                                                <input type="hidden" :name="`items[${index}][is_bundle]`"
                                                    value="1">
                                                <input type="hidden" :name="`items[${index}][bundle_id]`"
                                                    :value="item.bundle_id">
                                                <input type="hidden" :name="`items[${index}][kuantitas]`"
                                                    :value="item.kuantitas">
                                                <input type="hidden" :name="`items[${index}][harga]`"
                                                    :value="item.harga">
                                                <input type="hidden" :name="`items[${index}][subtotal]`"
                                                    :value="item.subtotal">
                                                <input type="hidden" :name="`items[${index}][deskripsi]`"
                                                    :value="item.deskripsi">
                                            </div>
                                        </template>

                                        <!-- BUNDLE ITEM - Simple & Clean -->
                                        <template x-if="item.is_bundle_item">
                                            <div
                                                class="ml-6 border-l-2 border-gray-300 dark:border-gray-600 pl-4 py-2 bg-gray-50 dark:bg-gray-800/50 rounded-r">
                                                <div class="flex items-center justify-between text-sm">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                                        <span class="font-medium text-gray-700 dark:text-gray-300"
                                                            x-text="item.nama || item.produk_nama || 'Bundle Item'"></span>
                                                        <span class="text-gray-500 dark:text-gray-400"
                                                            x-text="'(' + (item.kode || item.produk_kode || '') + ')'"></span>
                                                    </div>
                                                    <div
                                                        class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                                                        <span
                                                            x-text="item.kuantitas + ' ' + (item.satuan_nama || 'pcs')"></span>
                                                        <span class="text-gray-400"
                                                            x-show="item.base_quantity && item.kuantitas !== item.base_quantity">
                                                            (<span x-text="item.base_quantity"></span> × <span
                                                                x-text="Math.round(item.kuantitas / item.base_quantity)"></span>
                                                            paket)
                                                        </span>
                                                        <span>×</span>
                                                        <span x-text="formatRupiah(item.harga)"></span>
                                                        <!-- Show discount if applicable -->
                                                        <template x-if="item.diskon_persen && item.diskon_persen > 0">
                                                            <div class="flex items-center space-x-1">
                                                                <span class="text-red-500">(-<span
                                                                        x-text="item.diskon_persen"></span>%)</span>
                                                            </div>
                                                        </template>
                                                        <span>=</span>
                                                        <span class="font-semibold text-gray-700 dark:text-gray-300"
                                                            x-text="formatRupiah(Math.round(item.subtotal || 0))"></span>
                                                    </div>
                                                </div>
                                                <!-- Hidden inputs for bundle items -->
                                                <input type="hidden" :name="`items[${index}][produk_id]`"
                                                    :value="item.produk_id">
                                                <input type="hidden" :name="`items[${index}][is_bundle_item]`"
                                                    value="1">
                                                <input type="hidden" :name="`items[${index}][bundle_id]`"
                                                    :value="item.bundle_id">
                                                <input type="hidden" :name="`items[${index}][kuantitas]`"
                                                    :value="item.kuantitas">
                                                <input type="hidden" :name="`items[${index}][satuan_id]`"
                                                    :value="item.satuan_id">
                                                <input type="hidden" :name="`items[${index}][harga]`"
                                                    :value="item.harga">
                                                <input type="hidden" :name="`items[${index}][subtotal]`"
                                                    :value="item.subtotal">
                                                <input type="hidden" :name="`items[${index}][deskripsi]`"
                                                    :value="item.deskripsi">
                                                <input type="hidden" :name="`items[${index}][diskon_persen_item]`"
                                                    :value="item.diskon_persen || 0">
                                                <input type="hidden" :name="`items[${index}][diskon_nominal_item]`"
                                                    :value="item.diskon_nominal || 0">
                                            </div>
                                        </template>

                                        <!-- REGULAR ITEM -->
                                        <template x-if="!item.is_bundle && !item.is_bundle_item">
                                            <div
                                                class="border border-gray-200 dark:border-gray-600 rounded-lg p-5 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-200 hover:border-primary-200 dark:hover:border-primary-800">
                                                <div class="md:hidden flex justify-between items-center mb-4">
                                                    <span
                                                        class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-primary-100 dark:bg-primary-900/50 text-xs font-medium text-primary-800 dark:text-primary-300"
                                                        x-text="index + 1"></span>
                                                    <button type="button" @click="removeItem(index)"
                                                        class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="grid grid-cols-1 gap-6 pb-3">
                                                    <!-- Item Header Line -->
                                                    <div
                                                        class="flex items-center justify-between border-b border-gray-200 dark:border-gray-600 pb-2">
                                                        <span
                                                            class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                                            <span
                                                                class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-primary-100 dark:bg-primary-900/50 text-xs font-medium text-primary-800 dark:text-primary-300 mr-2"
                                                                x-text="index + 1"></span>
                                                            Item Detail
                                                        </span>
                                                        <div class="hidden md:block">
                                                            <button type="button" @click="removeItem(index)"
                                                                class="p-1.5 border border-gray-200 dark:border-gray-600 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Product Row -->
                                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-x-6 gap-y-4">
                                                        {{-- Produk --}}
                                                        <div class="md:col-span-5">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Produk
                                                                <span class="text-red-500">*</span></label>
                                                            <select :name="`items[${index}][produk_id]`"
                                                                x-model="item.produk_id"
                                                                @change="updateItemDetails(index)"
                                                                :data-index="index" :required="!item.is_bundle"
                                                                class="produk-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                                <option value="">Pilih Produk</option>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                        data-harga="{{ $product->harga_jual ?? 0 }}"
                                                                        data-satuan_id="{{ $product->satuan_id ?? '' }}">
                                                                        {{ $product->kode }} - {{ $product->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- Deskripsi Item --}}
                                                        <div class="md:col-span-7">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi
                                                                Item</label>
                                                            <textarea :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" rows="1"
                                                                placeholder="Deskripsi tambahan untuk item ini..."
                                                                class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Pricing Row -->
                                                    <div
                                                        class="grid grid-cols-1 md:grid-cols-12 gap-x-6 gap-y-4 bg-gray-50 dark:bg-gray-800/30 rounded-lg p-3 mt-2">
                                                        {{-- Kuantitas --}}
                                                        <div class="md:col-span-2">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kuantitas
                                                                <span class="text-red-500">*</span></label>
                                                            <input type="number" step="any" min="0.01"
                                                                :name="`items[${index}][kuantitas]`"
                                                                x-model.number="item.kuantitas"
                                                                @input="updateSubtotal(index)" required
                                                                placeholder="0"
                                                                class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
                                                        </div>

                                                        {{-- Satuan --}}
                                                        <div class="md:col-span-2">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Satuan
                                                                <span class="text-red-500">*</span></label>
                                                            <select :name="`items[${index}][satuan_id]`"
                                                                x-model="item.satuan_id" :required="!item.is_bundle"
                                                                class="satuan-select-single mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                                <option value="">Pilih</option>
                                                                @foreach ($satuans as $satuan)
                                                                    <option value="{{ $satuan->id }}">
                                                                        {{ $satuan->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- Harga --}}
                                                        <div class="md:col-span-3">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga
                                                                Satuan
                                                                <span class="text-red-500">*</span></label>
                                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                                <div
                                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <span
                                                                        class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                                </div>
                                                                <input type="number" step="any"
                                                                    :name="`items[${index}][harga]`"
                                                                    x-model.number="item.harga"
                                                                    @input="updateSubtotal(index)" required
                                                                    placeholder="0"
                                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                            </div>
                                                        </div>

                                                        {{-- Diskon Item (%) - Hidden for bundle items --}}
                                                        <div class="md:col-span-2" x-show="!item.is_bundle_item">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diskon
                                                                (%)</label>
                                                            <input type="number" step="any" min="0"
                                                                max="100"
                                                                :name="`items[${index}][diskon_persen]`"
                                                                x-model.number="item.diskon_persen"
                                                                @input="updateSubtotal(index)" placeholder="0"
                                                                class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
                                                        </div>
                                                        <!-- Bundle item discount display -->
                                                        <div class="md:col-span-2" x-show="item.is_bundle_item">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                Diskon (%)
                                                            </label>
                                                            <div
                                                                class="mt-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-center">
                                                                <span
                                                                    x-text="item.diskon_persen > 0 ? item.diskon_persen + '%' : '-'"></span>
                                                            </div>
                                                        </div>

                                                        {{-- Subtotal --}}
                                                        <div class="md:col-span-3">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subtotal</label>
                                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                                <div
                                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                    <span
                                                                        class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                                </div>
                                                                <input type="text"
                                                                    :name="`items[${index}][subtotal]`"
                                                                    x-model="item.subtotal" readonly
                                                                    :value="formatRupiah(item.subtotal)"
                                                                    class="bg-gray-100 dark:bg-gray-700 focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:text-white rounded-md font-medium">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- End Regular Item -->
                                        </template>
                                    </div> <!-- End Template Item Container -->
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Ringkasan Total -->
                <div
                    class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 form-card mb-6 summary-card">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Ringkasan Total
                        </h2>
                    </div>
                    <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-4">
                        <div class="md:col-span-2"></div> {{-- Spacer --}}
                        <div
                            class="space-y-2 rounded-lg p-4 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 shadow-inner">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Subtotal
                                    Keseluruhan:</span>
                                <span class="text-sm font-semibold text-gray-800 dark:text-white"
                                    x-text="formatRupiah(calculateTotal().subtotal)"></span>
                            </div>

                            <div class="flex justify-between items-center">
                                <label for="diskon_global_persen"
                                    class="text-sm font-medium text-gray-600 dark:text-gray-300">Diskon Global
                                    (%):</label>
                                <input type="number" step="any" min="0" max="100"
                                    name="diskon_global_persen" id="diskon_global_persen"
                                    x-model.number="diskonGlobalPersen" @input="updateDiskonGlobal('persen')"
                                    placeholder="0"
                                    class="w-24 text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div class="flex justify-between items-center">
                                <label for="diskon_global_nominal"
                                    class="text-sm font-medium text-gray-600 dark:text-gray-300">Diskon Global
                                    (Rp):</label>
                                <input type="number" step="any" min="0" name="diskon_global_nominal"
                                    id="diskon_global_nominal" x-model.number="diskonGlobalNominal"
                                    @input="updateDiskonGlobal('nominal')" placeholder="0"
                                    class="w-36 text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Setelah Diskon
                                    Global:</span>
                                <span class="text-sm font-semibold text-gray-800 dark:text-white"
                                    x-text="formatRupiah(calculateTotal().afterGlobalDiscount)"></span>
                            </div>

                            <!-- Ongkos Kirim -->
                            <div class="flex justify-between items-center">
                                <label for="ongkos_kirim"
                                    class="text-sm font-medium text-gray-600 dark:text-gray-300">
                                    Ongkos Kirim:
                                </label>
                                <input type="number" step="any" min="0" name="ongkos_kirim"
                                    id="ongkos_kirim" x-model.number="ongkosKirim" @input="calculateTotal()"
                                    placeholder="0"
                                    class="w-36 text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                            </div>

                            <!-- PPN Toggle -->
                            <div class="flex justify-between items-center mt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" id="include_ppn" x-model="includePPN"
                                        @change="calculateTotal()"
                                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <input type="hidden" name="ppn"
                                        :value="includePPN ? {{ setting('tax_percentage', 11) }} : 0">
                                    <span class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-300">Include PPN
                                        ({{ setting('tax_percentage', 11) }}%)</span>
                                </label>
                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400"
                                    x-text="includePPN ? formatRupiah(calculateTotal().ppnNominal) : 'Rp 0'"></span>
                            </div>

                            <hr class="dark:border-gray-600 my-1">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">Grand Total:</span>
                                <span class="text-lg font-bold text-primary-600 dark:text-primary-400"
                                    x-text="formatRupiah(calculateTotal().total)"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Informasi Tambahan -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 form-card">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Informasi Tambahan
                    </h2>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="input-group">
                            <label for="catatan" class="text-gray-700 dark:text-gray-300">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3" x-model="catatan"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">{{ old('catatan', $salesOrder->catatan) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="input-group">
                                <label for="terms_pembayaran" class="text-gray-700 dark:text-gray-300">Terms
                                    Pembayaran</label>
                                <select name="terms_pembayaran" id="terms_pembayaran"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                    <option value="">Pilih Terms Pembayaran</option>
                                    <option value="COD"
                                        {{ old('terms_pembayaran', $salesOrder->terms_pembayaran) == 'COD' ? 'selected' : '' }}>
                                        COD (Cash On Delivery)</option>
                                    <option value="PIA"
                                        {{ old('terms_pembayaran', $salesOrder->terms_pembayaran) == 'PIA' ? 'selected' : '' }}>
                                        PIA (Payment In Advance)</option>
                                    <option value="Net 7"
                                        {{ old('terms_pembayaran', $salesOrder->terms_pembayaran) == 'Net 7' ? 'selected' : '' }}>
                                        Net 7 (7 hari)</option>
                                    <option value="Net 14"
                                        {{ old('terms_pembayaran', $salesOrder->terms_pembayaran) == 'Net 14' ? 'selected' : '' }}>
                                        Net 14 (14 hari)</option>
                                    <option value="Net 30"
                                        {{ old('terms_pembayaran', $salesOrder->terms_pembayaran) == 'Net 30' ? 'selected' : '' }}>
                                        Net 30 (30 hari)</option>
                                    <option value="Net 60"
                                        {{ old('terms_pembayaran', $salesOrder->terms_pembayaran) == 'Net 60' ? 'selected' : '' }}>
                                        Net 60 (60 hari)</option>
                                    <option value="2/10 Net 30"
                                        {{ old('terms_pembayaran', $salesOrder->terms_pembayaran) == '2/10 Net 30' ? 'selected' : '' }}>
                                        2/10 Net 30 (Diskon 2% jika dibayar dalam 10 hari)</option>
                                    <option value="custom"
                                        {{ old('terms_pembayaran', $salesOrder->terms_pembayaran) == 'custom' ? 'selected' : '' }}>
                                        Kustom</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label for="terms_pembayaran_hari" class="text-gray-700 dark:text-gray-300">Jatuh
                                    Tempo (Hari)</label>
                                <input type="number" name="terms_pembayaran_hari" id="terms_pembayaran_hari"
                                    min="0"
                                    value="{{ old('terms_pembayaran_hari', $salesOrder->terms_pembayaran_hari) }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                <p class="text-xs text-gray-500 mt-1">Masukkan jumlah hari untuk jatuh tempo
                                    pembayaran.</p>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="syarat_ketentuan" class="text-gray-700 dark:text-gray-300">Syarat dan
                                Ketentuan</label>
                            <textarea name="syarat_ketentuan" id="syarat_ketentuan" rows="3" x-model="syarat_ketentuan"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">{{ old('syarat_ketentuan', $salesOrder->syarat_ketentuan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('penjualan.sales-order.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- Bundle Selection Modal -->
        <div x-show="showBundleModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showBundleModal = false">
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                    @click.stop>
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                        Pilih Bundle Produk
                                    </h3>
                                    <button @click="showBundleModal = false" type="button"
                                        class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Search Input -->
                                <div class="mb-4">
                                    <input type="text" x-model="bundleSearch" placeholder="Cari bundle..."
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <!-- Bundle List -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-96 overflow-y-auto">
                                    <template x-for="bundle in filteredBundles" :key="bundle.id">
                                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:shadow-md cursor-pointer transition-shadow"
                                            @click="selectBundle(bundle)">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <h4 class="font-semibold text-gray-900 dark:text-white"
                                                        x-text="bundle.nama"></h4>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400"
                                                        x-text="bundle.kode"></p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-semibold text-primary-600 dark:text-primary-400"
                                                        x-text="formatRupiah(bundle.harga_bundle)"></p>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2"
                                                x-text="bundle.deskripsi"></p>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span x-text="bundle.items?.length || 0"></span> item(s)
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- No Results -->
                                <div x-show="filteredBundles.length === 0"
                                    class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="mt-2">Tidak ada bundle yang ditemukan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="showBundleModal = false" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        // Alpine Store for global access
        document.addEventListener('alpine:init', () => {
            Alpine.store('salesOrderEditForm', {
                // Data
                customer_id: '{{ $salesOrder->customer_id }}',
                tanggal: '{{ Carbon\Carbon::parse($salesOrder->tanggal)->format('Y-m-d') }}',
                tanggal_kirim: '{{ Carbon\Carbon::parse($salesOrder->tanggal_kirim)->format('Y-m-d') }}',
                keterangan: '{{ addslashes($salesOrder->keterangan) }}',
                catatan: '{{ addslashes($salesOrder->catatan) }}',
                alamat_pengiriman: '{{ addslashes($salesOrder->alamat_pengiriman) }}',
                syarat_ketentuan: '{{ addslashes($salesOrder->syarat_ketentuan) }}',
                status_pembayaran: '{{ $salesOrder->status_pembayaran }}',
                status_pengiriman: '{{ $salesOrder->status_pengiriman }}',
                status: '{{ $salesOrder->status }}',

                // Items array
                items: [],

                // Calculations
                diskonGlobalPersen: {{ $salesOrder->diskon_global_persen ?? 0 }},
                diskonGlobalNominal: {{ $salesOrder->diskon_global_nominal ?? 0 }},
                ongkosKirim: {{ $salesOrder->ongkos_kirim ?? 0 }},
                includePPN: {{ $salesOrder->include_ppn ? 'true' : 'false' }},

                // Bundle modal
                showBundleModal: false,
                bundles: [],
                bundleSearch: '',

                // Computed properties
                get filteredBundles() {
                    if (!this.bundleSearch) return this.bundles;
                    return this.bundles.filter(bundle =>
                        bundle.nama.toLowerCase().includes(this.bundleSearch.toLowerCase()) ||
                        bundle.kode.toLowerCase().includes(this.bundleSearch.toLowerCase())
                    );
                },

                init() {
                    this.initEditForm();
                    if (!this._delegatedProdukBound) {
                        $(document).on('change.autofill-delegated',
                            'select[name^="items"][name$="[produk_id]"]', (e) => {
                                const el = e.currentTarget;
                                let idx = el.getAttribute('data-index');
                                if (idx === null) {
                                    const m = el.name.match(/^items\[(\d+)\]\[produk_id\]$/);
                                    if (m) idx = m[1];
                                }
                                idx = parseInt(idx);
                                if (!isNaN(idx)) {
                                    if (this.items[idx]) this.items[idx].produk_id = el.value;
                                    if (typeof this.updateItemDetails === 'function') this
                                        .updateItemDetails(idx);
                                }
                            });
                        this._delegatedProdukBound = true;
                    }
                },

                initEditForm() {
                    // Reset items array to prevent duplicates from previous loads
                    this.items = [];

                    // Load bundles
                    fetch('/penjualan/sales-order/get-bundles')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.bundles = data.bundles;
                            }
                        })
                        .catch(() => {});

                    // Load existing items and process bundles
                    const existingItems = [];
                    const bundleGroups = new Map();

                    @foreach ($salesOrder->details as $index => $detail)
                        const item{{ $index }} = {
                            id: {{ $detail->id }},
                            produk_id: '{{ $detail->produk_id }}',
                            deskripsi: '{{ addslashes($detail->deskripsi) }}',
                            kuantitas: {{ $detail->quantity }},
                            satuan_id: '{{ $detail->satuan_id }}',
                            harga: {{ $detail->harga }},
                            diskon_persen: {{ $detail->diskon_persen }},
                            subtotal: {{ $detail->subtotal }},
                            @if ($detail->bundle_id && $detail->is_bundle_item)
                                bundle_id: {{ $detail->bundle_id }},
                                is_bundle_item: true,
                                is_bundle: false,
                                @if ($detail->produk)
                                    nama: '{{ addslashes($detail->produk->nama) }}',
                                    kode: '{{ $detail->produk->kode }}',
                                    produk_nama: '{{ addslashes($detail->produk->nama) }}',
                                    produk_kode: '{{ $detail->produk->kode }}',
                                @endif
                                @if ($detail->satuan)
                                    satuan_nama: '{{ $detail->satuan->nama }}',
                                @endif
                                @if ($detail->bundle)
                                    bundle_name: '{{ addslashes($detail->bundle->nama) }}',
                                    bundle_code: '{{ $detail->bundle->kode }}',
                                @endif
                                // Clean description from bundle prefix if exists
                                deskripsi_clean: '{{ addslashes(preg_replace('/^└─\s*/', '', preg_replace('/\s*\(dari bundle.*\)$/', '', $detail->deskripsi))) }}',
                            @elseif ($detail->bundle_id && !$detail->is_bundle_item)
                                bundle_id: {{ $detail->bundle_id }},
                                    is_bundle: true,
                                    is_bundle_item: false,
                                    @if ($detail->bundle)
                                        bundle_name: '{{ addslashes($detail->bundle->nama) }}',
                                        bundle_code: '{{ $detail->bundle->kode }}',
                                    @endif
                            @else
                                is_bundle: false,
                                is_bundle_item: false,
                                @if ($detail->produk)
                                    produk_nama: '{{ addslashes($detail->produk->nama) }}',
                                    produk_kode: '{{ $detail->produk->kode }}',
                                @endif
                                @if ($detail->satuan)
                                    satuan_nama: '{{ $detail->satuan->nama }}',
                                @endif
                            @endif
                        };

                        existingItems.push(item{{ $index }});
                    @endforeach

                    // Separate bundle items and regular items
                    const bundleItems = [];
                    const regularItems = [];
                    const bundleHeaders = [];

                    existingItems.forEach(item => {
                        if (item.bundle_id && item.is_bundle_item) {
                            bundleItems.push(item);
                        } else if (item.bundle_id && item.is_bundle && !item.is_bundle_item) {
                            bundleHeaders.push(item);
                        } else {
                            regularItems.push(item);
                        }
                    });

                    // Process bundle groups - create headers for orphaned bundle items
                    bundleItems.forEach(item => {
                        if (!bundleGroups.has(item.bundle_id)) {
                            bundleGroups.set(item.bundle_id, {
                                bundle_id: item.bundle_id,
                                bundle_name: item.bundle_name,
                                bundle_code: item.bundle_code,
                                items: [],
                                bundleQuantity: 1
                            });
                        }
                        bundleGroups.get(item.bundle_id).items.push(item);
                    });

                    // Calculate bundle quantity - find the GCD of all quantities to determine how many complete bundles
                    bundleGroups.forEach((bundleGroup, bundleId) => {
                        if (bundleGroup.items.length > 1) {
                            // For multiple items, assume bundle quantity is the minimum quantity
                            // (assuming equal quantities per bundle for each item)
                            const quantities = bundleGroup.items.map(item => item.kuantitas);
                            bundleGroup.bundleQuantity = Math.min(...quantities);

                            // Adjust individual item quantities to be per-bundle basis
                            bundleGroup.items.forEach(item => {
                                item.base_quantity = item.kuantitas / bundleGroup
                                    .bundleQuantity;
                            });
                        } else if (bundleGroup.items.length === 1) {
                            // Single item bundle
                            bundleGroup.bundleQuantity = bundleGroup.items[0].kuantitas;
                            bundleGroup.items[0].base_quantity = 1;
                        }
                    });

                    // Add items in correct order: bundle header + bundle items + regular items
                    bundleGroups.forEach((bundleGroup, bundleId) => {
                        // Check if bundle header already exists
                        const existingHeader = bundleHeaders.find(h => h.bundle_id === bundleId);

                        if (existingHeader) {
                            // Use existing header
                            this.items.push(existingHeader);
                        } else if (bundleGroup.items.length > 0) {
                            // Create virtual bundle header
                            const totalBundlePrice = bundleGroup.items.reduce((sum, item) =>
                                sum + (item.harga * item.base_quantity), 0
                            );
                            const bundleHeader = {
                                id: 'bundle_' + bundleId,
                                bundle_id: bundleId,
                                is_bundle: true,
                                is_bundle_item: false,
                                bundle_name: bundleGroup.bundle_name,
                                bundle_code: bundleGroup.bundle_code,
                                deskripsi: bundleGroup.bundle_name,
                                kuantitas: bundleGroup.bundleQuantity,
                                harga: totalBundlePrice,
                                subtotal: bundleGroup.items.reduce((sum, item) => sum + item
                                    .subtotal, 0),
                                diskon_persen: 0
                            };
                            this.items.push(bundleHeader);
                        }

                        // Add bundle items
                        bundleGroup.items.forEach(item => {
                            this.items.push(item);
                        });
                    });

                    // Add regular items
                    regularItems.forEach(item => {
                        this.items.push(item);
                    });
                    this.calculateTotal();

                    // Initialize Select2 for existing items after DOM is ready
                    Alpine.nextTick(() => {
                        setTimeout(() => {
                            this.initializeSelectsForExistingItems();
                        }, 200);
                    });
                },

                initializeSelectsForExistingItems() {

                    // Initialize Select2 for all regular items (not bundle items)
                    this.items.forEach((item, index) => {
                        if (!item.is_bundle && !item.is_bundle_item) {
                            const produkSelect = $(`select[name="items[${index}][produk_id]"]`);
                            const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);


                            if (produkSelect.length > 0) {

                                // Ensure data-index attribute is set early
                                produkSelect.attr('data-index', index);

                                // Rebuild if already initialized
                                if (produkSelect.hasClass('select2-hidden-accessible')) {
                                    produkSelect.select2('destroy');
                                }


                                // Initialize Select2 and bridge events
                                produkSelect
                                    .off('select2:select.autofill change.autofill')
                                    .select2({
                                        placeholder: 'Pilih Produk',
                                        allowClear: true,
                                        width: '100%'
                                    })
                                    .on('select2:select.autofill', function() {
                                        // Bridge to native change which triggers delegated + .on('change.autofill')
                                        $(this).trigger('change');
                                    })
                                    .on('change.autofill', function() {
                                        const selectedProdukId = $(this).val();
                                        const nameAttr = this.getAttribute('name') || '';
                                        let currentIndex = -1;
                                        const m = nameAttr.match(
                                            /^items\[(\d+)\]\[produk_id\]$/);
                                        if (m) currentIndex = parseInt(m[1]);
                                        if (selectedProdukId && !isNaN(currentIndex)) {
                                            const selectedOption = $(this).find(
                                                'option:selected');
                                            const harga = selectedOption.data('harga');
                                            const satuanId = selectedOption.data('satuan_id');
                                            const store = Alpine.store('salesOrderEditForm');
                                            if (store && store.items[currentIndex]) {
                                                store.items[currentIndex].harga = parseFloat(
                                                    harga) || 0;
                                                store.items[currentIndex].satuan_id = satuanId ?
                                                    parseInt(satuanId) : '';
                                                $(`input[name="items[${currentIndex}][harga]"]`)
                                                    .val(store.items[currentIndex].harga);
                                                const satuanSelectEl = $(
                                                    `select[name="items[${currentIndex}][satuan_id]"]`
                                                );
                                                if (satuanSelectEl.length && store.items[
                                                        currentIndex].satuan_id) {
                                                    satuanSelectEl.val(store.items[currentIndex]
                                                        .satuan_id).trigger('change');
                                                }
                                                store.updateSubtotal(currentIndex);
                                            }
                                        }
                                    });

                                // Prime value
                                if (item.produk_id) {
                                    // Set value without firing change immediately (avoids stale async events)
                                    produkSelect.val(item.produk_id);
                                    // Safely call updater after current tick
                                    const nameAttr = produkSelect.attr('name') || '';
                                    const match = nameAttr.match(/^items\[(\d+)\]\[produk_id\]$/);
                                    if (match) {
                                        const derivedIndex = parseInt(match[1]);
                                        if (!isNaN(derivedIndex)) {
                                            // Use next microtask to ensure model exists
                                            setTimeout(() => {
                                                const store = Alpine.store(
                                                    'salesOrderEditForm');
                                                if (store && store.items[derivedIndex]) {
                                                    store.updateItemDetails(derivedIndex);
                                                }
                                            }, 0);
                                        }
                                    }
                                }
                            }

                            if (satuanSelect.length > 0) {
                                if (satuanSelect.hasClass('select2-hidden-accessible')) {
                                    satuanSelect.select2('destroy');
                                }

                                satuanSelect.select2({
                                    placeholder: 'Pilih Satuan',
                                    allowClear: true,
                                    width: '100%'
                                });

                                // Set the current value if it exists
                                if (item.satuan_id) {
                                    satuanSelect.val(item.satuan_id).trigger('change');
                                }
                            }
                        }
                    });
                },

                addItem() {
                    const itemId = Date.now();

                    this.items.push({
                        id: itemId,
                        produk_id: '',
                        deskripsi: '',
                        kuantitas: 1,
                        satuan_id: '',
                        harga: 0,
                        diskon_persen: 0,
                        subtotal: 0
                    });

                    Alpine.nextTick(() => {
                        setTimeout(() => {
                            const newIndex = this.items.length - 1;
                            const produkSelect = $(
                                `select[name="items[${newIndex}][produk_id]"]`);
                            const satuanSelect = $(
                                `select[name="items[${newIndex}][satuan_id]"]`);

                            if (produkSelect.length > 0) {
                                if (produkSelect.hasClass('select2-hidden-accessible')) {
                                    produkSelect.select2('destroy');
                                }

                                produkSelect.select2({
                                        placeholder: 'Pilih Produk',
                                        allowClear: true,
                                        width: '100%'
                                    })
                                    .off('select2:select.autofill-new change.autofill-new')
                                    .on('select2:select.autofill-new', function() {
                                        $(this).trigger('change');
                                    })
                                    .on('change.autofill-new', function() {
                                        const selectedProdukId = $(this).val();
                                        const nameAttr = this.getAttribute('name') ||
                                            '';
                                        let currentIndex = -1;
                                        const m = nameAttr.match(
                                            /^items\[(\d+)\]\[produk_id\]$/);
                                        if (m) currentIndex = parseInt(m[1]);
                                        if (selectedProdukId && currentIndex > -1) {
                                            const selectedOption = $(this).find(
                                                'option:selected');
                                            const harga = selectedOption.data('harga');
                                            const satuanId = selectedOption.data(
                                                'satuan_id');
                                            const store = Alpine.store(
                                                'salesOrderEditForm');
                                            if (store && store.items[currentIndex]) {
                                                store.items[currentIndex].harga =
                                                    parseFloat(harga) || 0;
                                                store.items[currentIndex].satuan_id =
                                                    satuanId ? parseInt(satuanId) : '';
                                                $(`input[name="items[${currentIndex}][harga]"]`)
                                                    .val(store.items[currentIndex]
                                                        .harga);
                                                const satuanSelect = $(
                                                    `select[name="items[${currentIndex}][satuan_id]"]`
                                                );
                                                if (satuanSelect.length && store.items[
                                                        currentIndex].satuan_id) {
                                                    satuanSelect.val(store.items[
                                                            currentIndex].satuan_id)
                                                        .trigger('change');
                                                }
                                                store.updateSubtotal(currentIndex);
                                            }
                                        }
                                    });
                            }

                            if (satuanSelect.length > 0) {
                                if (satuanSelect.hasClass('select2-hidden-accessible')) {
                                    satuanSelect.select2('destroy');
                                }

                                satuanSelect.select2({
                                    placeholder: 'Pilih Satuan',
                                    allowClear: true,
                                    width: '100%'
                                });
                            }
                        }, 100);
                    });
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calculateTotal();
                    // Re-initialize selects to sync indices and prevent stale events
                    Alpine.nextTick(() => {
                        setTimeout(() => {
                            this.initializeSelectsForExistingItems();
                        }, 30);
                    });
                },

                updateItemCalculation(index) {
                    const item = this.items[index];
                    if (item) {
                        item.subtotal = (item.harga * item.kuantitas) - ((item.diskon_persen / 100) * (item
                            .harga * item
                            .kuantitas));
                        this.calculateTotal();
                    }
                },

                updateSubtotal(index) {
                    this.updateItemCalculation(index);
                },

                updateItemDetails(index) {
                    // Defensive guards (silent in production)
                    if (index === undefined || index === null) return;
                    if (!Array.isArray(this.items)) return;
                    if (index < 0 || index >= this.items.length) return;
                    if (!this.items[index]) return;

                    const produkId = this.items[index].produk_id;
                    const self = this;

                    if (!produkId) {
                        self.items[index].satuan_id = '';
                        self.items[index].harga = 0;
                        self.updateSubtotal(index);
                        return;
                    }

                    // Get product data from the selected option's data attributes FIRST
                    try {
                        const produkSelect = $(`select[name="items[${index}][produk_id]"]`);
                        let selectedOption;

                        // Check if it's a Select2 element or regular select
                        if (produkSelect.hasClass('select2-hidden-accessible')) {
                            // For Select2, use the val() to get the value and find the option
                            const selectedValue = produkSelect.val();
                            selectedOption = produkSelect.find(`option[value="${selectedValue}"]`);
                        } else {
                            // For regular select, use find :selected
                            selectedOption = produkSelect.find('option:selected');
                        }


                        if (selectedOption && selectedOption.length) {
                            const hargaAttr = selectedOption.attr('data-harga');
                            const satuanIdAttr = selectedOption.attr('data-satuan_id');


                            if (hargaAttr) {
                                self.items[index].harga = parseFloat(hargaAttr) || 0;
                                // Directly set DOM value too
                                $(`input[name="items[${index}][harga]"]`).val(self.items[index].harga);
                            }

                            if (satuanIdAttr) {
                                self.items[index].satuan_id = satuanIdAttr;

                                // Update satuan select
                                const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
                                satuanSelect.val(self.items[index].satuan_id);

                                // Trigger change for both regular select and Select2
                                if (satuanSelect.hasClass('select2-hidden-accessible')) {
                                    setTimeout(() => {
                                        satuanSelect.trigger('change.select2');
                                    }, 50);
                                } else {
                                    satuanSelect.trigger('change');
                                }
                            }

                            self.updateSubtotal(index);
                        }
                    } catch (e) {}

                    // Fallback to API call if data attributes didn't work
                    fetch(`{{ url('api/products') }}/${produkId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data) {
                                const product = data.data;

                                // Set the description if it's empty
                                if (!self.items[index].deskripsi) {
                                    self.items[index].deskripsi = product.nama;
                                }

                                // Only update price and satuan if they weren't already set from data attributes
                                if (!self.items[index].harga || self.items[index].harga === 0) {
                                    self.items[index].harga = parseFloat(product.harga_jual) || 0;
                                    $(`input[name="items[${index}][harga]"]`).val(self.items[index]
                                        .harga);
                                }

                                if (!self.items[index].satuan_id) {
                                    self.items[index].satuan_id = product.satuan_id ? product
                                        .satuan_id : '';
                                    const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
                                    if (satuanSelect.length && self.items[index].satuan_id) {
                                        satuanSelect.val(self.items[index].satuan_id);
                                        if (satuanSelect.hasClass('select2-hidden-accessible')) {
                                            satuanSelect.trigger('change.select2');
                                        } else {
                                            satuanSelect.trigger('change');
                                        }
                                    }
                                }

                                // Always update subtotal
                                self.updateSubtotal(index);
                            }
                        })
                        .catch(() => {});
                },

                calculateSubtotal() {
                    return this.items.reduce((total, item) => {
                        // Only count bundle headers and regular items, skip bundle items to avoid double counting
                        if (item.is_bundle_item) {
                            return total; // Skip bundle items as they are already included in bundle price
                        }
                        return total + (parseFloat(item.subtotal) || 0);
                    }, 0);
                },

                refreshItemSelects() {
                    const self = this; // Ensure 'this' is correct for forEach

                    // Wait for Alpine's next render cycle to complete
                    Alpine.nextTick(() => {
                        // Use a longer delay to ensure DOM is fully rendered
                        setTimeout(() => {
                            // Process each item individually
                            self.items.forEach((item, index) => {
                                // Handle product select
                                const produkSelect = $(
                                    `select[name="items[${index}][produk_id]"]`);
                                if (produkSelect.length) {
                                    // Check if already initialized
                                    if (produkSelect.hasClass(
                                            'select2-hidden-accessible')) {
                                        produkSelect.select2('destroy');
                                    }


                                    // Initialize Select2
                                    produkSelect.select2({
                                        placeholder: 'Pilih Produk',
                                        allowClear: true,
                                        width: '100%'
                                    });

                                    // Remove any existing event handlers first
                                    produkSelect.off('change.autofill');

                                    // Add change event handler with namespace
                                    produkSelect.on('change.autofill', function() {
                                        const selectedProdukId = $(this)
                                            .val();
                                        const currentIndex =
                                            index; // Use the forEach index


                                        if (selectedProdukId) {
                                            const selectedOption = $(this)
                                                .find('option:selected');
                                            const harga = selectedOption
                                                .data('harga');
                                            const satuanId = selectedOption
                                                .data('satuan_id');

                                            // Directly update Alpine store
                                            self.items[currentIndex].harga =
                                                parseFloat(harga) || 0;
                                            self.items[currentIndex]
                                                .satuan_id = satuanId ?
                                                parseInt(satuanId) : '';

                                            // Directly update DOM elements
                                            $(`input[name="items[${currentIndex}][harga]"]`)
                                                .val(self.items[
                                                    currentIndex].harga);

                                            // Update satuan select
                                            const satuanSelect = $(
                                                `select[name="items[${currentIndex}][satuan_id]"]`
                                            );
                                            if (satuanSelect.length && self
                                                .items[currentIndex]
                                                .satuan_id) {
                                                satuanSelect.val(self.items[
                                                        currentIndex]
                                                    .satuan_id).trigger(
                                                    'change');
                                            }

                                            // Update subtotal
                                            self.updateSubtotal(
                                                currentIndex);
                                        }
                                    });

                                    // Set value if it exists
                                    if (item.produk_id) {
                                        produkSelect.val(item.produk_id).trigger(
                                            'change.select2');
                                    }
                                }

                                // Handle unit select
                                const satuanSelect = $(
                                    `select[name="items[${index}][satuan_id]"]`);
                                if (satuanSelect.length) {
                                    if (satuanSelect.hasClass(
                                            'select2-hidden-accessible')) {
                                        satuanSelect.select2('destroy');
                                    }

                                    satuanSelect.select2({
                                        placeholder: 'Pilih Satuan',
                                        allowClear: true,
                                        width: '100%'
                                    });
                                }
                            });
                        }, 200);
                    });
                },

                calculateTotal() {
                    const subtotal = this.items.reduce((total, item) => {
                        if (item.is_bundle_item) {
                            return total;
                        }
                        return total + (parseFloat(item.subtotal) || 0);
                    }, 0);

                    let diskonGlobalNominal = parseFloat(this.diskonGlobalNominal) || 0;

                    if (parseFloat(this.diskonGlobalPersen) > 0) {
                        diskonGlobalNominal = (parseFloat(this.diskonGlobalPersen) / 100) * subtotal;
                        this.diskonGlobalNominal = diskonGlobalNominal;
                    }

                    const afterDiscount = subtotal - diskonGlobalNominal;
                    const ongkosKirimValue = parseFloat(this.ongkosKirim) || 0;
                    const ppnNominal = this.includePPN ? (11 / 100) * afterDiscount : 0;
                    const total = afterDiscount + ppnNominal + ongkosKirimValue;

                    return {
                        subtotal,
                        afterGlobalDiscount: afterDiscount,
                        diskonGlobalNominal,
                        ppnNominal,
                        ongkosKirim: ongkosKirimValue,
                        total
                    };
                },

                updateBundleCalculations(index) {
                    const item = this.items[index];
                    if (item && item.is_bundle) {
                        item.subtotal = (parseFloat(item.harga) || 0) * (parseFloat(item.kuantitas) || 1);

                        const bundleId = item.bundle_id;
                        const bundleQuantity = parseFloat(item.kuantitas) || 1;

                        this.items.forEach((bundleItem, itemIndex) => {
                            if (bundleItem.is_bundle_item && bundleItem.bundle_id === bundleId) {
                                const baseQuantity = parseFloat(bundleItem.base_quantity) ||
                                    parseFloat(bundleItem
                                        .kuantitas) || 1;
                                bundleItem.kuantitas = baseQuantity * bundleQuantity;
                                bundleItem.subtotal = (parseFloat(bundleItem.harga) || 0) *
                                    bundleItem.kuantitas;
                            }
                        });

                        this.calculateTotal();
                    }
                },

                selectBundle(bundle) {
                    this.showBundleModal = false;
                    this.bundleSearch = '';

                    // Check if bundle already exists
                    const existingBundleIndex = this.items.findIndex(item =>
                        item.is_bundle && item.bundle_id === bundle.id
                    );

                    if (existingBundleIndex !== -1) {
                        // Bundle already exists, increase quantity
                        this.items[existingBundleIndex].kuantitas += 1;
                        this.updateBundleCalculations(existingBundleIndex);

                        // Show notification
                        if (typeof window.notify === 'function') {
                            window.notify(
                                `Kuantitas bundle "${bundle.nama}" ditambah menjadi ${this.items[existingBundleIndex].kuantitas}`,
                                'success', 'Bundle Updated');
                        }
                    } else {
                        // Bundle doesn't exist, add new bundle
                        this.addBundleToItems(bundle);
                    }
                },

                async addBundleToItems(bundle) {
                    try {
                        if (!bundle.id) {
                            throw new Error('Bundle ID is missing');
                        }

                        // Show loading notification if available
                        if (typeof window.notify === 'function') {
                            window.notify('Memuat data bundle...', 'info', 'Memproses');
                        }

                        const response = await fetch(
                            `/penjualan/sales-order/get-bundle-data/${bundle.id}`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]')?.getAttribute('content') ||
                                        ''
                                }
                            });

                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }

                        const data = await response.json();
                        if (!data.success) {
                            throw new Error(data.message || 'Failed to get bundle data');
                        }

                        await this.processBundleData(data.bundle, data.items);

                    } catch (error) {
                        console.error('Error adding bundle:', error);
                        if (typeof window.notify === 'function') {
                            window.notify('Gagal menambahkan bundle: ' + error.message, 'error',
                                'Error');
                        } else {
                            alert('Gagal menambahkan bundle: ' + error.message);
                        }
                    }
                },

                async processBundleData(bundleData, bundleItems) {
                    try {
                        // Get bundle discount
                        const bundleDiskonPersen = parseFloat(bundleData.diskon_persen || 0);

                        // Bundle header shows original price without discount applied
                        const bundleSubtotalBefore = parseFloat(bundleData.harga_bundle || 0);
                        const bundleSubtotal = bundleSubtotalBefore; // Header tidak dikurangi diskon

                        // Add bundle as main item with bundle type
                        const bundleMainItem = {
                            id: Date.now(),
                            produk_id: '', // Bundle doesn't have single product ID
                            bundle_id: bundleData.id,
                            item_type: 'bundle',
                            deskripsi: `Bundle: ${bundleData.nama}`,
                            kuantitas: 1,
                            satuan_id: '',
                            harga: bundleSubtotalBefore,
                            diskon_persen: 0, // Header tidak ada diskon
                            subtotal: bundleSubtotal, // Header subtotal tanpa diskon
                            is_bundle: true,
                            is_bundle_item: false,
                            bundle_name: bundleData.nama,
                            bundle_code: bundleData.kode
                        };

                        this.items.push(bundleMainItem);

                        // Add individual bundle items immediately after the header
                        bundleItems.forEach((item) => {
                            // Apply bundle discount to each child item
                            const itemSubtotalBefore = (item.harga_satuan || 0) * item.quantity;
                            const itemDiskonNominal = (itemSubtotalBefore *
                                bundleDiskonPersen) / 100;
                            const itemSubtotal = itemSubtotalBefore - itemDiskonNominal;

                            const childItem = {
                                id: Date.now() + Math.random(),
                                produk_id: item.produk_id,
                                bundle_id: bundleData.id,
                                item_type: 'bundle_item',
                                parent_bundle_name: bundleData.nama,
                                kode: item.produk_kode || '',
                                nama: item.produk_nama || '',
                                satuan_nama: item.satuan_nama || '',
                                deskripsi: `└─ ${item.produk_nama} (dari bundle ${bundleData.nama})`,
                                kuantitas: item.quantity,
                                base_quantity: item
                                    .quantity, // Store original quantity for calculation
                                satuan_id: item.satuan_id,
                                harga: item.harga_satuan,
                                diskon_persen: bundleDiskonPersen, // Apply bundle discount
                                subtotal: itemSubtotal, // Calculated with discount
                                is_bundle: false,
                                is_bundle_item: true,
                                readonly: true // Make it read-only to prevent editing
                            };

                            this.items.push(childItem);
                        });

                        // Reorganize items to group bundles properly
                        this.reorganizeItems();

                        // Calculate totals
                        this.calculateTotal();

                        // Show success notification
                        if (typeof window.notify === 'function') {
                            window.notify(`Bundle "${bundleData.nama}" berhasil ditambahkan`, 'success',
                                'Bundle Added');
                        }

                    } catch (error) {
                        console.error('Error processing bundle data:', error);
                        throw error;
                    }
                },

                reorganizeItems() {
                    // Separate regular items and bundle groups
                    const regularItems = [];
                    const bundleGroups = new Map();

                    // Categorize all items
                    this.items.forEach(item => {
                        if (item.is_bundle) {
                            // Bundle header
                            if (!bundleGroups.has(item.bundle_id)) {
                                bundleGroups.set(item.bundle_id, {
                                    header: null,
                                    children: []
                                });
                            }
                            bundleGroups.get(item.bundle_id).header = item;
                        } else if (item.is_bundle_item) {
                            // Bundle child item
                            if (!bundleGroups.has(item.bundle_id)) {
                                bundleGroups.set(item.bundle_id, {
                                    header: null,
                                    children: []
                                });
                            }
                            bundleGroups.get(item.bundle_id).children.push(item);
                        } else {
                            // Regular item
                            regularItems.push(item);
                        }
                    });

                    // Rebuild items array: regular items first, then bundle groups
                    const reorganizedItems = [...regularItems];

                    // Add bundle groups (header followed by children)
                    bundleGroups.forEach(group => {
                        if (group.header) {
                            reorganizedItems.push(group.header);
                            reorganizedItems.push(...group.children);
                        }
                    });

                    // Update the items array
                    this.items = reorganizedItems;
                },

                updateBundleCalculations(bundleIndex) {
                    const bundleHeader = this.items[bundleIndex];
                    if (!bundleHeader.is_bundle) return;

                    // Update bundle children quantities based on bundle header quantity
                    const bundleItems = this.items.filter(item =>
                        item.is_bundle_item && item.bundle_id === bundleHeader.bundle_id
                    );

                    bundleItems.forEach(item => {
                        // Update quantity based on bundle quantity and base quantity
                        item.kuantitas = item.base_quantity * bundleHeader.kuantitas;

                        // Recalculate subtotal
                        const itemSubtotalBefore = item.harga * item.kuantitas;
                        const itemDiskonNominal = (itemSubtotalBefore * item.diskon_persen) / 100;
                        item.subtotal = itemSubtotalBefore - itemDiskonNominal;
                    });

                    // Update bundle header subtotal
                    const bundleSubtotalBefore = bundleHeader.harga * bundleHeader.kuantitas;
                    bundleHeader.subtotal = bundleSubtotalBefore;

                    this.calculateTotal();
                },

                removeItem(index) {
                    const item = this.items[index];

                    // If removing a bundle header, also remove all its bundle items
                    if (item.is_bundle && item.bundle_id) {
                        // Find all bundle items with the same bundle_id
                        const bundleItemsToRemove = [];
                        for (let i = this.items.length - 1; i >= 0; i--) {
                            const currentItem = this.items[i];
                            if (currentItem.bundle_id === item.bundle_id) {
                                bundleItemsToRemove.push(i);
                            }
                        }

                        // Remove all bundle items (including the header) in reverse order to maintain indices
                        bundleItemsToRemove.sort((a, b) => b - a); // Sort descending
                        bundleItemsToRemove.forEach(itemIndex => {
                            this.items.splice(itemIndex, 1);
                        });
                    } else {
                        // Regular item removal
                        this.items.splice(index, 1);
                    }

                    this.calculateTotal();
                },

                updateDiskonGlobal(type) {
                    const subtotal = this.calculateSubtotal();

                    if (type === 'persen') {
                        let persen = parseFloat(this.diskonGlobalPersen) || 0;
                        if (persen < 0) persen = 0;
                        if (persen > 100) persen = 100;
                        this.diskonGlobalPersen = persen;
                        this.diskonGlobalNominal = (subtotal * persen) / 100;
                    } else if (type === 'nominal') {
                        let nominal = parseFloat(this.diskonGlobalNominal) || 0;
                        if (nominal < 0) nominal = 0;
                        if (nominal > subtotal) nominal = subtotal;
                        this.diskonGlobalNominal = nominal;
                        this.diskonGlobalPersen = subtotal > 0 ? (nominal / subtotal) * 100 : 0;
                    }
                },

                formatRupiah(amount) {
                    // Round to nearest integer to avoid decimal places
                    const roundedAmount = Math.round(amount || 0);
                    const formatter = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });

                    return formatter.format(roundedAmount);
                },

                validateForm(event) {
                    // Check if there are any items (bundle or regular)
                    if (this.items.length === 0) {
                        event.preventDefault();
                        alert('Harap tambahkan minimal satu item atau bundle untuk melanjutkan!');
                        return false;
                    }

                    // Check if customer is selected
                    if (!this.customer_id) {
                        event.preventDefault();
                        alert('Harap pilih customer terlebih dahulu!');
                        return false;
                    }

                    // Validate each item
                    for (let i = 0; i < this.items.length; i++) {
                        const item = this.items[i];

                        // Skip validation for bundle items (they have different requirements)
                        if (item.is_bundle_item) {
                            continue;
                        }

                        // For regular items and bundle headers, validate required fields
                        if (!item.produk_id && !item.is_bundle) {
                            event.preventDefault();
                            alert(`Item ${i + 1}: Harap pilih produk!`);
                            return false;
                        }

                        if (!item.satuan_id && !item.is_bundle) {
                            event.preventDefault();
                            alert(`Item ${i + 1}: Harap pilih satuan!`);
                            return false;
                        }

                        if (!item.kuantitas || item.kuantitas <= 0) {
                            event.preventDefault();
                            alert(`Item ${i + 1}: Kuantitas harus lebih dari 0!`);
                            return false;
                        }

                        if (!item.harga || item.harga < 0) {
                            event.preventDefault();
                            alert(`Item ${i + 1}: Harga tidak boleh kosong atau negatif!`);
                            return false;
                        }
                    }

                    return true;
                }
            });
        });

        // Function for template compatibility  
        function salesOrderEditForm() {
            return Alpine.store('salesOrderEditForm');
        }
    </script>

    @push('scripts')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                // Handle terms pembayaran changes
                $('#terms_pembayaran').change(function() {
                    const selectedTerms = $(this).val();
                    let days = 0;

                    switch (selectedTerms) {
                        case 'COD':
                            days = 0;
                            break;
                        case 'PIA':
                            days = 0;
                            break;
                        case 'Net 7':
                            days = 7;
                            break;
                        case 'Net 14':
                            days = 14;
                            break;
                        case 'Net 30':
                            days = 30;
                            break;
                        case 'Net 60':
                            days = 60;
                            break;
                        case '2/10 Net 30':
                            days = 30;
                            break;
                    }

                    if (days > 0 || selectedTerms === '') {
                        $('#terms_pembayaran_hari').val(days);
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
