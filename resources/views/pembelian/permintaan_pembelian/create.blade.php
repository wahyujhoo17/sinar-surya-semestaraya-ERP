<x-app-layout>
    <div x-data="purchaseRequestForm()" class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @push('styles')
            <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
        @endpush

        <form action="{{ route('pembelian.permintaan-pembelian.store') }}" method="POST" @submit="validateForm">
            @csrf

            {{-- Header Section --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 mb-6">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Permintaan Pembelian
                                </h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Tambahkan permintaan pembelian
                                    baru untuk departemen Anda.</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('pembelian.permintaan-pembelian.index') }}"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Simpan Permintaan
                        </button>
                    </div>
                </div>

                {{-- Form Section - Header Details --}}
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Nomor PR --}}
                    <div>
                        <label for="nomor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nomor Permintaan <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="nomor" id="nomor" value="{{ $nomorPR }}" readonly
                                class="block w-full pr-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
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

                    {{-- Department --}}
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Departemen <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <select name="department_id" id="department_id" required
                                class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                <option value="">Pilih Departemen</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->nama }}</option>
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

                    {{-- Catatan --}}
                    <div class="md:col-span-2 lg:col-span-3">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Catatan
                        </label>
                        <div class="mt-1">
                            <textarea id="catatan" name="catatan" rows="3"
                                class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"
                                placeholder="Catatan tambahan untuk permintaan ini..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Items Section --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Detail Item</h2>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8-4-8 4m16 0l-8 4m-8-4l8 4m8 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada item</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan item untuk
                            permintaan ini.</p>
                        <div class="mt-6">
                            <button type="button" @click="addItem"
                                class="inline-flex items-center gap-2 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Item Pertama
                            </button>
                        </div>
                    </div>

                    <div x-show="items.length > 0">
                        {{-- Table Header --}}
                        <div
                            class="hidden md:grid grid-cols-12 gap-4 px-4 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <div class="col-span-3">Produk/Ukuran</div>
                            <div class="col-span-3">Deskripsi</div>
                            <div class="col-span-1">Jumlah</div>
                            <div class="col-span-2">Satuan</div>
                            <div class="col-span-2">Harga Est.</div>
                            <div class="col-span-1 text-right">Aksi</div>
                        </div>

                        {{-- Items --}}
                        <div class="space-y-3 mt-2" id="items-container">
                            <template x-for="(item, index) in items" :key="index">
                                <div
                                    class="border dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800 shadow-sm">
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
                                        {{-- Produk & Ukuran --}}
                                        <div class="md:col-span-3">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 md:hidden">Produk/Ukuran</label>
                                            <div class="space-y-2">
                                                <div>
                                                    <select :id="`produk_select_${index}`"
                                                        :name="`items[${index}][produk_id]`"
                                                        class="produk-select mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                                        x-model="item.produk_id" @change="updateProdukDetail(index)">
                                                        <option value="">Pilih Produk</option>
                                                        @foreach ($produks as $produk)
                                                            <option value="{{ $produk->id }}"
                                                                data-nama="{{ $produk->nama }}"
                                                                data-ukuran="{{ $produk->ukuran }}"
                                                                data-satuan="{{ $produk->satuan_id }}"
                                                                data-harga="{{ $produk->harga_beli }}">
                                                                {{ $produk->kode }} - {{ $produk->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="flex items-center">
                                                    <span
                                                        class="text-xs text-gray-500 dark:text-gray-400 mr-2">Ukuran:</span>
                                                    <span x-text="item.ukuran || '-'"
                                                        class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                                                    <input type="hidden" :name="`items[${index}][ukuran]`"
                                                        x-model="item.ukuran">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Deskripsi --}}
                                        <div class="md:col-span-3">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 md:hidden">Deskripsi</label>
                                            <textarea :name="`items[${index}][deskripsi]`" x-model="item.deskripsi" rows="2" placeholder="Deskripsi item"
                                                class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md"></textarea>
                                        </div>

                                        {{-- Jumlah --}}
                                        <div class="md:col-span-1">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 md:hidden">Jumlah</label>
                                            <input type="number" step="1" min="1"
                                                :name="`items[${index}][quantity]`" x-model="item.quantity" required
                                                placeholder="0.00"
                                                class="mt-1 focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                        </div>

                                        {{-- Satuan --}}
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 md:hidden">Satuan</label>
                                            <select :name="`items[${index}][satuan_id]`" x-model="item.satuan_id"
                                                required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md">
                                                <option value="">Pilih</option>
                                                @foreach ($satuans as $satuan)
                                                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Harga Estimasi --}}
                                        <div class="md:col-span-2">
                                            <label
                                                class="block text-xs font-medium text-gray-700 dark:text-gray-300 md:hidden">Harga
                                                Est.</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                                </div>
                                                <input type="number" :name="`items[${index}][harga_estimasi]`"
                                                    x-model="item.harga_estimasi" min="0" placeholder="0"
                                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-12 pr-3 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="md:col-span-1 flex justify-end items-center">
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
                            </template>
                        </div>

                        {{-- Add Item Button (when items exist) --}}
                        <div class="mt-4 flex items-center justify-center">
                            <button type="button" @click="addItem"
                                class="inline-flex items-center gap-2 px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-gray-400 dark:text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Tambah Item Lainnya
                            </button>
                        </div>

                        {{-- Total Estimation --}}
                        <div class="mt-6 flex justify-end">
                            <div class="w-full md:w-1/3 bg-gray-50 dark:bg-gray-700/50 rounded-lg py-3 px-5">
                                <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                                    <span>Total Estimasi:</span>
                                    <span class="font-medium" x-text="formatRupiah(calculateTotal())"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row-reverse gap-2">
                    <button type="submit"
                        class="w-full sm:w-auto px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                        Simpan Permintaan
                    </button>
                    {{-- <button type="button" @click="saveDraft"
                        class="w-full sm:w-auto px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                        Simpan Draft
                    </button> --}}
                    <a href="{{ route('pembelian.permintaan-pembelian.index') }}"
                        class="w-full sm:w-auto px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        function purchaseRequestForm() {
            return {
                items: [],
                errors: [],
                selects: [],
                isSubmitting: false, // Add flag to prevent double submission

                init() {
                    this.addItem();
                },

                addItem() {
                    const index = this.items.length;
                    this.items.push({
                        produk_id: '',
                        ukuran: '',
                        deskripsi: '',
                        quantity: 1,
                        satuan_id: '',
                        harga_estimasi: 0
                    });

                    // Initialize TomSelect after the DOM updates
                    this.$nextTick(() => {
                        this.initTomSelect(index);
                    });
                },

                initTomSelect(index) {
                    const selectElement = document.getElementById(`produk_select_${index}`);
                    if (selectElement) {
                        // Destroy existing instance if exists
                        if (this.selects[index]) {
                            this.selects[index].destroy();
                        }

                        // Create new TomSelect instance
                        this.selects[index] = new TomSelect(selectElement, {
                            plugins: ['dropdown_input', 'clear_button'],
                            placeholder: 'Cari dan pilih produk...',
                            allowEmptyOption: true,
                            searchField: ['text'],
                            render: {
                                option: function(data, escape) {
                                    return `<div class="py-2 px-3">
                                        <div class="font-medium">${escape(data.text)}</div>
                                    </div>`;
                                },
                                item: function(data, escape) {
                                    return `<div>${escape(data.text)}</div>`;
                                }
                            },
                            onChange: (value) => {
                                this.items[index].produk_id = value;
                                this.updateProdukDetail(index);
                            }
                        });
                    }
                },

                updateProdukDetail(index) {
                    const produkId = this.items[index].produk_id;
                    if (produkId) {
                        const option = document.querySelector(`option[value="${produkId}"]`);
                        if (option) {
                            // Update ukuran from product data
                            this.items[index].ukuran = option.dataset.ukuran || '';

                            // Auto-select the corresponding satuan if available
                            if (option.dataset.satuan) {
                                this.items[index].satuan_id = option.dataset.satuan;
                            }

                            // Set harga_estimasi from the product's harga_beli if available
                            if (option.dataset.harga) {
                                this.items[index].harga_estimasi = parseFloat(option.dataset.harga) || 0;
                            }
                        }
                    } else {
                        this.items[index].ukuran = '';
                        // Don't reset the price if the user manually entered a value
                    }
                },

                removeItem(index) {
                    // Destroy TomSelect instance
                    if (this.selects[index]) {
                        this.selects[index].destroy();
                    }

                    // Remove items and reorder selects array
                    this.items.splice(index, 1);
                    this.selects.splice(index, 1);

                    // Reinitialize remaining selects with proper indices
                    this.$nextTick(() => {
                        for (let i = index; i < this.items.length; i++) {
                            this.initTomSelect(i);
                        }
                    });
                },

                calculateTotal() {
                    return this.items.reduce((total, item) => {
                        return total + (parseFloat(item.quantity || 0) * parseFloat(item.harga_estimasi || 0));
                    }, 0);
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
                        e.preventDefault(); // Prevent if already submitting
                        return false;
                    }

                    this.errors = [];
                    let valid = true; // Assume valid initially

                    // Check required header fields
                    const departmentId = document.getElementById('department_id').value;
                    const tanggal = document.getElementById('tanggal').value;

                    if (!departmentId) {
                        this.errors.push('Departemen wajib diisi.');
                        valid = false;
                    }
                    if (!tanggal) {
                        this.errors.push('Tanggal wajib diisi.');
                        valid = false;
                    }

                    // Check if items exist
                    if (this.items.length === 0) {
                        this.errors.push('Minimal harus ada 1 item');
                        valid = false;
                    } else {
                        // Check if all required fields in items are filled
                        this.items.forEach((item, index) => {
                            if (!item.produk_id || !item.quantity || item.quantity <= 0 || !item.satuan_id) {
                                this.errors.push(
                                    `Item #${index + 1} memiliki field (Produk, Jumlah > 0, Satuan) yang belum diisi atau tidak valid.`
                                );
                                valid = false;
                            }
                            // Add more specific checks if needed
                        });
                    }

                    if (!valid) {
                        alert("Terdapat kesalahan:\n- " + this.errors.join('\n- '));
                        e.preventDefault(); // Prevent submission ONLY if invalid
                        this.isSubmitting = false; // Reset flag if validation fails
                        return false; // Indicate validation failed
                    }

                    // If validation passes, disable button and set flag
                    this.isSubmitting = true;
                    const submitButton = e.target.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerText = 'Menyimpan...'; // Provide feedback
                    }
                    console.log('Validation passed, allowing default submission.');
                    // Allow default submission to proceed
                    return true;
                },

                saveDraft() {
                    if (this.isSubmitting) return; // Prevent if already submitting

                    // Add a hidden field to indicate this is a draft
                    const draftInput = document.createElement('input');
                    draftInput.type = 'hidden';
                    draftInput.name = 'is_draft';
                    draftInput.value = '1';
                    const form = document.querySelector('form');
                    form.appendChild(draftInput);

                    // Disable buttons and set flag
                    this.isSubmitting = true;
                    const draftButton = form.querySelector('button[x-on\\:click="saveDraft"]');
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (draftButton) {
                        draftButton.disabled = true;
                        draftButton.innerText = 'Menyimpan Draft...';
                    }
                    if (submitButton) {
                        submitButton.disabled = true; // Also disable the main submit button
                    }

                    // Submit the form without validation
                    form.submit();
                }
            }
        }
    </script>
</x-app-layout>
