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
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Edit Retur
                                Penjualan</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Edit retur penjualan #{{ $returPenjualan->nomor }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('penjualan.retur.show', $returPenjualan->id) }}"
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
            x-data="returPenjualanEditForm()" x-init="init()">

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

            <form method="POST" action="{{ route('penjualan.retur.update', $returPenjualan->id) }}"
                @submit="handleSubmit">
                @csrf
                @method('PUT')

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
                            <input type="text" id="nomor" name="nomor" value="{{ $returPenjualan->nomor }}"
                                readonly
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label for="tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tanggal Retur <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal"
                                value="{{ old('tanggal', $returPenjualan->tanggal) }}" required
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
                                        {{ old('tipe_retur', $returPenjualan->tipe_retur) == 'pengembalian_dana' ? 'checked' : '' }}
                                        class="form-radio h-5 w-5 text-primary-600 border-gray-300 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-500">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Pengembalian Dana</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="tipe_retur" value="tukar_barang"
                                        {{ old('tipe_retur', $returPenjualan->tipe_retur) == 'tukar_barang' ? 'checked' : '' }}
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
                                            {{ old('customer_id', $returPenjualan->customer_id) == $customer->id ? 'selected' : '' }}>
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
                                    class="select2-sales-order w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white shadow-sm">
                                    <option value="">Pilih Sales Order</option>
                                    @foreach ($salesOrders as $so)
                                        <option value="{{ $so->id }}"
                                            {{ old('sales_order_id', $returPenjualan->sales_order_id) == $so->id ? 'selected' : '' }}>
                                            {{ $so->nomor }} -
                                            {{ \Carbon\Carbon::parse($so->tanggal)->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
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
                            placeholder="Catatan tambahan untuk retur ini...">{{ old('catatan', $returPenjualan->catatan) }}</textarea>
                    </div>

                    {{-- Product Details Section --}}
                    <div x-show="items.length > 0" x-transition class="border-t pt-6">
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
                                <button type="button" @click="debugSoDetails"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Debug Data
                                </button>
                            </div>
                        </div>

                        <!-- Card-based layout for products -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <template x-for="(item, index) in items" :key="index">
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
                                                x-text="formatNumber(getDeliveredQty(item))"></span>
                                        </div>

                                        <div class="flex justify-between items-center text-sm">
                                            <label :for="`qty_retur_${index}`"
                                                class="text-gray-600 dark:text-gray-300">Qty Retur:</label>
                                            <div class="flex items-center">
                                                <input type="number" :id="`qty_retur_${index}`"
                                                    :name="`details[${index}][quantity]`"
                                                    class="w-24 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white text-right"
                                                    min="0" :max="getDeliveredQty(item)" step="0.01"
                                                    x-model="item.qty" @input="calculateTotals">
                                                <span class="ml-2 text-gray-600 dark:text-gray-300"
                                                    x-text="item.satuan_nama"></span>
                                            </div>
                                        </div>
                                        <!-- Optionally add validation error here if needed -->

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
                    </div>

                    {{-- Form Actions --}}
                    <div
                        class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('penjualan.retur.show', $returPenjualan->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Batal
                        </a>
                        <button type="submit" x-bind:disabled="!items.length || isSubmitting"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">Update Retur</span>
                            <span x-show="isSubmitting">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <!-- jQuery (must be loaded before Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function returPenjualanEditForm() {
                return {
                    selectedCustomer: '{{ old('customer_id', $returPenjualan->customer_id) }}',
                    selectedSalesOrder: '{{ old('sales_order_id', $returPenjualan->sales_order_id) }}',
                    salesOrders: @json($salesOrders),
                    soDetails: [],
                    items: {!! json_encode(
                        old(
                            'details',
                            $returPenjualan->details->map(function ($detail) {
                                    // PENTING: Gunakan prioritas dan format yang SAMA dengan fungsi addItemFromSalesOrder
                                    // Ambil data asli dari database untuk qty SO & delivered
                                    $qtySo = (float) ($detail->qty_so ?? 0);
                    
                                    // Dapatkan qty asli yang dikirimkan dari database
                                    $qtyTerkirim = (float) ($detail->qty_terkirim ?? 0);
                                    $quantityTerkirim = (float) ($detail->quantity_terkirim ?? 0);
                    
                                    // Gunakan nilai terkirim yang benar sesuai prioritas
                                    // PENTING: Prioritas harus sama dengan yang di function getDeliveredQty()
                                    $deliveredQty = 10; // Nilai default untuk testing
                                    if ($qtyTerkirim > 0) {
                                        $deliveredQty = $qtyTerkirim;
                                    } elseif ($quantityTerkirim > 0) {
                                        $deliveredQty = $quantityTerkirim;
                                    } elseif ($qtySo > 0) {
                                        $deliveredQty = $qtySo;
                                    }
                    
                                    return [
                                        'produk_id' => $detail->produk_id,
                                        'produk_kode' => $detail->produk->kode,
                                        'produk_nama' => $detail->produk->nama,
                                        'qty' => (float) $detail->quantity, // qty retur (tidak berubah)
                                        // Pastikan kedua field qty_terkirim dan quantity_terkirim sama
                                        'qty_terkirim' => $deliveredQty,
                                        'quantity_terkirim' => $deliveredQty,
                                        'qty_so' => $qtySo,
                                        'harga' => (float) $detail->harga,
                                        'satuan_id' => $detail->satuan_id ?? ($detail->produk->satuan_id ?? null),
                                        'satuan_nama' => $detail->produk->satuan->nama ?? '',
                                        'alasan' => $detail->alasan ?? '',
                                        'keterangan' => $detail->keterangan ?? '',
                                    ];
                                })->toArray(),
                        ),
                    ) !!},
                    totalQty: 0,
                    totalValue: 0,
                    isSubmitting: false, // Initialize with a quick check for NaN/undefined values
                    init() {
                        this.calculateTotals();
                        // Debug any item with potential quantity issues and normalize all quantity values
                        this.items.forEach((item, idx) => {
                            // Ensure numeric values
                            item.qty = parseFloat(item.qty) || 0;
                            item.qty_terkirim = parseFloat(item.qty_terkirim) || 0;
                            item.quantity_terkirim = parseFloat(item.quantity_terkirim) || 0;
                            item.qty_so = parseFloat(item.qty_so) || 0;

                            // Use the getDeliveredQty helper to normalize the qty_terkirim value
                            const normalizedQty = this.getDeliveredQty(item);

                            // Make sure both qty_terkirim and quantity_terkirim have the normalized value
                            item.qty_terkirim = normalizedQty;
                            item.quantity_terkirim = normalizedQty;

                            // Log any suspicious values
                            if (normalizedQty <= 0) {
                                console.warn(
                                    `Item #${idx} (${item.produk_nama}) has no valid quantity values after normalization`
                                );
                            } else {
                                console.log(`Item #${idx} (${item.produk_nama}) normalized qty: ${normalizedQty}`);
                            }
                        });

                        // Initialize Select2 after Alpine.js initializes
                        this.$nextTick(() => {
                            this.initializeSelect2();

                            // Load sales order details if sales order is already selected
                            if (this.selectedSalesOrder) {
                                this.loadSalesOrderDetails();
                            }
                        });
                    },

                    initializeSelect2() {
                        $('.select2-customer').select2({
                            placeholder: 'Pilih Customer',
                            allowClear: true,
                            width: '100%'
                        }).on('change', (e) => {
                            this.selectedCustomer = e.target.value;
                            this.loadSalesOrders();
                        });

                        $('.select2-sales-order').select2({
                            placeholder: 'Pilih Sales Order',
                            allowClear: true,
                            width: '100%'
                        }).on('change', (e) => {
                            this.selectedSalesOrder = e.target.value;
                            this.loadSalesOrderDetails();
                        });
                    },

                    loadSalesOrders() {
                        if (!this.selectedCustomer) {
                            this.salesOrders = [];
                            this.selectedSalesOrder = '';
                            this.soDetails = [];
                            this.updateSalesOrderSelect();
                            return;
                        }

                        fetch(`{{ route('penjualan.retur.get-sales-orders') }}?customer_id=${this.selectedCustomer}`)
                            .then(response => response.json())
                            .then(data => {
                                this.salesOrders = data;
                                this.selectedSalesOrder = '';
                                this.soDetails = [];
                                this.updateSalesOrderSelect();
                            })
                            .catch(error => {
                                console.error('Error loading sales orders:', error);
                                this.salesOrders = [];
                            });
                    },

                    loadSalesOrderDetails() {
                        if (!this.selectedSalesOrder) {
                            this.soDetails = [];
                            return;
                        }

                        fetch(
                                `{{ route('penjualan.retur.get-sales-order-details') }}?sales_order_id=${this.selectedSalesOrder}`
                            )
                            .then(response => response.json())
                            .then(data => {
                                // Ensure we have a valid array
                                let details = Array.isArray(data) ? data : [];

                                // Pre-process the details to ensure consistent data
                                details = details.map(detail => {
                                    // Make sure all quantity values are properly set as numbers
                                    // First, extract all relevant numeric values
                                    const qtyTerkirim = parseFloat(detail.qty_terkirim || 0);
                                    const quantityTerkirim = parseFloat(detail.quantity_terkirim || 0);
                                    const qtySo = parseFloat(detail.qty_so || 0);
                                    const qty = parseFloat(detail.qty || 0);

                                    // Use the largest non-zero value for the delivered quantity
                                    let deliveredQty = 0;
                                    if (qtyTerkirim > 0) deliveredQty = qtyTerkirim;
                                    else if (quantityTerkirim > 0) deliveredQty = quantityTerkirim;
                                    else if (qty > 0) deliveredQty = qty;
                                    else if (qtySo > 0) deliveredQty = qtySo;

                                    // Ensure all quantity fields are set consistently
                                    detail.qty_terkirim = deliveredQty;
                                    detail.quantity_terkirim = deliveredQty;
                                    detail.qty_so = qtySo > 0 ? qtySo : deliveredQty;
                                    detail.qty = deliveredQty;

                                    return detail;
                                });

                                this.soDetails = details;

                                // Debug - log data structure to console
                                console.log('Sales Order Details after processing:', this.soDetails);

                                // Check if we have quantity_terkirim data
                                if (this.soDetails.length > 0) {
                                    console.log('First item quantity values:', {
                                        quantity_terkirim: this.soDetails[0].quantity_terkirim,
                                        qty_terkirim: this.soDetails[0].qty_terkirim,
                                        qty_so: this.soDetails[0].qty_so,
                                        qty: this.soDetails[0].qty
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Error loading sales order details:', error);
                                this.soDetails = [];
                            });
                    },

                    updateSalesOrderSelect() {
                        const salesOrderSelect = $('#sales_order_id');
                        salesOrderSelect.empty();
                        salesOrderSelect.append('<option value="">Pilih Sales Order</option>');

                        this.salesOrders.forEach(so => {
                            const option = new Option(
                                `${so.nomor} - ${so.tanggal_formatted}`,
                                so.id,
                                false,
                                so.id == this.selectedSalesOrder
                            );
                            salesOrderSelect.append(option);
                        });

                        salesOrderSelect.trigger('change.select2');
                    },

                    addItemFromSalesOrder() {
                        // Filter produk yang belum ada di items
                        const existingProdukIds = this.items.map(item => item.produk_id);
                        const availableProducts = this.soDetails.filter(detail => !existingProdukIds.includes(detail
                            .produk_id));

                        if (availableProducts.length === 0) {
                            alert('Semua produk dari sales order sudah ditambahkan.');
                            return;
                        }

                        // Cari item yang tersedia pertama
                        const available = availableProducts[0];

                        console.log('Adding item from sales order:', available);

                        // Use our helper function to get the delivered quantity
                        const deliveredQty = this.getDeliveredQty(available);
                        console.log('Delivered quantity:', deliveredQty);

                        // Tambahkan produk baru ke items
                        const newItem = {
                            produk_id: available.produk_id,
                            produk_kode: available.produk_kode,
                            produk_nama: available.produk_nama,
                            qty: 0, // Start with 0 for user input
                            qty_terkirim: deliveredQty,
                            quantity_terkirim: deliveredQty,
                            qty_so: parseFloat(available.qty_so || available.quantity || 0),
                            harga: parseFloat(available.harga || available.harga_satuan || 0),
                            satuan_id: available.satuan_id || null,
                            satuan_nama: available.satuan_nama || '',
                            alasan: '',
                            keterangan: ''
                        };

                        this.items.push(newItem);
                        this.calculateTotals();

                        // Inisialisasi Select2 alasan setelah tambah item
                        setTimeout(() => {
                            $('.select2-alasan').select2({
                                placeholder: 'Pilih Alasan',
                                allowClear: true,
                                width: '100%'
                            });
                        }, 100);
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                        this.calculateTotals();
                    },

                    calculateTotals() {
                        this.totalQty = this.items.reduce((sum, item) => sum + (parseFloat(item.qty) || 0), 0);
                        this.totalValue = this.items.reduce((sum, item) => sum + ((parseFloat(item.qty) || 0) * (parseFloat(item
                            .harga) || 0)), 0);
                    },

                    // Add debug methods
                    debugSoDetails() {
                        console.table(this.soDetails);

                        if (this.soDetails.length > 0) {
                            const item = this.soDetails[0];
                            const qtyTerkirim = parseFloat(item.qty_terkirim || 0);
                            const quantityTerkirim = parseFloat(item.quantity_terkirim || 0);
                            const qtySo = parseFloat(item.qty_so || 0);
                            const qty = parseFloat(item.qty || 0);

                            const message =
                                `First SO detail: ${item.produk_nama}\n` +
                                `- qty_terkirim: ${qtyTerkirim.toFixed(2)}\n` +
                                `- quantity_terkirim: ${quantityTerkirim.toFixed(2)}\n` +
                                `- qty_so: ${qtySo.toFixed(2)}\n` +
                                `- qty: ${qty.toFixed(2)}\n` +
                                `- getDeliveredQty value: ${this.getDeliveredQty(item).toFixed(2)}`;

                            alert(message);
                        } else {
                            alert('No SO details available');
                        }
                    },

                    // Helper function to get the delivered quantity with fallbacks
                    getDeliveredQty(item) {
                        // Jika sudah ada nilai yang dinormalisasi, gunakan nilai tersebut
                        // (ini terjadi di init() atau saat mengambil data dari server)
                        if (item.qty_terkirim && parseFloat(item.qty_terkirim) > 0) {
                            return parseFloat(item.qty_terkirim);
                        }

                        // Jika tidak ada atau 0, coba fallback ke nilai lain
                        let qty = 0;

                        // Prioritas sama dengan yang digunakan saat inisialisasi items
                        if (item.quantity_terkirim && parseFloat(item.quantity_terkirim) > 0) {
                            qty = parseFloat(item.quantity_terkirim);
                        } else if (item.qty_so && parseFloat(item.qty_so) > 0) {
                            qty = parseFloat(item.qty_so);
                        } else if (item.qty && parseFloat(item.qty) > 0) {
                            qty = parseFloat(item.qty);
                        }

                        // Always return a valid number, never undefined or NaN
                        return parseFloat(qty) || 0;
                    },

                    formatNumber(number) {
                        // Always format as a number with 2 decimal places for consistency
                        const parsed = parseFloat(number || 0);
                        // Use Indonesian locale formatting with 2 decimal places
                        return new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(parsed);
                    },

                    handleSubmit(event) {
                        if (this.items.length === 0) {
                            event.preventDefault();
                            alert('Minimal harus ada 1 item untuk retur.');
                            return false;
                        }

                        // Validate quantities
                        for (let item of this.items) {
                            if (!item.qty || item.qty <= 0) {
                                event.preventDefault();
                                alert(`Qty untuk produk ${item.produk_nama} harus lebih dari 0.`);
                                return false;
                            }
                            if (item.qty > this.getDeliveredQty(item)) {
                                event.preventDefault();
                                alert(
                                    `Qty untuk produk ${item.produk_nama} tidak boleh melebihi qty terkirim (${this.getDeliveredQty(item)}).`
                                );
                                return false;
                            }
                            if (!item.alasan || item.alasan === '') {
                                event.preventDefault();
                                alert(`Alasan retur untuk produk ${item.produk_nama} wajib diisi.`);
                                return false;
                            }
                        }
                        this.isSubmitting = true;
                        return true;
                    },
                }
            }

            // Tutup Alpine.js object
        </script>
    @endpush
</x-app-layout>
