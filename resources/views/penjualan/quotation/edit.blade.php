<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Quotation', 'url' => route('penjualan.quotation.index')],
    ['label' => 'Edit'],
]" :currentPage="'Edit Quotation'">
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

            /* Form card styling */
            .form-card {
                transition: all 0.3s ease;
                border-radius: 12px;
            }

            .form-card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 2px rgba(0, 0, 0, 0.05);
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
    @endpush

    <div x-data="quotationForm({{ Illuminate\Support\Js::from($quotation) }}, {{ Illuminate\Support\Js::from($quotation->details) }})" x-cloak x-init="$nextTick(() => { init() })" class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Add meta tag for CSRF protection in fetch requests -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <form method="POST" action="{{ route('penjualan.quotation.update', $quotation->id) }}" id="quotationEditForm"
            @submit.prevent="validateAndSubmitForm($event)" autocomplete="off">
            @csrf
            @method('PUT')
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            {{-- Header Section --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 mb-6 form-card">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-900">
                    <div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Quotation</h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Perbarui detail quotation.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('penjualan.quotation.index') }}"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 hover:shadow-md">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmitQuotation"
                            class="px-4 py-2 bg-primary-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                {{-- Form Section - Header Details --}}
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
                    <div>
                        <label for="nomor_quotation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Quotation
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor" id="nomor_quotation"
                            value="{{ old('nomor', $quotation->nomor) }}" readonly
                            class="block w-full pr-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('nomor_quotation') border-red-500 dark:border-red-500 @enderror">
                        @error('nomor_quotation')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" id="tanggal"
                            value="{{ old('tanggal', $quotation->tanggal ? (is_string($quotation->tanggal) ? $quotation->tanggal : $quotation->tanggal->format('Y-m-d')) : date('Y-m-d')) }}"
                            required
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('tanggal') border-red-500 dark:border-red-500 @enderror">
                        @error('tanggal')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="customer_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer <span
                                class="text-red-500">*</span></label>
                        <select name="customer_id" id="customer_id" required
                            class="select2-dropdown block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md @error('customer_id') border-red-500 dark:border-red-500 @enderror">
                            <option value="">Pilih Customer</option>
                            @foreach ($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}" @if (old('customer_id', $quotation->customer_id) == $customer->id) selected @endif>
                                    {{ $customer->company ?? $customer->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_valid_hingga"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valid Hingga <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_berlaku" id="tanggal_valid_hingga" required
                            value="{{ old('tanggal_berlaku', $quotation->tanggal_berlaku ? (is_string($quotation->tanggal_berlaku) ? $quotation->tanggal_berlaku : $quotation->tanggal_berlaku->format('Y-m-d')) : date('Y-m-d', strtotime('+1 month'))) }}"
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('tanggal_berlaku') border-red-500 dark:border-red-500 @enderror">
                        @error('tanggal_valid_hingga')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status <span
                                class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="select2-dropdown block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                            @foreach ($statuses ?? [] as $key => $value)
                                <option value="{{ $key }}" @if (old('status', $quotation->form_status) == $key) selected @endif>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="catatan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                        <textarea id="catatan" name="catatan" rows="2"
                            class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md @error('catatan') border-red-500 dark:border-red-500 @enderror"
                            placeholder="Catatan tambahan...">{{ old('catatan', $quotation->catatan) }}</textarea>
                        @error('catatan')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label for="syarat_pembayaran"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Syarat
                            Pembayaran</label>
                        <textarea id="syarat_pembayaran" name="syarat_ketentuan" rows="2"
                            class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md @error('syarat_ketentuan') border-red-500 dark:border-red-500 @enderror"
                            placeholder="Syarat pembayaran...">{{ old('syarat_ketentuan', $quotation->syarat_ketentuan) }}</textarea>
                        @error('syarat_pembayaran')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Items Section --}}
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah Item
                    </button>
                </div>

                <div class="px-6 py-5">
                    <div x-show="$data.items && $data.items.length === 0" class="text-center py-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada item</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan item untuk
                            quotation ini.</p>
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

                    <div x-show="$data.items && $data.items.length > 0" class="overflow-x-auto">
                        <div class="space-y-3 mt-2" id="items-container">
                            <template x-for="(item, index) in $data.items" :key="item.id">
                                <div
                                    class="border border-gray-200 dark:border-gray-600 rounded-lg p-5 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-200 hover:border-primary-200 dark:hover:border-primary-800">
                                    <div class="md:hidden flex justify-between items-center mb-4">
                                        <span
                                            class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-primary-100 dark:bg-primary-900/50 text-xs font-medium text-primary-800 dark:text-primary-300"
                                            x-text="index + 1"></span>
                                        <button type="button" @click="removeItem(index)"
                                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                                                <input type="hidden" :name="`items[${index}][id]`"
                                                    x-model="$data.items[index].item_id_db"> {{-- For existing item ID --}}
                                                <select :name="`items[${index}][produk_id]`"
                                                    x-model="$data.items[index].produk_id"
                                                    @change="updateItemDetails(index, $event)" required
                                                    class="select2-dropdown-dynamic mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($products ?? [] as $product)
                                                        <option value="{{ $product->id }}"
                                                            data-harga="{{ $product->harga_jual ?? 0 }}"
                                                            data-satuan_id="{{ $product->satuan_id ?? '' }}">
                                                            {{ $product->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Deskripsi Item --}}
                                            <div class="md:col-span-7">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi
                                                    Item</label>
                                                <textarea :name="`items[${index}][deskripsi]`" x-model="$data.items[index].deskripsi" rows="1"
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
                                                    x-model.number="$data.items[index].quantity"
                                                    @input="calculateSubtotal(index)" required placeholder="0"
                                                    class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
                                            </div>

                                            {{-- Satuan --}}
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Satuan
                                                    <span class="text-red-500">*</span></label>
                                                <select :name="`items[${index}][satuan_id]`"
                                                    x-model="$data.items[index].satuan_id" required
                                                    class="select2-dropdown-dynamic mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                    <option value="">Pilih</option>
                                                    @foreach ($satuans ?? [] as $satuan)
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
                                                        x-model.number="$data.items[index].harga"
                                                        @input="calculateSubtotal(index)" required placeholder="0"
                                                        class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                </div>
                                            </div>

                                            {{-- Diskon Item (%) --}}
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diskon
                                                    (%)</label>
                                                <input type="number" step="any" min="0" max="100"
                                                    :name="`items[${index}][diskon_persen_item]`"
                                                    x-model.number="$data.items[index].diskon_persen"
                                                    @input="calculateSubtotal(index, 'persen')" placeholder="0"
                                                    class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
                                            </div>

                                            {{-- Diskon fields for controller compatibility --}}
                                            <input type="hidden" :name="`items[${index}][diskon_nominal_item]`"
                                                :value="$data.items[index].diskon_nominal ? $data.items[index].diskon_nominal
                                                    .toFixed(2) : '0.00'">
                                            <input type="hidden" :name="`items[${index}][diskon_persen]`"
                                                :value="$data.items[index].diskon_persen">
                                            <input type="hidden" :name="`items[${index}][diskon_nominal]`"
                                                :value="$data.items[index].diskon_nominal ? $data.items[index].diskon_nominal
                                                    .toFixed(2) : '0.00'">

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
                                                        :value="typeof $data.formatCurrency === 'function' ? $data
                                                            .formatCurrency($data.items[index].subtotal) : ($data.items[
                                                                index].subtotal || 0)"
                                                        readonly
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

            {{-- Summary Section --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 form-card">
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
                                x-text="$data.summary && typeof $data.formatCurrency === 'function' ? $data.formatCurrency($data.summary.total_sebelum_diskon_global) : '0,00'"></span>
                        </div>

                        <div class="flex justify-between items-center">
                            <label for="diskon_global_persen"
                                class="text-sm font-medium text-gray-600 dark:text-gray-300">Diskon Global (%):</label>
                            <input type="number" step="any" min="0" max="100" name="diskon_persen"
                                id="diskon_global_persen" x-model.number="$data.diskon_global_persen"
                                @input="calculateGlobalDiscount('persen')" placeholder="0"
                                class="w-24 text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div class="flex justify-between items-center">
                            <label for="diskon_global_nominal"
                                class="text-sm font-medium text-gray-600 dark:text-gray-300">Diskon Global
                                (Rp):</label>
                            <input type="number" step="any" min="0" name="diskon_nominal"
                                id="diskon_global_nominal" x-model.number="$data.diskon_global_nominal"
                                @input="calculateGlobalDiscount('nominal')" placeholder="0"
                                class="w-36 text-sm text-right border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Setelah Diskon
                                Global:</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-white"
                                x-text="$data.summary && typeof $data.formatCurrency === 'function' ? $data.formatCurrency($data.summary.total_setelah_diskon_global) : '0,00'"></span>
                        </div>

                        <!-- PPN Toggle -->
                        <div class="flex justify-between items-center mt-2">
                            <label class="flex items-center">
                                <input type="checkbox" id="include_ppn" x-model="$data.includePPN"
                                    @change="calculateTotals()"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <input type="hidden" name="ppn" :value="$data.includePPN ? 11 : 0">
                                <span class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-300">Include PPN
                                    (11%)</span>
                            </label>
                            <span class="text-sm font-semibold text-gray-800 dark:text-white"
                                x-text="$data.includePPN && $data.summary && typeof $data.formatCurrency === 'function' ? $data.formatCurrency($data.summary.ppn_nominal) : 'Rp 0,00'"></span>
                        </div>

                        <hr class="dark:border-gray-600 my-1">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Grand Total:</span>
                            <span class="text-lg font-bold text-primary-600 dark:text-primary-400"
                                x-text="$data.summary && typeof $data.formatCurrency === 'function' ? $data.formatCurrency($data.summary.grand_total) : '0,00'"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Error message display --}}
            <div x-show="$data.formErrors && $data.formErrors.length > 0"
                class="mb-4 p-3 rounded-md bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                <strong class="font-bold">Oops! Ada beberapa kesalahan:</strong>
                <ul class="mt-1 list-disc list-inside text-sm">
                    <template x-for="error in $data.formErrors" :key="error">
                        <li x-text="error"></li>
                    </template>
                </ul>
            </div>

            <div class="flex justify-end gap-2 mt-8 mb-4">
                <a href="{{ route('penjualan.quotation.index') }}"
                    class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 hover:shadow-md">
                    Batal
                </a>
                <button type="submit" @click="validateAndSubmitForm($event)"
                    class="px-4 py-2 bg-primary-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 hover:shadow-md hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function quotationForm(quotationData, quotationItemsData) {
                // Ensure we have valid objects to work with
                quotationData = quotationData || {};
                // Initialize with empty array to prevent undefined errors
                quotationItemsData = Array.isArray(quotationItemsData) ? quotationItemsData : [];

                return {
                    items: [],
                    products: {{ Illuminate\Support\Js::from($produks ?? []) }},
                    satuans: {{ Illuminate\Support\Js::from($satuans ?? []) }},
                    diskon_global_persen: parseFloat(quotationData.diskon_persen || 0) || 0,
                    diskon_global_nominal: parseFloat(quotationData.diskon_nominal || 0) || 0,
                    includePPN: quotationData && quotationData.ppn > 0,
                    summary: {
                        total_sebelum_diskon_global: 0,
                        total_setelah_diskon_global: 0,
                        ppn_nominal: 0,
                        grand_total: 0
                    },
                    formErrors: [],
                    nextItemId: 1, // Used for new items client-side key

                    init() {
                        // Initialize items as empty array before any processing
                        this.items = [];

                        // Initialize Select2 dropdowns after Alpine has processed the items
                        this.$nextTick(() => {
                            setTimeout(() => {
                                // Re-initialize all Select2 dropdowns after items are rendered
                                $('.select2-dropdown-dynamic').each(function() {
                                    if ($(this).data('select2')) {
                                        $(this).select2('destroy');
                                    }

                                    if ($(this).attr('name').includes('produk_id')) {
                                        $(this).select2({
                                            placeholder: "Pilih Produk",
                                            width: '100%',
                                            dropdownCssClass: 'select2-dropdown-modern'
                                        });
                                    } else if ($(this).attr('name').includes('satuan_id')) {
                                        $(this).select2({
                                            placeholder: "Pilih Satuan",
                                            width: '100%',
                                            dropdownCssClass: 'select2-dropdown-modern'
                                        });
                                    }
                                });
                            }, 200);
                        });

                        if (Array.isArray(quotationItemsData) && quotationItemsData.length > 0) {
                            this.items = quotationItemsData.map(item => {
                                try {
                                    // Make sure we extract produk and satuan information correctly
                                    const produkObj = item.produk || {};
                                    const satuanObj = item.satuan || {};

                                    // Calculate discount values properly
                                    const harga = parseFloat(item.harga) || 0;
                                    const qty = parseFloat(item.quantity) || 1;
                                    const diskonPersen = parseFloat(item.diskon_persen) || 0;
                                    const subtotalBeforeDiscount = harga * qty;
                                    const diskonNominal = (subtotalBeforeDiscount * diskonPersen) / 100;
                                    const subtotalAfterDiscount = subtotalBeforeDiscount - diskonNominal;



                                    return {
                                        id: this.nextItemId++, // Client-side unique ID for x-for key
                                        item_id_db: item.id, // Actual DB ID of the item
                                        produk_id: item.produk_id || '',
                                        produk_nama: produkObj.nama || '',
                                        quantity: qty,
                                        satuan_id: item.satuan_id || '',
                                        satuan_nama: satuanObj.nama || '',
                                        harga: harga,
                                        diskon_persen: diskonPersen,
                                        diskon_nominal: diskonNominal,
                                        deskripsi: item.deskripsi || '',
                                        subtotal: subtotalAfterDiscount
                                    };
                                } catch (error) {
                                    // Return a default item structure to prevent breaking the UI
                                    return {
                                        id: this.nextItemId++,
                                        item_id_db: item.id || null,
                                        produk_id: item.produk_id || '',
                                        quantity: 1,
                                        satuan_id: item.satuan_id || '',
                                        harga: 0,
                                        diskon_persen: 0,
                                        diskon_nominal: 0,
                                        deskripsi: '',
                                        subtotal: 0
                                    };
                                }
                            });
                        } else {
                            let oldItems = {!! json_encode(old('items', [])) !!};
                            if (oldItems.length > 0) {
                                this.items = oldItems.map(item => ({
                                    id: this.nextItemId++,
                                    item_id_db: item.id || null, // if old item has id from previous attempt
                                    produk_id: item.produk_id || '',
                                    quantity: parseFloat(item.kuantitas || item.quantity) || 1,
                                    satuan_id: item.satuan_id || '',
                                    harga: parseFloat(item.harga) || 0,
                                    diskon_persen: parseFloat(item.diskon_persen) || 0,
                                    diskon_nominal: 0,
                                    deskripsi: item.deskripsi || '',
                                    subtotal: parseFloat(item.subtotal) || 0
                                }));
                            }
                        }
                        if (!{!! json_encode(old('diskon_global_persen', null)) !!}) {
                            this.diskon_global_persen = parseFloat(quotationData.diskon_persen) || 0;
                        }
                        if (!{!! json_encode(old('diskon_global_nominal', null)) !!}) {
                            this.diskon_global_nominal = parseFloat(quotationData.diskon_nominal) || 0;
                        }

                        this.items.forEach((item, index) => this.calculateSubtotal(index));
                        this.calculateTotals();

                        // Initialization complete
                    },

                    addItem() {
                        try {
                            // Make sure items is an array
                            if (!Array.isArray(this.items)) {
                                this.items = [];
                            }

                            // Make sure nextItemId is a number
                            if (typeof this.nextItemId !== 'number') {
                                this.nextItemId = 1;
                            }

                            // Create a new item with safe defaults
                            const newItem = {
                                id: this.nextItemId++,
                                item_id_db: null, // New items don't have a DB id yet
                                produk_id: '',
                                produk_nama: '',
                                quantity: 1,
                                satuan_id: '',
                                satuan_nama: '',
                                harga: 0,
                                diskon_persen: 0,
                                diskon_nominal: 0,
                                subtotal: 0,
                                deskripsi: ''
                            };

                            // Add item to the array
                            this.items.push(newItem);

                            // Recalculate totals to update display
                            this.calculateTotals();

                            // Initialize Select2 for the new item after it's rendered
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    const newIndex = this.items.length - 1;
                                    const produkSelector = `select[name="items[${newIndex}][produk_id]"]`;
                                    const satuanSelector = `select[name="items[${newIndex}][satuan_id]"]`;

                                    // Initialize produk Select2
                                    $(produkSelector).select2({
                                        placeholder: "Pilih Produk",
                                        width: '100%',
                                        dropdownCssClass: 'select2-dropdown-modern'
                                    }).on('select2:select', (e) => {
                                        $(produkSelector).trigger('change');
                                        setTimeout(() => {
                                            this.updateItemDetails(newIndex, e);
                                        }, 50);
                                    });

                                    // Initialize satuan Select2
                                    $(satuanSelector).select2({
                                        placeholder: "Pilih Satuan",
                                        width: '100%',
                                        dropdownCssClass: 'select2-dropdown-modern'
                                    }).on('select2:select', (e) => {
                                        $(satuanSelector).trigger('change');
                                    });
                                }, 100);
                            });
                        } catch (error) {
                            // Silent error handling
                        }
                    },

                    removeItem(index) {
                        try {
                            // Make sure items is an array and index is valid
                            if (Array.isArray(this.items) && index >= 0 && index < this.items.length) {
                                // Remove the item at the specified index
                                this.items.splice(index, 1);

                                // Update totals after item removal
                                this.calculateTotals();
                            } else {
                                // Invalid index - no action taken
                            }
                        } catch (error) {
                            // Silent error handling
                        }
                    },

                    updateItemDetails(index, event) {
                        try {
                            // Safely access the items array
                            const items = this.$data && this.$data.items ? this.$data.items : this.items;
                            if (!Array.isArray(items) || !items[index]) {
                                return;
                            }

                            const item = items[index];

                            // Get the product element regardless of how the function was called
                            let productElement;
                            if (event && event.target) {
                                // If called from an event handler
                                productElement = event.target;
                            } else {
                                // If called programmatically (e.g., from addItem or elsewhere)
                                productElement = document.querySelector(`select[name="items[${index}][produk_id]"]`);
                                if (!productElement) {
                                    return;
                                }
                            }

                            // Check if the select element has options
                            if (!productElement.options || typeof productElement.selectedIndex !== 'number') {
                                return;
                            }

                            const selectedOption = productElement.options[productElement.selectedIndex];
                            if (!selectedOption || !selectedOption.value) {
                                // Reset values when no product is selected
                                item.harga = 0;
                                item.satuan_id = '';
                                item.produk_nama = '';
                                this.calculateSubtotal(index);
                                return;
                            }

                            // Update item with selected product details
                            item.produk_nama = selectedOption.text ? selectedOption.text.trim() : '';
                            item.harga = parseFloat(selectedOption.dataset && selectedOption.dataset.harga ? selectedOption
                                .dataset.harga : 0) || 0;
                            const defaultSatuanId = selectedOption.dataset && selectedOption.dataset.satuan_id ? selectedOption
                                .dataset.satuan_id : '';

                            if (defaultSatuanId) {
                                // Update the Alpine.js model
                                item.satuan_id = defaultSatuanId;

                                // Use a timeout to ensure the Select2 initialization has completed
                                setTimeout(() => {
                                    try {
                                        const satuanSelect = $(`select[name="items[${index}][satuan_id]"]`);

                                        if (satuanSelect.length) {
                                            // First ensure we have the actual DOM element selected
                                            const satuanElement = document.querySelector(
                                                `select[name="items[${index}][satuan_id]"]`);
                                            if (!satuanElement) {
                                                return;
                                            }

                                            // Check if option with the value exists
                                            let optionExists = false;
                                            for (let i = 0; i < satuanElement.options.length; i++) {
                                                if (satuanElement.options[i].value === defaultSatuanId) {
                                                    optionExists = true;
                                                    break;
                                                }
                                            }

                                            // First destroy if it's a Select2 instance
                                            if (satuanSelect.hasClass('select2-hidden-accessible')) {
                                                try {
                                                    satuanSelect.select2('destroy');
                                                } catch (e) {
                                                    // Silent error handling
                                                }
                                            }

                                            // Set the value directly on the DOM element first
                                            satuanElement.value = defaultSatuanId;

                                            // Then initialize Select2
                                            satuanSelect.select2({
                                                placeholder: "Pilih Satuan",
                                                width: '100%',
                                                dropdownCssClass: 'select2-dropdown-modern'
                                            });

                                            // Now set the value and trigger change on the jQuery element
                                            satuanSelect.val(defaultSatuanId).trigger('change');

                                            // Set satuan_nama for display if needed
                                            try {
                                                const satuanOption = document.querySelector(
                                                    `[name="items[${index}][satuan_id]"] option[value="${defaultSatuanId}"]`
                                                );
                                                if (satuanOption) {
                                                    item.satuan_nama = satuanOption.textContent ? satuanOption
                                                        .textContent.trim() : '';
                                                }
                                            } catch (err) {
                                                // Silent error handling
                                            }
                                        }
                                    } catch (e) {
                                        // Silent error handling
                                    }
                                }, 200);
                            }
                        } catch (error) {
                            // Silent error handling
                        }

                        this.calculateSubtotal(index);
                    },

                    calculateSubtotal(index, changedDiscountType = null) {
                        try {
                            // Safely access item using $data if available, otherwise fallback to this.items
                            const items = this.$data ? this.$data.items : this.items;
                            if (!Array.isArray(items) || !items[index]) {
                                return;
                            }

                            const item = items[index];

                            // Convert values to numbers, defaulting to 0 if invalid
                            let harga = parseFloat(item.harga) || 0;
                            let quantity = parseFloat(item.quantity) || 0;
                            let diskonPersen = parseFloat(item.diskon_persen) || 0;

                            // Ensure valid discount percentage (between 0 and 100)
                            diskonPersen = Math.max(0, Math.min(100, diskonPersen));
                            item.diskon_persen = diskonPersen;

                            // Calculate subtotal and discounts
                            let subtotalSebelumDiskon = harga * quantity;
                            let diskonNominalItem = (subtotalSebelumDiskon * diskonPersen) / 100;

                            // Update item properties
                            item.diskon_nominal = diskonNominalItem;
                            item.subtotal = subtotalSebelumDiskon - diskonNominalItem;

                            // Calculate totals across all items
                            this.calculateTotals();
                        } catch (error) {
                            // Silent error handling
                        }
                    },

                    calculateGlobalDiscount(type) {
                        try {
                            // Ensure summary object exists
                            if (!this.summary) {
                                this.summary = {
                                    total_sebelum_diskon_global: 0,
                                    total_setelah_diskon_global: 0,
                                    ppn_nominal: 0,
                                    grand_total: 0
                                };
                            }

                            // Get the current subtotal before global discount
                            let totalSebelumDiskonGlobal = parseFloat(this.summary.total_sebelum_diskon_global) || 0;

                            if (type === 'persen') {
                                // Handle percentage-based discount calculation
                                let persen = parseFloat(this.diskon_global_persen) || 0;

                                // Clamp percentage between 0 and 100
                                persen = Math.max(0, Math.min(100, persen));
                                this.diskon_global_persen = persen;

                                // Calculate the nominal discount amount based on percentage
                                this.diskon_global_nominal = (totalSebelumDiskonGlobal * persen) / 100;

                            } else if (type === 'nominal') {
                                // Handle nominal-based discount calculation
                                let nominal = parseFloat(this.diskon_global_nominal) || 0;

                                // Ensure nominal is between 0 and total
                                nominal = Math.max(0, Math.min(totalSebelumDiskonGlobal, nominal));
                                this.diskon_global_nominal = nominal;

                                // Calculate the percentage equivalent of this nominal discount
                                if (totalSebelumDiskonGlobal > 0) {
                                    this.diskon_global_persen = (nominal / totalSebelumDiskonGlobal) * 100;
                                } else {
                                    this.diskon_global_persen = 0;
                                }
                            }

                            // Update totals to reflect the new discount
                            this.calculateTotals();
                        } catch (error) {
                            // Silent error handling
                        }
                    },

                    calculateTotals() {
                        try {
                            // Ensure items is an array before proceeding
                            if (!Array.isArray(this.items)) {
                                this.items = [];
                            }

                            let currentSubtotalOverall = 0;

                            // Safely iterate through items
                            this.items.forEach((item, idx) => {
                                if (item) {
                                    const itemSubtotal = parseFloat(item.subtotal) || 0;
                                    currentSubtotalOverall += itemSubtotal;
                                }
                            });

                            // Ensure summary object exists
                            if (!this.summary) {
                                this.summary = {
                                    total_sebelum_diskon_global: 0,
                                    total_setelah_diskon_global: 0,
                                    ppn_nominal: 0,
                                    grand_total: 0
                                };
                            }

                            // Update summary with the calculated overall subtotal
                            this.summary.total_sebelum_diskon_global = currentSubtotalOverall;

                            // Calculate global discount nominal if percentage is provided
                            const diskonGlobalPersen = parseFloat(this.diskon_global_persen) || 0;
                            if (diskonGlobalPersen > 0 && (!this.diskon_global_nominal || this.diskon_global_nominal === 0)) {
                                this.diskon_global_nominal = (this.summary.total_sebelum_diskon_global * diskonGlobalPersen) /
                                    100;
                            }

                            // Calculate total after global discount
                            const diskonGlobalNominal = parseFloat(this.diskon_global_nominal) || 0;
                            let totalSetelahDiskonGlobal = this.summary.total_sebelum_diskon_global - diskonGlobalNominal;

                            // Ensure value is not negative
                            this.summary.total_setelah_diskon_global = totalSetelahDiskonGlobal < 0 ? 0 :
                                totalSetelahDiskonGlobal;

                            // Calculate PPN if included (11%)
                            const ppnRate = 11;
                            const includePPN = !!this.includePPN;
                            this.summary.ppn_nominal = includePPN ?
                                (this.summary.total_setelah_diskon_global * ppnRate / 100) : 0;

                            // Calculate grand total with PPN if applicable
                            this.summary.grand_total = this.summary.total_setelah_diskon_global + this.summary.ppn_nominal;
                        } catch (error) {
                            // Ensure we have a default summary even if calculation fails
                            this.summary = this.summary || {
                                total_sebelum_diskon_global: 0,
                                total_setelah_diskon_global: 0,
                                ppn_nominal: 0,
                                grand_total: 0
                            };
                        }
                    },

                    formatCurrency(value) {
                        try {
                            // Ensure we have a valid number
                            if (value === null || value === undefined || value === '') {
                                return '0,00';
                            }

                            // Convert to number if it's not already
                            if (typeof value !== 'number') {
                                value = parseFloat(value) || 0;
                            }

                            // Format the number with Indonesian locale
                            return value.toLocaleString('id-ID', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        } catch (error) {
                            return '0,00'; // Return default value in case of error
                        }
                    },

                    validateAndSubmitForm(event) {
                        try {
                            // Initialize or reset form errors array
                            this.formErrors = [];

                            // Define required fields for validation
                            const requiredFields = [{
                                    name: 'nomor_quotation',
                                    label: 'Nomor Quotation'
                                },
                                {
                                    name: 'tanggal',
                                    label: 'Tanggal'
                                },
                                {
                                    name: 'customer_id',
                                    label: 'Customer'
                                },
                                {
                                    name: 'tanggal_valid_hingga',
                                    label: 'Valid Hingga'
                                }
                            ];

                            // Check each required field
                            requiredFields.forEach(field => {
                                try {
                                    const inputElement = document.getElementById(field.name);
                                    if (!inputElement || !inputElement.value) {
                                        this.formErrors.push(`${field.label} wajib diisi.`);
                                    }
                                } catch (err) {
                                    // Add error anyway to prevent submission if field validation fails
                                    this.formErrors.push(`Terjadi kesalahan saat memeriksa ${field.label}.`);
                                }
                            });

                            // Ensure items array exists and is an array
                            const items = Array.isArray(this.items) ? this.items : [];

                            // Check that there's at least one item
                            if (items.length === 0) {
                                this.formErrors.push('Minimal harus ada 1 item dalam quotation.');
                            }

                            // Validate each item
                            items.forEach((item, index) => {
                                if (!item) {
                                    this.formErrors.push(`Item ${index + 1}: Data tidak valid.`);
                                    return;
                                }

                                if (!item.produk_id) {
                                    this.formErrors.push(`Item ${index + 1}: Produk wajib dipilih.`);
                                }

                                const quantity = parseFloat(item.quantity) || 0;
                                if (quantity <= 0) {
                                    this.formErrors.push(`Item ${index + 1}: Kuantitas harus lebih dari 0.`);
                                }

                                if (!item.satuan_id) {
                                    this.formErrors.push(`Item ${index + 1}: Satuan wajib dipilih.`);
                                }

                                const harga = parseFloat(item.harga) || 0;
                                if (harga < 0) {
                                    this.formErrors.push(`Item ${index + 1}: Harga tidak boleh negatif.`);
                                }
                            });

                            // If there are validation errors, prevent form submission and show errors
                            if (this.formErrors.length > 0) {
                                event.preventDefault();

                                // Scroll to error display if exists
                                try {
                                    const errorDisplay = document.querySelector(
                                        '[x-show="$data.formErrors && $data.formErrors.length > 0"]');
                                    if (errorDisplay) {
                                        errorDisplay.scrollIntoView({
                                            behavior: 'smooth',
                                            block: 'center'
                                        });
                                    }
                                } catch (err) {
                                    // Silent error handling
                                }

                                return false;
                            }

                            // Debug message to confirm we're submitting the form
                            // Set flag to prevent duplicate submission
                            window.alpineFormSubmitted = true;

                            // Prepare form data for submission - create a proper FormData object
                            const form = document.getElementById('quotationEditForm');
                            const formData = new FormData(form);

                            // Make sure all items are properly structured for backend processing
                            this.items.forEach((item, i) => {
                                // Direct setting of form data values
                                formData.append(`items[${i}][produk_id]`, item.produk_id || '');
                                formData.append(`items[${i}][quantity]`, item.quantity || '');
                                formData.append(`items[${i}][satuan_id]`, item.satuan_id || '');
                                formData.append(`items[${i}][harga]`, item.harga || '');
                                formData.append(`items[${i}][diskon_persen_item]`, item.diskon_persen || '0');
                                formData.append(`items[${i}][diskon_nominal_item]`, item.diskon_nominal || '0');
                                formData.append(`items[${i}][deskripsi]`, item.deskripsi || '');

                                // Also create backup hidden input fields for redundancy
                                const createOrUpdateHiddenInput = (name, value) => {
                                    let input = document.querySelector(`input[name="items[${i}][${name}]"]`);
                                    if (!input) {
                                        input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = `items[${i}][${name}]`;
                                        form.appendChild(input);
                                    }
                                    input.value = value ?? '';
                                };

                                // Create all necessary hidden fields
                                createOrUpdateHiddenInput('produk_id', item.produk_id);
                                createOrUpdateHiddenInput('quantity', item.quantity);
                                createOrUpdateHiddenInput('satuan_id', item.satuan_id);
                                createOrUpdateHiddenInput('harga', item.harga);
                                createOrUpdateHiddenInput('diskon_persen_item', item.diskon_persen);
                                createOrUpdateHiddenInput('diskon_nominal_item', item.diskon_nominal);
                                createOrUpdateHiddenInput('deskripsi', item.deskripsi);
                            });

                            // Handle global discount values
                            formData.set('diskon_persen', this.diskon_global_persen || '0');
                            formData.set('diskon_nominal', this.diskon_global_nominal || '0');
                            formData.set('ppn', this.includePPN ? '11' : '0');

                            // Also add these as hidden fields as backup
                            const createGlobalHiddenInput = (name, value) => {
                                let input = document.querySelector(`input[name="${name}"]`);
                                if (!input) {
                                    input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = name;
                                    form.appendChild(input);
                                }
                                input.value = value ?? '0';
                            };

                            createGlobalHiddenInput('diskon_persen', this.diskon_global_persen || '0');
                            createGlobalHiddenInput('diskon_nominal', this.diskon_global_nominal || '0');
                            createGlobalHiddenInput('ppn', this.includePPN ? '11' : '0');

                            // Use fetch API for the submission
                            const url = form.getAttribute('action');
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                            // Start with the usual form submission first
                            try {
                                form.submit();
                                return true;
                            } catch (standardError) {
                                // Try fetch as backup method

                                // Try fetch as a fallback
                                fetch(url, {
                                        method: 'POST',
                                        body: formData,
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Accept': 'application/json'
                                        },
                                        credentials: 'same-origin'
                                    })
                                    .then(response => {
                                        if (response.ok) {
                                            window.location.href = "{{ route('penjualan.quotation.index') }}";
                                        } else {
                                            throw new Error('Form submission failed: ' + response.statusText);
                                        }
                                    })
                                    .catch(error => {
                                        // Last fallback - try jQuery if available
                                        if (typeof jQuery !== 'undefined') {
                                            jQuery.ajax({
                                                url: url,
                                                type: 'POST',
                                                data: new FormData(form),
                                                processData: false,
                                                contentType: false,
                                                success: function() {
                                                    window.location.href =
                                                        "{{ route('penjualan.quotation.index') }}";
                                                }
                                            });
                                        }
                                    });
                            }
                        } catch (error) {
                            // Prevent submission if there was an error in validation logic
                            event.preventDefault();
                            this.formErrors.push('Terjadi kesalahan saat validasi: ' + error.message);
                            return false;
                        }
                    }
                };
            }

            // Improved event handling for form submission
            document.addEventListener('DOMContentLoaded', function() {
                // Reset the form submission flag on page load
                window.alpineFormSubmitted = false;
                window.formSubmissionAttempted = 0;

                // Get form and submit button elements
                const form = document.getElementById('quotationEditForm');
                const submitBtn = document.getElementById('btnSubmitQuotation');

                // Check for presence of Alpine.js
                const hasAlpine = typeof Alpine !== 'undefined';

                if (submitBtn && form) {
                    // Handle button click event
                    submitBtn.addEventListener('click', function(e) {
                        // Prevent multiple submission attempts
                        if (window.alpineFormSubmitted && window.formSubmissionAttempted > 2) {
                            return;
                        }

                        window.formSubmissionAttempted++;

                        // Ensure form is fully validated before submission
                        try {
                            // Try Alpine approach first if available
                            if (hasAlpine) {
                                const alpineInstance = Alpine.$data(form);
                                if (alpineInstance && typeof alpineInstance.validateAndSubmitForm ===
                                    'function') {
                                    e.preventDefault();
                                    alpineInstance.validateAndSubmitForm(e);
                                    return;
                                }
                            }

                            // Fallback to direct form submission with minimal validation
                            e.preventDefault();

                            // Perform basic validation
                            let hasError = false;
                            const requiredInputs = form.querySelectorAll('[required]');
                            requiredInputs.forEach(input => {
                                if (!input.value) {
                                    hasError = true;
                                    input.classList.add('border-red-500');
                                }
                            });

                            if (!hasError) {
                                // Get all item inputs and ensure they're included in the submission
                                const itemRows = document.querySelectorAll(
                                    '[x-show="$data.items && $data.items.length > 0"] [x-for="(item, index) in $data.items"]'
                                );
                                if (itemRows.length === 0) {
                                    alert('Minimal harus ada 1 item dalam quotation.');
                                    return;
                                }

                                // Mark form as submitted
                                window.alpineFormSubmitted = true;

                                // Ensure form has proper method attribute
                                const method = form.getAttribute('method') || 'POST';
                                if (!form.querySelector('input[name="_method"][value="PUT"]')) {
                                    const methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'PUT';
                                    form.appendChild(methodInput);
                                }

                                // Submit the form
                                form.submit();
                            }
                        } catch (err) {
                            // Last-resort submission - just try to submit the form directly
                            try {
                                window.alpineFormSubmitted = true;
                                form.submit();
                            } catch (finalError) {
                                alert('Terjadi kesalahan saat mengirim form. Silakan coba lagi.');
                            }
                        }
                    });
                }

                // Handle native form submission event
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // If no items were successfully processed, prevent submission
                        const items = document.querySelectorAll('input[name^="items["][name$="][produk_id]"]');
                        if (items.length === 0) {
                            // Don't prevent submission here, as we might have hidden inputs that aren't visible in the DOM tree
                        }
                    });
                }
            });
        </script>

        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            // Initialize Select2 dropdowns after the page loads
            $(document).ready(function() {
                // Initialize static dropdowns
                $('#customer_id').select2({
                    placeholder: "Pilih Customer",
                    allowClear: true,
                    width: '100%',
                    dropdownCssClass: 'select2-dropdown-modern'
                });

                $('#status').select2({
                    minimumResultsForSearch: -1,
                    width: '100%',
                    dropdownCssClass: 'select2-dropdown-modern'
                });

                // Function to initialize dynamic Select2 elements
                function initDynamicSelect2() {
                    $('.select2-dropdown-dynamic').each(function() {
                        // Check if Select2 is already initialized
                        if (!$(this).data('select2')) {
                            if ($(this).attr('name').includes('produk_id')) {
                                $(this).select2({
                                    placeholder: "Pilih Produk",
                                    width: '100%',
                                    dropdownCssClass: 'select2-dropdown-modern'
                                }).on('select2:select', function(e) {
                                    // Trigger change event for Alpine.js to detect
                                    $(this).trigger('change');

                                    // Get the index from the name attribute
                                    const nameAttr = $(this).attr('name');
                                    const match = nameAttr.match(/items\[(\d+)\]/);
                                    if (match && match[1]) {
                                        const index = parseInt(match[1]);
                                        // Get Alpine component and call updateItemDetails
                                        const component = Alpine.$data(document.querySelector(
                                            '[x-data]'));
                                        if (component && typeof component.updateItemDetails ===
                                            'function') {
                                            setTimeout(() => {
                                                component.updateItemDetails(index);
                                            }, 50);
                                        }
                                    }
                                });
                            } else if ($(this).attr('name').includes('satuan_id')) {
                                $(this).select2({
                                    placeholder: "Pilih Satuan",
                                    width: '100%',
                                    dropdownCssClass: 'select2-dropdown-modern'
                                }).on('select2:select', function(e) {
                                    $(this).trigger('change');
                                });
                            }
                        }
                    });
                }

                // Initialize Select2 for initial elements
                initDynamicSelect2();

                // Observe DOM changes to initialize dynamic Select2 elements
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes.length) {
                            // Check if any of the added nodes contain our select elements
                            mutation.addedNodes.forEach(function(node) {
                                if (node.nodeType === 1) { // Element node
                                    const dynamicSelects = node.querySelectorAll(
                                        '.select2-dropdown-dynamic');
                                    if (dynamicSelects.length) {
                                        initDynamicSelect2();
                                    }
                                }
                            });
                        }
                    });
                });

                // Start observing the items container for changes
                const itemsContainer = document.getElementById('items-container');
                if (itemsContainer) {
                    observer.observe(itemsContainer, {
                        childList: true,
                        subtree: true
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
