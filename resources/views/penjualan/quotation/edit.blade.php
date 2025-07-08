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
                                <input type="hidden" name="ppn"
                                    :value="$data.includePPN ? {{ setting('tax_percentage', 11) }} : 0">
                                <span class="ml-2 text-sm font-medium text-gray-600 dark:text-gray-300">Include PPN
                                    ({{ setting('tax_percentage', 11) }}%)</span>
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
                quotationData = quotationData || {};
                quotationItemsData = Array.isArray(quotationItemsData) ? quotationItemsData : [];
                return {
                    items: [],
                    products: @json($products ?? []),
                    satuans: @json($satuans ?? []),
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
                    nextItemId: 1,
                    init() {
                        this.items = [];
                        if (Array.isArray(quotationItemsData) && quotationItemsData.length > 0) {
                            this.items = quotationItemsData.map(item => {
                                const harga = parseFloat(item.harga) || 0;
                                const qty = parseFloat(item.quantity) || 1;
                                const diskonPersen = parseFloat(item.diskon_persen) || 0;
                                const subtotalBeforeDiscount = harga * qty;
                                const diskonNominal = (subtotalBeforeDiscount * diskonPersen) / 100;
                                const subtotalAfterDiscount = subtotalBeforeDiscount - diskonNominal;
                                return {
                                    id: this.nextItemId++,
                                    item_id_db: item.id,
                                    produk_id: item.produk_id || '',
                                    produk_nama: item.produk?.nama || '',
                                    quantity: qty,
                                    satuan_id: item.satuan_id || '',
                                    satuan_nama: item.satuan?.nama || '',
                                    harga: harga,
                                    diskon_persen: diskonPersen,
                                    diskon_nominal: diskonNominal,
                                    deskripsi: item.deskripsi || '',
                                    subtotal: subtotalAfterDiscount
                                };
                            });
                        } else {
                            let oldItems = {!! json_encode(old('items', [])) !!};
                            if (oldItems.length > 0) {
                                this.items = oldItems.map(item => ({
                                    id: this.nextItemId++,
                                    item_id_db: item.id || null,
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
                        this.items.forEach((item, index) => this.calculateSubtotal(index));
                        this.calculateTotals();
                        this.$nextTick(() => {
                            this.initSelect2();
                        });
                    },
                    initSelect2() {
                        // Static Select2
                        $('#customer_id').select2({
                            placeholder: 'Pilih Customer',
                            allowClear: true,
                            width: '100%',
                            dropdownCssClass: 'select2-dropdown-modern'
                        });
                        $('#status').select2({
                            minimumResultsForSearch: -1,
                            width: '100%',
                            dropdownCssClass: 'select2-dropdown-modern'
                        });
                        // Dynamic Select2
                        const itemsContainer = document.getElementById('items-container');
                        if (itemsContainer) {
                            const observer = new MutationObserver(mutations => {
                                mutations.forEach(mutation => {
                                    mutation.addedNodes.forEach(node => {
                                        if (node.nodeType === 1) {
                                            const selects = node.querySelectorAll(
                                                '.select2-dropdown-dynamic');
                                            selects.forEach(select => {
                                                if (!$(select).data('select2')) {
                                                    if (select.name.includes('produk_id')) {
                                                        $(select).select2({
                                                            placeholder: 'Pilih Produk',
                                                            width: '100%',
                                                            dropdownCssClass: 'select2-dropdown-modern'
                                                        }).on('select2:select', e => {
                                                            const nameAttr = $(select)
                                                                .attr('name');
                                                            const match = nameAttr
                                                                .match(
                                                                /items\[(\d+)\]/);
                                                            if (match && match[1]) {
                                                                const idx = parseInt(
                                                                    match[1]);
                                                                this.items[idx]
                                                                    .produk_id = e
                                                                    .target.value;
                                                                setTimeout(() => this
                                                                    .updateItemDetails(
                                                                        idx), 100);
                                                            }
                                                        });
                                                    } else if (select.name.includes(
                                                        'satuan_id')) {
                                                        $(select).select2({
                                                            placeholder: 'Pilih Satuan',
                                                            width: '100%',
                                                            dropdownCssClass: 'select2-dropdown-modern'
                                                        }).on('select2:select', e => {
                                                            const nameAttr = $(select)
                                                                .attr('name');
                                                            const match = nameAttr
                                                                .match(
                                                                /items\[(\d+)\]/);
                                                            if (match && match[1]) {
                                                                const idx = parseInt(
                                                                    match[1]);
                                                                this.items[idx]
                                                                    .satuan_id = e
                                                                    .target.value;
                                                            }
                                                        });
                                                    }
                                                }
                                            });
                                        }
                                    });
                                });
                            });
                            observer.observe(itemsContainer, {
                                childList: true,
                                subtree: true
                            });
                        }
                    },
                    addItem() {
                        this.items.push({
                            id: this.nextItemId++,
                            item_id_db: null,
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
                        });
                        this.$nextTick(() => {
                            this.initSelect2();
                        });
                    },
                    removeItem(index) {
                        this.items.splice(index, 1);
                        this.calculateTotals();
                    },
                    updateItemDetails(index) {
                        const item = this.items[index];
                        const produk = this.products.find(p => p.id == item.produk_id);
                        if (produk) {
                            item.harga = parseFloat(produk.harga_jual) || 0;
                            item.satuan_id = produk.satuan_id || '';
                            item.produk_nama = produk.nama || '';
                        } else {
                            item.harga = 0;
                            item.satuan_id = '';
                            item.produk_nama = '';
                        }
                        this.calculateSubtotal(index);
                    },
                    calculateSubtotal(index) {
                        const item = this.items[index];
                        const qty = parseFloat(item.quantity) || 0;
                        const harga = parseFloat(item.harga) || 0;
                        const diskonPersen = parseFloat(item.diskon_persen) || 0;
                        const subtotalBefore = qty * harga;
                        const diskonNominal = (subtotalBefore * diskonPersen) / 100;
                        item.diskon_nominal = diskonNominal;
                        item.subtotal = subtotalBefore - diskonNominal;
                        this.calculateTotals();
                    },
                    calculateTotals() {
                        let subtotal = 0;
                        this.items.forEach(item => {
                            subtotal += parseFloat(item.subtotal) || 0;
                        });
                        this.summary.total_sebelum_diskon_global = subtotal;
                        let diskonNominal = this.diskon_global_nominal;
                        if (this.diskon_global_persen > 0) {
                            diskonNominal = (this.diskon_global_persen / 100) * subtotal;
                            this.diskon_global_nominal = diskonNominal;
                        }
                        this.summary.total_setelah_diskon_global = subtotal - diskonNominal;
                        let ppnNominal = 0;
                        if (this.includePPN) {
                            ppnNominal = ({{ setting('tax_percentage', 11) }} / 100) * this.summary
                            .total_setelah_diskon_global;
                        }
                        this.summary.ppn_nominal = ppnNominal;
                        this.summary.grand_total = this.summary.total_setelah_diskon_global + ppnNominal;
                    },
                    calculateGlobalDiscount(type) {
                        if (type === 'persen') {
                            this.diskon_global_nominal = (this.diskon_global_persen / 100) * this.summary
                                .total_sebelum_diskon_global;
                        } else if (type === 'nominal') {
                            this.diskon_global_persen = (this.diskon_global_nominal / this.summary
                                .total_sebelum_diskon_global) * 100;
                        }
                        this.calculateTotals();
                    },
                    formatCurrency(val) {
                        val = parseFloat(val) || 0;
                        return val.toLocaleString('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 2
                        });
                    },
                    validateAndSubmitForm(e) {
                        this.formErrors = [];
                        if (!this.items.length) {
                            this.formErrors.push('Minimal 1 item produk harus diisi.');
                        }
                        this.items.forEach((item, idx) => {
                            if (!item.produk_id) this.formErrors.push('Produk pada item #' + (idx + 1) +
                                ' wajib diisi.');
                            if (!item.satuan_id) this.formErrors.push('Satuan pada item #' + (idx + 1) +
                                ' wajib diisi.');
                            if (!item.quantity || item.quantity <= 0) this.formErrors.push('Kuantitas pada item #' + (
                                idx + 1) + ' wajib diisi dan > 0.');
                            if (!item.harga || item.harga < 0) this.formErrors.push('Harga pada item #' + (idx + 1) +
                                ' wajib diisi dan >= 0.');
                        });
                        if (this.formErrors.length) {
                            e.preventDefault();
                            return false;
                        }
                        // Set hidden input for global diskon, ppn, total
                        const form = document.getElementById('quotationEditForm');
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
                        createGlobalHiddenInput('ppn', this.includePPN ? '{{ setting('tax_percentage', 11) }}' : '0');
                        createGlobalHiddenInput('total', this.summary.grand_total || '0');
                        form.submit();
                    }
                }
            }
            // Inisialisasi Select2 untuk static element saat dokumen siap
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof jQuery === 'undefined' || typeof $ === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
                    script.onload = initializeSelect2;
                    document.head.appendChild(script);
                } else {
                    initializeSelect2();
                }

                function initializeSelect2() {
                    $('#customer_id').select2({
                        placeholder: 'Pilih Customer',
                        allowClear: true,
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-modern'
                    });
                    $('#status').select2({
                        minimumResultsForSearch: -1,
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-modern'
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
