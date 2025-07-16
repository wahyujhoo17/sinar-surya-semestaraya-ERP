<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Uang Muka Penjualan', 'url' => route('keuangan.uang-muka-penjualan.index')],
    ['name' => 'Tambah Uang Muka', 'url' => '#'],
]" :currentPage="'Tambah Uang Muka Penjualan Baru'">

    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @push('styles')
            <!-- Select2 CSS -->
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <style>
                [x-cloak] {
                    display: none !important;
                }

                .form-card {
                    transition: all 0.3s ease;
                }

                .form-card:hover {
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }

                .payment-section {
                    position: relative;
                    overflow: hidden;
                    border-radius: 0.75rem;
                }

                .payment-section::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                    z-index: 0;
                    opacity: 0.5;
                }

                .payment-method-card {
                    transition: all 0.3s ease;
                    position: relative;
                    z-index: 10;
                }

                .payment-method-card:hover {
                    transform: translateY(-2px);
                }

                .summary-badge {
                    position: absolute;
                    top: -10px;
                    right: -10px;
                    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
                    color: white;
                    border-radius: 9999px;
                    padding: 0.25rem 0.75rem;
                    font-size: 0.75rem;
                    font-weight: 600;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                }

                /* Select2 Custom Styling */
                .select2-container--default .select2-selection--single {
                    height: 48px !important;
                    border: 1px solid #d1d5db !important;
                    border-radius: 0.75rem !important;
                    padding: 0 16px !important;
                    line-height: 48px !important;
                }

                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    padding-left: 0 !important;
                    line-height: 48px !important;
                }

                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 46px !important;
                    right: 10px !important;
                }

                .select2-container--default.select2-container--focus .select2-selection--single {
                    border-color: #3b82f6 !important;
                    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
                }

                .select2-dropdown {
                    border: 1px solid #d1d5db !important;
                    border-radius: 0.75rem !important;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                }

                .select2-results__option {
                    padding: 12px 16px !important;
                }

                .select2-results__option--highlighted {
                    background-color: #3b82f6 !important;
                }
            </style>
        @endpush

        <script>
            function uangMukaForm() {
                return {
                    metode: @json(old('metode_pembayaran', '')),
                    selectedCustomer: @json(old('customer_id', null)),
                    selectedSalesOrder: @json(old('sales_order_id', null)),
                    jumlah: @json(old('jumlah', 0)),
                    showValidationError: false,
                    errorMessage: '',
                    isSubmitting: false,
                    filteredSalesOrders: [],
                    allSalesOrders: @json($salesOrders),

                    init() {
                        // Watch metode pembayaran
                        this.$watch('metode', (value) => {
                            if (value === 'kas') {
                                this.showKasFields();
                            } else if (value === 'bank') {
                                this.showBankFields();
                            } else {
                                this.hideAllFields();
                            }
                        });

                        // Watch customer selection
                        this.$watch('selectedCustomer', (customerId) => {
                            this.filterSalesOrders(customerId);
                        });

                        // Initialize fields based on old values
                        if (this.metode) {
                            this.$nextTick(() => {
                                if (this.metode === 'kas') {
                                    this.showKasFields();
                                } else if (this.metode === 'bank') {
                                    this.showBankFields();
                                }
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

                    showKasFields() {
                        const kasField = document.getElementById('kas_field');
                        const bankField = document.getElementById('bank_field');
                        const kasSelect = document.getElementById('kas_id');
                        const bankSelect = document.getElementById('rekening_bank_id');

                        if (kasField) kasField.style.display = 'block';
                        if (bankField) bankField.style.display = 'none';
                        if (kasSelect) kasSelect.required = true;
                        if (bankSelect) bankSelect.required = false;
                    },

                    showBankFields() {
                        const kasField = document.getElementById('kas_field');
                        const bankField = document.getElementById('bank_field');
                        const kasSelect = document.getElementById('kas_id');
                        const bankSelect = document.getElementById('rekening_bank_id');

                        if (kasField) kasField.style.display = 'none';
                        if (bankField) bankField.style.display = 'block';
                        if (kasSelect) kasSelect.required = false;
                        if (bankSelect) bankSelect.required = true;
                    },

                    hideAllFields() {
                        const kasField = document.getElementById('kas_field');
                        const bankField = document.getElementById('bank_field');
                        const kasSelect = document.getElementById('kas_id');
                        const bankSelect = document.getElementById('rekening_bank_id');

                        if (kasField) kasField.style.display = 'none';
                        if (bankField) bankField.style.display = 'none';
                        if (kasSelect) kasSelect.required = false;
                        if (bankSelect) bankSelect.required = false;
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

                    submitForm() {
                        if (this.isSubmitting) return;

                        // Reset error state
                        this.showValidationError = false;
                        this.errorMessage = '';

                        // Basic client-side validation
                        if (!this.selectedCustomer) {
                            this.showValidationError = true;
                            this.errorMessage = 'Silakan pilih customer';
                            return;
                        }

                        if (!this.jumlah || this.jumlah <= 0) {
                            this.showValidationError = true;
                            this.errorMessage = 'Jumlah uang muka harus lebih dari 0';
                            return;
                        }

                        if (!this.metode) {
                            this.showValidationError = true;
                            this.errorMessage = 'Silakan pilih metode pembayaran';
                            return;
                        }

                        // Set submitting state
                        this.isSubmitting = true;

                        // Submit form
                        const form = document.getElementById('uang-muka-form');
                        if (form) {
                            form.submit();
                        }
                    }
                };
            }
        </script>

        <form action="{{ route('keuangan.uang-muka-penjualan.store') }}" method="POST" id="uang-muka-form"
            x-data="uangMukaForm()" x-init="init()">

            @csrf

            {{-- Header Section --}}
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Tambah Uang Muka Penjualan</h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Buat transaksi uang muka penjualan yang baru
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0 flex space-x-3">
                        <a href="{{ route('keuangan.uang-muka-penjualan.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            {{-- Error Alert --}}
            <div x-show="showValidationError" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-2"
                class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Validation Error</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <span x-text="errorMessage"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
                {{-- Left Column - Form --}}
                <div class="xl:col-span-2 space-y-6">
                    {{-- Basic Information Card --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden form-card">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Informasi Dasar
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Nomor --}}
                                <div>
                                    <label for="nomor"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Nomor Uang Muka
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nomor" id="nomor"
                                        value="{{ old('nomor', $nomor) }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                        readonly>
                                    @error('nomor')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Tanggal --}}
                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Tanggal
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal" id="tanggal"
                                        value="{{ old('tanggal', date('Y-m-d')) }}"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                        required>
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Customer --}}
                                <div>
                                    <label for="customer_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Customer
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select name="customer_id" id="customer_id"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
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
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Sales Order (Optional) --}}
                                <div>
                                    <label for="sales_order_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Sales Order (Opsional)
                                    </label>
                                    <select name="sales_order_id" id="sales_order_id" x-model="selectedSalesOrder"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                        <option value="">Pilih Sales Order</option>
                                        <template x-for="so in filteredSalesOrders" :key="so.id">
                                            <option :value="so.id"
                                                x-text="`${so.nomor} - ${formatCurrency(so.total)}`"></option>
                                        </template>
                                    </select>
                                    @error('sales_order_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Jumlah --}}
                                <div class="md:col-span-2">
                                    <label for="jumlah"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Jumlah Uang Muka
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="jumlah" id="jumlah" x-model="jumlah"
                                            value="{{ old('jumlah') }}" min="0" step="0.01"
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                            placeholder="0.00" required>
                                    </div>
                                    @error('jumlah')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Keterangan --}}
                                <div class="md:col-span-2">
                                    <label for="keterangan"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Keterangan
                                    </label>
                                    <textarea name="keterangan" id="keterangan" rows="3"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                        placeholder="Masukkan keterangan uang muka...">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Method Card --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden form-card payment-section">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Metode Pembayaran
                            </h3>
                        </div>
                        <div class="p-6">
                            {{-- Payment Method Selection --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Pilih Metode Pembayaran
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {{-- Kas Option --}}
                                    <label class="payment-method-card cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="kas"
                                            x-model="metode" class="sr-only"
                                            {{ old('metode_pembayaran') === 'kas' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 rounded-xl transition-all duration-200"
                                            :class="metode === 'kas' ?
                                                'border-green-500 bg-green-50 dark:bg-green-900/20' :
                                                'border-gray-200 dark:border-gray-600 hover:border-green-300'">
                                            <div class="flex items-center">
                                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Kas
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Penerimaan
                                                        tunai</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    {{-- Bank Option --}}
                                    <label class="payment-method-card cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="bank"
                                            x-model="metode" class="sr-only"
                                            {{ old('metode_pembayaran') === 'bank' ? 'checked' : '' }}>
                                        <div class="p-4 border-2 rounded-xl transition-all duration-200"
                                            :class="metode === 'bank' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' :
                                                'border-gray-200 dark:border-gray-600 hover:border-blue-300'">
                                            <div class="flex items-center">
                                                <svg class="w-6 h-6 text-blue-500 mr-3" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                </svg>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Bank
                                                        Transfer</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Transfer bank
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('metode_pembayaran')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kas Field --}}
                            <div id="kas_field" style="display: none;">
                                <label for="kas_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pilih Kas
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="kas_id" id="kas_id"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
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
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Bank Field --}}
                            <div id="bank_field" style="display: none;">
                                <label for="rekening_bank_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pilih Rekening Bank
                                    <span class="text-red-500">*</span>
                                </label>
                                <select name="rekening_bank_id" id="rekening_bank_id"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
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
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nomor Referensi --}}
                            <div id="referensi_field" class="mt-4">
                                <label for="nomor_referensi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nomor Referensi
                                </label>
                                <input type="text" name="nomor_referensi" id="nomor_referensi"
                                    value="{{ old('nomor_referensi') }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="Masukkan nomor referensi...">
                                @error('nomor_referensi')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="button" @click="submitForm()" :disabled="isSubmitting"
                            class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!isSubmitting">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Uang Muka
                            </span>
                            <span x-show="isSubmitting" x-cloak>
                                <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                        <a href="{{ route('keuangan.uang-muka-penjualan.index') }}"
                            class="flex-1 sm:flex-none bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 text-center">
                            Batal
                        </a>
                    </div>
                </div>

                {{-- Right Column - Summary --}}
                <div class="xl:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden form-card sticky top-6">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 relative">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Ringkasan
                            </h3>
                            <div class="summary-badge">
                                New
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                {{-- Customer Info --}}
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Customer</div>
                                    <div class="font-medium text-gray-900 dark:text-white"
                                        x-text="selectedCustomer ? $('#customer_id option:selected').text() || 'Belum dipilih' : 'Belum dipilih'">
                                        Belum dipilih
                                    </div>
                                </div>

                                {{-- Amount Info --}}
                                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <div class="text-sm text-blue-600 dark:text-blue-400 mb-1">Jumlah Uang Muka</div>
                                    <div class="text-xl font-bold text-blue-900 dark:text-blue-100"
                                        x-text="formatCurrency(jumlah)">
                                        Rp 0
                                    </div>
                                </div>

                                {{-- Payment Method Info --}}
                                <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-sm text-green-600 dark:text-green-400 mb-1">Metode Pembayaran
                                    </div>
                                    <div class="font-medium text-green-900 dark:text-green-100">
                                        <span x-show="!metode" class="text-gray-500">Belum dipilih</span>
                                        <span x-show="metode === 'kas'">Kas</span>
                                        <span x-show="metode === 'bank'">Bank Transfer</span>
                                    </div>
                                </div>

                                {{-- Sales Order Info --}}
                                <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg"
                                    x-show="selectedSalesOrder">
                                    <div class="text-sm text-yellow-600 dark:text-yellow-400 mb-1">Sales Order</div>
                                    <div class="font-medium text-yellow-900 dark:text-yellow-100">
                                        <template x-if="selectedSalesOrder">
                                            <span
                                                x-text="filteredSalesOrders.find(so => so.id == selectedSalesOrder)?.nomor || 'SO tidak ditemukan'"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Quick Tips --}}
                            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tips:</h4>
                                <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500 flex-shrink-0"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Pilih customer terlebih dahulu
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500 flex-shrink-0"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Sales Order bersifat opsional
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500 flex-shrink-0"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Jumlah harus lebih dari 0
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 text-green-500 flex-shrink-0"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Pilih metode pembayaran
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>

    {{-- Include jQuery and Select2 --}}
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

</x-app-layout>
