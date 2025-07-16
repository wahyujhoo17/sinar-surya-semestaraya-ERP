<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Uang Muka Penjualan', 'url' => route('keuangan.uang-muka-penjualan.index')],
    ['name' => 'Tambah Uang Muka', 'url' => '#'],
]" :currentPage="'Tambah Uang Muka Penjualan Baru'">

    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            [x-cloak] {
                display: none !important;
            }

            /* Select2 Custom Styling */
            .select2-container--default .select2-selection--single {
                height: 42px !important;
                border: 1px solid #d1d5db !important;
                border-radius: 0.5rem !important;
                padding: 0 12px !important;
                line-height: 40px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                padding-left: 0 !important;
                line-height: 40px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 40px !important;
                right: 8px !important;
            }

            .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 1px #3b82f6 !important;
            }

            .select2-dropdown {
                border: 1px solid #d1d5db !important;
                border-radius: 0.5rem !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            }

            .select2-results__option {
                padding: 8px 12px !important;
            }

            .select2-results__option--highlighted {
                background-color: #3b82f6 !important;
            }
        </style>
    @endpush

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Tambah Uang Muka Penjualan
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Buat transaksi uang muka penjualan baru
                            </p>
                        </div>
                        <a href="{{ route('keuangan.uang-muka-penjualan.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('keuangan.uang-muka-penjualan.store') }}" method="POST" x-data="uangMukaForm()"
                x-init="init()">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Form -->
                    <div class="lg:col-span-2">
                        <!-- Basic Information -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    Informasi Dasar
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nomor -->
                                    <div>
                                        <label for="nomor"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Nomor Uang Muka *
                                        </label>
                                        <input type="text" name="nomor" id="nomor"
                                            value="{{ old('nomor', $nomor) }}"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                            readonly>
                                        @error('nomor')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Tanggal -->
                                    <div>
                                        <label for="tanggal"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Tanggal *
                                        </label>
                                        <input type="date" name="tanggal" id="tanggal"
                                            value="{{ old('tanggal', date('Y-m-d')) }}"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                            required>
                                        @error('tanggal')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Customer -->
                                    <div>
                                        <label for="customer_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Customer *
                                        </label>
                                        <select name="customer_id" id="customer_id"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                            required>
                                            <option value="">Pilih Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->nama ?: $customer->company }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Sales Order (Optional) -->
                                    <div>
                                        <label for="sales_order_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Sales Order (Opsional)
                                        </label>
                                        <select name="sales_order_id" id="sales_order_id" x-model="selectedSalesOrder"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                            <option value="">Pilih Sales Order</option>
                                            <template x-for="so in filteredSalesOrders" :key="so.id">
                                                <option :value="so.id"
                                                    x-text="`${so.nomor} - ${formatCurrency(so.total)}`"></option>
                                            </template>
                                        </select>
                                        @error('sales_order_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Jumlah -->
                                    <div class="md:col-span-2">
                                        <label for="jumlah"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Jumlah Uang Muka *
                                        </label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" name="jumlah" id="jumlah" x-model="jumlah"
                                                value="{{ old('jumlah') }}" min="0" step="0.01"
                                                class="pl-12 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                                placeholder="0.00" required>
                                        </div>
                                        @error('jumlah')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="md:col-span-2">
                                        <label for="keterangan"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Keterangan
                                        </label>
                                        <textarea name="keterangan" id="keterangan" rows="3"
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="Masukkan keterangan...">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    Metode Pembayaran
                                </h3>

                                <!-- Payment Method Selection -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Pilih Metode Pembayaran *
                                    </label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <!-- Kas -->
                                        <label class="relative">
                                            <input type="radio" name="metode_pembayaran" value="kas"
                                                x-model="metode" class="sr-only"
                                                {{ old('metode_pembayaran') === 'kas' ? 'checked' : '' }}>
                                            <div class="p-4 border-2 rounded-lg cursor-pointer transition-all"
                                                :class="metode === 'kas' ?
                                                    'border-blue-500 bg-blue-50 dark:bg-blue-900/20' :
                                                    'border-gray-200 dark:border-gray-600'">
                                                <div class="flex items-center">
                                                    <svg class="w-6 h-6 mr-3 text-green-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                        </path>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">Kas
                                                        </div>
                                                        <div class="text-sm text-gray-500">Penerimaan tunai</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- Bank -->
                                        <label class="relative">
                                            <input type="radio" name="metode_pembayaran" value="bank"
                                                x-model="metode" class="sr-only"
                                                {{ old('metode_pembayaran') === 'bank' ? 'checked' : '' }}>
                                            <div class="p-4 border-2 rounded-lg cursor-pointer transition-all"
                                                :class="metode === 'bank' ?
                                                    'border-blue-500 bg-blue-50 dark:bg-blue-900/20' :
                                                    'border-gray-200 dark:border-gray-600'">
                                                <div class="flex items-center">
                                                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                        </path>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">Bank
                                                            Transfer</div>
                                                        <div class="text-sm text-gray-500">Transfer bank</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('metode_pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kas Field -->
                                <div id="kas_field" style="display: none;" class="mb-4">
                                    <label for="kas_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Pilih Kas *
                                    </label>
                                    <select name="kas_id" id="kas_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">Pilih Kas</option>
                                        @foreach ($kasAccounts as $kas)
                                            <option value="{{ $kas->id }}"
                                                {{ old('kas_id') == $kas->id ? 'selected' : '' }}>
                                                {{ $kas->nama }} - Saldo: Rp
                                                {{ number_format($kas->saldo, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kas_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Bank Field -->
                                <div id="bank_field" style="display: none;" class="mb-4">
                                    <label for="rekening_bank_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Pilih Rekening Bank *
                                    </label>
                                    <select name="rekening_bank_id" id="rekening_bank_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                        <option value="">Pilih Rekening Bank</option>
                                        @foreach ($bankAccounts as $bank)
                                            <option value="{{ $bank->id }}"
                                                {{ old('rekening_bank_id') == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->nama_bank }} - {{ $bank->nomor_rekening }} - Saldo: Rp
                                                {{ number_format($bank->saldo, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('rekening_bank_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nomor Referensi -->
                                <div>
                                    <label for="nomor_referensi"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nomor Referensi
                                    </label>
                                    <input type="text" name="nomor_referensi" id="nomor_referensi"
                                        value="{{ old('nomor_referensi') }}"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="Masukkan nomor referensi...">
                                    @error('nomor_referensi')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('keuangan.uang-muka-penjualan.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                :disabled="isSubmitting">
                                <span x-show="!isSubmitting">Simpan</span>
                                <span x-show="isSubmitting" x-cloak>Menyimpan...</span>
                            </button>
                        </div>
                    </div>

                    <!-- Sidebar Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    Ringkasan
                                </h3>

                                <div class="space-y-4">
                                    <!-- Customer -->
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Customer</div>
                                        <div class="font-medium text-gray-900 dark:text-white"
                                            x-text="getSelectedCustomerText()">
                                            Belum dipilih
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <div class="text-sm text-blue-600 dark:text-blue-400">Jumlah</div>
                                        <div class="text-lg font-bold text-blue-900 dark:text-blue-100"
                                            x-text="formatCurrency(jumlah)">
                                            Rp 0
                                        </div>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <div class="text-sm text-green-600 dark:text-green-400">Metode Pembayaran</div>
                                        <div class="font-medium text-green-900 dark:text-green-100">
                                            <span x-show="!metode" class="text-gray-500">Belum dipilih</span>
                                            <span x-show="metode === 'kas'">Kas</span>
                                            <span x-show="metode === 'bank'">Bank Transfer</span>
                                        </div>
                                    </div>

                                    <!-- Sales Order -->
                                    <div x-show="selectedSalesOrder"
                                        class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                        <div class="text-sm text-yellow-600 dark:text-yellow-400">Sales Order</div>
                                        <div class="font-medium text-yellow-900 dark:text-yellow-100">
                                            <template x-if="selectedSalesOrder">
                                                <span x-text="getSelectedSalesOrderText()"></span>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tips -->
                                <div class="mt-6 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tips:</h4>
                                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                        <li>• Pilih customer terlebih dahulu</li>
                                        <li>• Sales Order bersifat opsional</li>
                                        <li>• Jumlah harus lebih dari 0</li>
                                        <li>• Pilih metode pembayaran</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            function uangMukaForm() {
                return {
                    metode: @json(old('metode_pembayaran', '')),
                    selectedCustomer: @json(old('customer_id', null)),
                    selectedSalesOrder: @json(old('sales_order_id', null)),
                    jumlah: @json(old('jumlah', 0)),
                    isSubmitting: false,
                    filteredSalesOrders: [],
                    allSalesOrders: @json($salesOrders),

                    init() {
                        // Watch metode pembayaran
                        this.$watch('metode', (value) => {
                            this.handlePaymentMethodChange(value);
                        });

                        // Watch customer selection
                        this.$watch('selectedCustomer', (customerId) => {
                            this.filterSalesOrders(customerId);
                        });

                        // Initialize fields based on old values
                        if (this.metode) {
                            this.$nextTick(() => {
                                this.handlePaymentMethodChange(this.metode);
                            });
                        }

                        // Filter sales orders for selected customer
                        this.filterSalesOrders(this.selectedCustomer);

                        // Initialize Select2 for customer dropdown
                        this.$nextTick(() => {
                            this.initSelect2();
                        });
                    },

                    initSelect2() {
                        // Initialize customer Select2
                        $('#customer_id').select2({
                            placeholder: 'Pilih Customer',
                            allowClear: true,
                            width: '100%'
                        }).on('change', (e) => {
                            this.selectedCustomer = e.target.value;
                        });

                        // Set initial value if exists
                        if (this.selectedCustomer) {
                            $('#customer_id').val(this.selectedCustomer).trigger('change');
                        }
                    },

                    handlePaymentMethodChange(value) {
                        const kasField = document.getElementById('kas_field');
                        const bankField = document.getElementById('bank_field');
                        const kasSelect = document.getElementById('kas_id');
                        const bankSelect = document.getElementById('rekening_bank_id');

                        if (value === 'kas') {
                            if (kasField) kasField.style.display = 'block';
                            if (bankField) bankField.style.display = 'none';
                            if (kasSelect) kasSelect.required = true;
                            if (bankSelect) bankSelect.required = false;
                        } else if (value === 'bank') {
                            if (kasField) kasField.style.display = 'none';
                            if (bankField) bankField.style.display = 'block';
                            if (kasSelect) kasSelect.required = false;
                            if (bankSelect) bankSelect.required = true;
                        } else {
                            if (kasField) kasField.style.display = 'none';
                            if (bankField) bankField.style.display = 'none';
                            if (kasSelect) kasSelect.required = false;
                            if (bankSelect) bankSelect.required = false;
                        }
                    },

                    filterSalesOrders(customerId) {
                        if (customerId && this.allSalesOrders) {
                            this.filteredSalesOrders = this.allSalesOrders.filter(so => so.customer_id == customerId);
                        } else {
                            this.filteredSalesOrders = this.allSalesOrders || [];
                        }
                    },

                    formatCurrency(value) {
                        if (!value) return 'Rp 0';
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(value);
                    },

                    getSelectedCustomerText() {
                        if (!this.selectedCustomer) return 'Belum dipilih';
                        const option = document.querySelector(`#customer_id option[value="${this.selectedCustomer}"]`);
                        return option ? option.textContent : 'Belum dipilih';
                    },

                    getSelectedSalesOrderText() {
                        if (!this.selectedSalesOrder) return '';
                        const so = this.filteredSalesOrders.find(item => item.id == this.selectedSalesOrder);
                        return so ? `${so.nomor} - ${this.formatCurrency(so.total)}` : '';
                    }
                };
            }
        </script>
    @endpush

</x-app-layout>
