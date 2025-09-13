<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Modern Select2 styling */
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
                background-color: #ffffff;
                transition: all 0.2s ease-in-out;
            }

            .select2-container--default .select2-selection--single:hover {
                border-color: #6366f1;
            }

            .select2-container--default .select2-selection--single:focus,
            .select2-container--default.select2-container--focus .select2-selection--single,
            .select2-container--default.select2-container--open .select2-selection--single {
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
                outline: none;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #374151;
                line-height: 28px;
            }

            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #9CA3AF;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 38px;
                position: absolute;
                top: 1px;
                right: 1px;
                width: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .select2-dropdown {
                border-color: #D1D5DB;
                border-radius: 0.375rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                overflow: hidden;
                background-color: #ffffff;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #6366f1;
                color: #ffffff;
            }

            .select2-container--default .select2-search--dropdown .select2-search__field {
                border-color: #D1D5DB;
                border-radius: 0.25rem;
                padding: 0.4rem 0.75rem;
                background-color: #ffffff;
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

            .dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #9CA3AF;
            }

            .dark .select2-dropdown {
                background-color: #1F2937;
                border-color: #4B5563;
            }

            .dark .select2-container--default .select2-results__option {
                color: #F9FAFB;
                background-color: #1F2937;
            }

            .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #6366f1;
                color: #ffffff;
            }

            .dark .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: #374151;
                border-color: #4B5563;
                color: #F9FAFB;
            }

            .dark .select2-container--default .select2-results__option[aria-selected=true] {
                background-color: #374151;
            }

            /* Custom styling for product result */
            .select2-result-product .product-name {
                font-weight: 500;
                color: #374151;
            }

            .select2-result-product .product-stock {
                margin-top: 2px;
                font-size: 0.75rem;
                color: #6b7280;
            }

            .dark .select2-result-product .product-name {
                color: #f3f4f6;
            }

            .dark .select2-result-product .product-stock {
                color: #9ca3af;
            }
        </style>
    @endpush
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Buat Transfer Antar Gudang
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                Buat catatan transfer barang dari satu gudang ke gudang lain. Semua barang yang ditransfer akan dikelola
                untuk menjaga akurasi stok.
            </p>
        </div>

        <!-- Create Form -->
        <div
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <form action="{{ route('inventaris.transfer-gudang.store') }}" method="POST" id="transferForm">
                @csrf
                <div class="p-6 sm:p-8">
                    <!-- Main Form Fields -->
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Nomor Transfer -->
                        <div class="sm:col-span-3">
                            <label for="nomor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor
                                Transfer</label>
                            <div class="mt-1">
                                <input type="text" name="nomor" id="nomor" value="{{ $nomor }}" readonly
                                    class="bg-gray-50 dark:bg-gray-700 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:text-white rounded-md">
                            </div>
                            @error('nomor')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Transfer -->
                        <div class="sm:col-span-3">
                            <label for="tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                Transfer</label>
                            <div class="mt-1">
                                <input type="date" name="tanggal" id="tanggal"
                                    value="{{ old('tanggal', date('Y-m-d')) }}"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            </div>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gudang Asal -->
                        <div class="sm:col-span-3">
                            <label for="gudang_asal_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gudang Asal</label>
                            <div class="mt-1">
                                <select id="gudang_asal_id" name="gudang_asal_id"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                    <option value="">-- Pilih Gudang Asal --</option>
                                    @foreach ($gudangs as $gudang)
                                        <option value="{{ $gudang->id }}"
                                            {{ old('gudang_asal_id') == $gudang->id ? 'selected' : '' }}>
                                            {{ $gudang->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('gudang_asal_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gudang Tujuan -->
                        <div class="sm:col-span-3">
                            <label for="gudang_tujuan_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gudang Tujuan</label>
                            <div class="mt-1">
                                <select id="gudang_tujuan_id" name="gudang_tujuan_id"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                    <option value="">-- Pilih Gudang Tujuan --</option>
                                    @foreach ($gudangs as $gudang)
                                        <option value="{{ $gudang->id }}"
                                            {{ old('gudang_tujuan_id') == $gudang->id ? 'selected' : '' }}>
                                            {{ $gudang->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('gudang_tujuan_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="sm:col-span-6">
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                            <div class="mt-1">
                                <textarea id="catatan" name="catatan" rows="3"
                                    class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan') }}</textarea>
                            </div>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Detail Produk -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Detail Produk</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Tambahkan produk yang akan ditransfer antar gudang
                        </p>

                        <div class="mt-4">
                            <!-- Items List -->
                            <div class="overflow-x-auto" x-data="transferDetailHandler()">
                                <div x-data="{ showQtyAlert: false, alertMsg: '' }" x-show="showQtyAlert" x-transition
                                    class="fixed top-6 right-6 z-50">
                                    <div
                                        class="bg-red-600 text-white px-4 py-3 rounded shadow-lg flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span x-text="alertMsg"></span>
                                    </div>
                                </div>
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col"
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                #
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Produk
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Stok Tersedia
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Quantity
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Satuan
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Keterangan
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                                        id="detail-items">
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr>
                                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                                    x-text="index + 1"></td>
                                                <td class="px-3 py-4">
                                                    <div class="relative">
                                                        <select x-model="item.produk_id" :name="`produk_id[${index}]`"
                                                            @change="updateStokInfo(index)" :disabled="isLoading"
                                                            :id="`produk_select_${index}`"
                                                            class="select2-dropdown-dynamic shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                            <option value="">-- Pilih Produk --</option>
                                                            <template x-for="produk in _produks"
                                                                :key="produk?.produk_id || Math.random()">
                                                                <option x-show="produk && produk.produk_id"
                                                                    :value="produk.produk_id"
                                                                    x-text="produk && produk.nama ? produk.nama : '(Produk tanpa nama)'"
                                                                    :data-satuan-id="produk && produk.satuan_id ? produk.satuan_id : ''"
                                                                    :data-stok="produk && typeof produk.stok !== 'undefined' ?
                                                                        produk.stok : 0">
                                                                </option>
                                                            </template>
                                                        </select>
                                                        <div x-show="isLoading"
                                                            class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                            <svg class="animate-spin h-5 w-5 text-gray-500"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12"
                                                                    cy="12" r="10" stroke="currentColor"
                                                                    stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-3 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-200">
                                                    <span x-text="item.stok_tersedia"></span>
                                                    <span x-text="item.satuan_nama"></span>
                                                </td>
                                                <td class="px-3 py-4 max-w-[120px]">
                                                    <input x-model.number="item.quantity" :name="`quantity[${index}]`"
                                                        type="number" min="0.01" step="0.01"
                                                        :max="item.stok_tersedia"
                                                        @input="if (item.quantity > item.stok_tersedia) { item.quantity = item.stok_tersedia; alertMsg = 'Jumlah melebihi stok tersedia!'; showQtyAlert = true; setTimeout(() => showQtyAlert = false, 2000); }"
                                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                </td>
                                                <td class="px-3 py-4">
                                                    <!-- Hidden input for satuan_id to ensure it's submitted even when UI select is disabled -->
                                                    <input type="hidden" :name="`satuan_id[${index}]`"
                                                        :value="item.satuan_id">

                                                    <!-- Visual select (disabled for UI only) -->
                                                    <select x-model="item.satuan_id" disabled
                                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                        <option value="">-- Pilih Satuan --</option>
                                                        @foreach ($satuans as $satuan)
                                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="px-3 py-4">
                                                    <input x-model="item.keterangan" :name="`keterangan[${index}]`"
                                                        type="text"
                                                        class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap text-right">
                                                    <button type="button" @click="removeItem(index)"
                                                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

                                <!-- Add Item Button -->
                                <div class="mt-4">
                                    <button type="button" @click="addItem()"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-primary-900 dark:text-primary-300 dark:hover:bg-primary-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Tambah Produk
                                    </button>
                                </div>

                                <!-- Warning if no items -->
                                <div x-show="items.length === 0"
                                    class="mt-4 bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 dark:border-yellow-600 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-600"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                                Anda harus menambahkan minimal satu produk untuk transfer.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700/40 border-t dark:border-gray-700 flex justify-between items-center">
                    <a href="{{ route('inventaris.transfer-gudang.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                        Kembali
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-primary-500 dark:hover:bg-primary-600">
                        Simpan Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            function transferDetailHandler() {
                return {
                    items: [],
                    _produks: [],
                    gudangAsalId: null,
                    isLoading: false,
                    allStokProduk: @json($allStokProduk ?? []),

                    init() {
                        // Check if allStokProduk is properly loaded
                        console.log('Initializing transfer form with', this.allStokProduk ? this.allStokProduk.length : 0,
                            'products in stock');
                        console.log('All stock products data:', this.allStokProduk);

                        // Add first item
                        this.addItem();

                        // Listen for gudang asal changes
                        const gudangAsalEl = document.getElementById('gudang_asal_id');
                        if (gudangAsalEl) {
                            console.log('Setting up gudang asal event listener');
                            gudangAsalEl.addEventListener('change', (e) => {
                                console.log('Gudang asal change event fired, new value:', e.target.value);
                                this.gudangAsalId = e.target.value;
                                console.log('Gudang asal changed to:', this.gudangAsalId);
                                console.log('Available products data:', this.allStokProduk);
                                this.fetchProduks();
                            });

                            // Initial fetch if gudang already selected
                            this.gudangAsalId = gudangAsalEl.value;
                            if (this.gudangAsalId) {
                                console.log('Initial gudang selection:', this.gudangAsalId);
                                console.log('Available products data:', this.allStokProduk);
                                this.fetchProduks();
                            }
                        } else {
                            console.error('Gudang asal element not found');
                        }

                        // Initialize Select2 with MutationObserver
                        this.initSelect2();
                    }, // Ganti fetchProduks dengan filter lokal
                    fetchProduks() {
                        try {
                            if (!this.gudangAsalId) {
                                console.log('No warehouse selected, clearing product list');
                                this._produks = [];
                                return;
                            }

                            // Check if allStokProduk is valid
                            if (!this.allStokProduk || !Array.isArray(this.allStokProduk)) {
                                console.error('allStokProduk is not valid:', this.allStokProduk);
                                this._produks = [];
                                return;
                            }

                            console.log(
                                `Filtering ${this.allStokProduk.length} products for warehouse ID ${this.gudangAsalId}`);
                            console.log('Full allStokProduk data sample:', this.allStokProduk.slice(0, 3));

                            // Filter produk yang stoknya > 0 di gudang yang dipilih dan memiliki data lengkap
                            // Convert IDs to strings for comparison to avoid type mismatch issues
                            const gudangIdStr = String(this.gudangAsalId);

                            this._produks = this.allStokProduk.filter(p => {
                                try {
                                    if (!p) {
                                        console.warn('Null product entry');
                                        return false;
                                    }

                                    // Log each product being checked
                                    console.log(
                                        `Checking product: ID=${p.produk_id}, Gudang=${p.gudang_id}, Stok=${p.stok}, Nama=${p.nama}`
                                    );

                                    // Check each condition separately and log failures
                                    if (!p.gudang_id) {
                                        console.warn('Product missing gudang_id:', p);
                                        return false;
                                    }

                                    if (String(p.gudang_id) !== gudangIdStr) {
                                        // This is normal - just products from other warehouses
                                        return false;
                                    }

                                    if (!p.produk_id) {
                                        console.warn('Product missing produk_id:', p);
                                        return false;
                                    }

                                    if (p.stok === undefined || p.stok === null) {
                                        console.warn('Product has undefined/null stok:', p);
                                        return false;
                                    }

                                    // Parse stok as number to handle string values
                                    const stokNum = parseFloat(p.stok);
                                    if (isNaN(stokNum) || stokNum <= 0) {
                                        console.warn('Product has invalid or zero stok:', p);
                                        return false;
                                    }

                                    if (!p.nama) {
                                        console.warn('Product missing nama:', p);
                                        return false;
                                    }

                                    return true;
                                } catch (filterError) {
                                    console.error('Error processing product entry:', filterError, p);
                                    return false;
                                }
                            });

                            console.log(`Found ${this._produks.length} valid products for warehouse ID ${this.gudangAsalId}`);

                            // Log the first few filtered products for debugging
                            if (this._produks.length > 0) {
                                console.log('First few filtered products:', this._produks.slice(0, 3));
                            }

                            if (this._produks.length === 0) {
                                console.warn('No products found for warehouse ID', this.gudangAsalId);
                                // Clear all product selections when no products are available
                                this.items.forEach(item => {
                                    item.produk_id = '';
                                    item.stok_tersedia = 0;
                                    item.satuan_id = '';
                                    item.satuan_nama = '';
                                });
                                alert('Tidak ada produk dengan stok tersedia di gudang ini. Silakan pilih gudang lain.');
                            }

                            // Clear selected products that are not available in the new warehouse
                            this.clearInvalidSelections();

                            // Refresh Select2 options after products are updated
                            this.$nextTick(() => {
                                console.log('Refreshing Select2 options with', this._produks.length, 'products');
                                $('.select2-dropdown-dynamic').each((index, element) => {
                                    console.log('Processing dropdown', index, 'with name:', element.name);
                                    if ($(element).data('select2')) {
                                        // Clear existing options
                                        $(element).empty();

                                        // Add default option
                                        $(element).append('<option value="">-- Pilih Produk --</option>');

                                        // Add filtered products
                                        console.log('Adding products to dropdown:', this._produks);
                                        this._produks.forEach(produk => {
                                            if (produk && produk.produk_id) {
                                                const option = $(`
                                                    <option value="${produk.produk_id}"
                                                            data-satuan-id="${produk.satuan_id || ''}"
                                                            data-stok="${produk.stok || 0}">
                                                        ${produk.nama || '(Produk tanpa nama)'}
                                                    </option>
                                                `);
                                                $(element).append(option);
                                                console.log('Added product option:', produk.nama);
                                            }
                                        });

                                        // Trigger change to update Select2
                                        $(element).trigger('change');
                                        console.log('Select2 refreshed for dropdown', index);
                                    } else {
                                        console.log('Select2 not initialized for dropdown', index);
                                    }
                                });
                            });
                        } catch (error) {
                            console.error('Fatal error in fetchProduks:', error);
                            this._produks = [];
                            alert('Terjadi kesalahan saat memuat data produk. Silakan refresh halaman.');
                        }
                    },

                    // Get stock info for selected product
                    async updateStokInfo(index) {
                        console.log(`updateStokInfo called for index ${index}`);

                        if (!this.items || !this.items[index]) {
                            console.error('Invalid items array or index:', index);
                            return;
                        }

                        const produkId = this.items[index].produk_id;
                        console.log(`Selected product ID: ${produkId}, Warehouse ID: ${this.gudangAsalId}`);

                        if (!produkId) {
                            console.log('No product selected, clearing stock info');
                            this.items[index].stok_tersedia = 0;
                            this.items[index].satuan_nama = '';
                            this.items[index].satuan_id = '';
                            return;
                        }

                        if (!this.gudangAsalId) {
                            console.error('No warehouse selected');
                            this.items[index].stok_tersedia = 0;
                            this.items[index].satuan_nama = '';
                            this.items[index].satuan_id = '';
                            return;
                        }

                        try {
                            // First check if we already have this product info in our _produks array
                            console.log(
                                `Looking for product ID ${produkId} in cache of ${this._produks ? this._produks.length : 0} products`
                            );

                            // Ensure produk_id is treated as a string for comparison
                            const produkIdStr = String(produkId);

                            const selectedProduk = this._produks && Array.isArray(this._produks) ?
                                this._produks.find(p => p && p.produk_id && String(p.produk_id) === produkIdStr) :
                                null;

                            if (selectedProduk) {
                                console.log(`Found product ID ${produkId} in cache:`, selectedProduk);

                                // Ensure we're working with numeric values for stock
                                let stok = 0;
                                if (selectedProduk.stok !== undefined && selectedProduk.stok !== null) {
                                    stok = parseFloat(selectedProduk.stok);
                                    if (isNaN(stok)) {
                                        console.warn(`Invalid stok value: ${selectedProduk.stok}, defaulting to 0`);
                                        stok = 0;
                                    }
                                }

                                this.items[index].stok_tersedia = stok;
                                this.items[index].satuan_nama = selectedProduk.satuan_nama || '-';
                                this.items[index].satuan_id = selectedProduk.satuan_id || '';
                            } else {
                                // Fallback to API call if not found
                                console.log(`Product ID ${produkId} not found in cache, making API call`);

                                // Show we're loading
                                this.isLoading = true;

                                try {
                                    const response = await fetch(
                                        `/inventaris/transfer-gudang/get-stok?produk_id=${produkId}&gudang_id=${this.gudangAsalId}`
                                    );

                                    // Check if the response is OK
                                    if (!response.ok) {
                                        const errorText = await response.text();
                                        console.error(`API response error (${response.status}):`, errorText);
                                        throw new Error(`HTTP error! status: ${response.status}`);
                                    }

                                    let data;
                                    try {
                                        data = await response.json();
                                        console.log('API response:', data);
                                    } catch (jsonError) {
                                        console.error('Failed to parse JSON response:', jsonError);
                                        throw new Error('Gagal memproses respons dari server');
                                    }

                                    // Check if data contains error
                                    if (data && data.error) {
                                        console.error('API returned error:', data.error);
                                        throw new Error(data.error);
                                    }

                                    if (!data) {
                                        console.error('Empty response data');
                                        throw new Error('Data tidak tersedia dari server');
                                    }

                                    // Validate data structure
                                    if (data && typeof data === 'object') {
                                        // Parse stok as float to ensure it's a number
                                        let stok = 0;
                                        if (data.stok !== undefined && data.stok !== null) {
                                            stok = parseFloat(data.stok);
                                            if (isNaN(stok)) {
                                                console.warn(`Invalid stok value from API: ${data.stok}, defaulting to 0`);
                                                stok = 0;
                                            }
                                        }

                                        this.items[index].stok_tersedia = stok;
                                        this.items[index].satuan_nama = (data.satuan !== undefined && data.satuan !==
                                                null) ?
                                            data.satuan : '-';
                                        this.items[index].satuan_id = (data.satuan_id !== undefined && data.satuan_id !==
                                            null) ? data.satuan_id : '';

                                        console.log(`Updated stock info for product ID ${produkId}:`, {
                                            stok: this.items[index].stok_tersedia,
                                            satuan: this.items[index].satuan_nama,
                                            satuan_id: this.items[index].satuan_id
                                        });
                                    } else {
                                        throw new Error('Format data tidak valid');
                                    }
                                } finally {
                                    this.isLoading = false;
                                }
                            }
                        } catch (error) {
                            console.error('Error fetching stock info:', error);
                            alert(
                                'Gagal mendapatkan informasi stok produk: ' + error.message +
                                '. Silakan coba pilih produk lain atau refresh halaman.'
                            );
                            this.items[index].stok_tersedia = 0;
                            this.items[index].satuan_nama = '-';
                            this.items[index].satuan_id = '';
                            this.isLoading = false;
                        }
                    },

                    addItem() {
                        try {
                            console.log('Adding new item to transfer list');
                            // Add default values for all properties to avoid undefined errors
                            const newItem = {
                                produk_id: '',
                                quantity: 1,
                                satuan_id: '',
                                keterangan: '',
                                stok_tersedia: 0,
                                satuan_nama: ''
                            };
                            this.items.push(newItem);
                            console.log('New item added. Current items:', this.items.length);
                            return newItem;
                        } catch (e) {
                            console.error('Error adding item:', e);
                            alert('Terjadi kesalahan saat menambah item. Silakan refresh halaman.');
                            return null;
                        }
                    },

                    initSelect2() {
                        // Initialize Select2 dengan MutationObserver untuk mendeteksi dropdown baru
                        const itemsContainer = document.getElementById('detail-items');
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
                                                            placeholder: '-- Pilih Produk --',
                                                            allowClear: true,
                                                            width: '100%',
                                                            templateResult: function(
                                                                option) {
                                                                if (!option.id)
                                                                    return option
                                                                        .text;

                                                                const stok = $(
                                                                        option
                                                                        .element)
                                                                    .data('stok') ||
                                                                    0;
                                                                const $result = $(`
                                                                    <div class="select2-result-product">
                                                                        <div class="product-name">${option.text}</div>
                                                                        <div class="product-stock text-xs text-gray-500">Stok: ${stok}</div>
                                                                    </div>
                                                                `);
                                                                return $result;
                                                            },
                                                            templateSelection: function(
                                                                option) {
                                                                return option.text;
                                                            }
                                                        }).on('select2:select', e => {
                                                            const nameAttr = $(select)
                                                                .attr('name');
                                                            const match = nameAttr
                                                                .match(
                                                                    /produk_id\[(\d+)\]/
                                                                );
                                                            if (match && match[1]) {
                                                                const idx = parseInt(
                                                                    match[1]);
                                                                this.items[idx]
                                                                    .produk_id = e
                                                                    .target.value;
                                                                setTimeout(() => this
                                                                    .updateStokInfo(
                                                                        idx), 100);
                                                            }
                                                        }).on('select2:clear', e => {
                                                            const nameAttr = $(select)
                                                                .attr('name');
                                                            const match = nameAttr
                                                                .match(
                                                                    /produk_id\[(\d+)\]/
                                                                );
                                                            if (match && match[1]) {
                                                                const idx = parseInt(
                                                                    match[1]);
                                                                this.items[idx]
                                                                    .produk_id = '';
                                                                setTimeout(() => this
                                                                    .updateStokInfo(
                                                                        idx), 100);
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

                            // Initialize Select2 untuk dropdown yang sudah ada
                            this.$nextTick(() => {
                                $('.select2-dropdown-dynamic').each((index, select) => {
                                    if (!$(select).data('select2') && select.name.includes('produk_id')) {
                                        $(select).select2({
                                            placeholder: '-- Pilih Produk --',
                                            allowClear: true,
                                            width: '100%',
                                            templateResult: function(option) {
                                                if (!option.id) return option.text;

                                                const stok = $(option.element).data('stok') || 0;
                                                const $result = $(`
                                                    <div class="select2-result-product">
                                                        <div class="product-name">${option.text}</div>
                                                        <div class="product-stock text-xs text-gray-500">Stok: ${stok}</div>
                                                    </div>
                                                `);
                                                return $result;
                                            },
                                            templateSelection: function(option) {
                                                return option.text;
                                            }
                                        }).on('select2:select', e => {
                                            const nameAttr = $(select).attr('name');
                                            const match = nameAttr.match(/produk_id\[(\d+)\]/);
                                            if (match && match[1]) {
                                                const idx = parseInt(match[1]);
                                                this.items[idx].produk_id = e.target.value;
                                                setTimeout(() => this.updateStokInfo(idx), 100);
                                            }
                                        }).on('select2:clear', e => {
                                            const nameAttr = $(select).attr('name');
                                            const match = nameAttr.match(/produk_id\[(\d+)\]/);
                                            if (match && match[1]) {
                                                const idx = parseInt(match[1]);
                                                this.items[idx].produk_id = '';
                                                setTimeout(() => this.updateStokInfo(idx), 100);
                                            }
                                        });
                                    }
                                });
                            });
                        }
                    },

                    clearInvalidSelections() {
                        // Clear selected products that are not available in current warehouse
                        this.items.forEach((item, index) => {
                            if (item.produk_id) {
                                const selectedProduct = this._produks.find(p => p && p.produk_id == item.produk_id);
                                if (!selectedProduct) {
                                    console.log('Clearing invalid selection for item', index, 'product ID:', item
                                        .produk_id);
                                    item.produk_id = '';
                                    item.stok_tersedia = 0;
                                    item.satuan_id = '';
                                    item.satuan_nama = '';
                                }
                            }
                        });
                    },
                };
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Ensure jQuery is loaded
                if (typeof jQuery === 'undefined' || typeof $ === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
                    script.onload = function() {
                        console.log('jQuery loaded, initializing form');
                        initializeForm();
                    };
                    document.head.appendChild(script);
                } else {
                    console.log('jQuery already available, initializing form');
                    initializeForm();
                }

                function initializeForm() {
                    const form = document.getElementById('transferForm');

                    // Debug form elements
                    console.log('Form elements:', {
                        'gudang_asal_id': document.getElementById('gudang_asal_id'),
                        'gudang_tujuan_id': document.getElementById('gudang_tujuan_id'),
                        'nomor': document.getElementById('nomor'),
                        'tanggal': document.getElementById('tanggal')
                    });

                    form.addEventListener('submit', function(e) {
                        try {
                            const gudangAsal = document.getElementById('gudang_asal_id').value;
                            const gudangTujuan = document.getElementById('gudang_tujuan_id').value;

                            // Debug form data
                            console.log('Form submission triggered');
                            console.log('Gudang Asal:', gudangAsal);
                            console.log('Gudang Tujuan:', gudangTujuan);

                            if (!gudangAsal) {
                                e.preventDefault();
                                alert('Silakan pilih gudang asal!');
                                return;
                            }

                            if (!gudangTujuan) {
                                e.preventDefault();
                                alert('Silakan pilih gudang tujuan!');
                                return;
                            }

                            if (gudangAsal === gudangTujuan && gudangAsal !== '') {
                                e.preventDefault();
                                alert('Gudang asal dan tujuan tidak boleh sama!');
                                return;
                            }

                            // Validate items have been added and have produk_id
                            const transferHandler = document.querySelector('[x-data="transferDetailHandler()"]')
                                .__x
                                .$data;

                            if (!transferHandler || !transferHandler.items || transferHandler.items.length ===
                                0) {
                                e.preventDefault();
                                alert('Silakan tambahkan minimal satu produk untuk transfer!');
                                return;
                            }

                            const invalidItems = transferHandler.items.filter(item => !item.produk_id);
                            if (invalidItems.length > 0) {
                                e.preventDefault();
                                alert(
                                    `Ada ${invalidItems.length} produk yang belum dipilih. Silakan pilih produk atau hapus baris tersebut.`
                                );
                                return;
                            }

                            // Verify quantities are valid numbers and within stock limits
                            const invalidQty = transferHandler.items.filter(item =>
                                !item.quantity ||
                                isNaN(parseFloat(item.quantity)) ||
                                parseFloat(item.quantity) <= 0 ||
                                parseFloat(item.quantity) > parseFloat(item.stok_tersedia)
                            );

                            if (invalidQty.length > 0) {
                                e.preventDefault();
                                console.error('Invalid quantities:', invalidQty);
                                alert(
                                    `Ada ${invalidQty.length} produk dengan kuantitas tidak valid. Pastikan kuantitas lebih dari 0 dan tidak melebihi stok tersedia.`
                                );
                                return;
                            }

                            // Extra validation for satuan_id
                            const invalidSatuan = transferHandler.items.filter(item => !item.satuan_id);
                            if (invalidSatuan.length > 0) {
                                e.preventDefault();
                                console.error('Missing satuan_id:', invalidSatuan);
                                alert(
                                    `Ada ${invalidSatuan.length} produk tanpa satuan. Silakan pilih produk dengan benar.`
                                );
                                return;
                            }

                            // Log final form data that will be submitted
                            console.log('Form validation passed. Submitting...');
                            console.log('Final form data:');
                            const formData = new FormData(form);
                            for (const pair of formData.entries()) {
                                console.log(pair[0], pair[1]);
                            }
                        } catch (error) {
                            // If any error occurs during validation, allow form to be submitted and let server validation handle it
                            console.error('Error validating form:', error);
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
