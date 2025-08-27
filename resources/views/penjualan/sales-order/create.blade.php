<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Sales Order', 'url' => route('penjualan.sales-order.index')],
    ['label' => 'Tambah'],
]" :currentPage="'Tambah Sales Order'">
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
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.05);
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

    <div class="py-8 max-w-full mx-auto sm:px-6 lg:px-8">
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
                            Tambah Sales Order
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Buat sales order baru untuk pelanggan.
                        </p>
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

        <form id="salesOrderForm" action="{{ route('penjualan.sales-order.store') }}" method="POST"
            x-data="salesOrderForm()" x-init="init()" @submit="validateForm($event)">
            @csrf

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
                            <input type="text" name="nomor" id="nomor" value="{{ old('nomor', $nomor) }}"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                        </div>
                        <div class="input-group">
                            <label for="nomor_po" class="text-gray-700 dark:text-gray-300">Nomor PO Customer</label>
                            <input type="text" name="nomor_po" id="nomor_po" value="{{ old('nomor_po') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                        </div>
                        <div class="input-group">
                            <label for="tanggal" class="text-gray-700 dark:text-gray-300">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $tanggal) }}"
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
                                @if ($quotation)
                                    <option value="{{ $quotation->id }}" selected>{{ $quotation->nomor }} -
                                        {{ $quotation->customer->nama ?? $quotation->customer->company }}</option>
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
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->company ? $customer->company . ' - ' . $customer->nama : $customer->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="tanggal_kirim" class="text-gray-700 dark:text-gray-300">Tanggal Kirim</label>
                            <input type="date" name="tanggal_kirim" id="tanggal_kirim"
                                value="{{ old('tanggal_kirim', $tanggal_kirim) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                        </div>

                        <div class="input-group">
                            <label for="status_pembayaran" class="text-gray-700 dark:text-gray-300">Status Pembayaran
                                <span class="text-red-500">*</span></label>
                            <select name="status_pembayaran" id="status_pembayaran" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                @foreach ($status_pembayaran as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status_pembayaran') == $key ? 'selected' : ($key == 'belum_bayar' ? 'selected' : '') }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="status_pengiriman" class="text-gray-700 dark:text-gray-300">Status Pengiriman
                                <span class="text-red-500">*</span></label>
                            <select name="status_pengiriman" id="status_pengiriman" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                @foreach ($status_pengiriman as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status_pengiriman') == $key ? 'selected' : ($key == 'belum_dikirim' ? 'selected' : '') }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-group md:col-span-2">
                            <label for="alamat_pengiriman" class="text-gray-700 dark:text-gray-300">Alamat
                                Pengiriman</label>
                            <textarea name="alamat_pengiriman" id="alamat_pengiriman" rows="3" x-model="alamat_pengiriman"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">{{ old('alamat_pengiriman') }}</textarea>
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
                        <div class="flex items-center gap-2">
                            <button type="button" @click="showBundleModal = true"
                                class="group inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 rounded-lg text-sm font-medium text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Tambah Bundle
                            </button>
                            <button type="button" @click="addItem"
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
                            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                                <button type="button" @click="showBundleModal = true"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    Tambah Bundle Produk
                                </button>
                                <button type="button" @click="addItem"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Item Individual
                                </button>
                            </div>
                        </div>

                        <div x-show="items.length > 0" class="overflow-x-auto">
                            <div class="space-y-4 mt-4" id="items-container">
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="relative">
                                        <!-- BUNDLE HEADER - Simple & Clean -->
                                        <div x-show="item.is_bundle"
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
                                            <input type="hidden" :name="`items[${index}][is_bundle]`" value="1">
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
                                        <!-- BUNDLE ITEM - Simple & Clean -->
                                        <div x-show="item.is_bundle_item"
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

                                        <!-- REGULAR ITEM DESIGN -->
                                        <div x-show="!item.is_bundle && !item.is_bundle_item"
                                            class="border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-200">

                                            <!-- Header -->
                                            <div
                                                class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                                                <div class="flex items-center space-x-3">
                                                    <span
                                                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/50 text-sm font-bold text-primary-800 dark:text-primary-300"
                                                        x-text="index + 1"></span>
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        Item Detail</h3>
                                                </div>
                                                <button type="button" @click="removeItem(index)"
                                                    class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Content -->
                                            <div class="p-4 space-y-4">
                                                <!-- Product Selection -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                            Produk <span class="text-red-500">*</span>
                                                        </label>
                                                        <select :name="`items[${index}][produk_id]`"
                                                            x-model="item.produk_id"
                                                            @change="updateItemDetails(index)"
                                                            :required="!item.is_bundle && !item.is_bundle_item"
                                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                                            <option value="">Pilih Produk</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    :data-harga="{{ $product->harga_jual ?? 0 }}"
                                                                    :data-satuan_id="{{ $product->satuan_id ?? '' }}">
                                                                    {{ $product->kode }} - {{ $product->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                            Deskripsi Item
                                                        </label>
                                                        <textarea :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" rows="2"
                                                            placeholder="Deskripsi tambahan..."
                                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white resize-none"></textarea>
                                                    </div>
                                                </div>

                                                <!-- Pricing Info -->
                                                <div
                                                    class="grid grid-cols-2 md:grid-cols-5 gap-3 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                                                    <div>
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                            Qty <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="number" step="any" min="0.01"
                                                            :name="`items[${index}][kuantitas]`"
                                                            x-model.number="item.kuantitas"
                                                            @input="updateSubtotal(index)"
                                                            :required="!item.is_bundle && !item.is_bundle_item"
                                                            class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:text-white text-center">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                            Unit <span class="text-red-500">*</span>
                                                        </label>
                                                        <select :name="`items[${index}][satuan_id]`"
                                                            x-model="item.satuan_id"
                                                            :required="!item.is_bundle && !item.is_bundle_item"
                                                            class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                                                            <option value="">Pilih</option>
                                                            @foreach ($satuans as $satuan)
                                                                <option value="{{ $satuan->id }}">
                                                                    {{ $satuan->nama }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                            Price <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="number" step="any"
                                                            :name="`items[${index}][harga]`"
                                                            x-model.number="item.harga" @input="updateSubtotal(index)"
                                                            :required="!item.is_bundle && !item.is_bundle_item"
                                                            class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                                                    </div>
                                                    <div x-show="!item.is_bundle_item">
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                            Disc (%)
                                                        </label>
                                                        <input type="number" step="any" min="0"
                                                            max="100" :name="`items[${index}][diskon_persen]`"
                                                            x-model.number="item.diskon_persen"
                                                            @input="updateSubtotal(index)" placeholder="0"
                                                            class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-1 focus:ring-primary-500 dark:bg-gray-700 dark:text-white text-center">
                                                    </div>
                                                    <!-- Bundle item spacer for discount column -->
                                                    <div x-show="item.is_bundle_item">
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                            Disc (%)
                                                        </label>
                                                        <div
                                                            class="w-full px-2 py-1 text-sm bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded text-center">
                                                            <span
                                                                x-text="item.diskon_persen > 0 ? item.diskon_persen + '%' : '-'"></span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                            Subtotal
                                                        </label>
                                                        <div
                                                            class="px-2 py-1 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-700 rounded text-sm font-bold text-primary-700 dark:text-primary-300 text-center">
                                                            <span x-text="formatRupiah(item.subtotal)"></span>
                                                        </div>
                                                        <input type="hidden" :name="`items[${index}][subtotal]`"
                                                            :value="item.subtotal">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 gap-6 pb-3">
                                        <!-- Item Header Line -->
                                        <div x-show="!item.is_bundle && !item.is_bundle_item"
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
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
                                                <!-- Regular Product Select -->
                                                <div x-show="!item.is_bundle && !item.is_bundle_item">
                                                    <select :name="`items[${index}][produk_id]`"
                                                        x-model="item.produk_id" @change="updateItemDetails(index)"
                                                        :required="!item.is_bundle && !item.is_bundle_item"
                                                        class="produk-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                        <option value="">Pilih Produk</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                :data-harga="{{ $product->harga_jual ?? 0 }}"
                                                                :data-satuan_id="{{ $product->satuan_id ?? '' }}">
                                                                {{ $product->kode }} - {{ $product->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- Bundle Display (readonly) -->
                                                <div x-show="item.is_bundle || item.is_bundle_item">
                                                    <input type="text"
                                                        :value="item.is_bundle ? (item.bundle_code + ' - ' + item
                                                            .bundle_name) : (item.kode + ' - ' + item.nama)"
                                                        readonly
                                                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm">
                                                    <!-- Bundle inputs are handled in their respective templates -->
                                                </div>
                                            </div>

                                            {{-- Deskripsi Item --}}
                                            <div class="md:col-span-7">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi
                                                    Item</label>
                                                <textarea :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" rows="1"
                                                    :readonly="item.is_bundle_item" placeholder="Deskripsi tambahan untuk item ini..."
                                                    :class="item.is_bundle_item ?
                                                        'mt-1 block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm' :
                                                        'mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md'"></textarea>
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
                                                    x-model.number="item.kuantitas" @input="updateSubtotal(index)"
                                                    :readonly="item.is_bundle_item"
                                                    :required="!item.is_bundle && !item.is_bundle_item"
                                                    placeholder="0"
                                                    :class="item.is_bundle_item ?
                                                        'mt-1 block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm text-center' :
                                                        'mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center'">>
                                            </div>

                                            {{-- Satuan --}}
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Satuan
                                                    <span class="text-red-500">*</span></label>
                                                <!-- Regular Satuan Select -->
                                                <div x-show="!item.is_bundle_item">
                                                    <select :name="`items[${index}][satuan_id]`"
                                                        x-model="item.satuan_id"
                                                        :required="!item.is_bundle && !item.is_bundle_item"
                                                        class="satuan-select-single mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                        <option value="">Pilih</option>
                                                        @foreach ($satuans as $satuan)
                                                            <option value="{{ $satuan->id }}">
                                                                {{ $satuan->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- Bundle Item Satuan (readonly) -->
                                                <div x-show="item.is_bundle_item">
                                                    <input type="text" :value="item.satuan_nama || ''" readonly
                                                        class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm">
                                                    <input type="hidden" :name="`items[${index}][satuan_id]`"
                                                        :value="item.satuan_id">
                                                </div>
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
                                                        :name="`items[${index}][harga]`" x-model.number="item.harga"
                                                        @input="updateSubtotal(index)" :readonly="item.is_bundle_item"
                                                        :required="!item.is_bundle && !item.is_bundle_item"
                                                        placeholder="0"
                                                        :class="item.is_bundle_item ?
                                                            'block w-full pl-12 pr-3 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-md shadow-sm' :
                                                            'focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md'">>
                                                </div>
                                            </div>

                                            {{-- Diskon Item (%) - Hidden for bundle items --}}
                                            <div class="md:col-span-2" x-show="!item.is_bundle_item">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diskon
                                                    (%)</label>
                                                <input type="number" step="any" min="0" max="100"
                                                    :name="`items[${index}][diskon_persen]`"
                                                    x-model.number="item.diskon_persen" @input="updateSubtotal(index)"
                                                    placeholder="0"
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
                                                    <input type="text" :name="`items[${index}][subtotal]`"
                                                        x-model="formatRupiah(item.subtotal)" readonly
                                                        class="bg-gray-100 dark:bg-gray-700 focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:text-white rounded-md font-medium">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Ringkasan Total -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 form-card mb-6 summary-card">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
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

                        <hr class="dark:border-gray-600 my-1">

                        <!-- Ongkos Kirim -->
                        <div class="flex justify-between items-center">
                            <label for="ongkos_kirim" class="text-sm font-medium text-gray-600 dark:text-gray-300">
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
                        <textarea name="catatan" id="catatan" rows="3"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="input-group">
                            <label for="terms_pembayaran" class="text-gray-700 dark:text-gray-300">Terms
                                Pembayaran</label>
                            <select name="terms_pembayaran" id="terms_pembayaran"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                                <option value="">Pilih Terms Pembayaran</option>
                                <option value="COD" {{ old('terms_pembayaran') == 'COD' ? 'selected' : '' }}>
                                    COD (Cash On Delivery)</option>
                                <option value="PIA" {{ old('terms_pembayaran') == 'PIA' ? 'selected' : '' }}>
                                    PIA (Payment In Advance)</option>
                                <option value="Net 7" {{ old('terms_pembayaran') == 'Net 7' ? 'selected' : '' }}>
                                    Net 7 (7 hari)</option>
                                <option value="Net 14" {{ old('terms_pembayaran') == 'Net 14' ? 'selected' : '' }}>Net
                                    14 (14 hari)
                                </option>
                                <option value="Net 30" {{ old('terms_pembayaran') == 'Net 30' ? 'selected' : '' }}>Net
                                    30 (30 hari)
                                </option>
                                <option value="Net 60" {{ old('terms_pembayaran') == 'Net 60' ? 'selected' : '' }}>Net
                                    60 (60 hari)
                                </option>
                                <option value="2/10 Net 30"
                                    {{ old('terms_pembayaran') == '2/10 Net 30' ? 'selected' : '' }}>2/10 Net 30
                                    (Diskon 2% jika dibayar dalam 10 hari)</option>
                                <option value="custom" {{ old('terms_pembayaran') == 'custom' ? 'selected' : '' }}>
                                    Kustom</option>
                            </select>
                        </div>

                        <div class="input-group">
                            <label for="terms_pembayaran_hari" class="text-gray-700 dark:text-gray-300">Jatuh
                                Tempo (Hari)</label>
                            <input type="number" name="terms_pembayaran_hari" id="terms_pembayaran_hari"
                                min="0" value="{{ old('terms_pembayaran_hari') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                            <p class="text-xs text-gray-500 mt-1">Masukkan jumlah hari untuk jatuh tempo
                                pembayaran.</p>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="syarat_ketentuan" class="text-gray-700 dark:text-gray-300">Syarat dan
                            Ketentuan</label>
                        <textarea name="syarat_ketentuan" id="syarat_ketentuan" rows="3" x-model="syarat_ketentuan"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">{{ old('syarat_ketentuan') }}</textarea>
                    </div>
                </div>
            </div>
    </div>

    <div class="flex items-center justify-end space-x-3">
        <div class="flex items-center mr-4">
            <input type="checkbox" id="check_stock" name="check_stock" value="1" checked
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
            <label for="check_stock" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                Cek Ketersediaan Stok
            </label>
        </div>

        <div class="flex items-center mr-4">
            <input type="checkbox" id="create_production_plan" name="create_production_plan" value="1"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
            <label for="create_production_plan" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                Buat Perencanaan Produksi Otomatis
            </label>
        </div>

        <div class="flex items-center mr-4">
            <input type="checkbox" id="buat_permintaan_barang" name="buat_permintaan_barang" value="1"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
            <label for="buat_permintaan_barang" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                Buat Permintaan Barang Otomatis
            </label>
        </div>

        <div id="gudang_selection" class="hidden">
            <select name="gudang_id" id="gudang_id"
                class="w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                <option value="">Pilih Gudang</option>
                @foreach ($gudangs as $gudang)
                    <option value="{{ $gudang->id }}" {{ old('gudang_id', 1) == $gudang->id ? 'selected' : '' }}>
                        {{ $gudang->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('penjualan.sales-order.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            Batal
        </a>
        <button type="submit"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Simpan Sales Order
        </button>
    </div>

    <!-- Bundle Selection Modal -->
    <div x-show="showBundleModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showBundleModal = false">
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
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
    </form>
    </div>

    @push('scripts')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            // Show gudang selection when checkbox is checked
            $(document).ready(function() {
                // Set check_stock checked and show gudang selection by default
                $('#check_stock').prop('checked', true);
                $('#gudang_selection').removeClass('hidden');
                $('#gudang_id').prop('required', true);

                $('#buat_permintaan_barang').change(function() {
                    if (this.checked) {
                        $('#gudang_selection').removeClass('hidden');
                        $('#gudang_id').prop('required', true);
                    } else {
                        // Only hide if all related checkboxes are unchecked
                        const anyChecked = $('#check_stock').is(':checked') ||
                            $('#create_production_plan').is(':checked') ||
                            $('#buat_permintaan_barang').is(':checked');
                        if (!anyChecked) {
                            $('#gudang_selection').addClass('hidden');
                            $('#gudang_id').prop('required', false);
                        }
                    }
                });

                // Stock checking and production planning checkboxes
                $('#check_stock, #create_production_plan, #buat_permintaan_barang').change(function() {
                    const anyChecked = $('#check_stock').is(':checked') ||
                        $('#create_production_plan').is(':checked') ||
                        $('#buat_permintaan_barang').is(':checked');

                    if (anyChecked) {
                        $('#gudang_selection').removeClass('hidden');
                        $('#gudang_id').prop('required', true);
                    } else {
                        $('#gudang_selection').addClass('hidden');
                        $('#gudang_id').prop('required', false);
                    }
                });

                // Production planning requires stock checking
                $('#create_production_plan').change(function() {
                    if (this.checked) {
                        $('#check_stock').prop('checked', true).trigger('change');
                    }
                });

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

            function salesOrderForm() {
                return {
                    items: [],
                    customer_id: "{{ old('customer_id', $quotation ? $quotation->customer_id : '') }}" ? parseInt(
                        "{{ old('customer_id', $quotation ? $quotation->customer_id : '') }}") : '',
                    alamat_pengiriman: "{{ old('alamat_pengiriman', $quotation && $quotation->customer ? addslashes($quotation->customer->alamat_pengiriman) : '') }}",
                    syarat_ketentuan: "{{ old('syarat_ketentuan', $quotation ? addslashes($quotation->syarat_ketentuan) : '') }}",
                    diskonGlobalPersen: parseFloat(
                        "{{ old('diskon_global_persen', $quotation ? $quotation->diskon_persen : 0) }}"),
                    diskonGlobalNominal: parseFloat(
                        "{{ old('diskon_global_nominal', $quotation ? $quotation->diskon_nominal : 0) }}"),
                    ongkosKirim: parseFloat("{{ old('ongkos_kirim', $quotation ? $quotation->ongkos_kirim : 0) }}"),
                    includePPN: {{ old('ppn', $quotation ? ($quotation->ppn > 0 ? 'true' : 'false') : 'true') }},

                    // Bundle related data
                    showBundleModal: false,
                    bundleSearch: '',
                    bundles: @json($bundles),

                    // Computed properties for bundle filtering
                    get filteredBundles() {
                        if (!this.bundleSearch) return this.bundles;
                        return this.bundles.filter(bundle =>
                            bundle.nama.toLowerCase().includes(this.bundleSearch.toLowerCase()) ||
                            bundle.kode.toLowerCase().includes(this.bundleSearch.toLowerCase())
                        );
                    },

                    addItem() {
                        // Generate a unique ID for this item
                        const itemId = Date.now();

                        const newItem = {
                            id: itemId,
                            produk_id: '',
                            deskripsi: '',
                            kuantitas: 1,
                            satuan_id: '',
                            harga: 0,
                            diskon_persen: 0,
                            subtotal: 0,
                            is_bundle: false,
                            is_bundle_item: false,
                            bundle_id: null
                        };

                        console.log('Adding regular item:', newItem);
                        this.items.push(newItem);
                        console.log('Items after adding regular item:', this.items);

                        const self = this; // Capture 'this'

                        // Wait for Alpine to update the DOM
                        this.$nextTick(() => {
                            // Use a longer timeout to ensure DOM is completely ready
                            setTimeout(() => {
                                // Get the latest index (which is the new item)
                                const newIndex = self.items.length - 1;

                                // Find the new selects directly by using attribute selectors
                                const produkSelect = $(`select[name="items[${newIndex}][produk_id]"]`);
                                const satuanSelect = $(`select[name="items[${newIndex}][satuan_id]"]`);

                                // Initialize the product select
                                if (produkSelect.length > 0) {
                                    // Destroy any existing Select2 to avoid duplicates
                                    if (produkSelect.hasClass('select2-hidden-accessible')) {
                                        produkSelect.select2('destroy');
                                    }

                                    // Initialize Select2
                                    produkSelect.select2({
                                        placeholder: 'Pilih Produk',
                                        allowClear: true,
                                        width: '100%'
                                    });
                                }

                                // Initialize the unit select
                                if (satuanSelect.length > 0) {
                                    // Destroy any existing Select2 to avoid duplicates
                                    if (satuanSelect.hasClass('select2-hidden-accessible')) {
                                        satuanSelect.select2('destroy');
                                    }

                                    // Initialize Select2
                                    satuanSelect.select2({
                                        placeholder: 'Pilih Satuan',
                                        allowClear: true,
                                        width: '100%'
                                    });
                                }

                                // Call our full refresh method for safety
                                if (typeof self.refreshItemSelects === 'function') {
                                    self.refreshItemSelects();
                                }
                            }, 150); // Increased timeout for more reliability
                        });
                    },

                    init() {
                        this.initializeSelect2();

                        @if (old('items'))
                            @foreach (old('items') as $index => $item)
                                this.items.push({
                                    produk_id: "{{ $item['produk_id'] }}" ? parseInt("{{ $item['produk_id'] }}") : '',
                                    deskripsi: "{{ addslashes($item['deskripsi'] ?? '') }}",
                                    kuantitas: parseFloat("{{ $item['kuantitas'] ?? 1 }}"),
                                    satuan_id: "{{ $item['satuan_id'] }}" ? parseInt("{{ $item['satuan_id'] }}") : '',
                                    harga: parseFloat("{{ $item['harga'] ?? 0 }}"),
                                    diskon_persen: parseFloat("{{ $item['diskon_persen'] ?? 0 }}"),
                                    subtotal: parseFloat(
                                        "{{ ($item['harga'] ?? 0) * ($item['kuantitas'] ?? 1) * (1 - ($item['diskon_persen'] ?? 0) / 100) }}"
                                    )
                                });
                            @endforeach
                        @elseif ($quotation)
                            @foreach ($quotation->details as $detail)
                                this.items.push({
                                    produk_id: {{ $detail->produk_id ?: "''" }},
                                    deskripsi: "{{ addslashes($detail->deskripsi ?? '') }}",
                                    kuantitas: parseFloat("{{ $detail->quantity }}"),
                                    satuan_id: {{ $detail->satuan_id ?: "''" }},
                                    harga: parseFloat("{{ $detail->harga }}"),
                                    diskon_persen: parseFloat("{{ $detail->diskon_persen ?? 0 }}"),
                                    subtotal: parseFloat("{{ $detail->subtotal }}")
                                });
                            @endforeach
                        @else
                            if (this.items.length === 0) {
                                this.addItem();
                            }
                        @endif

                        const self = this; // Capture 'this'
                        this.$nextTick(() => {
                            if (self.customer_id) {
                                $('#customer_id').val(self.customer_id).trigger('change.select2');
                            }
                            if (self.items.length > 0 && !({!! json_encode(!old('items') && !$quotation) !!} && self.items.length === 1 && self
                                    .items[0].produk_id === '')) {
                                if (typeof self.refreshItemSelects === 'function') {
                                    self.refreshItemSelects();
                                }
                            }
                            if (typeof self.calculateTotal === 'function') {
                                self.calculateTotal();
                            }
                        });
                    },

                    initializeSelect2() {
                        const self = this; // Capture 'this' for Select2 event handlers
                        $(document).ready(() => {
                            // Initialize primary selects
                            $('#customer_id').select2({
                                placeholder: 'Pilih Customer',
                                allowClear: true,
                                width: '100%'
                            }).on('change', (e) => {
                                const currentAlpineVal = String(self.customer_id);
                                const newSelectVal = String(e.target.value);

                                if (currentAlpineVal !== newSelectVal) {
                                    self.customer_id = e.target.value ? parseInt(e.target.value) : '';

                                    // Fetch customer's shipping address when a customer is selected
                                    if (self.customer_id) {
                                        fetch(`/api/customers/${self.customer_id}`)
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data && data.alamat_pengiriman) {
                                                    self.alamat_pengiriman = data.alamat_pengiriman;
                                                } else {
                                                    self.alamat_pengiriman = '';
                                                }
                                            })
                                            .catch(error => console.error('Error fetching customer data:',
                                                error));
                                    } else {
                                        self.alamat_pengiriman = '';
                                    }
                                }
                            });

                            // Initialize any existing item selects on page load
                            self.refreshItemSelects();

                            $('#quotation_id').select2({
                                placeholder: 'Pilih Quotation (Opsional)',
                                allowClear: true,
                                width: '100%',
                                ajax: {
                                    url: '{{ route('penjualan.api.quotations') }}',
                                    dataType: 'json',
                                    delay: 250,
                                    data: function(params) {
                                        return {
                                            search: params.term || '',
                                            page: params.page || 1,
                                            status: 'disetujui'
                                        };
                                    },
                                    processResults: function(data, params) {
                                        params.page = params.page || 1;
                                        const results = data.data.map(q => {
                                            let customerName = 'N/A';
                                            if (q.customer) {
                                                customerName = q.customer.nama || q.customer
                                                    .company || 'Customer Name Missing';
                                            }
                                            return {
                                                id: q.id,
                                                text: `${q.nomor} - ${customerName}`
                                            };
                                        });
                                        return {
                                            results: results,
                                            pagination: {
                                                more: data.current_page < data.last_page
                                            }
                                        };
                                    },
                                    cache: true
                                }
                            }).on('change', (e) => {
                                if (typeof self.loadQuotationData === 'function') {
                                    self.loadQuotationData();
                                }
                            });
                        });
                    },

                    refreshItemSelects() {
                        const self = this; // Ensure 'this' is correct for forEach

                        // Wait for Alpine's next render cycle to complete
                        this.$nextTick(() => {
                            // Use a longer delay to ensure DOM is fully rendered
                            setTimeout(() => {
                                // Process each item individually
                                self.items.forEach((item, index) => {
                                    // Handle product select
                                    const produkSelect = $(`select[name="items[${index}][produk_id]"]`);
                                    if (produkSelect.length) {
                                        // Check if already initialized
                                        if (produkSelect.hasClass('select2-hidden-accessible')) {
                                            produkSelect.select2('destroy');
                                        }

                                        // Initialize Select2
                                        produkSelect.select2({
                                            placeholder: 'Pilih Produk',
                                            allowClear: true,
                                            width: '100%'
                                        }).on('change', function() {
                                            // Set up a direct event handler on the Select2 itself
                                            const selectedProdukId = $(this).val();
                                            if (selectedProdukId) {
                                                const selectedOption = $(this).find(
                                                    'option:selected');
                                                const harga = selectedOption.data('harga');
                                                const satuanId = selectedOption.data(
                                                    'satuan_id');

                                                // Directly update Alpine model
                                                self.items[index].harga = parseFloat(harga) ||
                                                    0;
                                                self.items[index].satuan_id = satuanId ?
                                                    parseInt(satuanId) : '';

                                                // Directly update DOM elements
                                                $(`input[name="items[${index}][harga]"]`).val(
                                                    self.items[index].harga);

                                                // Update satuan select
                                                const satuanSelect = $(
                                                    `select[name="items[${index}][satuan_id]"]`
                                                );
                                                if (satuanSelect.length && self.items[index]
                                                    .satuan_id) {
                                                    satuanSelect.val(self.items[index]
                                                        .satuan_id).trigger('change');
                                                }

                                                // Update subtotal
                                                self.updateSubtotal(index);
                                            }
                                        });

                                        // Set value if it exists
                                        if (item.produk_id) {
                                            produkSelect.val(item.produk_id).trigger('change.select2');
                                        }
                                    }

                                    // Handle satuan select
                                    const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
                                    if (satuanSelect.length) {
                                        // Check if already initialized
                                        if (satuanSelect.hasClass('select2-hidden-accessible')) {
                                            satuanSelect.select2('destroy');
                                        }

                                        // Initialize Select2
                                        satuanSelect.select2({
                                            placeholder: 'Pilih Satuan',
                                            allowClear: true,
                                            width: '100%'
                                        });

                                        // Set value if it exists
                                        if (item.satuan_id) {
                                            satuanSelect.val(item.satuan_id).trigger('change.select2');
                                        }
                                    }
                                });
                            }, 200); // Even longer delay to ensure DOM is fully ready
                        });
                    },

                    loadQuotationData() {
                        const quotationId = document.getElementById('quotation_id').value;
                        const self = this; // Capture 'this' context

                        if (!quotationId) {
                            self.customer_id = '';
                            self.alamat_pengiriman = '';
                            self.syarat_ketentuan = '';
                            self.items = [];
                            self.diskonGlobalPersen = 0;
                            self.diskonGlobalNominal = 0;
                            self.ongkosKirim = 0;
                            self.includePPN = true;

                            self.$nextTick(() => {
                                $('#customer_id').val(null).trigger('change.select2');
                                if (self.items.length === 0) {
                                    if (typeof self.addItem === 'function') self.addItem();
                                } else {
                                    if (typeof self.refreshItemSelects === 'function') self.refreshItemSelects();
                                }
                                if (typeof self.calculateTotal === 'function') self.calculateTotal();
                            });
                            return;
                        }

                        const url = `/penjualan/sales-order/get-quotation-data/${quotationId}`;

                        fetch(url)
                            .then(response => {
                                if (!response.ok) {
                                    return response.text().then(text => {
                                        throw new Error(
                                            `HTTP error! Status: ${response.status}, Message: ${response.statusText}, Body: ${text}`
                                        );
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data && data.success === true && data.data && typeof data.data === 'object' && !Array
                                    .isArray(data.data)) {
                                    const quotation = data.data;

                                    self.customer_id = quotation.customer_id ? parseInt(quotation.customer_id) : '';
                                    self.alamat_pengiriman = (quotation.customer && typeof quotation.customer ===
                                            'object' && quotation.customer.alamat_pengiriman) ? quotation.customer
                                        .alamat_pengiriman :
                                        '';
                                    self.syarat_ketentuan = quotation.syarat_ketentuan || '';

                                    console.log('Quotation data received:', quotation);

                                    self.items = [];
                                    if (quotation.details && Array.isArray(quotation.details) && quotation.details.length >
                                        0) {
                                        quotation.details.forEach((detail, detailIndex) => {
                                            console.log(`Processing detail ${detailIndex}:`, detail);

                                            if (typeof detail !== 'object' || detail === null) {
                                                return;
                                            }
                                            const produkId = detail.produk_id ? parseInt(detail.produk_id) : '';
                                            const satuanId = detail.satuan_id ? parseInt(detail.satuan_id) : '';
                                            const harga = parseFloat(detail.harga) || 0;
                                            const kuantitas = parseFloat(detail.quantity) || 1;
                                            const diskonPersen = parseFloat(detail.diskon_persen) || 0;
                                            const itemSubtotal = parseFloat(detail.subtotal) || ((harga *
                                                kuantitas) * (1 - (diskonPersen / 100)));

                                            console.log(`Detail ${detailIndex} discount info:`, {
                                                produk_id: produkId,
                                                is_bundle_item: detail.is_bundle_item,
                                                bundle_id: detail.bundle_id,
                                                diskon_persen: diskonPersen,
                                                original_diskon_persen: detail.diskon_persen
                                            });

                                            // Handle bundle items properly
                                            const newItem = {
                                                id: Date.now() + Math.random(),
                                                originalDetailId: detail
                                                    .id, // Store original detail ID for reference
                                                produk_id: produkId,
                                                deskripsi: detail.deskripsi || '',
                                                kuantitas: kuantitas,
                                                satuan_id: satuanId,
                                                harga: harga,
                                                diskon_persen: diskonPersen,
                                                subtotal: itemSubtotal,
                                                // Bundle properties
                                                bundle_id: detail.bundle_id || null,
                                                item_type: detail.item_type || 'regular',
                                                // Fix bundle detection logic
                                                is_bundle: (detail.item_type === 'bundle' || (detail
                                                    .bundle_id && detail.is_bundle_item !== 1)),
                                                is_bundle_item: (detail.is_bundle_item === 1 || detail
                                                    .item_type === 'bundle_item'),
                                                bundle_name: (detail.bundle && detail.bundle.nama) || detail
                                                    .bundle_name || '',
                                                bundle_code: (detail.bundle && detail.bundle.kode) || detail
                                                    .bundle_code || '',
                                                base_quantity: detail.base_quantity || kuantitas,
                                                readonly: (detail.is_bundle_item === 1 || detail.item_type ===
                                                    'bundle_item'),
                                                // Additional bundle data
                                                nama: detail.produk ? detail.produk.nama : '',
                                                kode: detail.produk ? detail.produk.kode : '',
                                                satuan_nama: detail.satuan ? detail.satuan.nama : ''
                                            };

                                            console.log(`Created item ${detailIndex}:`, newItem);
                                            self.items.push(newItem);
                                        });

                                        console.log('Items before reorganization:', self.items);

                                        // Create bundle headers for bundle items that don't have headers
                                        const bundleGroups = new Map();

                                        // Group bundle items by bundle_id
                                        self.items.forEach(item => {
                                            if (item.bundle_id && item.is_bundle_item) {
                                                if (!bundleGroups.has(item.bundle_id)) {
                                                    bundleGroups.set(item.bundle_id, {
                                                        children: [],
                                                        bundleData: null
                                                    });
                                                }
                                                bundleGroups.get(item.bundle_id).children.push(item);

                                                // Store bundle data from first child
                                                if (!bundleGroups.get(item.bundle_id).bundleData && item
                                                    .bundle_name) {
                                                    bundleGroups.get(item.bundle_id).bundleData = {
                                                        id: item.bundle_id,
                                                        nama: item.bundle_name,
                                                        kode: item.bundle_code,
                                                        harga_bundle: quotation.details.find(d => d
                                                                .bundle_id === item.bundle_id && d.bundle)
                                                            ?.bundle?.harga_bundle || 0
                                                    };
                                                }
                                            }
                                        });

                                        // Create bundle headers for groups that don't have them
                                        bundleGroups.forEach((group, bundleId) => {
                                            const hasHeader = self.items.some(item => item.bundle_id === bundleId &&
                                                item.is_bundle);

                                            if (!hasHeader && group.bundleData && group.children.length > 0) {
                                                // Simple approach: Calculate bundle quantity from children quantities
                                                // For most bundles, all children will have the same multiplier
                                                const firstChild = group.children[0];
                                                console.log('First child data:', firstChild);

                                                // Assume base quantity is 1 for each child in a bundle
                                                // Bundle quantity = child quantity / 1 = child quantity
                                                let bundleQuantity = Math.round(firstChild.kuantitas);

                                                // However, if all children have the same quantity, use that as bundle quantity
                                                const allSameQuantity = group.children.every(child => child
                                                    .kuantitas === firstChild.kuantitas);
                                                if (allSameQuantity) {
                                                    bundleQuantity = Math.round(firstChild.kuantitas);
                                                } else {
                                                    // If different quantities, try to find the common divisor
                                                    bundleQuantity = Math.round(Math.min(...group.children.map(c =>
                                                        c.kuantitas)));
                                                }

                                                console.log('Calculated bundle quantity:', bundleQuantity);

                                                // Update children with proper base quantities (assume 1 for each item in bundle)
                                                group.children.forEach(child => {
                                                    child.base_quantity = child.kuantitas / bundleQuantity;
                                                    console.log(
                                                        `Child ${child.produk_id}: qty=${child.kuantitas}, base_qty=${child.base_quantity}`
                                                    );
                                                });

                                                const bundleSubtotal = parseFloat(group.bundleData.harga_bundle ||
                                                    0) * bundleQuantity;

                                                const bundleHeader = {
                                                    id: Date.now() + Math.random(),
                                                    produk_id: '',
                                                    bundle_id: bundleId,
                                                    item_type: 'bundle',
                                                    deskripsi: `Bundle: ${group.bundleData.nama}`,
                                                    kuantitas: bundleQuantity,
                                                    satuan_id: '',
                                                    harga: parseFloat(group.bundleData.harga_bundle || 0),
                                                    diskon_persen: 0,
                                                    subtotal: bundleSubtotal,
                                                    is_bundle: true,
                                                    is_bundle_item: false,
                                                    bundle_name: group.bundleData.nama,
                                                    bundle_code: group.bundleData.kode,
                                                    readonly: false
                                                };

                                                console.log('Creating missing bundle header:', bundleHeader);
                                                console.log('Bundle quantity calculated:', bundleQuantity);
                                                console.log('Children base quantities:', group.children.map(c => ({
                                                    id: c.produk_id,
                                                    base_qty: c.base_quantity,
                                                    current_qty: c.kuantitas
                                                })));

                                                self.items.unshift(bundleHeader); // Add at beginning
                                            }
                                        });

                                        // Reorganize items to group bundles properly
                                        if (typeof self.reorganizeItems === 'function') {
                                            self.reorganizeItems();
                                            console.log('Items after reorganization:', self.items);
                                        }
                                    }

                                    self.diskonGlobalPersen = parseFloat(quotation.diskon_persen) || 0;
                                    self.diskonGlobalNominal = parseFloat(quotation.diskon_nominal) || 0;
                                    self.ongkosKirim = parseFloat(quotation.ongkos_kirim) || 0;
                                    self.includePPN = quotation.ppn ? parseFloat(quotation.ppn) > 0 :
                                        true;

                                    self.$nextTick(() => {
                                        if (self.customer_id) {
                                            $('#customer_id').val(self.customer_id).trigger('change.select2');
                                        } else {
                                            $('#customer_id').val(null).trigger('change.select2');
                                        }

                                        if (self.items.length === 0) {
                                            if (typeof self.addItem === 'function') {
                                                self.addItem();
                                            }
                                        } else {
                                            if (typeof self.refreshItemSelects === 'function') {
                                                self.refreshItemSelects();
                                            }
                                        }

                                        if (typeof self.calculateTotal === 'function') {
                                            self.calculateTotal();
                                        }
                                    });
                                } else {
                                    let reason = "Unknown reason for failure.";
                                    if (!data) {
                                        reason = "Parsed data object is null or undefined.";
                                    } else if (data.success !== true) {
                                        reason =
                                            `data.success is not true (it is: ${data.success}). Message: ${data.message || 'N/A'}`;
                                    } else if (!data.data) {
                                        reason = `data.data is null or undefined.`;
                                    } else if (typeof data.data !== 'object') {
                                        reason = `data.data is not an object (it is: ${typeof data.data}).`;
                                    } else if (Array.isArray(data.data)) {
                                        reason = `data.data is an array, but an object was expected.`;
                                    }
                                    alert('Gagal memuat data quotation: ' + (data && data.message ? data.message :
                                        'Struktur data dari server tidak sesuai atau data tidak ditemukan.'
                                    ));
                                }
                            })
                            .catch(error => {
                                alert('Gagal memuat data quotation. Kesalahan jaringan atau server.');
                            });
                    },

                    updateItemDetails(index) {
                        const produkId = this.items[index].produk_id;
                        const self = this; // Capture 'this'

                        if (!produkId) {
                            self.items[index].satuan_id = '';
                            self.items[index].harga = 0;
                            self.updateSubtotal(index);
                            return;
                        }

                        // Get product data from the selected option's data attributes IMMEDIATELY
                        try {
                            const produkSelect = $(`select[name="items[${index}][produk_id]"]`);
                            const selectedOption = produkSelect.find('option:selected');

                            if (selectedOption.length) {
                                const hargaAttr = selectedOption.attr('data-harga');
                                const satuanIdAttr = selectedOption.attr('data-satuan_id');

                                if (hargaAttr) {
                                    self.items[index].harga = parseFloat(hargaAttr) || 0;
                                    // Directly set DOM value too
                                    $(`input[name="items[${index}][harga]"]`).val(self.items[index].harga);
                                }

                                if (satuanIdAttr) {
                                    // Set the value in Alpine model
                                    self.items[index].satuan_id = parseInt(satuanIdAttr) || '';

                                    // Directly update the satuan select with jQuery (bypass Select2 temporarily)
                                    const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
                                    satuanSelect.val(self.items[index].satuan_id);

                                    // Force Select2 to recognize the change
                                    setTimeout(() => {
                                        satuanSelect.trigger('change.select2');
                                    }, 50);
                                }

                                // Always update subtotal
                                self.updateSubtotal(index);
                            }
                        } catch (e) {
                            // Silent error handling
                        }

                        // Still fetch from API for completeness
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
                                        $(`input[name="items[${index}][harga]"]`).val(self.items[index].harga);
                                    }

                                    if (!self.items[index].satuan_id) {
                                        self.items[index].satuan_id = product.satuan_id ? parseInt(product.satuan_id) : '';
                                        const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
                                        if (satuanSelect.length && self.items[index].satuan_id) {
                                            satuanSelect.val(self.items[index].satuan_id).trigger('change.select2');
                                        }
                                    }

                                    // Always update subtotal
                                    self.updateSubtotal(index);
                                }
                            })
                            .catch(error => {
                                // Silent error handling
                            });
                    },

                    removeItem(index) {
                        const item = this.items[index];

                        // If removing a bundle, also remove all its items
                        if (item.is_bundle) {
                            // Find and remove all bundle items that belong to this bundle
                            for (let i = this.items.length - 1; i >= 0; i--) {
                                if (this.items[i].bundle_id === item.bundle_id && this.items[i].is_bundle_item) {
                                    this.items.splice(i, 1);
                                    // Adjust index if we removed items before current index
                                    if (i < index) {
                                        index--;
                                    }
                                }
                            }
                        }

                        // Remove the main item
                        this.items.splice(index, 1);
                        this.calculateTotal();
                    },

                    updateSubtotal(index) {
                        const item = this.items[index];
                        const productTotal = item.harga * item.kuantitas;
                        const discountValue = (item.diskon_persen / 100) * productTotal;
                        item.subtotal = productTotal - discountValue;

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

                        this.calculateTotal();
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

                    calculateTotal() {
                        const subtotal = this.calculateSubtotal();
                        let diskonGlobalNominal = parseFloat(this.diskonGlobalNominal) || 0;

                        // Recalculate diskonGlobalNominal if diskonGlobalPersen is the source of truth
                        if (parseFloat(this.diskonGlobalPersen) > 0 && this.lastChangedDiskon === 'persen') {
                            diskonGlobalNominal = (parseFloat(this.diskonGlobalPersen) / 100) * subtotal;
                            this.diskonGlobalNominal = diskonGlobalNominal; // Update the model
                        } else if (this.lastChangedDiskon === 'nominal') {
                            // Recalculate diskonGlobalPersen if diskonGlobalNominal is the source of truth
                            if (subtotal > 0) {
                                this.diskonGlobalPersen = (diskonGlobalNominal / subtotal) * 100;
                            } else {
                                this.diskonGlobalPersen = 0;
                            }
                        }

                        const afterDiscount = subtotal - diskonGlobalNominal;
                        const ongkosKirimValue = parseFloat(this.ongkosKirim) || 0;
                        const ppnNominal = this.includePPN ? ({{ setting('tax_percentage', 11) }} / 100) * afterDiscount : 0;
                        const total = afterDiscount + ppnNominal + ongkosKirimValue;

                        // Update reactive properties for display
                        this.displaySubtotal = subtotal;
                        this.displayDiskonGlobalNominal = diskonGlobalNominal;
                        this.displayPPNNominal = ppnNominal;
                        this.displayGrandTotal = total;

                        return {
                            subtotal,
                            afterGlobalDiscount: afterDiscount,
                            diskonGlobalNominal,
                            ppnNominal,
                            ongkosKirim: ongkosKirimValue, // include this for clarity if needed elsewhere
                            total
                        };
                    },

                    // Bundle quantity update method
                    updateBundleCalculations(index) {
                        const item = this.items[index];
                        if (item && item.is_bundle) {
                            // Update bundle subtotal based on quantity
                            item.subtotal = (parseFloat(item.harga) || 0) * (parseFloat(item.kuantitas) || 1);

                            // Update all bundle items quantities to match bundle multiplier
                            const bundleId = item.bundle_id;
                            const bundleQuantity = parseFloat(item.kuantitas) || 1;

                            this.items.forEach((bundleItem, itemIndex) => {
                                if (bundleItem.is_bundle_item && bundleItem.bundle_id === bundleId) {
                                    // Update bundle item quantity (base quantity * bundle quantity)
                                    const baseQuantity = parseFloat(bundleItem.base_quantity) || parseFloat(bundleItem
                                        .kuantitas) || 1;
                                    bundleItem.kuantitas = baseQuantity * bundleQuantity;
                                    bundleItem.subtotal = (parseFloat(bundleItem.harga) || 0) * bundleItem.kuantitas;
                                }
                            });

                            // Recalculate totals
                            this.calculateTotal();
                        }
                    },

                    // Bundle methods
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

                            // Fetch detailed bundle data
                            const url = `/penjualan/sales-order/get-bundle-data/${bundle.id}`;

                            const response = await fetch(url, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                        'content') || ''
                                }
                            });

                            if (!response.ok) {
                                const errorText = await response.text();
                                console.error('Response error:', errorText);
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }

                            const data = await response.json();

                            if (!data.success) {
                                throw new Error(data.message || 'Failed to get bundle data');
                            }

                            await this.processBundleData(data.bundle, data.items);

                        } catch (error) {
                            console.error('Error adding bundle:', error);

                            // Hide loading notification
                            if (typeof window.notify === 'function') {
                                window.notify('Gagal menambahkan bundle: ' + error.message, 'error', 'Error');
                            } else {
                                alert('Gagal menambahkan bundle: ' + error.message);
                            }
                        }
                    },

                    async processBundleData(bundleData, bundleItems) {
                        try {
                            console.log('processBundleData called with:', {
                                bundleData,
                                bundleItems
                            });

                            // Get bundle discount
                            const bundleDiskonPersen = parseFloat(bundleData.diskon_persen || 0);
                            console.log('Bundle discount percent:', bundleDiskonPersen);

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

                            console.log('Adding bundle header:', bundleMainItem);
                            // Add bundle header at the end
                            this.items.push(bundleMainItem);

                            // Add individual bundle items immediately after the header
                            bundleItems.forEach((item, itemIndex) => {
                                console.log(`Processing bundle item ${itemIndex}:`, item);

                                // Apply bundle discount to each child item
                                const itemSubtotalBefore = (item.harga_satuan || 0) * item.quantity;
                                const itemDiskonNominal = (itemSubtotalBefore * bundleDiskonPersen) / 100;
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
                                    base_quantity: item.quantity, // Store original quantity for calculation
                                    satuan_id: item.satuan_id,
                                    harga: item.harga_satuan,
                                    diskon_persen: bundleDiskonPersen, // Apply bundle discount
                                    subtotal: itemSubtotal, // Calculated with discount
                                    is_bundle: false,
                                    is_bundle_item: true,
                                    readonly: true // Make it read-only to prevent editing
                                };

                                console.log(`Adding bundle child item ${itemIndex}:`, childItem);
                                this.items.push(childItem);
                            });

                            console.log('Items after adding bundle:', this.items);

                            // Reorganize items to group bundles properly
                            if (typeof this.reorganizeItems === 'function') {
                                this.reorganizeItems();
                                console.log('Items after reorganization:', this.items);
                            }

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

                    validateForm(event) {
                        // Debug: Log items data before submission
                        console.log('Items before submission:', this.items);
                        console.log('Items count:', this.items.length);
                        this.items.forEach((item, index) => {
                            console.log(`Item ${index}:`, {
                                produk_id: item.produk_id,
                                is_bundle: item.is_bundle,
                                is_bundle_item: item.is_bundle_item,
                                bundle_id: item.bundle_id,
                                kuantitas: item.kuantitas,
                                harga: item.harga
                            });

                            // Check for invalid combinations
                            if (item.is_bundle && item.is_bundle_item) {
                                console.error(
                                    `❌ INVALID: Item ${index} has both is_bundle=true AND is_bundle_item=true!`);
                            }
                            if (item.is_bundle && !item.bundle_id) {
                                console.error(`❌ INVALID: Item ${index} has is_bundle=true but bundle_id is null!`);
                            }
                        });

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

                        return true;
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
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
