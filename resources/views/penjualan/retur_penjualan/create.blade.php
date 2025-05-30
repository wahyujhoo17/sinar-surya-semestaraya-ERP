<x-app-layout>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Modern Select2 styling */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
            padding: 8px 12px;
            border-color: #D1D5DB;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #9CA3AF;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827;
            font-weight: 500;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6B7280;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
            display: flex;
            align-items: center;
            right: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6B7280 transparent transparent transparent;
            border-width: 5px 5px 0 5px;
            transition: transform 0.2s ease;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6B7280 transparent;
            border-width: 0 5px 5px 5px;
        }

        .select2-dropdown {
            border-color: #D1D5DB;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-top: 4px;
            animation: select2DropdownFadeIn 0.2s ease;
        }

        @keyframes select2DropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            transition: all 0.15s ease;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6366f1;
            color: white;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #EEF2FF;
            color: #4F46E5;
            font-weight: 500;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: #D1D5DB;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #6366f1;
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .select2-container--default .select2-search--dropdown {
            padding: 10px;
        }

        .select2-results__message {
            padding: 8px 12px;
            color: #6B7280;
            font-style: italic;
        }

        /* Dark mode styling */
        .dark .select2-container--default .select2-selection--single {
            background-color: #1F2937;
            border-color: #4B5563;
            color: #F9FAFB;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .dark .select2-container--default .select2-selection--single:hover {
            border-color: #6B7280;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #F9FAFB;
        }

        .dark .select2-dropdown {
            background-color: #1F2937;
            border-color: #4B5563;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
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
            background-color: #2D3748;
            color: #A5B4FC;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9CA3AF;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #9CA3AF transparent transparent transparent;
        }

        .dark .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #9CA3AF transparent;
        }

        .dark .select2-results__message {
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
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
        }

        .btn-primary:hover {
            background-color: #4f46e5;
            transform: translateY(-1px);
        }

        .summary-card {
            background: linear-gradient(to bottom right, #f9fafb, #f3f4f6);
            border-radius: 12px;
        }

        .dark .summary-card {
            background: linear-gradient(to bottom right, #1f2937, #111827);
        }
    </style>


    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Tambah Retur
                                Penjualan</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Buat retur penjualan baru untuk pengembalian barang dari customer
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('penjualan.retur.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden"
            x-data="returPenjualanForm()" x-init="init()">

            {{-- Display general errors --}}
            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Terjadi kesalahan dalam validasi form:
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Display success message --}}
            @if (session('success'))
                <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 dark:text-green-300">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Display error message --}}
            @if (session('error'))
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"></path>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('penjualan.retur.store') }}" @submit="handleSubmit">
                @csrf

                {{-- Header Information --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Retur</h3>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Basic Information --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nomor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nomor Retur <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nomor" name="nomor" value="{{ $nomorRetur }}" readonly
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Retur <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal"
                                value="{{ old('tanggal', date('Y-m-d')) }}" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tipe_retur"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipe Retur <span class="text-red-500">*</span>
                            </label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="tipe_retur" value="pengembalian_dana"
                                        {{ old('tipe_retur', 'pengembalian_dana') == 'pengembalian_dana' ? 'checked' : '' }}
                                        class="form-radio h-5 w-5 text-primary-600 border-gray-300 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-500">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Pengembalian Dana</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="tipe_retur" value="tukar_barang"
                                        {{ old('tipe_retur') == 'tukar_barang' ? 'checked' : '' }}
                                        class="form-radio h-5 w-5 text-primary-600 border-gray-300 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-500">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Tukar Barang</span>
                                </label>
                            </div>
                            @error('tipe_retur')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="customer_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Customer <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </span>
                                <select id="customer_id" name="customer_id" x-model="selectedCustomer"
                                    @change="loadSalesOrders" required x-ref="customerSelect"
                                    class="select2-customer w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white shadow-sm">
                                    <option value="">Pilih Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->kode }} - {{ $customer->company ?? $customer->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('customer_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="relative">
                            <label for="sales_order_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Sales Order <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                        </path>
                                    </svg>
                                </span>
                                <select id="sales_order_id" name="sales_order_id" x-model="selectedSalesOrder"
                                    @change="loadSalesOrderDetails" required x-ref="salesOrderSelect"
                                    class="select2-sales-order w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white shadow-sm"
                                    :disabled="!selectedCustomer">
                                    <option value="">Pilih Sales Order</option>
                                    <template x-for="so in salesOrders" :key="so.id">
                                        <option :value="so.id"
                                            x-text="`${so.nomor} - ${so.tanggal_formatted}`">
                                        </option>
                                    </template>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"
                                    x-show="!selectedCustomer">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            @error('sales_order_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Catatan
                        </label>
                        <textarea id="catatan" name="catatan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            placeholder="Catatan tambahan untuk retur ini...">{{ old('catatan') }}</textarea>
                    </div>

                    {{-- Product Details Section --}}
                    <div x-show="soDetails.length > 0" x-transition class="border-t pt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Produk</h3>
                            <div class="flex space-x-2">
                                <button type="button" @click="addItemFromSalesOrder"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Item
                                </button>
                            </div>
                        </div>

                        <!-- Card-based layout for products -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <template x-for="(item, index) in soDetails" :key="index">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 item-card">
                                    <div class="flex justify-between items-start mb-3">
                                        <h4 class="font-medium text-gray-900 dark:text-white"
                                            x-text="item.produk_nama"></h4>
                                        <button type="button" @click="removeItem(index)"
                                            class="text-gray-400 hover:text-red-500 transition-colors duration-150">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <input type="hidden" :name="`details[${index}][produk_id]`"
                                        :value="item.produk_id">
                                    <input type="hidden" :name="`details[${index}][satuan_id]`"
                                        :value="item.satuan_id">

                                    <div class="space-y-3">
                                        <div
                                            class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-300">
                                            <span>Qty Dikirim:</span>
                                            <span class="font-medium"
                                                x-text="formatNumber(item.quantity_terkirim)"></span>
                                        </div>

                                        <div class="flex justify-between items-center text-sm">
                                            <label :for="`qty_retur_${index}`"
                                                class="text-gray-600 dark:text-gray-300">Qty Retur:</label>
                                            <div class="flex items-center">
                                                <input type="number" :id="`qty_retur_${index}`"
                                                    :name="`details[${index}][quantity]`"
                                                    class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-right"
                                                    min="0" :max="item.quantity_terkirim" step="0.01"
                                                    x-model="item.quantity_retur"
                                                    @input="validateReturQuantity(item)">
                                                <span class="ml-2 text-gray-600 dark:text-gray-300"
                                                    x-text="item.satuan_nama"></span>
                                            </div>
                                        </div>
                                        <div x-show="item.quantityError" class="text-red-500 text-xs mt-1"
                                            x-text="item.quantityError"></div>

                                        <div>
                                            <label :for="`alasan_${index}`"
                                                class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Alasan:</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <select :id="`alasan_${index}`" :name="`details[${index}][alasan]`"
                                                    class="select2-alasan w-full pl-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white shadow-sm"
                                                    x-model="item.alasan" required :data-index="index">
                                                    <option value="">Pilih Alasan</option>
                                                    <option value="Rusak">Barang Rusak</option>
                                                    <option value="Cacat">Barang Cacat</option>
                                                    <option value="Salah Kirim">Salah Kirim</option>
                                                    <option value="Tidak Sesuai">Tidak Sesuai Pesanan</option>
                                                    <option value="Kelebihan">Kelebihan Kirim</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label :for="`keterangan_${index}`"
                                                class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Keterangan:</label>
                                            <textarea :id="`keterangan_${index}`" :name="`details[${index}][keterangan]`"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                                placeholder="Keterangan tambahan..." x-model="item.keterangan" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Products Modal -->
                        <div x-show="showProductsModal" class="fixed inset-0 overflow-y-auto z-50"
                            style="display: none;">
                            <div
                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div x-show="showProductsModal" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity"
                                    aria-hidden="true">
                                    <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                                </div>

                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                    aria-hidden="true">&#8203;</span>

                                <div x-show="showProductsModal" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                    <div class="absolute top-0 right-0 pt-4 pr-4">
                                        <button @click="showProductsModal = false" type="button"
                                            class="bg-white dark:bg-gray-800 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <span class="sr-only">Close</span>
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <div>
                                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                                Filter Produk
                                            </h3>
                                            <div class="mt-4">
                                                <div class="mb-4">
                                                    <label for="filter-product"
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Cari Produk
                                                    </label>
                                                    <input type="text" id="filter-product"
                                                        x-model="productSearchTerm"
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                                        placeholder="Ketik nama produk...">
                                                </div>

                                                <div class="mb-4">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Filter Alasan
                                                    </label>
                                                    <select x-model="filterAlasan"
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                                        <option value="">Semua Alasan</option>
                                                        <option value="Rusak">Barang Rusak</option>
                                                        <option value="Cacat">Barang Cacat</option>
                                                        <option value="Salah Kirim">Salah Kirim</option>
                                                        <option value="Tidak Sesuai">Tidak Sesuai Pesanan</option>
                                                        <option value="Kelebihan">Kelebihan Kirim</option>
                                                        <option value="Lainnya">Lainnya</option>
                                                    </select>
                                                </div>

                                                <div class="mb-4">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                        Urutkan Berdasarkan
                                                    </label>
                                                    <select x-model="sortBy"
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                                                        <option value="produk_nama">Nama Produk</option>
                                                        <option value="quantity_terkirim">Quantity Terkirim</option>
                                                        <option value="quantity_retur">Quantity Retur</option>
                                                    </select>
                                                </div>

                                                <div class="flex items-center justify-end space-x-3 mt-6">
                                                    <button @click="resetFilters" type="button"
                                                        class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                                                        Reset
                                                    </button>
                                                    <button @click="applyFilters" type="button"
                                                        class="px-4 py-2 bg-primary-600 border border-transparent text-sm font-medium rounded-md text-white hover:bg-primary-700">
                                                        Terapkan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button"
                                class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200"
                                @click="resetForm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors duration-200"
                                :class="{ 'opacity-50 cursor-not-allowed': !isFormValidCheck() }">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Retur
                            </button>
                        </div>
                    </div>

                    <div x-show="soDetails.length === 0 && selectedSalesOrder"
                        class="border-t pt-6 text-center py-10 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada produk tersedia
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sales order ini tidak memiliki produk
                            yang dikirim atau sudah di-retur semua.</p>
                    </div>

                    <div x-show="!selectedSalesOrder"
                        class="border-t pt-6 text-center py-10 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Pilih Sales Order</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih customer dan sales order untuk
                            memulai.</p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <!-- jQuery (required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            function returPenjualanForm() {
                return {
                    selectedCustomer: "{{ old('customer_id', '') }}",
                    selectedSalesOrder: "{{ old('sales_order_id', '') }}",
                    salesOrders: [],
                    soDetails: [],
                    originalSoDetails: [],
                    showProductsModal: false,
                    productSearchTerm: '',
                    filterAlasan: '',
                    sortBy: 'produk_nama',
                    lastItemId: 0,

                    async init() {
                        // Initialize Select2 on document ready
                        this.$nextTick(() => {
                            this.initSelect2();

                            if (this.selectedCustomer) {
                                this.loadSalesOrders();
                            }
                        });
                    },
                    initSelect2() {
                        // Initialize Select2 for customer dropdown with improved UI
                        $(this.$refs.customerSelect).select2({
                            placeholder: 'Pilih Customer',
                            allowClear: true,
                            width: '100%',
                            templateResult: this.formatCustomerResult,
                            templateSelection: this.formatCustomerSelection,
                            escapeMarkup: function(markup) {
                                return markup;
                            }
                        }).on('select2:select', (e) => {
                            this.selectedCustomer = e.target.value;
                            this.loadSalesOrders();
                        }).on('select2:clear', () => {
                            this.selectedCustomer = '';
                            this.loadSalesOrders();
                        });

                        // Initialize Select2 for sales order dropdown
                        $(this.$refs.salesOrderSelect).select2({
                            placeholder: 'Pilih Sales Order',
                            allowClear: true,
                            width: '100%',
                            templateResult: this.formatSalesOrderResult,
                            templateSelection: this.formatSalesOrderSelection,
                            escapeMarkup: function(markup) {
                                return markup;
                            },
                            disabled: !this.selectedCustomer
                        }).on('select2:select', (e) => {
                            this.selectedSalesOrder = e.target.value;
                            this.loadSalesOrderDetails();
                        }).on('select2:clear', () => {
                            this.selectedSalesOrder = '';
                            this.soDetails = [];
                        });

                        // Set initial values if present
                        if (this.selectedCustomer) {
                            $(this.$refs.customerSelect).val(this.selectedCustomer).trigger('change');
                        }
                    },

                    // Format the customer dropdown items
                    formatCustomerResult(customer) {
                        return customer.text;
                    },

                    // Format the selected customer
                    formatCustomerSelection(customer) {
                        return customer.text;
                    },

                    // Format the sales order dropdown items
                    formatSalesOrderResult(salesOrder) {
                        if (!salesOrder.id) return salesOrder.text;
                        const salesOrderText = salesOrder.text || '';
                        const parts = salesOrderText.split(' - ');
                        if (parts.length < 2) return salesOrderText;

                        return `<div class="flex items-center py-1">
                                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center text-green-600 dark:text-green-300 font-semibold text-xs">
                                        SO
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900 dark:text-white">${parts[0]}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Tanggal: ${parts[1]}</div>
                                    </div>
                                </div>`;
                    },

                    // Format the selected sales order
                    formatSalesOrderSelection(salesOrder) {
                        if (!salesOrder.id) return salesOrder.text;
                        const salesOrderText = salesOrder.text || '';
                        const parts = salesOrderText.split(' - ');
                        if (parts.length < 2) return salesOrderText;

                        return `<span class="font-medium">${parts[0]}</span> <span class="text-gray-500 text-sm">(${parts[1]})</span>`;
                    },

                    async loadSalesOrders() {
                        if (!this.selectedCustomer) {
                            this.salesOrders = [];
                            this.selectedSalesOrder = '';
                            this.soDetails = [];
                            // Update Select2 for sales order
                            $(this.$refs.salesOrderSelect).empty().prop('disabled', true);
                            $(this.$refs.salesOrderSelect).select2({
                                placeholder: 'Pilih Sales Order',
                                disabled: true
                            });
                            return;
                        }

                        try {
                            const response = await fetch(
                                `{{ route('penjualan.retur.get-sales-orders') }}?customer_id=${this.selectedCustomer}`);
                            const data = await response.json();
                            // Tambahkan tanggal_formatted pada setiap sales order
                            this.salesOrders = data.salesOrders.map(so => ({
                                ...so,
                                tanggal_formatted: new Date(so.tanggal).toLocaleDateString('id-ID')
                            }));

                            // Reset selected sales order and details
                            this.selectedSalesOrder = '';
                            this.soDetails = [];

                            // Update Select2 for sales order
                            $(this.$refs.salesOrderSelect).empty().prop('disabled', false);
                            $(this.$refs.salesOrderSelect).append(new Option('Pilih Sales Order', '', true, true));

                            this.salesOrders.forEach(so => {
                                const option = new Option(
                                    `${so.nomor} - ${so.tanggal_formatted}`,
                                    so.id,
                                    false,
                                    false
                                );
                                $(this.$refs.salesOrderSelect).append(option);
                            });

                            $(this.$refs.salesOrderSelect).trigger('change');
                        } catch (error) {
                            console.error('Error loading sales orders:', error);
                        }
                    },

                    async loadSalesOrderDetails() {
                        if (!this.selectedSalesOrder) {
                            this.soDetails = [];
                            this.originalSoDetails = [];
                            return;
                        }

                        try {
                            const response = await fetch(
                                `{{ route('penjualan.retur.get-sales-order-details') }}?sales_order_id=${this.selectedSalesOrder}`
                            );
                            const data = await response.json();

                            // Handle both formats: direct array or {details: [...]} format
                            let detailsArray;

                            if (Array.isArray(data)) {
                                // API returned a direct array of details
                                detailsArray = data;
                                console.log('API returned array format:', data);
                            } else if (data && data.details && Array.isArray(data.details)) {
                                // API returned {details: [...]} format
                                detailsArray = data.details;
                                console.log('API returned object.details format:', data.details);
                            } else {
                                console.error('Invalid data format received:', data);
                                // Set empty arrays if no valid data format found
                                this.soDetails = [];
                                this.originalSoDetails = [];

                                // Show notification to user
                                const notification = document.createElement('div');
                                notification.className =
                                    'fixed top-4 right-4 bg-amber-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-500 ease-in-out';
                                notification.innerHTML = `
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium">Sales order ini tidak memiliki detail produk atau data tidak valid.</span>
                                    </div>
                                `;
                                document.body.appendChild(notification);

                                // Animate in
                                setTimeout(() => {
                                    notification.style.transform = 'translateX(0)';
                                }, 10);

                                // Remove after 4 seconds
                                setTimeout(() => {
                                    notification.style.transform = 'translateX(100%)';
                                    setTimeout(() => {
                                        notification.remove();
                                    }, 500);
                                }, 4000);

                                return;
                            }

                            // Now safely map the data since we've validated it exists and is an array
                            this.soDetails = detailsArray.map(item => ({
                                ...item,
                                id: ++this.lastItemId,
                                quantity_retur: 0,
                                alasan: '',
                                keterangan: '',
                                quantityError: null,
                                isCustomItem: false
                            }));

                            // Keep a copy of the original data for filtering
                            this.originalSoDetails = [...this.soDetails];

                            // Initialize Select2 for alasan dropdowns with a delay
                            setTimeout(() => {
                                this.initAlasanSelect2();
                            }, 100);
                        } catch (error) {
                            console.error('Error loading sales order details:', error);
                            // Display user-friendly error message
                            const notification = document.createElement('div');
                            notification.className =
                                'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-500 ease-in-out';
                            notification.innerHTML = `
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">Terjadi kesalahan saat memuat detail sales order. Silakan coba lagi.</span>
                                </div>
                            `;
                            document.body.appendChild(notification);

                            // Animate in
                            setTimeout(() => {
                                notification.style.transform = 'translateX(0)';
                            }, 10);

                            // Remove after 4 seconds
                            setTimeout(() => {
                                notification.style.transform = 'translateX(100%)';
                                setTimeout(() => {
                                    notification.remove();
                                }, 500);
                            }, 4000);
                        }
                    },

                    initAlasanSelect2() {
                        $('.select2-alasan').each((i, elem) => {
                            $(elem).select2({
                                placeholder: 'Pilih Alasan',
                                allowClear: true,
                                width: '100%',
                                minimumResultsForSearch: -1, // Disable search for short lists
                                templateResult: this.formatAlasanResult,
                                templateSelection: this.formatAlasanSelection,
                                escapeMarkup: function(markup) {
                                    return markup;
                                }
                            }).on('select2:select', (e) => {
                                // Use data-index for robust item identification
                                const index = $(elem).data('index');
                                if (this.soDetails[index]) {
                                    this.soDetails[index].alasan = e.target.value;
                                }
                            });
                        });
                    },

                    // Format the alasan dropdown items with color coding and icons
                    formatAlasanResult(alasan) {
                        if (!alasan.id) return alasan.text;

                        let icon, color;
                        switch (alasan.id) {
                            case 'Rusak':
                                icon =
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                                color = 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30';
                                break;
                            case 'Cacat':
                                icon =
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>';
                                color = 'text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/30';
                                break;
                            case 'Salah Kirim':
                                icon =
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>';
                                color = 'text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30';
                                break;
                            case 'Tidak Sesuai':
                                icon =
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                                color = 'text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30';
                                break;
                            case 'Kelebihan':
                                icon =
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>';
                                color = 'text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-900/30';
                                break;
                            case 'Lainnya':
                                icon =
                                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                                color = 'text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-900/30';
                                break;
                            default:
                                icon = '';
                                color = 'text-gray-600 dark:text-gray-400';
                        }

                        return `<div class="flex items-center py-1">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center ${color}">
                                        ${icon}
                                    </div>
                                    <div class="ml-2 font-medium">${alasan.text}</div>
                                </div>`;
                    },

                    // Format the selected alasan with color coding
                    formatAlasanSelection(alasan) {
                        if (!alasan.id) return alasan.text;

                        let badgeColor;
                        switch (alasan.id) {
                            case 'Rusak':
                                badgeColor = 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300';
                                break;
                            case 'Cacat':
                                badgeColor = 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300';
                                break;
                            case 'Salah Kirim':
                                badgeColor = 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300';
                                break;
                            case 'Tidak Sesuai':
                                badgeColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300';
                                break;
                            case 'Kelebihan':
                                badgeColor = 'bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-300';
                                break;
                            case 'Lainnya':
                                badgeColor = 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300';
                                break;
                            default:
                                badgeColor = '';
                        }

                        return `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${badgeColor}">
                                    ${alasan.text}
                                </span>`;
                    },

                    validateReturQuantity(item) {
                        if (parseFloat(item.quantity_retur) < 0) {
                            item.quantityError = 'Quantity tidak boleh negatif';
                            item.quantity_retur = 0;
                        } else if (!item.isCustomItem && parseFloat(item.quantity_retur) > parseFloat(item.quantity_terkirim)) {
                            item.quantityError = 'Quantity tidak boleh melebihi quantity terkirim';
                            item.quantity_retur = item.quantity_terkirim;
                        } else {
                            item.quantityError = null;
                        }
                    },

                    removeItem(index) {
                        if (confirm('Anda yakin ingin menghapus item ini?')) {
                            this.soDetails.splice(index, 1);

                            // Re-initialize Select2 after removing an item
                            setTimeout(() => {
                                this.initAlasanSelect2();
                            }, 100);
                        }
                    },

                    addItemFromSalesOrder() {
                        // Cari item dari originalSoDetails yang belum ada di soDetails (belum diretur)
                        const existingProdukIds = this.soDetails.map(item => item.produk_id);
                        const available = this.originalSoDetails.find(item => !existingProdukIds.includes(item.produk_id));
                        if (!available) {
                            alert('Semua item dari sales order sudah ditambahkan.');
                            return;
                        }
                        // Salin data item dan tambahkan ke soDetails
                        const newItem = {
                            ...available,
                            id: ++this.lastItemId,
                            quantity_retur: 0,
                            alasan: '',
                            keterangan: '',
                            quantityError: null,
                            isCustomItem: false
                        };
                        this.soDetails.push(newItem);
                        setTimeout(() => {
                            this.initAlasanSelect2();
                        }, 100);
                    },

                    resetFilters() {
                        this.productSearchTerm = '';
                        this.filterAlasan = '';
                        this.sortBy = 'produk_nama';
                    },

                    applyFilters() {
                        // First, reset to original data
                        this.soDetails = [...this.originalSoDetails];

                        // Apply search filter
                        if (this.productSearchTerm) {
                            const searchTerm = this.productSearchTerm.toLowerCase();
                            this.soDetails = this.soDetails.filter(item =>
                                item.produk_nama.toLowerCase().includes(searchTerm)
                            );
                        }

                        // Apply alasan filter
                        if (this.filterAlasan) {
                            this.soDetails = this.soDetails.filter(item =>
                                item.alasan === this.filterAlasan
                            );
                        }

                        // Apply sorting
                        this.soDetails.sort((a, b) => {
                            if (this.sortBy === 'produk_nama') {
                                return a.produk_nama.localeCompare(b.produk_nama);
                            } else if (this.sortBy === 'quantity_terkirim') {
                                return parseFloat(b.quantity_terkirim) - parseFloat(a.quantity_terkirim);
                            } else if (this.sortBy === 'quantity_retur') {
                                return parseFloat(b.quantity_retur) - parseFloat(a.quantity_retur);
                            }
                            return 0;
                        });

                        this.showProductsModal = false;

                        // Re-initialize Select2 after filtering
                        setTimeout(() => {
                            this.initAlasanSelect2();
                        }, 100);
                    },

                    isFormValidCheck() {
                        if (!this.selectedCustomer) {
                            return false;
                        }
                        // Pastikan ada minimal satu item dengan quantity_retur > 0 dan alasan terisi
                        const hasItems = this.soDetails.some(item =>
                            parseFloat(item.quantity_retur) > 0 && item.alasan && item.alasan !== ''
                        );
                        // Pastikan semua item yang quantity_retur > 0 harus ada alasan
                        const allItemsValid = this.soDetails.every(item =>
                            parseFloat(item.quantity_retur) === 0 || (parseFloat(item.quantity_retur) > 0 && item.alasan &&
                                item.alasan !== '')
                        );
                        // Pastikan quantity_retur tidak null/undefined
                        const allQtyValid = this.soDetails.every(item =>
                            item.quantity_retur !== null && item.quantity_retur !== undefined && !isNaN(parseFloat(item
                                .quantity_retur))
                        );
                        return hasItems && allItemsValid && allQtyValid;
                    },

                    resetForm() {
                        this.soDetails = this.soDetails.map(item => ({
                            ...item,
                            quantity_retur: 0,
                            alasan: '',
                            keterangan: '',
                            quantityError: null
                        }));

                        // Reset Select2 dropdowns for alasan
                        $('.select2-alasan').val(null).trigger('change');
                    },

                    handleSubmit(e) {
                        if (!this.isFormValidCheck()) {
                            e.preventDefault();

                            // Show a more stylish notification instead of alert
                            const notification = document.createElement('div');
                            notification.className =
                                'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-500 ease-in-out';
                            notification.innerHTML = `
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">Mohon lengkapi semua data yang diperlukan.</span>
                                </div>
                            `;

                            document.body.appendChild(notification);

                            // Animate in
                            setTimeout(() => {
                                notification.style.transform = 'translateX(0)';
                            }, 10);

                            // Remove after 4 seconds
                            setTimeout(() => {
                                notification.style.transform = 'translateX(100%)';
                                setTimeout(() => {
                                    notification.remove();
                                }, 500);
                            }, 4000);
                        }
                    },

                    formatNumber(value) {
                        return parseFloat(value).toLocaleString('id-ID');
                    },

                    formatDate(dateString) {
                        const date = new Date(dateString);
                        return date.toLocaleDateString('id-ID');
                    }
                };
            }
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2 for static elements with modern styling
                $('.select2-customer').select2({
                    placeholder: 'Pilih Customer',
                    allowClear: true,
                    width: '100%',
                    templateResult: formatCustomerResult,
                    templateSelection: formatCustomerSelection,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                $('.select2-sales-order').select2({
                    placeholder: 'Pilih Sales Order',
                    allowClear: true,
                    width: '100%',
                    templateResult: formatSalesOrderResult,
                    templateSelection: formatSalesOrderSelection,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });

                // Helper functions for initial Select2 rendering
                function formatCustomerResult(customer) {
                    return customer.text;
                }

                function formatCustomerSelection(customer) {
                    return customer.text;
                }

                function formatSalesOrderResult(salesOrder) {
                    if (!salesOrder.id) return salesOrder.text;
                    return salesOrder.text;
                }

                function formatSalesOrderSelection(salesOrder) {
                    if (!salesOrder.id) return salesOrder.text;
                    return salesOrder.text;
                }

                // Add floating labels effect
                $('.select2-container--default .select2-selection--single').addClass('transition-all duration-200');

                // Add animation when dropdown opens
                $(document).on('select2:open', () => {
                    document.querySelector('.select2-search__field').focus();
                });
            });
        </script>
    @endpush
</x-app-layout>
