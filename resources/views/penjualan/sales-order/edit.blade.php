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
            x-data="salesOrderForm()" x-init="init()">
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
                            <label for="tanggal" class="text-gray-700 dark:text-gray-300">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', $salesOrder->tanggal) }}" required
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
                            <input type="date" name="tanggal_kirim" id="tanggal_kirim"
                                value="{{ old('tanggal_kirim', $salesOrder->tanggal_kirim) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">
                        </div>

                        <div class="input-group">
                            <label for="status_pembayaran" class="text-gray-700 dark:text-gray-300">Status Pembayaran
                                <span class="text-red-500">*</span></label>
                            <select name="status_pembayaran" id="status_pembayaran" required
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
                            <div class="mt-6">
                                <button type="button" @click="addItem"
                                    class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Tambah Item Pertama
                                </button>
                            </div>
                        </div>

                        <div x-show="items.length > 0" class="overflow-x-auto">
                            <div class="space-y-3 mt-2" id="items-container">
                                <template x-for="(item, index) in items" :key="index">
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
                                                    <select :name="`items[${index}][produk_id]`"
                                                        x-model="item.produk_id" @change="updateItemDetails(index)"
                                                        required
                                                        class="produk-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                        <option value="">Pilih Produk</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                :data-harga="{{ $product->harga_jual ?? 0 }}"
                                                                :data-satuan_id="{{ $product->satuan_id ?? '' }}">
                                                                {{ $product->kode }} - {{ $product->nama }}</option>
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
                                                        x-model.number="item.kuantitas" @input="updateSubtotal(index)"
                                                        required placeholder="0"
                                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
                                                </div>

                                                {{-- Satuan --}}
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Satuan
                                                        <span class="text-red-500">*</span></label>
                                                    <select :name="`items[${index}][satuan_id]`"
                                                        x-model="item.satuan_id" required
                                                        class="satuan-select-single mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                        <option value="">Pilih</option>
                                                        @foreach ($satuans as $satuan)
                                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}
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
                                                            x-model.number="item.harga" @input="updateSubtotal(index)"
                                                            required placeholder="0"
                                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                    </div>
                                                </div>

                                                {{-- Diskon Item (%) --}}
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diskon
                                                        (%)</label>
                                                    <input type="number" step="any" min="0"
                                                        max="100" :name="`items[${index}][diskon_persen]`"
                                                        x-model.number="item.diskon_persen"
                                                        @input="updateSubtotal(index)" placeholder="0"
                                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
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
                                    <input type="hidden" name="ppn" :value="includePPN ? 11 : 0">
                                    <span class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-300">Include PPN
                                        (11%)</span>
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
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm dark:text-white">{{ old('catatan', $salesOrder->catatan) }}</textarea>
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
    </div>

    @push('scripts')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            function salesOrderForm() {
                return {
                    items: [],
                    customer_id: "{{ old('customer_id', $salesOrder->customer_id) }}",
                    alamat_pengiriman: "{{ old('alamat_pengiriman', $salesOrder->alamat_pengiriman) }}",
                    syarat_ketentuan: "{{ old('syarat_ketentuan', $salesOrder->syarat_ketentuan) }}",
                    diskonGlobalPersen: {{ old('diskon_persen', $salesOrder->diskon_persen) ?? 0 }},
                    diskonGlobalNominal: {{ old('diskon_nominal', $salesOrder->diskon_nominal) ?? 0 }},
                    ongkosKirim: {{ old('ongkos_kirim', $salesOrder->ongkos_kirim) ?? 0 }},
                    includePPN: {{ old('ppn', $salesOrder->ppn) > 0 ? 'true' : 'false' }}, // PPN is checked by default if value > 0

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

                        const self = this;

                        this.$nextTick(() => {
                            setTimeout(() => {
                                const newIndex = self.items.length - 1;

                                const produkSelect = $(`select[name="items[${newIndex}][produk_id]"]`);
                                const satuanSelect = $(`select[name="items[${newIndex}][satuan_id]"]`);

                                if (produkSelect.length > 0) {
                                    if (produkSelect.hasClass('select2-hidden-accessible')) {
                                        produkSelect.select2('destroy');
                                    }

                                    produkSelect.select2({
                                        placeholder: 'Pilih Produk',
                                        allowClear: true,
                                        width: '100%'
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

                                if (typeof self.refreshItemSelects === 'function') {
                                    self.refreshItemSelects();
                                }
                            }, 150);
                        });
                    },

                    init() {
                        this.initializeSelect2();

                        this.items = [];
                        const existingItems = [];

                        @if (old('items'))
                            @foreach (old('items') as $index => $item)
                                existingItems.push({
                                    id: Date.now() + {{ $index }},
                                    produk_id: "{{ $item['produk_id'] }}" ? parseInt("{{ $item['produk_id'] }}") : '',
                                    deskripsi: "{{ addslashes($item['deskripsi'] ?? '') }}",
                                    kuantitas: parseFloat("{{ $item['kuantitas'] }}"),
                                    satuan_id: "{{ $item['satuan_id'] }}" ? parseInt("{{ $item['satuan_id'] }}") : '',
                                    harga: parseFloat("{{ $item['harga'] }}"),
                                    diskon_persen: parseFloat("{{ $item['diskon_persen'] ?? 0 }}"),
                                    subtotal: parseFloat(
                                        "{{ ($item['harga'] ?? 0) * ($item['kuantitas'] ?? 1) * (1 - ($item['diskon_persen'] ?? 0) / 100) }}"
                                    )
                                });
                            @endforeach
                        @else
                            @foreach ($salesOrder->details as $index => $detail)
                                existingItems.push({
                                    id: Date.now() + {{ $index }},
                                    produk_id: {{ $detail->produk_id ?: "''" }},
                                    deskripsi: "{{ addslashes($detail->deskripsi ?? '') }}",
                                    kuantitas: parseFloat("{{ $detail->quantity }}"),
                                    satuan_id: {{ $detail->satuan_id ?: "''" }},
                                    harga: parseFloat("{{ $detail->harga }}"),
                                    diskon_persen: parseFloat("{{ $detail->diskon_persen ?? 0 }}"),
                                    subtotal: parseFloat("{{ $detail->subtotal }}")
                                });
                            @endforeach
                        @endif

                        this.items = existingItems;

                        const self = this;
                        this.$nextTick(() => {
                            if (self.customer_id) {
                                $('#customer_id').val(self.customer_id).trigger('change.select2');
                            }
                            if (self.items.length > 0) {
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
                            $('.customer-select').select2({
                                placeholder: 'Pilih Customer',
                                allowClear: true,
                                width: '100%'
                            }).on('change', (e) => {
                                const customerId = e.target.value ? parseInt(e.target.value) : '';
                                self.customer_id = customerId;

                                // Fetch customer's shipping address when a customer is selected
                                if (customerId) {
                                    fetch(`{{ url('api/customers') }}/${customerId}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data && data.alamat_pengiriman) {
                                                self.alamat_pengiriman = data.alamat_pengiriman;
                                            } else {
                                                self.alamat_pengiriman = '';
                                            }
                                        })
                                        .catch(error => console.error('Error fetching customer data:', error));
                                } else {
                                    self.alamat_pengiriman = '';
                                }
                            });

                            $('.quotation-select').select2({
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

                            if (typeof self.refreshItemSelects === 'function') {
                                self.refreshItemSelects();
                            }
                        });
                    },

                    refreshItemSelects() {
                        const self = this;

                        this.$nextTick(() => {
                            setTimeout(() => {
                                self.items.forEach((item, index) => {
                                    const produkSelect = $(`select[name="items[${index}][produk_id]"]`);
                                    if (produkSelect.length) {
                                        if (produkSelect.hasClass('select2-hidden-accessible')) {
                                            produkSelect.select2('destroy');
                                        }

                                        produkSelect.select2({
                                            placeholder: 'Pilih Produk',
                                            allowClear: true,
                                            width: '100%'
                                        }).on('change', function() {
                                            const selectedProdukId = $(this).val();
                                            self.items[index].produk_id = selectedProdukId ?
                                                parseInt(selectedProdukId) : '';

                                            if (selectedProdukId) {
                                                const selectedOption = $(this).find(
                                                    'option:selected');
                                                const harga = selectedOption.data('harga');
                                                const satuanId = selectedOption.data(
                                                    'satuan_id');

                                                self.items[index].harga = parseFloat(harga) ||
                                                    0;
                                                self.items[index].satuan_id = satuanId ?
                                                    parseInt(satuanId) : '';

                                                $(`input[name="items[${index}][harga]"]`).val(
                                                    self.items[index].harga);

                                                const satuanSelectEl = $(
                                                    `select[name="items[${index}][satuan_id]"]`
                                                );
                                                if (satuanSelectEl.length) {
                                                    satuanSelectEl.val(self.items[index]
                                                        .satuan_id).trigger(
                                                        'change.select2');
                                                }

                                                self.updateSubtotal(index);
                                            } else {
                                                self.items[index].harga = 0;
                                                self.items[index].satuan_id = '';
                                                $(`input[name="items[${index}][harga]"]`).val(
                                                    0);
                                                const satuanSelectEl = $(
                                                    `select[name="items[${index}][satuan_id]"]`
                                                );
                                                if (satuanSelectEl.length) {
                                                    satuanSelectEl.val('').trigger(
                                                        'change.select2');
                                                }
                                                self.updateSubtotal(index);
                                            }
                                        });

                                        if (item.produk_id) {
                                            produkSelect.val(item.produk_id).trigger('change.select2');
                                        } else {
                                            produkSelect.val(null).trigger('change.select2');
                                        }
                                    }

                                    const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
                                    if (satuanSelect.length) {
                                        if (satuanSelect.hasClass('select2-hidden-accessible')) {
                                            satuanSelect.select2('destroy');
                                        }

                                        satuanSelect.select2({
                                            placeholder: 'Pilih Satuan',
                                            allowClear: true,
                                            width: '100%'
                                        }).on('change', function() {
                                            self.items[index].satuan_id = $(this).val() ?
                                                parseInt($(this).val()) : '';
                                        });

                                        if (item.satuan_id) {
                                            satuanSelect.val(item.satuan_id).trigger('change.select2');
                                        } else {
                                            satuanSelect.val(null).trigger('change.select2');
                                        }
                                    }
                                });
                            }, 200);
                        });
                    },

                    loadQuotationData() {
                        const quotationId = $('#quotation_id').val();
                        if (!quotationId) return;


                        fetch(`{{ route('penjualan.sales-order.get-quotation-data', ['id' => '__ID__']) }}`.replace('__ID__',
                                quotationId))
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {

                                if (data.success) {
                                    const quotation = data.data;
                                    console.log('Customer ID from quotation:', quotation.customer_id);
                                    console.log('Customer data:', quotation.customer);

                                    this.customer_id = quotation.customer_id;

                                    if (quotation.customer && quotation.customer.alamat_pengiriman) {
                                        this.alamat_pengiriman = quotation.customer.alamat_pengiriman;
                                    }

                                    this.syarat_ketentuan = quotation.syarat_ketentuan || '';

                                    const newItems = [];
                                    if (quotation.details && Array.isArray(quotation.details)) {
                                        quotation.details.forEach((detail, idx) => {
                                            newItems.push({
                                                id: Date.now() + idx,
                                                produk_id: detail.produk_id ? parseInt(detail.produk_id) :
                                                    '',
                                                deskripsi: detail.deskripsi || '',
                                                kuantitas: parseFloat(detail.quantity) || 1,
                                                satuan_id: detail.satuan_id ? parseInt(detail.satuan_id) :
                                                    '',
                                                harga: parseFloat(detail.harga) || 0,
                                                diskon_persen: parseFloat(detail.diskon_persen) || 0,
                                                subtotal: parseFloat(detail.subtotal) || 0
                                            });
                                        });
                                    }
                                    this.items = newItems;

                                    this.diskonGlobalPersen = parseFloat(quotation.diskon_persen) || 0;
                                    this.diskonGlobalNominal = parseFloat(quotation.diskon_nominal) || 0;
                                    this.includePPN = quotation.ppn > 0;

                                    const self = this;
                                    this.$nextTick(() => {
                                        if (typeof self.refreshItemSelects === 'function') {
                                            self.refreshItemSelects();
                                        }
                                        if (typeof self.calculateTotal === 'function') {
                                            self.calculateTotal();
                                        }
                                    });

                                    alert('Data quotation berhasil dimuat ke sales order.');
                                } else {
                                    alert('Gagal memuat data quotation: ' + (data.message ||
                                        'Data tidak ditemukan atau format tidak sesuai.'));
                                }
                            })
                            .catch(error => {
                                alert('Gagal memuat data quotation. Silakan coba lagi. Error: ' + error.message);
                            });
                    },

                    updateItemDetails(index) {
                        const produkId = this.items[index].produk_id;
                        const self = this;

                        if (!produkId) {
                            self.items[index].satuan_id = '';
                            self.items[index].harga = 0;
                            const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
                            if (satuanSelect.length) {
                                satuanSelect.val(null).trigger('change.select2');
                            }
                            self.updateSubtotal(index);
                            return;
                        }

                        fetch(`{{ url('api/products') }}/${produkId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.data) {
                                    const product = data.data;

                                    if (!self.items[index].deskripsi && product.nama) {
                                        self.items[index].deskripsi = product.nama;
                                    }

                                    if (self.items[index].harga === 0 && product.harga_jual) {
                                        self.items[index].harga = parseFloat(product.harga_jual) || 0;
                                        $(`input[name="items[${index}][harga]"]`).val(self.items[index].harga);
                                    }

                                    if (!self.items[index].satuan_id && product.satuan_id) {
                                        self.items[index].satuan_id = product.satuan_id ? parseInt(product.satuan_id) : '';
                                        const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);
                                        if (satuanSelect.length && self.items[index].satuan_id) {
                                            satuanSelect.val(self.items[index].satuan_id).trigger('change.select2');
                                        }
                                    }
                                    self.updateSubtotal(index);
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching product details for API fallback:', error);
                            });
                    },

                    removeItem(index) {
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
                        return this.items.reduce((total, item) => total + (parseFloat(item.subtotal) || 0), 0);
                    },

                    calculateTotal() {
                        const items = this.items || [];
                        const subtotal = items.reduce((total, item) => total + parseFloat(item.subtotal || 0), 0);
                        const diskonGlobalPersen = parseFloat(this.diskonGlobalPersen) || 0;
                        const diskonGlobalNominal = parseFloat(this.diskonGlobalNominal) || 0;
                        const ongkosKirim = parseFloat(this.ongkosKirim) || 0;

                        let afterDiscount;
                        if (diskonGlobalPersen > 0) {
                            afterDiscount = subtotal - (subtotal * diskonGlobalPersen / 100);
                        } else {
                            afterDiscount = subtotal - diskonGlobalNominal;
                        }

                        const ppnRate = 11; // 11%
                        const ppnNominal = this.includePPN ? (afterDiscount * ppnRate / 100) : 0;

                        const total = Math.max(0, afterDiscount + ppnNominal + ongkosKirim);

                        return {
                            subtotal,
                            afterGlobalDiscount: afterDiscount,
                            ppnNominal,
                            total
                        };
                    },

                    formatRupiah(amount) {
                        const formatter = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        });

                        return formatter.format(amount || 0);
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
