<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Buat Retur Pembelian Baru
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Form untuk membuat retur pembelian baru
                    </p>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Dashboard</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('pembelian.retur-pembelian.index') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Retur
                        Pembelian</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li class="text-gray-800 dark:text-gray-100">
                    Buat Baru
                </li>
            </ol>
        </nav>

        <form action="{{ route('pembelian.retur-pembelian.store') }}" method="POST" x-data="returPembelianForm()">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left column - Header Info -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Retur</h2>

                        <!-- Nomor Retur -->
                        <div class="mb-4">
                            <label for="nomor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor
                                Retur</label>
                            <input type="text" name="nomor" id="nomor" value="{{ $nomorRetur }}" readonly
                                required
                                class="w-full rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            @error('nomor')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Retur -->
                        <div class="mb-4">
                            <label for="tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Retur</label>
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', date('Y-m-d')) }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            @error('tanggal')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Supplier -->
                        <div class="mb-4">
                            <label for="supplier_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier</label>
                            <select name="supplier_id" id="supplier_id" required x-on:change="loadPurchaseOrders"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Purchase Order -->
                        <div class="mb-4">
                            <label for="purchase_order_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Purchase
                                Order</label>
                            <select name="purchase_order_id" id="purchase_order_id" required
                                x-on:change="loadPurchaseOrderItems" x-bind:disabled="!purchaseOrdersLoaded"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="">-- Pilih Purchase Order --</option>
                                <template x-for="po in purchaseOrders">
                                    <option :value="po.id" x-text="`${po.nomor} - ${po.tanggal}`"></option>
                                </template>
                            </select>
                            @error('purchase_order_id')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Gudang  -->
                        <div class="mb-4">
                            <label for="gudang_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gudang</label>
                            <select name="gudang_id" id="gudang_id" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach ($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}"
                                        {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                        {{ $gudang->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gudang_id')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tipe Retur -->
                        <div class="mb-4">
                            <label for="tipe_retur"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe
                                Retur</label>
                            <select name="tipe_retur" id="tipe_retur"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="pengembalian_dana"
                                    {{ old('tipe_retur') == 'pengembalian_dana' ? 'selected' : '' }}>
                                    Pengembalian Dana
                                </option>
                                <option value="tukar_barang"
                                    {{ old('tipe_retur') == 'tukar_barang' ? 'selected' : '' }}>
                                    Tukar Barang
                                </option>
                            </select>
                            @error('tipe_retur')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right column - Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Detail Barang</h2>
                            <button type="button" @click="addItem"
                                class="inline-flex items-center px-3 py-1.5 bg-primary-600 hover:bg-primary-700 rounded-md text-white text-sm font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Baris
                            </button>
                        </div>

                        <!-- Loading indicator -->
                        <div class="text-center py-4" x-show="isLoadingItems">
                            <div
                                class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-primary-500">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                Memuat data...
                            </div>
                        </div>

                        <!-- No items selected notice -->
                        <div class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg"
                            x-show="!isLoadingItems && items.length === 0 && !noItemsFound">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada item</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Pilih supplier dan purchase order untuk menambahkan item
                            </p>
                        </div>

                        <!-- No items available in selected PO -->
                        <div class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg"
                            x-show="!isLoadingItems && noItemsFound">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada item</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada item yang tersedia pada Purchase Order yang dipilih
                            </p>
                        </div>

                        <!-- Items table -->
                        <div class="overflow-x-auto" x-show="!isLoadingItems && items.length > 0 && !noItemsFound">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Kuantitas
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Alasan
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-2 py-4">
                                                <input type="hidden" :name="`items[${index}][produk_id]`"
                                                    :value="item.produk_id">
                                                <div class="text-sm text-gray-900 dark:text-gray-100 font-medium"
                                                    x-text="item.nama_item"></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400"
                                                    x-text="'Kode: ' + item.kode_produk"></div>
                                            </td>
                                            <td class="px-2 py-4">
                                                <div class="flex items-center">
                                                    <input type="number" :name="`items[${index}][quantity]`"
                                                        x-model="item.quantity" min="0.01" step="0.01"
                                                        :max="item.max_quantity" required
                                                        class="w-20 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm mr-2">
                                                    <input type="hidden" :name="`items[${index}][satuan_id]`"
                                                        :value="item.satuan_id">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400"
                                                        x-text="item.satuan_nama"></span>
                                                </div>
                                            </td>
                                            <td class="px-2 py-4">
                                                <select :name="`items[${index}][alasan]`" x-model="item.alasan"
                                                    required
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                    <option value="">-- Pilih Alasan --</option>
                                                    <option value="Rusak">Rusak</option>
                                                    <option value="Cacat">Cacat</option>
                                                    <option value="Tidak Sesuai">Tidak Sesuai</option>
                                                    <option value="Kadaluarsa">Kadaluarsa</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </td>
                                            <td class="px-2 py-4">
                                                <input type="text" :name="`items[${index}][keterangan]`"
                                                    x-model="item.keterangan" placeholder="Keterangan tambahan"
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            </td>
                                            <td class="px-2 py-4 text-center">
                                                <button type="button" @click="removeItem(index)"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('pembelian.retur-pembelian.index') }}"
                            class="px-5 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-md text-sm font-medium">Batal</a>
                        <button type="submit"
                            class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md text-sm font-medium">
                            Simpan Retur Pembelian
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <style>
            /* Select2 Common Styles */
            .select2-selection {
                height: 38px !important;
                min-height: 38px !important;
                display: flex !important;
                align-items: center !important;
                transition: all 0.15s ease-in-out !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                width: 100% !important;
                line-height: 38px !important;
                padding: 0 0.75rem !important;
                padding-right: 1rem !important;
                font-size: 0.75rem !important;
                /* Mengubah ukuran font menjadi lebih kecil */
                font-weight: normal !important;
                height: 38px !important;
                display: flex !important;
                align-items: center !important;
            }

            /* Make dropdowns scrollable with fixed height */
            .select2-results__options {
                max-height: 250px !important;
                overflow-y: auto !important;
                scrollbar-width: thin !important;
            }

            /* Custom scrollbar for Webkit browsers */
            .select2-results__options::-webkit-scrollbar {
                width: 6px !important;
            }

            .select2-results__options::-webkit-scrollbar-track {
                background: transparent !important;
            }

            .select2-container--select2-light .select2-results__options::-webkit-scrollbar-thumb {
                background-color: #d1d5db !important;
                border-radius: 20px !important;
            }

            .select2-container--select2-dark .select2-results__options::-webkit-scrollbar-thumb {
                background-color: #4b5563 !important;
                border-radius: 20px !important;
            }

            /* Improve dropdown appearance */
            .select2-dropdown {
                border-radius: 8px !important;
                overflow: hidden !important;
                margin-top: 4px !important;
            }

            .select2-results {
                padding: 6px !important;
            }

            /* Make the dropdown width match container */
            .select2-container {
                width: 100% !important;
            }

            /* Selection clear button styling */
            .select2-selection__clear {
                margin-right: 8px !important;
                background: none !important;
                border: none !important;
                cursor: pointer !important;
                font-weight: bold !important;
                font-size: 16px !important;
                opacity: 0.6 !important;
                transition: all 0.2s !important;
                color: #9CA3AF !important;
                position: absolute !important;
                right: 20px !important;
            }

            .select2-selection__clear:hover {
                opacity: 1 !important;
                color: #EF4444 !important;
            }

            /* Scrollable dropdown specific styling */
            .select2-dropdown-scrollable .select2-results__options {
                max-height: 200px !important;
                overflow-y: auto !important;
                padding: 4px !important;
            }

            /* Improving dropdown item appearance */
            .select2-container--select2-light .select2-results__option,
            .select2-container--select2-dark .select2-results__option {
                color: rgb(209, 213, 219) !important;
                padding: 6px 10px !important;
                margin: 0 !important;
                border-radius: 4px !important;
                transition: all 0.15s ease !important;
                font-size: 0.75rem !important;
            }

            .select2-container--select2-light .select2-results__option {
                color: #374151 !important;
                padding: 6px 10px !important;
                margin: 0 !important;
                border-radius: 4px !important;
                transition: all 0.15s ease !important;
                font-size: 0.75rem !important;
            }

            /* Improved hover/selection state */
            .select2-container--select2-light .select2-results__option--highlighted[aria-selected] {
                background-color: rgba(59, 130, 246, 0.1) !important;
                color: #374151 !important;
                border-radius: 4px !important;
            }

            .select2-container--select2-dark .select2-results__option--highlighted[aria-selected] {
                background-color: rgba(59, 130, 246, 0.15) !important;
                color: rgb(209, 213, 219) !important;
                border-radius: 4px !important;
            }

            /* Animation for dropdown opening */
            .select2-container--open .select2-dropdown {
                animation: selectDropdownFadeIn 0.15s ease-out;
            }

            @keyframes selectDropdownFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-4px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Select2 General Styles */
            .select2-dropdown-clear {
                z-index: 10000;
                /* Ensure dropdowns appear above other elements */
                border-radius: 8px !important;
                overflow: hidden !important;
                padding: 6px !important;
                margin-top: 4px !important;
            }

            /* Make Select2 match other form inputs */
            .select2-container {
                width: 100% !important;
            }

            .select2-selection {
                height: 38px !important;
                min-height: 38px !important;
                display: flex !important;
                align-items: center !important;
                transition: all 0.15s ease-in-out !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            }

            .select2-selection__rendered {
                width: 100% !important;
                line-height: 38px !important;
                padding: 0 0.75rem !important;
                padding-right: 1rem !important;
                font-size: 0.75rem !important;
                /* Mengubah ukuran font menjadi lebih kecil */
                font-weight: normal !important;
                height: 38px !important;
                display: flex !important;
                align-items: center !important;
            }

            /* Improve the placeholder styling */
            .select2-container--select2-dark .select2-selection__placeholder,
            .select2-container--select2-light .select2-selection__placeholder {
                color: #9CA3AF !important;
                opacity: 0.8;
            }

            /* Create custom dropdown appearance */
            .select2-container .select2-selection--single {
                position: relative;
                height: 38px !important;
            }

            /* Search field styling */
            .select2-search--dropdown {
                padding: 6px 10px !important;
            }

            .select2-search--dropdown .select2-search__field {
                padding: 6px 8px !important;
                border-radius: 4px !important;
                font-size: 0.75rem !important;
                line-height: 1.4 !important;
                border-width: 1px !important;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Include Select2 CSS & JS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            function returPembelianForm() {
                return {
                    purchaseOrders: [],
                    purchaseOrdersLoaded: false,
                    items: [],
                    availableItems: [],
                    isLoadingItems: false,
                    noItemsFound: false,

                    init() {
                        if (document.getElementById('supplier_id').value) {
                            this.loadPurchaseOrders();
                        }

                        // Mendeteksi perubahan mode gelap/terang
                        const darkModeObserver = new MutationObserver((mutations) => {
                            mutations.forEach((mutation) => {
                                if (mutation.attributeName === 'class') {
                                    const isDark = document.documentElement.classList.contains('dark');
                                    this.refreshSelect2Themes(isDark);
                                }
                            });
                        });

                        // Memantau perubahan pada class di html element
                        darkModeObserver.observe(document.documentElement, {
                            attributes: true,
                            attributeFilter: ['class']
                        });

                        // Initialize Select2 on dropdowns with a slight delay
                        // to ensure proper DOM rendering
                        setTimeout(() => {
                            if (typeof $.fn.select2 !== 'undefined') {
                                const isDark = document.documentElement.classList.contains('dark');
                                this.initSelect2Dropdowns(isDark);
                            }
                        }, 100);
                    },

                    refreshSelect2Themes(isDark) {
                        // Update theme for all select2 instances
                        const theme = isDark ? 'select2-dark' : 'select2-light';

                        if ($('#supplier_id').data('select2')) {
                            $('#supplier_id').select2('destroy');
                            this.initSupplierDropdown(isDark);
                        }

                        if ($('#gudang_id').data('select2')) {
                            $('#gudang_id').select2('destroy');
                            this.initGudangDropdown(isDark);
                        }

                        if ($('#purchase_order_id').data('select2')) {
                            const poValue = $('#purchase_order_id').val();
                            $('#purchase_order_id').select2('destroy');
                            this.initPurchaseOrderDropdown(isDark);
                            $('#purchase_order_id').val(poValue).trigger('change');
                        }
                    },

                    initSelect2Dropdowns(isDark) {
                        // Initialize all dropdowns
                        this.initSupplierDropdown(isDark);
                        this.initGudangDropdown(isDark);
                    },

                    initSupplierDropdown(isDark) {
                        const theme = isDark ? 'select2-dark' : 'select2-light';

                        // Initialize supplier dropdown
                        $('#supplier_id').select2({
                            placeholder: '-- Pilih Supplier --',
                            theme: theme,
                            width: '100%',
                            dropdownCssClass: 'select2-dropdown-clear select2-dropdown-scrollable',
                            selectionCssClass: 'select2-custom-selection',
                            allowClear: true,
                            templateResult: formatSupplier,
                            templateSelection: formatSupplierSelection,
                            // Improve dropdown scrolling responsiveness
                            scrollAfterSelect: true,
                            closeOnSelect: true
                        }).on('select2:select', (e) => {
                            // Trigger Alpine.js change event
                            this.loadPurchaseOrders();
                        }).on('select2:open', function() {
                            // Improve accessibility and mobile experience
                            setTimeout(function() {
                                $('.select2-search__field').focus();
                            }, 100);
                        });

                        // Custom supplier option formatter - simplified version without icons
                        function formatSupplier(supplier) {
                            if (!supplier.id) return supplier.text;

                            // Create a simple styled option for the dropdown
                            let $supplier = $(
                                '<div class="py-1 px-2 h-[38px] flex items-center">' +
                                '<div class="font-medium text-xs">' + supplier.text + '</div>' +
                                '</div>'
                            );

                            return $supplier;
                        }

                        // Custom supplier selection formatter (when selected)
                        function formatSupplierSelection(supplier) {
                            return supplier.text || supplier.id;
                        }

                        // Apply custom styles to match the modern design
                        $("#supplier_id").next(".select2-container").find(".select2-selection").addClass(
                            "rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 form-select focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                        );

                        // Remove default Select2 arrow
                        $("#supplier_id").next(".select2-container").find(".select2-selection__arrow").hide();
                        $("#supplier_id").next(".select2-container").css("display", "block");
                    },

                    initGudangDropdown(isDark) {
                        const theme = isDark ? 'select2-dark' : 'select2-light';

                        // Initialize Select2 for gudang_id dropdown
                        $('#gudang_id').select2({
                            placeholder: '-- Pilih Gudang --',
                            theme: theme,
                            width: '100%',
                            dropdownCssClass: 'select2-dropdown-clear select2-dropdown-scrollable',
                            selectionCssClass: 'select2-custom-selection',
                            allowClear: true,
                            templateResult: formatGudang,
                            templateSelection: formatGudangSelection,
                            // Improve dropdown scrolling responsiveness
                            scrollAfterSelect: true,
                            closeOnSelect: true
                        }).on('select2:open', function() {
                            // Improve accessibility and mobile experience
                            setTimeout(function() {
                                $('.select2-search__field').focus();
                            }, 100);
                        });

                        // Custom gudang option formatter - simplified version without icons
                        function formatGudang(gudang) {
                            if (!gudang.id) return gudang.text;

                            // Create a simple styled option for the dropdown
                            let $gudang = $(
                                '<div class="py-1 px-2 h-[38px] flex items-center">' +
                                '<div class="font-medium text-xs">' + gudang.text + '</div>' +
                                '</div>'
                            );

                            return $gudang;
                        }

                        // Custom gudang selection formatter (when selected)
                        function formatGudangSelection(gudang) {
                            return gudang.text || gudang.id;
                        }

                        // Apply custom styles to gudang dropdown
                        $("#gudang_id").next(".select2-container").find(".select2-selection").addClass(
                            "rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 form-select focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                        );

                        // Remove default Select2 arrow
                        $("#gudang_id").next(".select2-container").find(".select2-selection__arrow").hide();
                        $("#gudang_id").next(".select2-container").css("display", "block");
                    },

                    initPurchaseOrderDropdown(isDark) {
                        const theme = isDark ? 'select2-dark' : 'select2-light';
                        const self = this;

                        $('#purchase_order_id').select2({
                            placeholder: '-- Pilih Purchase Order --',
                            theme: theme,
                            width: '100%',
                            dropdownCssClass: 'select2-dropdown-clear select2-dropdown-scrollable',
                            selectionCssClass: 'select2-custom-selection',
                            allowClear: true,
                            templateResult: formatPurchaseOrder,
                            templateSelection: formatPurchaseOrderSelection,
                            // Improve dropdown scrolling responsiveness
                            scrollAfterSelect: true,
                            closeOnSelect: true
                        }).on('select2:select', (e) => {
                            self.loadPurchaseOrderItems();
                        }).on('select2:open', function() {
                            // Improve accessibility and mobile experience
                            setTimeout(function() {
                                $('.select2-search__field').focus();
                            }, 100);
                        });

                        // Custom PO option formatter
                        function formatPurchaseOrder(po) {
                            if (!po.id) return po.text;

                            // Extract PO number and date from text (assuming format: "PO123 - 2023-01-01")
                            const textParts = po.text.split(' - ');
                            const poNumber = textParts[0];
                            const poDate = textParts.length > 1 ? textParts[1] : '';

                            // Create a simplified text-only styled option for the dropdown
                            let $po = $(
                                '<div class="py-1 px-2 h-[38px] flex items-center">' +
                                '<div class="flex flex-col justify-center">' +
                                '<div class="font-medium text-xs">' + poNumber + '</div>' +
                                (poDate ? '<div class="text-xs text-gray-500 dark:text-gray-400">' + poDate + '</div>' :
                                    '') +
                                '</div>' +
                                '</div>'
                            );

                            return $po;
                        }

                        // Custom PO selection formatter (when selected)
                        function formatPurchaseOrderSelection(po) {
                            return po.text || po.id;
                        }

                        // Apply custom styles to match the modern design
                        $("#purchase_order_id").next(".select2-container").find(".select2-selection").addClass(
                            "rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 form-select focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50"
                        );

                        // Remove default Select2 arrow
                        $("#purchase_order_id").next(".select2-container").find(".select2-selection__arrow").hide();
                        $("#purchase_order_id").next(".select2-container").css("display", "block");

                        // Add hint text below dropdown
                        setTimeout(() => {
                            if (!$('.purchase-order-hint').length) {
                                $("#purchase_order_id").parent().append(
                                    '<div class="purchase-order-hint text-xs text-gray-500 dark:text-gray-400 mt-1 ml-1">Pilih item dari Purchase Order untuk ditambahkan ke retur</div>'
                                );
                            }
                        }, 200);
                    },

                    loadPurchaseOrders() {
                        const supplierId = document.getElementById('supplier_id').value;
                        if (!supplierId) return;

                        this.purchaseOrdersLoaded = false;
                        this.items = [];

                        // Reset and destroy any existing purchase_order_id Select2
                        if ($('#purchase_order_id').data('select2')) {
                            $('#purchase_order_id').select2('destroy');
                        }

                        fetch(`{{ route('pembelian.retur-pembelian.get-purchase-orders') }}?supplier_id=${supplierId}`)
                            .then(response => response.json())
                            .then(data => {
                                this.purchaseOrders = data.purchaseOrders;
                                this.purchaseOrdersLoaded = true;

                                // Initialize Select2 on the purchase order dropdown after data is loaded
                                this.$nextTick(() => {
                                    setTimeout(() => {
                                        if (typeof $.fn.select2 !== 'undefined') {
                                            const isDark = document.documentElement.classList.contains(
                                                'dark');
                                            this.initPurchaseOrderDropdown(isDark);
                                        }
                                    }, 100);
                                });
                            })
                            .catch(error => console.error('Error loading purchase orders:', error));
                    },

                    loadPurchaseOrderItems() {
                        const poId = document.getElementById('purchase_order_id').value;
                        if (!poId) return;

                        // Also handle when called directly from Select2 event
                        if (typeof poId === 'object' && poId.target) {
                            const selectElement = poId.target;
                            if (!selectElement.value) return;
                        }

                        this.isLoadingItems = true;
                        this.items = [];
                        this.noItemsFound = false;

                        fetch(`{{ route('pembelian.retur-pembelian.get-purchase-order-items') }}?po_id=${poId}`)
                            .then(response => response.json())
                            .then(data => {
                                this.availableItems = data.items.map(item => ({
                                    ...item,
                                    alasan: '',
                                    keterangan: '',
                                    max_quantity: item.quantity
                                }));
                                this.noItemsFound = this.availableItems.length === 0;
                                this.isLoadingItems = false;
                            })
                            .catch(error => {
                                console.error('Error loading purchase order items:', error);
                                this.isLoadingItems = false;
                                this.noItemsFound = true;
                            });
                    },

                    addItem() {
                        if (this.availableItems.length === 0) {
                            alert('Tidak ada item yang tersedia untuk ditambahkan.');
                            return;
                        }

                        // Find items that aren't already in the items array
                        const availableToAdd = this.availableItems.filter(available =>
                            !this.items.some(item => item.produk_id === available.produk_id)
                        );

                        if (availableToAdd.length === 0) {
                            alert('Semua item yang tersedia sudah ditambahkan.');
                            return;
                        }

                        // Add the first available item
                        this.items.push({
                            ...availableToAdd[0],
                            quantity: Math.min(1, availableToAdd[0].quantity)
                        });
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                    }
                }
            }
        </script>

        <style>
            /* Select2 General Styles */
            .select2-dropdown-clear {
                z-index: 10000;
                /* Ensure dropdowns appear above other elements */
                border-radius: 8px !important;
                overflow: hidden !important;
                padding: 6px !important;
                margin-top: 4px !important;
            }

            /* Make Select2 match other form inputs */
            .select2-container {
                width: 100% !important;
            }

            .select2-selection {
                height: auto !important;
                min-height: 38px !important;
                display: flex !important;
                align-items: center !important;
                transition: all 0.15s ease-in-out !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            }

            .select2-selection__rendered {
                width: 100% !important;
                line-height: 1.5rem !important;
                padding: 0.5rem 0.75rem !important;
                padding-right: 1rem !important;
                font-size: 0.875rem !important;
                font-weight: normal !important;
            }

            /* Improve the placeholder styling */
            .select2-container--select2-dark .select2-selection__placeholder,
            .select2-container--select2-light .select2-selection__placeholder {
                color: #9CA3AF !important;
                opacity: 0.8;
            }

            /* Create custom dropdown appearance */
            .select2-container .select2-selection--single {
                position: relative;
            }

            /* Dihapus panah dropdown */
            .select2-container .select2-selection--single:after {
                content: none !important;
            }

            .select2-container.select2-container--open .select2-selection--single:after {
                content: none !important;
            }

            /* Dark theme styles */
            .select2-container--select2-dark .select2-selection--single,
            .select2-container--select2-dark .select2-selection--multiple {
                background-color: rgb(17, 24, 39) !important;
                border-color: rgb(75, 85, 99) !important;
                color: white !important;
            }

            .select2-container--select2-dark .select2-selection__rendered {
                color: white !important;
            }

            .select2-container--select2-dark .select2-selection__arrow,
            .select2-container--select2-light .select2-selection__arrow {
                display: none !important;
            }

            .select2-container--select2-dark .select2-search--dropdown .select2-search__field {
                background-color: rgba(55, 65, 81, 0.7) !important;
                border-color: rgb(75, 85, 99) !important;
                color: white !important;
            }

            .select2-container--select2-dark .select2-dropdown {
                background-color: rgb(31, 41, 55) !important;
                border-color: rgb(75, 85, 99) !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            }

            .select2-container--select2-light .select2-dropdown {
                background-color: #ffffff !important;
                border-color: rgb(209, 213, 219) !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            }

            .select2-container--select2-dark .select2-results__option {
                color: rgb(209, 213, 219) !important;
                padding: 6px 10px !important;
                margin: 0 !important;
                border-radius: 4px !important;
                transition: all 0.15s ease !important;
                font-size: 0.75rem !important;
            }

            .select2-container--select2-light .select2-results__option {
                color: #374151 !important;
                padding: 6px 10px !important;
                margin: 0 !important;
                border-radius: 4px !important;
                transition: all 0.15s ease !important;
                font-size: 0.75rem !important;
            }

            .select2-container--select2-dark .select2-results__option--highlighted[aria-selected] {
                background-color: rgba(59, 130, 246, 0.15) !important;
                color: rgb(209, 213, 219) !important;
            }

            .select2-container--select2-light .select2-results__option--highlighted[aria-selected] {
                background-color: rgba(59, 130, 246, 0.1) !important;
                color: #374151 !important;
            }

            .select2-container--select2-dark .select2-results__option[aria-selected=true] {
                background-color: rgba(59, 130, 246, 0.6) !important;
                color: white !important;
                font-weight: 500 !important;
            }

            .select2-container--select2-light .select2-results__option[aria-selected=true] {
                background-color: rgba(59, 130, 246, 0.2) !important;
                color: rgb(37, 99, 235) !important;
                font-weight: 500 !important;
            }

            /* Select2 Light Theme Styles */
            .select2-container--select2-light .select2-selection--single,
            .select2-container--select2-light .select2-selection--multiple {
                background-color: #ffffff !important;
                border-color: rgb(209, 213, 219) !important;
            }

            /* Menghapus custom dropdown arrow */
            .select2-container--select2-light .select2-selection--single:after,
            .select2-container--select2-dark .select2-selection--single:after {
                content: none !important;
            }

            /* Focus state styling */
            .select2-container--select2-light.select2-container--focus .select2-selection,
            .select2-container--select2-dark.select2-container--focus .select2-selection,
            .select2-container--select2-light.select2-container--open .select2-selection,
            .select2-container--select2-dark.select2-container--open .select2-selection {
                border-color: rgb(59, 130, 246) !important;
                box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
                outline: none !important;
            }

            /* Selection clear button positioning */
            .select2-container--select2-light .select2-selection__clear,
            .select2-container--select2-dark .select2-selection__clear {
                margin-right: 20px;
                color: #9CA3AF;
                font-size: 1.25em !important;
                font-weight: normal !important;
                line-height: 1 !important;
                transition: color 0.2s ease !important;
                opacity: 0.6 !important;
                padding: 0 4px !important;
            }

            .select2-container--select2-light .select2-selection__clear:hover,
            .select2-container--select2-dark .select2-selection__clear:hover {
                color: #EF4444 !important;
                opacity: 1 !important;
            }

            /* Search field styling */
            .select2-search--dropdown {
                padding: 6px 10px !important;
            }

            .select2-search--dropdown .select2-search__field {
                padding: 6px 8px !important;
                border-radius: 4px !important;
                font-size: 0.75rem !important;
                line-height: 1.4 !important;
                border-width: 1px !important;
            }
        </style>
    @endpush
</x-app-layout>
