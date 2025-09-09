<x-app-layout>
    <div x-data="purchaseOrderForm()" class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @push('styles')
            <style>
                .form-card {
                    transition: all 0.3s ease;
                }

                .form-card:hover {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }

                /* Hide spinners from number inputs */
                input[type=number]::-webkit-inner-spin-button,
                input[type=number]::-webkit-outer-spin-button {
                    -webkit-appearance: none;
                    margin: 0;
                }

                input[type=number] {
                    -moz-appearance: textfield;
                    /* Firefox */
                }
            </style>
        @endpush

        <form action="{{ route('pembelian.purchasing-order.store') }}" method="POST" @submit="validateForm">
            @csrf

            {{-- Header Section --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 mb-6 form-card">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Purchase Order</h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tambahkan purchase order baru
                                    untuk pesanan ke supplier.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('pembelian.purchasing-order.index') }}"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Simpan Purchase Order
                        </button>
                    </div>
                </div>

                {{-- Form Section - Header Details --}}
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6">
                    {{-- First Row: Nomor PO, Tanggal, Supplier --}}
                    <div>
                        <label for="nomor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nomor Purchase Order <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="text" name="nomor" id="nomor"
                                value="{{ $nomorPO ?? 'PO-' . date('Ymd') . '-001' }}" readonly
                                class="block w-full pr-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal PO --}}
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" required
                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label for="supplier_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Supplier <span class="text-red-500">*</span>
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <select name="supplier_id" id="supplier_id" required
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">Pilih Supplier</option>
                                @foreach ($suppliers ?? [] as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama }}</option>
                                @endforeach
                                @push('styles')
                                    <!-- Select2 CSS -->
                                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
                                        rel="stylesheet" />
                                    <style>
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
                                    </style>
                                @endpush

                                @push('scripts')
                                    <!-- jQuery -->
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <!-- Select2 JS -->
                                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                                    <script>
                                        $(document).ready(function() {
                                            $('#supplier_id').select2({
                                                theme: 'default',
                                                placeholder: 'Pilih Supplier',
                                                allowClear: true
                                            });
                                            // Optional: focus select2 on open
                                            $('#supplier_id').on('select2:open', function() {
                                                $('.select2-search__field').focus();
                                            });
                                        });
                                    </script>
                                @endpush
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Second Row: Tanggal Pengiriman, PR, Status --}}
                    {{-- Tanggal Pengiriman --}}
                    <div>
                        <label for="tanggal_pengiriman"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tanggal Pengiriman
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="date" name="tanggal_pengiriman" id="tanggal_pengiriman"
                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Purchase Request (Optional) --}}
                    <div>
                        <label for="pr_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Berdasarkan Permintaan Pembelian
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <select name="pr_id" id="pr_id"
                                @change="loadItemsFromPurchaseRequest($event.target.value)"
                                data-placeholder="Tidak Berdasarkan PR"
                                class="pr-select block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">Tidak Berdasarkan PR</option>
                                @foreach ($purchaseRequests ?? [] as $pr)
                                    <option value="{{ $pr->id }}">{{ $pr->nomor }} - {{ $pr->tanggal }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Status
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <select name="status" id="status"
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="draft">Draft</option>
                                <option value="submit">Submit</option>
                                <option value="approved">Disetujui</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Alamat Pengiriman --}}
                    <div class="md:col-span-3">
                        <label for="alamat_pengiriman"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Alamat Pengiriman
                        </label>
                        <div>
                            <textarea id="alamat_pengiriman" name="alamat_pengiriman" rows="2"
                                class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                placeholder="Alamat pengiriman barang..."></textarea>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="md:col-span-1 lg:col-span-2">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Catatan
                        </label>
                        <div>
                            <textarea id="catatan" name="catatan" rows="2"
                                class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                placeholder="Catatan tambahan untuk purchase order ini..."></textarea>
                        </div>
                    </div>

                    {{-- Syarat & Ketentuan --}}
                    <div class="md:col-span-1">
                        <label for="syarat_ketentuan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Syarat & Ketentuan
                        </label>
                        <div>
                            <textarea id="syarat_ketentuan" name="syarat_ketentuan" rows="2"
                                class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                placeholder="Syarat dan ketentuan pembelian..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items Section --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 form-card">
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
                    <div x-show="!items.length" class="text-center py-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada item</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan item untuk
                            purchase order ini.</p>
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
                        {{-- Items --}}
                        <div class="space-y-3 mt-2" id="items-container">
                            <template x-for="(item, index) in items" :key="index">
                                <div
                                    class="border-2 border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    {{-- Mobile Item Header --}}
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

                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        {{-- Produk --}}
                                        <div x-data="customProductDropdown({
                                            initialProdukId: item.produk_id,
                                            itemIndex: index
                                        })" x-init="init()"
                                            class="relative md:col-span-4">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Produk</label>
                                            <input type="hidden" :name="`items[${index}][produk_id]`"
                                                x-model="selectedProdukIdProxy">

                                            <button type="button" @click="toggleDropdown"
                                                @keydown.escape="closeDropdown()"
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md text-left relative shadow-sm">
                                                <span x-text="selectedProdukText || 'Pilih Produk'"
                                                    class="block truncate"></span>
                                                <span
                                                    class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </button>

                                            <div x-show="open" @click.away="closeDropdown()"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95"
                                                class="absolute z-20 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black dark:ring-gray-700 ring-opacity-5 overflow-auto focus:outline-none sm:text-sm border border-gray-300 dark:border-gray-600"
                                                style="display: none;">
                                                <div class="p-2">
                                                    <input type="text" x-model="searchTerm"
                                                        @keydown.arrow-down.prevent="focusNextOption()"
                                                        @keydown.arrow-up.prevent="focusPreviousOption()"
                                                        @keydown.enter.prevent="selectFocusedOption()"
                                                        placeholder="Cari produk..." x-ref="searchInput"
                                                        class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm py-2 px-3">
                                                </div>
                                                <ul tabindex="-1" role="listbox"
                                                    :aria-activedescendant="focusedOptionIndex !== null ? `option-${focusedOptionIndex}` : null"
                                                    x-ref="listbox">
                                                    <template x-for="(produk, prodIndex) in filteredProduks"
                                                        :key="produk.id">
                                                        <li @click="selectProduk(produk)"
                                                            @mouseenter="focusedOptionIndex = prodIndex"
                                                            @mouseleave="focusedOptionIndex = null"
                                                            :id="`option-${prodIndex}`" role="option"
                                                            :aria-selected="isSelected(produk.id)"
                                                            :class="{
                                                                'font-bold text-blue-800 dark:text-blue-300': produk
                                                                    .owned,
                                                                'bg-primary-100 dark:bg-primary-700 text-white': focusedOptionIndex ===
                                                                    prodIndex && !isSelected(produk.id),
                                                                'bg-primary-50 dark:bg-primary-600': isSelected(produk
                                                                    .id) && focusedOptionIndex !== prodIndex,
                                                                'bg-primary-200 dark:bg-primary-800 text-white': isSelected(
                                                                    produk.id) && focusedOptionIndex === prodIndex
                                                            }"
                                                            class="cursor-pointer select-none relative py-2 pl-3 pr-9 text-gray-900 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                            <span class="block truncate"
                                                                x-text="`${produk.kode} - ${produk.nama} ${produk.owned ? '[Milik Supplier]' : ''}`"></span>
                                                            <span x-show="isSelected(produk.id)"
                                                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600 dark:text-primary-400">
                                                                <svg class="h-5 w-5"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </span>
                                                        </li>
                                                    </template>
                                                </ul>
                                                <div x-show="filteredProduks.length === 0 && searchTerm"
                                                    class="py-2 px-4 text-gray-500 dark:text-gray-400">
                                                    Produk tidak ditemukan.
                                                </div>
                                                <div x-show="filteredProduks.length === 0 && !searchTerm && allProduksInternal.length > 0"
                                                    class="py-2 px-4 text-gray-500 dark:text-gray-400">
                                                    Mulai ketik untuk mencari.
                                                </div>
                                                <div x-show="allProduksInternal.length === 0"
                                                    class="py-2 px-4 text-gray-500 dark:text-gray-400">
                                                    Tidak ada produk tersedia. Pilih supplier terlebih dahulu.
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Jumlah --}}
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah</label>
                                            <input type="number" step="1" min="1"
                                                :name="`items[${index}][quantity]`" x-model="item.quantity" required
                                                @input="updateSubtotal(index)" placeholder="0"
                                                class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md text-center">
                                        </div>

                                        {{-- Satuan --}}
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Satuan</label>
                                            <select :name="`items[${index}][satuan_id]`" x-model="item.satuan_id"
                                                required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                <option value="">Pilih</option>
                                                @foreach ($satuans ?? [] as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Harga --}}
                                        <div class="md:col-span-4">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Harga</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" :name="`items[${index}][harga]`"
                                                    x-model="item.harga" min="0" placeholder="0"
                                                    @input="updateSubtotal(index)"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second row for Diskon, Subtotal, Deskripsi, Aksi --}}
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-4">
                                        {{-- Diskon --}}
                                        <div class="md:col-span-3">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Diskon</label>
                                            <div class="mt-1 flex items-center gap-2">
                                                <div class="relative rounded-md shadow-sm flex-1">
                                                    <input type="number" :name="`items[${index}][diskon_persen]`"
                                                        x-model="item.diskon_persen" min="0" max="100"
                                                        step="0.01" placeholder="0"
                                                        @input="updateDiskonNominal(index)"
                                                        class="focus:ring-primary-500 focus:border-primary-500 block w-full pr-8 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                    <div
                                                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <span
                                                            class="text-gray-500 dark:text-gray-400 sm:text-sm">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" :name="`items[${index}][diskon_nominal]`"
                                                    x-model="item.diskon_nominal" min="0" step="0.01"
                                                    placeholder="0" @input="updateDiskonPersen(index)"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                            </div>
                                        </div>

                                        {{-- Subtotal --}}
                                        <div class="md:col-span-3">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Subtotal</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" :name="`items[${index}][subtotal]`"
                                                    x-model="item.subtotal" readonly min="0" placeholder="0"
                                                    class="bg-gray-50 dark:bg-gray-700 focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:text-white rounded-md font-medium">
                                            </div>
                                        </div>

                                        {{-- Deskripsi --}}
                                        <div class="md:col-span-5">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                                            <textarea :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" rows="2" placeholder="Deskripsi item"
                                                class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"></textarea>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="md:col-span-1">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Aksi</label>
                                            <div class="mt-1 flex md:justify-end items-start">
                                                <button type="button" @click="removeItem(index)"
                                                    class="hidden md:inline-flex items-center p-1.5 border border-transparent rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Add Item Button (when items exist) --}}
                        <div class="mt-4 flex items-center justify-center">
                            <button type="button" @click="addItem"
                                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-gray-400 dark:text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tambah Item Lainnya
                            </button>
                        </div>

                        {{-- Order Summary Section --}}
                        <div class="mt-8 flex justify-end">
                            <div class="w-full md:w-1/3 lg:w-1/4">
                                <div
                                    class="bg-white dark:bg-gray-800 shadow-md rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="font-medium text-gray-900 dark:text-white">Ringkasan Order</h3>
                                    </div>
                                    <div class="p-5 space-y-3">
                                        {{-- Subtotal --}}
                                        <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                                            <span>Subtotal:</span>
                                            <span x-text="formatRupiah(calculateSubtotal())"></span>
                                            <input type="hidden" name="subtotal" x-model="calculateSubtotal()">
                                        </div>

                                        {{-- Diskon Order --}}
                                        <div class="flex justify-between items-center space-x-4">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Diskon:</span>
                                            <div class="flex items-center gap-2">
                                                <div class="relative rounded-md shadow-sm">
                                                    <input type="number" name="diskon_persen" x-model="diskonPersen"
                                                        min="0" max="100" step="0.01" placeholder="0"
                                                        @input="updateOrderDiskonNominal"
                                                        class="focus:ring-primary-500 focus:border-primary-500 block w-full pr-8 py-1 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                    <div
                                                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <span
                                                            class="text-gray-500 dark:text-gray-400 sm:text-sm">%</span>
                                                    </div>
                                                </div>
                                                <div class="relative rounded-md shadow-sm flex-1">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span
                                                            class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                    </div>
                                                    <input type="number" name="diskon_nominal"
                                                        x-model="diskonNominal" min="0" step="0.01"
                                                        placeholder="0" @input="updateOrderDiskonPersen"
                                                        class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 py-1 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- PPN --}}
                                        <div class="flex justify-between items-center space-x-4">
                                            <div class="flex items-center">
                                                <span class="text-sm text-gray-700 dark:text-gray-300 mr-2">PPN
                                                    ({{ setting('tax_percentage', 11) }}%):</span>
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" x-model="includePPN"
                                                        @change="updateTotals()" class="sr-only peer">
                                                    <!-- Hidden input untuk mengirim nilai include_ppn ke backend -->
                                                    <input type="hidden" name="include_ppn"
                                                        :value="includePPN ? 1 : 0">
                                                    <div
                                                        class="relative w-10 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-500 dark:peer-focus:ring-primary-600 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500">
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" name="ppn" x-model="ppn" readonly
                                                    class="bg-gray-50 dark:bg-gray-700 focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 py-1 sm:text-sm border-gray-300 dark:border-gray-600 dark:text-white rounded-md">
                                            </div>
                                        </div>

                                        {{-- Ongkos Kirim --}}
                                        <div class="flex justify-between items-center space-x-4">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Ongkos Kirim:</span>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" name="ongkos_kirim" x-model="ongkosKirim"
                                                    @input="updateTotals()" min="0" placeholder="0"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 py-1 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                            </div>
                                        </div>

                                        {{-- Divider --}}
                                        <div class="border-t border-gray-200 dark:border-gray-600 my-3"></div>

                                        {{-- Total --}}
                                        <div
                                            class="flex justify-between items-center font-medium text-gray-900 dark:text-white">
                                            <span class="font-bold">TOTAL:</span>
                                            <span class="text-lg font-bold text-primary-600 dark:text-primary-400"
                                                x-text="formatRupiah(calculateTotal())"></span>
                                            <input type="hidden" name="total" x-model="calculateTotal()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                        <div class="flex items-center">
                            <span x-show="isSubmitting" class="mr-3">
                                <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            <span x-show="errors.length" class="text-sm text-red-600 dark:text-red-400">
                                <span x-text="errors.length"></span> kesalahan perlu diperbaiki
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="{{ route('pembelian.purchasing-order.index') }}"
                                class="w-full sm:w-auto order-2 sm:order-1 px-4 py-2.5 flex justify-center items-center bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit"
                                class="w-full sm:w-auto order-1 sm:order-2 px-4 py-2.5 flex justify-center items-center bg-primary-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Purchase Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function customProductDropdown(config) {
            return {
                open: false,
                searchTerm: '',
                selectedProdukIdInternal: config.initialProdukId || null,
                selectedProdukText: '',
                allProduksInternal: [],
                itemIndex: config.itemIndex,
                focusedOptionIndex: null,

                init() {
                    this.$watch('$store.purchaseOrder.produksData', (newProduks) => {
                        this.allProduksInternal = Array.isArray(newProduks) ? [...newProduks] : [];
                        this.updateSelectedText();
                        if (this.selectedProdukIdInternal && !this.allProduksInternal.find(p => p.id == this
                                .selectedProdukIdInternal)) {}
                    });

                    this.allProduksInternal = Array.isArray(this.$store.purchaseOrder.produksData) ? [...this.$store
                        .purchaseOrder.produksData
                    ] : [];
                    this.updateSelectedText();

                    this.$watch(`$store.purchaseOrder.items[${this.itemIndex}].produk_id`, (newId) => {
                        if (this.selectedProdukIdInternal !== newId) {
                            this.selectedProdukIdInternal = newId;
                            this.updateSelectedText();
                        }
                    });
                    if (this.$store.purchaseOrder.items[this.itemIndex].produk_id !== this.selectedProdukIdInternal) {
                        this.selectedProdukIdInternal = this.$store.purchaseOrder.items[this.itemIndex].produk_id;
                        this.updateSelectedText();
                    }
                },

                get selectedProdukIdProxy() {
                    return this.selectedProdukIdInternal;
                },
                set selectedProdukIdProxy(value) {
                    if (this.selectedProdukIdInternal !== value) {
                        this.selectedProdukIdInternal = value;
                        this.$store.purchaseOrder.items[this.itemIndex].produk_id = value;
                        this.updateSelectedText();
                        this.$store.purchaseOrder.updateProdukDetail(this.itemIndex);
                    }
                },

                updateSelectedText() {
                    if (this.selectedProdukIdInternal) {
                        const produk = this.allProduksInternal.find(p => p.id == this.selectedProdukIdInternal);
                        if (produk) {
                            this.selectedProdukText =
                                `${produk.kode} - ${produk.nama} ${produk.owned ? '[Milik Supplier]' : ''}`;
                        } else {
                            const globalProduk = (@json($produks ?? []).find(p => p.id == this
                                .selectedProdukIdInternal));
                            if (globalProduk) {
                                this.selectedProdukText =
                                    `${globalProduk.kode} - ${globalProduk.nama} ${globalProduk.owned ? '[Milik Supplier]' : ''}`;
                            } else {
                                this.selectedProdukText = 'Pilih Produk';
                            }
                        }
                    } else {
                        this.selectedProdukText = 'Pilih Produk';
                    }
                },

                get filteredProduks() {
                    if (!this.searchTerm) {
                        return this.allProduksInternal;
                    }
                    return this.allProduksInternal.filter(produk => {
                        const searchText = this.searchTerm.toLowerCase();
                        return (produk.nama && produk.nama.toLowerCase().includes(searchText)) ||
                            (produk.kode && produk.kode.toLowerCase().includes(searchText));
                    });
                },

                toggleDropdown() {
                    this.open = !this.open;
                    if (this.open) {
                        this.searchTerm = '';
                        this.focusedOptionIndex = null;
                        this.$nextTick(() => {
                            this.$refs.searchInput.focus();
                        });
                    }
                },

                closeDropdown() {
                    this.open = false;
                    this.focusedOptionIndex = null;
                },

                selectProduk(produk) {
                    this.selectedProdukIdInternal = produk.id;
                    this.$store.purchaseOrder.items[this.itemIndex].produk_id = produk.id;
                    this.updateSelectedText();
                    this.$store.purchaseOrder.updateProdukDetail(this.itemIndex);
                    this.closeDropdown();
                },

                isSelected(produkId) {
                    return this.selectedProdukIdInternal == produkId;
                },

                focusNextOption() {
                    if (this.filteredProduks.length === 0) return;
                    if (this.focusedOptionIndex === null || this.focusedOptionIndex === this.filteredProduks.length - 1) {
                        this.focusedOptionIndex = 0;
                    } else {
                        this.focusedOptionIndex++;
                    }
                    this.scrollToFocusedOption();
                },

                focusPreviousOption() {
                    if (this.filteredProduks.length === 0) return;
                    if (this.focusedOptionIndex === null || this.focusedOptionIndex === 0) {
                        this.focusedOptionIndex = this.filteredProduks.length - 1;
                    } else {
                        this.focusedOptionIndex--;
                    }
                    this.scrollToFocusedOption();
                },

                selectFocusedOption() {
                    if (this.focusedOptionIndex !== null && this.filteredProduks[this.focusedOptionIndex]) {
                        this.selectProduk(this.filteredProduks[this.focusedOptionIndex]);
                    }
                },
                scrollToFocusedOption() {
                    this.$nextTick(() => {
                        const listElement = this.$refs.listbox;
                        const optionElement = listElement.querySelector(`#option-${this.focusedOptionIndex}`);
                        if (optionElement) {
                            optionElement.scrollIntoView({
                                block: 'nearest'
                            });
                        }
                    });
                }
            }
        }

        function purchaseOrderForm() {
            return {
                items: [],
                errors: [],
                isSubmitting: false,
                diskonPersen: 0,
                diskonNominal: 0,
                ppn: 0,
                ongkosKirim: 0,
                includePPN: false, // Default false agar user harus eksplisit mengaktifkan PPN
                produksData: @json($produks ?? []),

                init() {
                    if (!Alpine.store('purchaseOrder')) {
                        Alpine.store('purchaseOrder', this);
                    }

                    if (this.items.length === 0) {
                        this.addItem();
                    } else {
                        this.items = JSON.parse(JSON.stringify(this.items));
                    }


                    this.$watch('items', () => {
                        this.updateTotals();
                    }, {
                        deep: true
                    });

                    const supplierSelect = document.getElementById('supplier_id');
                    if (supplierSelect) {
                        // Use Select2 event for compatibility
                        $(supplierSelect).on('select2:select', (e) => {
                            this.fetchSupplierProduk(e.params.data.id);
                        });
                        // Initial fetch if value exists
                        if (supplierSelect.value) {
                            this.fetchSupplierProduk(supplierSelect.value);
                        }
                    }

                    // Check if PR ID is pre-filled or in URL and initialize Select2 on PR select
                    const prSelect = document.getElementById('pr_id');
                    const urlParams = new URLSearchParams(window.location.search);
                    const prIdFromUrl = urlParams.get('pr_id');

                    if (prSelect) {
                        // Initialize Select2 for PR dropdown (use existing options already rendered)
                        $(prSelect).select2({
                            placeholder: $(prSelect).data('placeholder') || 'Tidak Berdasarkan PR',
                            allowClear: true,
                            width: '100%'
                        });

                        // When user selects a PR via Select2, load items
                        $(prSelect).on('select2:select', (e) => {
                            const id = e.params.data.id;
                            this.loadItemsFromPurchaseRequest(id);
                        });

                        // When user clears the selection, clear items (existing method returns early if no id)
                        $(prSelect).on('select2:clear', () => {
                            this.loadItemsFromPurchaseRequest('');
                        });

                        // If PR ID is provided in URL, set Select2 value and load items
                        if (prIdFromUrl) {
                            $(prSelect).val(prIdFromUrl).trigger('change');
                            window.notify('Memuat item dari permintaan pembelian...', 'info', 'Memuat Data');
                            setTimeout(() => {
                                this.loadItemsFromPurchaseRequest(prIdFromUrl);
                            }, 500);
                        } else if (prSelect.value) {
                            // Trigger load if select has prefilled value
                            $(prSelect).trigger('change');
                            this.loadItemsFromPurchaseRequest(prSelect.value);
                        }
                    }
                },

                fetchSupplierProduk(supplierId) {
                    if (!supplierId) {
                        this.produksData = @json($produks ?? []);
                        this.items.forEach((item, index) => {
                            if (item.produk_id && !this.produksData.find(p => p.id == item.produk_id)) {}
                        });
                        return;
                    }
                    fetch(`/pembelian/purchase-order/supplier-produk?supplier_id=${supplierId}`)
                        .then(res => {
                            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                            return res.json();
                        })
                        .then(data => {
                            if (data && data.produks) {
                                this.produksData = data.produks;
                                this.items.forEach((item, index) => {
                                    if (item.produk_id && !this.produksData.find(p => p.id == item
                                            .produk_id)) {}
                                });
                            } else {
                                this.produksData = [];
                            }
                        }).catch(error => {
                            console.error('Error fetching supplier produk:', error);
                            this.produksData = [];
                        });
                },

                loadItemsFromPurchaseRequest(prId) {
                    if (!prId) return;

                    fetch(`/pembelian/purchase-order/pr-items?pr_id=${prId}`)
                        .then(res => {
                            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                            return res.json();
                        })
                        .then(data => {
                            if (data && data.items && data.items.length > 0) {
                                // Menampilkan konfirmasi sebelum mengganti item yang sudah ada
                                if (this.items.length > 0 && this.items[0].produk_id) {
                                    // Gunakan modal konfirmasi alih-alih alert/confirm
                                    window.showAlert({
                                        title: 'Konfirmasi Pergantian Item',
                                        message: 'Item sudah ada dalam daftar. Apakah Anda ingin mengganti item yang sudah ada dengan item dari permintaan pembelian?',
                                        type: 'info',
                                        confirmText: 'Ya, Ganti',
                                        cancelText: 'Batalkan',
                                        showConfirm: true,
                                        showCancel: true,
                                        onConfirm: () => {
                                            this.replaceItemsWithPrItems(data.items);
                                        },
                                    });
                                    return;
                                }

                                this.replaceItemsWithPrItems(data.items);
                            } else {
                                // Tampilkan toast notification untuk tidak ada item
                                window.notify('Tidak ada item ditemukan pada permintaan pembelian ini.', 'warning',
                                    'Permintaan Pembelian Kosong');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching purchase request items:', error);
                            window.notify('Terjadi kesalahan saat memuat item dari permintaan pembelian.', 'error',
                                'Gagal Memuat Data');
                        });
                },

                replaceItemsWithPrItems(prItems) {
                    // Mengganti item yang ada dengan item dari PR
                    this.items = prItems.map(item => ({
                        produk_id: item.produk_id,
                        nama_item: item.nama_item,
                        deskripsi: item.deskripsi || '',
                        quantity: item.quantity,
                        satuan_id: item.satuan_id,
                        harga: item.harga || 0,
                        diskon_persen: item.diskon_persen || 0,
                        diskon_nominal: item.diskon_nominal || 0,
                        subtotal: item.subtotal || 0
                    }));

                    // Update subtotal dan total
                    this.updateTotals();

                    // Tampilkan toast notification untuk sukses
                    const message = `
                        <div class="flex flex-col">
                            <span>${prItems.length} item berhasil dimuat dari permintaan pembelian.</span>
                            <span class="text-xs mt-1">Total: ${this.formatRupiah(this.calculateSubtotal())}</span>
                        </div>
                    `;
                    window.notify(message, 'success', 'Item Berhasil Dimuat');
                },

                addItem() {
                    const index = this.items.length;
                    this.items.push({
                        produk_id: null,
                        nama_item: '',
                        deskripsi: '',
                        quantity: 1,
                        satuan_id: '',
                        harga: 0,
                        diskon_persen: 0,
                        diskon_nominal: 0,
                        subtotal: 0
                    });
                },

                updateProdukDetail(index) {
                    const item = this.items[index];
                    const produkId = item.produk_id;

                    if (produkId) {
                        const produk = this.produksData.find(p => p.id == produkId) || (@json($produks ?? []).find(
                            p => p.id == produkId));
                        if (produk) {
                            item.nama_item = produk.nama || '';
                            item.deskripsi = item.deskripsi || '';
                            if (produk.satuan_id) {
                                item.satuan_id = produk.satuan_id;
                            }
                            if (produk.harga_beli) {
                                item.harga = parseFloat(produk.harga_beli) || 0;
                            }
                        } else {
                            item.nama_item = '';
                        }
                    } else {
                        item.nama_item = '';
                    }
                    this.updateSubtotal(index);
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                updateSubtotal(index) {
                    const item = this.items[index];
                    const quantity = parseFloat(item.quantity) || 0;
                    const harga = parseFloat(item.harga) || 0;
                    const diskonNominal = parseFloat(item.diskon_nominal) || 0;
                    item.subtotal = Math.max(0, (quantity * harga) - diskonNominal);
                    this.updateTotals();
                },

                updateDiskonNominal(index) {
                    const item = this.items[index];
                    const quantity = parseFloat(item.quantity) || 0;
                    const harga = parseFloat(item.harga) || 0;
                    const diskonPersen = parseFloat(item.diskon_persen) || 0;
                    const totalPrice = quantity * harga;
                    item.diskon_nominal = (totalPrice * diskonPersen) / 100;
                    this.updateSubtotal(index);
                },

                updateDiskonPersen(index) {
                    const item = this.items[index];
                    const quantity = parseFloat(item.quantity) || 0;
                    const harga = parseFloat(item.harga) || 0;
                    const diskonNominal = parseFloat(item.diskon_nominal) || 0;
                    const totalPrice = quantity * harga;
                    if (totalPrice > 0) {
                        item.diskon_persen = (diskonNominal / totalPrice) * 100;
                    } else {
                        item.diskon_persen = 0;
                    }
                    this.updateSubtotal(index);
                },

                calculateSubtotal() {
                    return this.items.reduce((total, item) => {
                        return total + parseFloat(item.subtotal || 0);
                    }, 0);
                },

                updateOrderDiskonNominal() {
                    const subtotal = this.calculateSubtotal();
                    this.diskonNominal = Math.min((subtotal * parseFloat(this.diskonPersen || 0)) / 100, subtotal);
                    if (isNaN(this.diskonNominal)) this.diskonNominal = 0;
                    this.updateTotals();
                },

                updateOrderDiskonPersen() {
                    const subtotal = this.calculateSubtotal();
                    const currentDiskonNominal = parseFloat(this.diskonNominal || 0);
                    this.diskonNominal = Math.min(currentDiskonNominal, subtotal);
                    if (isNaN(this.diskonNominal)) this.diskonNominal = 0;

                    if (subtotal > 0 && this.diskonNominal >= 0) {
                        this.diskonPersen = (this.diskonNominal / subtotal) * 100;
                    } else {
                        this.diskonPersen = 0;
                    }
                    if (isNaN(this.diskonPersen)) this.diskonPersen = 0;
                    this.updateTotals();
                },

                updateTotals() {
                    const subtotal = this.calculateSubtotal();
                    let currentDiskonNominal = parseFloat(this.diskonNominal || 0);
                    if (isNaN(currentDiskonNominal) || currentDiskonNominal < 0) currentDiskonNominal = 0;
                    this.diskonNominal = Math.min(currentDiskonNominal, subtotal);

                    const afterDiscount = subtotal - this.diskonNominal;

                    if (this.includePPN) {
                        this.ppn = Math.max(0, afterDiscount) * ({{ setting('tax_percentage', 11) }} / 100);
                    } else {
                        this.ppn = 0;
                    }
                    if (isNaN(this.ppn)) this.ppn = 0;

                    // Ensure ongkos kirim is a valid number
                    this.ongkosKirim = parseFloat(this.ongkosKirim || 0);
                    if (isNaN(this.ongkosKirim) || this.ongkosKirim < 0) this.ongkosKirim = 0;
                },

                calculateTotal() {
                    const subtotal = this.calculateSubtotal();
                    const diskon = parseFloat(this.diskonNominal || 0);
                    const ppnAmount = parseFloat(this.ppn || 0);
                    const ongkosKirim = parseFloat(this.ongkosKirim || 0);
                    let total = subtotal - diskon + ppnAmount + ongkosKirim;
                    return isNaN(total) ? 0 : total;
                },

                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(angka);
                },

                validateForm(e) {
                    if (this.isSubmitting) {
                        e.preventDefault();
                        return false;
                    }

                    this.errors = [];
                    let valid = true;

                    const supplier_id = document.getElementById('supplier_id').value;
                    const tanggal = document.getElementById('tanggal').value;

                    if (!supplier_id) this.errors.push('Supplier wajib diisi.');
                    if (!tanggal) this.errors.push('Tanggal wajib diisi.');

                    if (this.items.length === 0) {
                        this.errors.push('Minimal harus ada 1 item.');
                    } else {
                        this.items.forEach((item, index) => {
                            if (!item.produk_id || !item.quantity || parseFloat(item.quantity) <= 0 || !item
                                .satuan_id || item.harga === null || parseFloat(item.harga) < 0) {
                                this.errors.push(
                                    `Item #${index + 1}: Produk, Jumlah (>0), Satuan, dan Harga (>=0) wajib diisi.`
                                );
                            }
                        });
                    }

                    valid = this.errors.length === 0;

                    if (!valid) {
                        alert("Terdapat kesalahan:\n- " + this.errors.join('\n- '));
                        e.preventDefault();
                        this.isSubmitting = false;
                        return false;
                    }

                    this.isSubmitting = true;
                    const submitButton = e.target.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        const buttonText = submitButton.querySelector('span');
                        const buttonIcon = submitButton.querySelector('svg');
                        if (buttonText) buttonText.innerText = 'Menyimpan...';
                    }
                    return true;
                }
            }
        }
    </script>
</x-app-layout>
