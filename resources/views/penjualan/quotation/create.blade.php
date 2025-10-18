<x-app-layout :breadcrumbs="[
    ['label' => 'Penjualan'],
    ['label' => 'Quotation', 'url' => route('penjualan.quotation.index')],
    ['label' => 'Tambah'],
]" :currentPage="'Tambah Quotation'">
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

            /* Ensure buttons are visible and properly styled */
            button[type="button"] {
                display: inline-flex !important;
                align-items: center !important;
                gap: 0.5rem !important;
                opacity: 1 !important;
                visibility: visible !important;
            }

            /* Bundle item styling */
            .bundle-item {
                border-left: 4px solid #60a5fa;
                background: rgba(59, 130, 246, 0.05);
                margin-left: 1.5rem;
            }

            .dark .bundle-item {
                background: rgba(59, 130, 246, 0.1);
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

    <div x-data="quotationForm()" x-cloak x-init="$nextTick(() => { init() })" class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Add meta tag for CSRF protection in fetch requests -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <form method="POST" action="{{ route('penjualan.quotation.store') }}" id="quotationCreateForm"
            @submit.prevent="validateAndSubmitForm($event)" autocomplete="off">
            @csrf
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
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Quotation</h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Buat quotation baru untuk
                                    customer.</p>
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
                            Simpan Quotation
                        </button>
                    </div>
                </div>

                {{-- Form Section - Header Details --}}
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
                    <div>
                        <label for="nomor_quotation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Quotation
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="nomor" id="nomor_quotation" value="{{ old('nomor', $nomor) }}"
                            readonly
                            class="block w-full pr-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('nomor_quotation') border-red-500 dark:border-red-500 @enderror">
                        @error('nomor_quotation')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $tanggal) }}"
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
                                <option value="{{ $customer->id }}" @if (old('customer_id') == $customer->id) selected @endif>
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
                            value="{{ old('tanggal_berlaku', $tanggal_berlaku) }}"
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
                                <option value="{{ $key }}" @if (old('status', 'draft') == $key) selected @endif>
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
                            placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
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
                            placeholder="Syarat pembayaran...">{{ old('syarat_ketentuan', $quotation_terms ?? '') }}</textarea>
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
                    <div class="flex items-center gap-2">
                        <button type="button" @click="addItem"
                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 dark:bg-primary-900/30 hover:bg-primary-100 dark:hover:bg-primary-900/50 rounded-lg text-sm font-medium text-primary-700 dark:text-primary-300 border border-primary-200 dark:border-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Item
                        </button>
                        <button type="button" @click="showBundleModal = true"
                            class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Tambah Bundle
                        </button>
                    </div>
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
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                            <button type="button" @click="addItem"
                                class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Item Pertama
                            </button>
                            <button type="button" @click="showBundleModal = true"
                                class="inline-flex items-center gap-2 px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Tambah Bundle Produk
                            </button>
                        </div>
                    </div>

                    <div x-show="$data.items && $data.items.length > 0" class="overflow-x-auto">
                        <div class="space-y-3 mt-2" id="items-container">
                            <template x-for="(item, index) in $data.items" :key="item.id">
                                <div>
                                    <!-- Bundle Header (standalone card) -->
                                    <div x-show="item.is_bundle"
                                        style="border-left: 4px solid #10b981; background-color: #ecfdf5; padding: 16px; border-radius: 8px; margin-bottom: 10px;">

                                        <div
                                            style="display: flex; align-items: center; justify-content: space-between;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div
                                                    style="width: 40px; height: 40px; background-color: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                    <span style="color: white; font-size: 14px;">📦</span>
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; color: #047857;"
                                                        x-text="item.bundle_name"></div>
                                                    <div style="font-size: 14px; color: #059669;"
                                                        x-text="item.bundle_code"></div>
                                                </div>
                                                <span
                                                    style="background-color: #d1fae5; color: #047857; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 500;">
                                                    Bundle
                                                </span>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <span style="font-weight: 500; color: #047857;">Qty:</span>
                                                    <input type="number" x-model.number="item.kuantitas"
                                                        @input="updateBundleCalculations(index)"
                                                        style="width: 80px; padding: 4px 8px; text-align: center; border: 1px solid #a7f3d0; border-radius: 6px; outline: none;"
                                                        min="1" step="1">
                                                    <span style="color: #059669; font-size: 14px;">paket</span>
                                                </div>
                                                <div style="display: flex; align-items: center; gap: 8px;">
                                                    <span style="font-weight: 500; color: #047857;">Diskon
                                                        Tambahan:</span>
                                                    <input type="number"
                                                        x-model.number="item.additional_diskon_persen"
                                                        @input="updateBundleCalculations(index)"
                                                        style="width: 70px; padding: 4px 8px; text-align: center; border: 1px solid #a7f3d0; border-radius: 6px; outline: none;"
                                                        min="0" max="100" step="0.01"
                                                        placeholder="0">
                                                    <span style="color: #059669; font-size: 14px;">%</span>
                                                </div>
                                                <div style="text-align: right;">
                                                    <div style="font-weight: bold; color: #047857;"
                                                        x-text="formatRupiah(item.subtotal || 0)"></div>
                                                    <div style="font-size: 12px; color: #059669;">
                                                        <span x-text="item.kuantitas"></span> x
                                                        <!-- Show original price and discounted price if there's additional discount -->
                                                        <template
                                                            x-if="item.additional_diskon_persen && item.additional_diskon_persen > 0">
                                                            <span>
                                                                <span
                                                                    style="text-decoration: line-through; color: #9CA3AF;"
                                                                    x-text="formatRupiah(item.harga)"></span>
                                                                <span style="color: #059669; font-weight: 500;"
                                                                    x-text="formatRupiah(item.harga * (1 - (item.additional_diskon_persen || 0) / 100))"></span>
                                                            </span>
                                                        </template>
                                                        <!-- Show original price if no additional discount -->
                                                        <template
                                                            x-if="!item.additional_diskon_persen || item.additional_diskon_persen <= 0">
                                                            <span x-text="formatRupiah(item.harga)"></span>
                                                        </template>
                                                    </div>
                                                    <div style="font-size: 11px; color: #dc2626;"
                                                        x-show="item.additional_diskon_persen && item.additional_diskon_persen > 0">
                                                        Diskon Tambahan: <span
                                                            x-text="item.additional_diskon_persen.toFixed(2)"></span>%
                                                    </div>
                                                </div>
                                                <button type="button" @click="removeItem(index)"
                                                    style="padding: 8px; color: #ef4444; background: transparent; border: none; border-radius: 6px; cursor: pointer;"
                                                    onmouseover="this.style.backgroundColor='#fef2f2'"
                                                    onmouseout="this.style.backgroundColor='transparent'">
                                                    ❌
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Hidden fields for bundle header -->
                                        <input type="hidden" :name="`items[${index}][id]`" x-model="item.item_id_db">
                                        <input type="hidden" :name="`items[${index}][bundle_id]`"
                                            :value="item.bundle_id">
                                        <input type="hidden" :name="`items[${index}][is_bundle]`" value="1">
                                        <input type="hidden" :name="`items[${index}][kuantitas]`"
                                            :value="item.kuantitas">
                                        <input type="hidden" :name="`items[${index}][harga]`" :value="item.harga">
                                        <input type="hidden" :name="`items[${index}][subtotal]`"
                                            :value="item.subtotal">
                                        <input type="hidden" :name="`items[${index}][deskripsi]`"
                                            :value="item.deskripsi">
                                        <input type="hidden" :name="`items[${index}][diskon_persen_item]`"
                                            :value="item.diskon_persen || 0">
                                        <input type="hidden" :name="`items[${index}][additional_bundle_discount]`"
                                            :value="item.additional_bundle_discount || 0">
                                    </div>

                                    <!-- Regular Item Card -->
                                    <div x-show="!item.is_bundle && !item.is_bundle_item"
                                        class="border border-gray-200 dark:border-gray-600 rounded-lg p-5 shadow-sm hover:shadow-md transition-all duration-200 hover:border-primary-200 dark:hover:border-primary-800 bg-white dark:bg-gray-800">

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
                                                <div class="md:col-span-5"
                                                    x-show="!item.is_bundle && !item.is_bundle_item">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Produk
                                                        <span class="text-red-500">*</span></label>
                                                    <input type="hidden" :name="`items[${index}][id]`"
                                                        x-model="$data.items[index].item_id_db"> {{-- For existing item ID --}}
                                                    <select :name="`items[${index}][produk_id]`"
                                                        x-model="$data.items[index].produk_id"
                                                        @change="updateItemDetails(index, $event)"
                                                        :required="!item.is_bundle && !item.is_bundle_item"
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

                                                {{-- Deskripsi Item untuk regular items --}}
                                                <div class="md:col-span-7"
                                                    x-show="!item.is_bundle && !item.is_bundle_item">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi
                                                        Item</label>
                                                    <textarea :name="`items[${index}][deskripsi]`" x-model="$data.items[index].deskripsi" rows="1"
                                                        placeholder="Deskripsi tambahan untuk item ini..."
                                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"></textarea>
                                                </div>

                                                {{-- Bundle/Bundle Item Display --}}
                                                <div class="md:col-span-12"
                                                    x-show="item.is_bundle || item.is_bundle_item">
                                                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg">
                                                        <h4 class="font-medium text-blue-900 dark:text-blue-200"
                                                            x-text="item.deskripsi || item.bundle_name"></h4>
                                                        <template x-if="item.is_bundle_item">
                                                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                                                Item dari bundle: <span
                                                                    x-text="item.bundle_name"></span>
                                                            </p>
                                                        </template>
                                                    </div>
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
                                                        x-model.number="$data.items[index].kuantitas"
                                                        @input="calculateSubtotal(index)"
                                                        :required="!item.is_bundle_item" placeholder="0"
                                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
                                                </div>

                                                {{-- Satuan --}}
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Satuan
                                                        <span class="text-red-500">*</span></label>
                                                    <select :name="`items[${index}][satuan_id]`"
                                                        x-model="$data.items[index].satuan_id"
                                                        :required="!item.is_bundle && !item.is_bundle_item"
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
                                                            @input="calculateSubtotal(index)"
                                                            :required="!item.is_bundle_item" placeholder="0"
                                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                    </div>
                                                </div>

                                                {{-- Diskon Item (%) --}}
                                                <div class="md:col-span-2">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Diskon
                                                        (%)</label>
                                                    <input type="number" step="any" min="0"
                                                        max="100" :name="`items[${index}][diskon_persen_item]`"
                                                        x-model.number="$data.items[index].diskon_persen"
                                                        @input="calculateSubtotal(index, 'persen')" placeholder="0"
                                                        class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
                                                </div>

                                                {{-- Diskon fields for controller compatibility --}}
                                                <input type="hidden" :name="`items[${index}][diskon_nominal_item]`"
                                                    :value="$data.items[index].diskon_nominal ? $data.items[index]
                                                        .diskon_nominal
                                                        .toFixed(2) : '0.00'">

                                                {{-- Bundle fields for controller compatibility --}}
                                                <input type="hidden" :name="`items[${index}][is_bundle]`"
                                                    :value="item.is_bundle ? 1 : 0">
                                                <input type="hidden" :name="`items[${index}][is_bundle_item]`"
                                                    :value="item.is_bundle_item ? 1 : 0">
                                                <input type="hidden" :name="`items[${index}][bundle_id]`"
                                                    :value="item.bundle_id || ''">
                                                <input type="hidden" :name="`items[${index}][bundle_nama]`"
                                                    :value="item.bundle_nama || ''">

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
                                                                .formatCurrency($data.items[index].subtotal) : ($data
                                                                    .items[
                                                                        index].subtotal || 0)"
                                                            readonly
                                                            class="bg-gray-100 dark:bg-gray-700 focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:text-white rounded-md font-medium">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>

                            <!-- BUNDLE CHILD ITEMS - Simplified display -->
                            <template x-for="(item, index) in items" :key="'child-' + item.id">
                                <div x-show="item.is_bundle_item"
                                    class="ml-6 border-l-2 border-gray-300 dark:border-gray-600 pl-4 py-2 bg-gray-50 dark:bg-gray-800/50 rounded-r">

                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                            <span class="font-medium text-gray-700 dark:text-gray-300"
                                                x-text="item.nama || item.produk_nama || 'Bundle Item'"></span>
                                            <span class="text-gray-500 dark:text-gray-400"
                                                x-text="'(' + (item.kode || item.produk_kode || '') + ')' "></span>
                                        </div>
                                        <div
                                            class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span x-text="item.kuantitas + ' ' + (item.satuan_nama || 'pcs')"></span>
                                            <span class="text-gray-400"
                                                x-show="item.base_quantity && item.kuantitas !== item.base_quantity">
                                                (<span x-text="item.base_quantity"></span> × <span
                                                    x-text="Math.round(item.kuantitas / item.base_quantity)"></span>
                                                paket)
                                            </span>
                                            <span>×</span>
                                            <!-- Show original price if there's discount -->
                                            <template x-if="item.total_diskon_persen && item.total_diskon_persen > 0">
                                                <div class="flex items-center space-x-1">
                                                    <span class="line-through text-gray-400"
                                                        x-text="formatRupiah(item.harga)"></span>
                                                    <span class="text-green-600 font-medium"
                                                        x-text="formatRupiah(item.harga * (1 - (item.total_diskon_persen || 0) / 100))"></span>
                                                </div>
                                            </template>
                                            <!-- Show original price if no discount -->
                                            <template
                                                x-if="!item.total_diskon_persen || item.total_diskon_persen <= 0">
                                                <span x-text="formatRupiah(item.harga)"></span>
                                            </template>
                                            <!-- Show total discount if applicable -->
                                            <template x-if="item.total_diskon_persen && item.total_diskon_persen > 0">
                                                <div class="flex items-center space-x-1">
                                                    <span class="text-red-500">(-<span
                                                            x-text="item.total_diskon_persen.toFixed(2)"></span>%)</span>
                                                </div>
                                            </template>
                                            <span>=</span>
                                            <span class="font-semibold text-gray-700 dark:text-gray-300"
                                                x-text="formatRupiah(item.subtotal || 0)"></span>
                                        </div>
                                    </div>

                                    <!-- Hidden inputs for bundle child items -->
                                    <input type="hidden" :name="`items[${index}][id]`" x-model="item.item_id_db">
                                    <input type="hidden" :name="`items[${index}][produk_id]`"
                                        :value="item.produk_id">
                                    <input type="hidden" :name="`items[${index}][is_bundle_item]`" value="1">
                                    <input type="hidden" :name="`items[${index}][bundle_id]`"
                                        :value="item.bundle_id">
                                    <input type="hidden" :name="`items[${index}][kuantitas]`"
                                        :value="item.kuantitas">
                                    <input type="hidden" :name="`items[${index}][satuan_id]`"
                                        :value="item.satuan_id">
                                    <input type="hidden" :name="`items[${index}][harga]`" :value="item.harga">
                                    <input type="hidden" :name="`items[${index}][deskripsi]`"
                                        :value="item.deskripsi">
                                    <!-- Store total discount from bundle -->
                                    <input type="hidden" :name="`items[${index}][diskon_persen_item]`"
                                        :value="item.total_diskon_persen || 0">
                                    <input type="hidden" :name="`items[${index}][diskon_nominal_item]`"
                                        :value="item.diskon_nominal || 0">
                                    <input type="hidden" :name="`items[${index}][subtotal]`" :value="item.subtotal">
                                    <!-- Bundle Additional Discount -->
                                    <input type="hidden" :name="`items[${index}][additional_diskon_persen]`"
                                        :value="item.additional_diskon_persen || 0" x-show="item.is_bundle">
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

                        <!-- Ongkos Kirim -->
                        <div class="flex justify-between items-center mt-2">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-300">Ongkos Kirim:</label>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-500">Rp</span>
                                <input type="number" name="ongkos_kirim" x-model="$data.ongkosKirim"
                                    @input="calculateTotals()" min="0" step="1000"
                                    class="w-32 text-right rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:text-white text-sm"
                                    placeholder="0">
                            </div>
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
                    Simpan Quotation
                </button>
            </div>
        </form>

        <!-- Bundle Modal -->
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

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function quotationForm() {
                return {
                    items: [],
                    products: @json($products ?? []),
                    bundles: @json($bundles ?? []),
                    satuans: @json($satuans ?? []),
                    diskon_global_persen: 0,
                    diskon_global_nominal: 0,
                    includePPN: false,
                    ongkosKirim: 0,
                    summary: {
                        total_sebelum_diskon_global: 0,
                        total_setelah_diskon_global: 0,
                        ppn_nominal: 0,
                        grand_total: 0
                    },
                    formErrors: [],
                    nextItemId: 1,
                    showBundleModal: false,
                    bundleSearch: '',
                    init() {
                        // Load old items if available
                        let oldItems = {!! json_encode(old('items', [])) !!};
                        if (oldItems.length > 0) {
                            this.items = oldItems.map(item => ({
                                id: this.nextItemId++,
                                item_id_db: item.id || null,
                                produk_id: item.produk_id || '',
                                kuantitas: parseFloat(item.kuantitas || item.quantity) || 1,
                                satuan_id: item.satuan_id || '',
                                harga: parseFloat(item.harga) || 0,
                                diskon_persen: parseFloat(item.diskon_persen) || 0,
                                diskon_nominal: 0,
                                deskripsi: item.deskripsi || '',
                                subtotal: parseFloat(item.subtotal) || 0,
                                // Bundle related fields
                                bundle_id: item.bundle_id || null,
                                item_type: item.item_type || 'product',
                                is_bundle: item.is_bundle || false,
                                is_bundle_item: item.is_bundle_item || false,
                                bundle_name: item.bundle_name || '',
                                bundle_code: item.bundle_code || ''
                            }));
                        }

                        this.items.forEach((item, index) => this.calculateSubtotal(index));
                        this.calculateTotals();
                        this.$nextTick(() => {
                            this.initSelect2();
                        });
                    },
                    get filteredBundles() {
                        if (!this.bundleSearch.trim()) {
                            return this.bundles;
                        }
                        const search = this.bundleSearch.toLowerCase();
                        return this.bundles.filter(bundle =>
                            bundle.nama.toLowerCase().includes(search) ||
                            bundle.kode.toLowerCase().includes(search) ||
                            (bundle.deskripsi && bundle.deskripsi.toLowerCase().includes(search))
                        );
                    },
                    formatCurrency(value) {
                        if (typeof value !== 'number') {
                            value = parseFloat(value) || 0;
                        }
                        return value.toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    },
                    formatRupiah(value) {
                        if (typeof value !== 'number') {
                            value = parseFloat(value) || 0;
                        }
                        return 'Rp ' + value.toLocaleString('id-ID', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
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
                        // Find the correct position to insert regular items
                        // Regular items should be inserted before any bundle items
                        let insertIndex = 0;

                        // Find the first bundle item to insert regular items before it
                        for (let i = 0; i < this.items.length; i++) {
                            if (this.items[i].is_bundle || this.items[i].is_bundle_item) {
                                insertIndex = i;
                                break;
                            } else {
                                // If it's a regular item, update the insert index to after it
                                insertIndex = i + 1;
                            }
                        }

                        const newItem = {
                            id: this.nextItemId++,
                            item_id_db: null,
                            produk_id: '',
                            produk_nama: '',
                            kuantitas: 1,
                            satuan_id: '',
                            satuan_nama: '',
                            harga: 0,
                            diskon_persen: 0,
                            diskon_nominal: 0,
                            subtotal: 0,
                            deskripsi: ''
                        };

                        // Insert at the calculated position to keep regular items grouped together
                        this.items.splice(insertIndex, 0, newItem);

                        this.$nextTick(() => {
                            this.initSelect2();
                        });

                        // Reorganize items to ensure proper grouping
                        this.reorganizeItems();
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

                        // Skip bundle items - they have their own calculation
                        if (item.is_bundle_item) return;

                        const qty = parseFloat(item.kuantitas) || 0;
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
                            // Skip bundle child items to avoid double counting - only count bundle headers and regular items
                            if (item.is_bundle_item) return;
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
                        const ongkosKirim = parseFloat(this.ongkosKirim) || 0;
                        this.summary.grand_total = this.summary.total_setelah_diskon_global + ppnNominal + ongkosKirim;
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
                            const url = `/penjualan/quotation/get-bundle-data/${bundle.id}`;

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
                            // Get bundle discount
                            const bundleDiskonPersen = parseFloat(bundleData.diskon_persen || 0);

                            // Bundle header shows original price without discount applied
                            const bundleSubtotalBefore = parseFloat(bundleData.harga_bundle || 0);
                            const bundleSubtotal = bundleSubtotalBefore; // Header tidak dikurangi diskon

                            // Add bundle as main item with bundle type
                            const bundleMainItem = {
                                id: this.nextItemId++,
                                produk_id: '', // Bundle doesn't have single product ID
                                bundle_id: bundleData.id,
                                item_type: 'bundle',
                                deskripsi: `Bundle: ${bundleData.nama}`,
                                kuantitas: 1,
                                satuan_id: '',
                                harga: bundleSubtotalBefore,
                                diskon_persen: 0, // Header tidak ada diskon
                                diskon_nominal: 0, // Header tidak ada diskon nominal
                                subtotal: bundleSubtotal, // Header subtotal tanpa diskon
                                is_bundle: true,
                                is_bundle_item: false,
                                bundle_name: bundleData.nama,
                                bundle_code: bundleData.kode
                            };

                            // Add bundle header at the end
                            this.items.push(bundleMainItem);

                            // Add individual bundle items immediately after the header
                            const bundleChildItems = [];
                            bundleItems.forEach((item) => {
                                // Apply bundle discount to each child item
                                const itemSubtotalBefore = (item.harga_satuan || 0) * item.quantity;
                                const itemDiskonNominal = (itemSubtotalBefore * bundleDiskonPersen) / 100;
                                const itemSubtotalAfter = itemSubtotalBefore - itemDiskonNominal;

                                const childItem = {
                                    id: this.nextItemId++,
                                    produk_id: item.produk_id,
                                    bundle_id: bundleData.id,
                                    item_type: 'bundle_item',
                                    parent_bundle_name: bundleData.nama,
                                    kode: item.produk_kode || '',
                                    nama: item.produk_nama || '',
                                    satuan_nama: item.satuan_nama || '',
                                    deskripsi: `└─ ${item.produk_nama} (dari bundle ${bundleData.nama})`,
                                    kuantitas: item.quantity,
                                    base_quantity: item.quantity, // For calculating bundle multiplier
                                    satuan_id: item.satuan_id,
                                    harga: item.harga_satuan || 0,
                                    // Bundle discount fields
                                    bundle_diskon_persen: bundleDiskonPersen,
                                    additional_diskon_persen: 0, // Default additional discount
                                    total_diskon_persen: bundleDiskonPersen,
                                    // Legacy fields for compatibility
                                    diskon_persen: bundleDiskonPersen,
                                    diskon_nominal: itemDiskonNominal,
                                    subtotal: itemSubtotalAfter,
                                    is_bundle: false,
                                    is_bundle_item: true,
                                    bundle_name: bundleData.nama,
                                    bundle_code: bundleData.kode
                                };

                                bundleChildItems.push(childItem);
                            });

                            // Add all bundle child items at once to keep them grouped
                            this.items.push(...bundleChildItems);

                            // Reorganize items to ensure proper grouping
                            this.reorganizeItems();

                            // Recalculate totals
                            this.calculateTotals();

                            // Show success notification if available
                            if (typeof window.notify === 'function') {
                                window.notify(`Bundle "${bundleData.nama}" berhasil ditambahkan`, 'success', 'Berhasil');
                            }
                        } catch (error) {
                            console.error('Error processing bundle data:', error);
                            let errorMessage = 'Gagal memproses data bundle';
                            if (error.message) errorMessage += ': ' + error.message;
                            if (typeof window.notify === 'function') {
                                window.notify(errorMessage, 'error', 'Error');
                            } else {
                                alert(errorMessage);
                            }
                        }
                    },
                    updateBundleCalculations(index) {
                        const item = this.items[index];
                        if (!item.is_bundle) return;

                        // Get bundle-level additional discount from bundle header item
                        const bundleAdditionalDiscount = parseFloat(item.additional_diskon_persen) || 0;

                        // Bundle header: Apply additional discount to subtotal
                        const baseSubtotal = (parseFloat(item.harga) || 0) * (parseFloat(item.kuantitas) || 1);
                        const discountAmount = baseSubtotal * (bundleAdditionalDiscount / 100);

                        item.diskon_persen = bundleAdditionalDiscount;
                        item.diskon_nominal = discountAmount;
                        item.subtotal = baseSubtotal - discountAmount;

                        // Update all bundle items quantities and apply discount to child items only
                        const bundleId = item.bundle_id;
                        const bundleQuantity = parseFloat(item.kuantitas) || 1;

                        // Get bundle discount from bundle data
                        const bundleData = this.bundles.find(b => b.id == bundleId);
                        const bundleDiskonPersen = bundleData ? parseFloat(bundleData.diskon_persen || 0) : 0;

                        this.items.forEach((bundleItem, itemIndex) => {
                            if (bundleItem.is_bundle_item && bundleItem.bundle_id === bundleId) {
                                // Update bundle item quantity (base quantity * bundle quantity)
                                const baseQuantity = parseFloat(bundleItem.base_quantity) || parseFloat(bundleItem
                                    .kuantitas) || 1;
                                bundleItem.kuantitas = baseQuantity * bundleQuantity;

                                // Set bundle discount
                                bundleItem.bundle_diskon_persen = bundleDiskonPersen;

                                // Apply bundle-level additional discount to all child items
                                bundleItem.additional_diskon_persen = bundleAdditionalDiscount;

                                // Calculate combined discount and subtotal
                                this.calculateBundleItemDiscount(itemIndex);
                            }
                        });

                        // Recalculate totals
                        this.calculateTotals();
                    },

                    calculateBundleItemDiscount(index) {
                        const item = this.items[index];
                        if (!item.is_bundle_item) return;

                        // Get base values
                        const harga = parseFloat(item.harga) || 0;
                        const kuantitas = parseFloat(item.kuantitas) || 0;
                        const bundleDiskonPersen = parseFloat(item.bundle_diskon_persen) || 0;
                        const additionalDiskonPersen = parseFloat(item.additional_diskon_persen) || 0;

                        // Calculate subtotal before any discount
                        const subtotalBefore = harga * kuantitas;

                        // Calculate total discount percentage (bundle + additional)
                        // Formula: Total = Bundle + Additional - (Bundle * Additional / 100)
                        // This prevents over-discounting when combining percentages
                        const totalDiskonPersen = bundleDiskonPersen + additionalDiskonPersen -
                            (bundleDiskonPersen * additionalDiskonPersen / 100);

                        // Calculate nominal discount
                        const diskonNominal = (subtotalBefore * totalDiskonPersen) / 100;

                        // Calculate final subtotal
                        const subtotalAfter = subtotalBefore - diskonNominal;

                        // Update item values
                        item.total_diskon_persen = Math.round(totalDiskonPersen * 100) / 100; // Round to 2 decimal places
                        item.diskon_persen = Math.round(totalDiskonPersen * 100) / 100; // Round to match total_diskon_persen
                        item.diskon_nominal = diskonNominal;
                        item.subtotal = subtotalAfter;

                        // Debug log for bundle item discount calculation
                        console.log('Bundle item discount calculation (CREATE):', {
                            produk_nama: item.produk_nama || item.nama,
                            bundleDiskonPersen,
                            additionalDiskonPersen,
                            totalDiskonPersen: item.total_diskon_persen,
                            finalDiskonPersen: item.diskon_persen
                        });

                        // Recalculate totals
                        this.calculateTotals();
                    },
                    validateAndSubmitForm(e) {
                        this.formErrors = [];

                        // Count only non-bundle-items for validation
                        const validatableItems = this.items.filter(item => !item.is_bundle_item);

                        if (!validatableItems.length) {
                            this.formErrors.push('Minimal 1 item produk harus diisi.');
                        }

                        this.items.forEach((item, idx) => {
                            // Skip validation for bundle child items
                            if (item.is_bundle_item) return;

                            // For bundle headers, skip product and satuan validation
                            if (item.is_bundle) {
                                if (!item.kuantitas || item.kuantitas <= 0) {
                                    this.formErrors.push('Kuantitas pada bundle #' + (idx + 1) +
                                        ' wajib diisi dan > 0.');
                                }
                                if (!item.harga || item.harga < 0) {
                                    this.formErrors.push('Harga pada bundle #' + (idx + 1) + ' wajib diisi dan >= 0.');
                                }
                            } else {
                                // Regular product validation
                                if (!item.produk_id) {
                                    this.formErrors.push('Produk pada item #' + (idx + 1) + ' wajib diisi.');
                                }
                                if (!item.satuan_id) {
                                    this.formErrors.push('Satuan pada item #' + (idx + 1) + ' wajib diisi.');
                                }
                                if (!item.kuantitas || item.kuantitas <= 0) {
                                    this.formErrors.push('Kuantitas pada item #' + (idx + 1) + ' wajib diisi dan > 0.');
                                }
                                if (!item.harga || item.harga < 0) {
                                    this.formErrors.push('Harga pada item #' + (idx + 1) + ' wajib diisi dan >= 0.');
                                }
                            }
                        });

                        if (this.formErrors.length) {
                            e.preventDefault();
                            return false;
                        }

                        // Set hidden input for global diskon, ppn, total
                        const form = document.getElementById('quotationCreateForm');
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
                        createGlobalHiddenInput('ppn', this.includePPN ? "{{ setting('tax_percentage', 11) }}" : '0');
                        createGlobalHiddenInput('ongkos_kirim', this.ongkosKirim || '0');
                        createGlobalHiddenInput('total', this.summary.grand_total || '0');
                        form.submit();
                    }
                };
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
