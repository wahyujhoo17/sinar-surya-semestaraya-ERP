<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Hutang Usaha', 'url' => route('keuangan.pembayaran-hutang.index')],
    ['name' => 'Buat Pembayaran', 'url' => '#'],
]" :currentPage="'Buat Pembayaran Hutang'">

    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @push('styles')
            <style>
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
                    background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
                    color: white;
                    border-radius: 9999px;
                    padding: 0.25rem 0.75rem;
                    font-size: 0.75rem;
                    font-weight: 600;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                }

                .info-card {
                    position: relative;
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                }
            </style>
        @endpush

        <form action="{{ route('keuangan.pembayaran-hutang.store') }}" method="POST" id="payment-form"
            x-data="{
                metode: '{{ old('metode_pembayaran', '') }}',
                currentSisaHutang: {{ old('jumlah', $sisaHutang ?? 0) }},
                totalHutang: {{ $totalHutang ?? ($sisaHutang ?? 0) }},
                totalDibayar: {{ $totalHutang ?? ($sisaHutang ?? 0) }} - {{ old('jumlah', $sisaHutang ?? 0) }},
                showValidationError: false,
                errorMessage: '',
                isSubmitting: false,
                init() {
                    this.$watch('metode', (value) => {
                        if (value === 'kas') {
                            this.showKasFields();
                        } else if (value === 'bank') {
                            this.showBankFields();
                        } else {
                            this.hideAllFields();
                        }
                    });
            
                    // Set initial state
                    if (this.metode) {
                        this.$nextTick(() => {
                            if (this.metode === 'kas') {
                                this.showKasFields();
                            } else if (this.metode === 'bank') {
                                this.showBankFields();
                            }
                        });
                    }
                },
                showKasFields() {
                    document.querySelectorAll('.kas-field').forEach(el => el.style.display = 'block');
                    document.querySelectorAll('.bank-field').forEach(el => el.style.display = 'none');
                },
                showBankFields() {
                    document.querySelectorAll('.kas-field').forEach(el => el.style.display = 'none');
                    document.querySelectorAll('.bank-field').forEach(el => el.style.display = 'block');
                },
                hideAllFields() {
                    document.querySelectorAll('.kas-field').forEach(el => el.style.display = 'none');
                    document.querySelectorAll('.bank-field').forEach(el => el.style.display = 'none');
                },
                validatePayment() {
                    let amount = parseFloat(document.getElementById('jumlah').value);
                    if (amount > this.currentSisaHutang) {
                        this.showValidationError = true;
                        this.errorMessage = `Jumlah pembayaran tidak boleh melebihi sisa hutang (Rp ${new Intl.NumberFormat('id-ID').format(this.currentSisaHutang)})`;
                        return false;
                    }
                    this.showValidationError = false;
                    this.errorMessage = '';
                    return true;
                },
                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(angka);
                },
                updateJumlah() {
                    let amount = parseFloat(document.getElementById('jumlah').value || 0);
                    this.totalDibayar = this.totalHutang - amount;
                    this.validatePayment();
                }
            }" @submit.prevent="if(validatePayment()) { isSubmitting = true; $el.submit(); }">
            @csrf

            {{-- Header Section --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 mb-6 form-card">
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750">
                    <div>
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Pembayaran Hutang</h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Formulir pembayaran hutang
                                    usaha ke supplier PT Sinar Surya Semestaraya</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('keuangan.hutang-usaha.index') }}"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Simpan Pembayaran
                        </button>
                    </div>
                </div>

                {{-- Payment Summary Section --}}
                <div class="px-6 pt-5 pb-2">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 rounded-xl p-5 mb-5 shadow-sm relative">
                        <div class="summary-badge">Ringkasan</div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Informasi Hutang
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-blue-100 dark:border-blue-900/50 flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total Hutang</span>
                                <span x-text="formatRupiah(totalHutang)"
                                    class="text-xl font-bold text-gray-900 dark:text-white mt-1"></span>
                            </div>
                            <div
                                class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-blue-100 dark:border-blue-900/50 flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Tagihan Belum Dibayar</span>
                                <span x-text="formatRupiah(currentSisaHutang)"
                                    class="text-xl font-bold text-blue-600 dark:text-blue-400 mt-1"></span>
                            </div>
                            <div
                                class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-blue-100 dark:border-blue-900/50 flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Setelah Pembayaran</span>
                                <span x-text="formatRupiah(totalDibayar)"
                                    class="text-xl font-bold text-green-600 dark:text-green-400 mt-1"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Section - Basic Details --}}
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Left Column --}}
                        <div class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="nomor"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nomor Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" id="nomor" name="nomor"
                                            value="{{ old('nomor', $paymentNumber) }}" readonly
                                            class="bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md shadow-sm pl-10">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('nomor')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tanggal Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="date" id="tanggal" name="tanggal"
                                            value="{{ old('tanggal', date('Y-m-d')) }}" required
                                            class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md shadow-sm pl-10">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="supplier_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Supplier <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <select id="supplier_id" name="supplier_id"
                                        @if ($po) disabled @endif
                                        class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md shadow-sm pl-10">
                                        <option value="">-- Pilih Supplier --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id', $po->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                        </svg>
                                    </div>
                                </div>
                                @if ($po)
                                    <input type="hidden" name="supplier_id" value="{{ $po->supplier_id }}">
                                @endif
                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="purchase_order_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Purchase Order <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <select id="purchase_order_id" name="purchase_order_id"
                                        @if ($po) disabled @endif
                                        class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md shadow-sm pl-10">
                                        @if ($po)
                                            <option value="{{ $po->id }}" selected>{{ $po->nomor }} -
                                                {{ number_format($po->total, 0, ',', '.') }}</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm6 2a1 1 0 10-2 0v1H7a1 1 0 100 2h2v1a1 1 0 102 0v-1h2a1 1 0 100-2h-2V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @if ($po)
                                    <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">
                                @endif
                                @error('purchase_order_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jumlah"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                    </div>
                                    <input type="number" id="jumlah" name="jumlah"
                                        value="{{ old('jumlah', $sisaHutang) }}" required
                                        class="pl-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md shadow-sm"
                                        @input="updateJumlah()" max="currentSisaHutang">
                                </div>
                                <div x-show="showValidationError" x-cloak class="mt-2">
                                    <p class="text-sm text-red-600 dark:text-red-500" x-text="errorMessage"></p>
                                </div>
                                @error('jumlah')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div>
                            <div
                                class="payment-section bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750 p-6 rounded-xl border border-gray-200 dark:border-gray-700 mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                        <path fill-rule="evenodd"
                                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Metode Pembayaran
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label
                                        class="payment-method-card cursor-pointer bg-white dark:bg-gray-800 rounded-lg border-2 p-4 flex flex-col items-center"
                                        :class="{ 'border-primary-500 dark:border-primary-400 shadow-md': metode === 'kas', 'border-gray-200 dark:border-gray-700': metode !== 'kas' }">
                                        <input type="radio" name="metode_pembayaran" value="kas"
                                            class="sr-only" x-model="metode">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3"
                                            :class="{ 'text-primary-500': metode === 'kas', 'text-gray-400 dark:text-gray-500': metode !== 'kas' }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        <div class="font-medium"
                                            :class="{ 'text-primary-600 dark:text-primary-400': metode === 'kas', 'text-gray-900 dark:text-white': metode !== 'kas' }">
                                            Kas</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">
                                            Pembayaran langsung melalui kas</div>
                                    </label>

                                    <label
                                        class="payment-method-card cursor-pointer bg-white dark:bg-gray-800 rounded-lg border-2 p-4 flex flex-col items-center"
                                        :class="{ 'border-primary-500 dark:border-primary-400 shadow-md': metode === 'bank', 'border-gray-200 dark:border-gray-700': metode !== 'bank' }">
                                        <input type="radio" name="metode_pembayaran" value="bank"
                                            class="sr-only" x-model="metode">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3"
                                            :class="{ 'text-primary-500': metode === 'bank', 'text-gray-400 dark:text-gray-500': metode !== 'bank' }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <div class="font-medium"
                                            :class="{ 'text-primary-600 dark:text-primary-400': metode === 'bank', 'text-gray-900 dark:text-white': metode !== 'bank' }">
                                            Transfer Bank</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">
                                            Pembayaran melalui transfer bank</div>
                                    </label>
                                </div>

                                @error('metode_pembayaran')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="kas-field mt-4" style="display: none;">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        Detail Pembayaran Kas
                                    </h3>

                                    <div>
                                        <label for="kas_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Akun Kas <span x-show="metode === 'kas'" class="text-red-500">*</span>
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <select id="kas_id" name="kas_id"
                                                x-bind:required="metode === 'kas'"
                                                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md shadow-sm pl-10">
                                                <option value="">-- Pilih Akun Kas --</option>
                                                @foreach ($kasAccounts as $kas)
                                                    <option value="{{ $kas->id }}"
                                                        {{ old('kas_id') == $kas->id ? 'selected' : '' }}>
                                                        {{ $kas->nama }} - Saldo: Rp
                                                        {{ number_format($kas->saldo, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        @error('kas_id')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="bank-field mt-4" style="display: none;">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Detail Pembayaran Bank
                                    </h3>

                                    <div class="space-y-4">
                                        <div>
                                            <label for="rekening_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Rekening Bank <span x-show="metode === 'bank'"
                                                    class="text-red-500">*</span>
                                            </label>
                                            <div class="relative rounded-md shadow-sm">
                                                <select id="rekening_id" name="rekening_id"
                                                    x-bind:required="metode === 'bank'"
                                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md shadow-sm pl-10">
                                                    <option value="">-- Pilih Rekening Bank --</option>
                                                    @foreach ($bankAccounts as $bank)
                                                        <option value="{{ $bank->id }}"
                                                            {{ old('rekening_id') == $bank->id ? 'selected' : '' }}>
                                                            {{ $bank->nama_bank }} - {{ $bank->nomor_rekening }} -
                                                            Saldo: Rp {{ number_format($bank->saldo, 0, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('rekening_id')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="no_referensi"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                No. Referensi <span x-show="metode === 'bank'"
                                                    class="text-red-500">*</span>
                                            </label>
                                            <div class="relative rounded-md shadow-sm">
                                                <input type="text" id="no_referensi" name="no_referensi"
                                                    value="{{ old('no_referensi') }}"
                                                    x-bind:required="metode === 'bank'"
                                                    placeholder="Nomor referensi transfer bank"
                                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500 block w-full rounded-md shadow-sm pl-10">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('no_referensi')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Catatan
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <textarea id="catatan" name="catatan" rows="4"
                                class="shadow-sm block w-full focus:ring-primary-500 focus:border-primary-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md pl-10"
                                placeholder="Catatan tambahan untuk pembayaran ini...">{{ old('catatan') }}</textarea>
                            <div class="absolute top-3 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div
                    class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750 border-t border-gray-200 dark:border-gray-700">
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
                            <span x-show="showValidationError" class="text-sm text-red-600 dark:text-red-400">
                                Harap periksa form pembayaran
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="{{ route('keuangan.hutang-usaha.index') }}"
                                class="w-full sm:w-auto order-2 sm:order-1 px-4 py-2.5 flex justify-center items-center bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit" x-bind:disabled="isSubmitting"
                                class="w-full sm:w-auto order-1 sm:order-2 px-4 py-2.5 flex justify-center items-center bg-primary-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-colors">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Pembayaran'">Simpan
                                    Pembayaran</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize select2 if available
                if (typeof $.fn.select2 !== 'undefined') {
                    $('#supplier_id').select2({
                        placeholder: '-- Pilih Supplier --',
                        theme: document.documentElement.classList.contains('dark') ? 'select2-dark' :
                            'select2-light',
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-clear'
                    });
                    $('#purchase_order_id').select2({
                        placeholder: '-- Pilih Purchase Order --',
                        theme: document.documentElement.classList.contains('dark') ? 'select2-dark' :
                            'select2-light',
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-clear'
                    });
                    $('#kas_id').select2({
                        placeholder: '-- Pilih Akun Kas --',
                        theme: document.documentElement.classList.contains('dark') ? 'select2-dark' :
                            'select2-light',
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-clear'
                    });
                    $('#rekening_id').select2({
                        placeholder: '-- Pilih Rekening Bank --',
                        theme: document.documentElement.classList.contains('dark') ? 'select2-dark' :
                            'select2-light',
                        width: '100%',
                        dropdownCssClass: 'select2-dropdown-clear'
                    });

                    // Apply custom styles to match our modern design
                    $(".select2-selection").addClass(
                        "rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700");

                    // Add icons to Select2 dropdowns
                    setTimeout(function() {
                        $('.select2-selection__rendered').each(function() {
                            if (!$(this).find('.select2-icon').length) {
                                $(this).prepend('<span class="select2-icon"></span>');
                            }
                        });
                    }, 100);
                }

                // Dynamic PO loading when supplier changes
                $('#supplier_id').on('change', function() {
                    const supplierId = $(this).val();
                    if (supplierId) {
                        // Show loading indicator
                        $('#purchase_order_id').html('<option value="">Loading...</option>');

                        $.ajax({
                            url: '/api/supplier/' + supplierId + '/purchase-orders',
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                let options =
                                    '<option value="">-- Pilih Purchase Order --</option>';

                                data.forEach(function(po) {
                                    options += `<option value="${po.id}" data-sisa="${po.sisa_hutang}" data-total="${po.total}">
                                    ${po.nomor} - Total: Rp ${formatNumber(po.total)} - Sisa: Rp ${formatNumber(po.sisa_hutang)}
                                </option>`;
                                });

                                $('#purchase_order_id').html(options);
                            },
                            error: function() {
                                $('#purchase_order_id').html(
                                    '<option value="">Error loading data</option>');
                            }
                        });
                    } else {
                        $('#purchase_order_id').html('<option value="">-- Pilih Purchase Order --</option>');
                    }
                });

                // Set payment amount based on selected PO
                $('#purchase_order_id').on('change', function() {
                    const selected = $(this).find(':selected');
                    const sisaHutang = parseFloat(selected.data('sisa') || 0);
                    const totalHutang = parseFloat(selected.data('total') || 0);

                    if (sisaHutang) {
                        $('#jumlah').val(sisaHutang);

                        // Update the Alpine.js state
                        const paymentForm = document.getElementById('payment-form').__x.$data;
                        paymentForm.currentSisaHutang = sisaHutang;
                        paymentForm.totalHutang = totalHutang;
                        paymentForm.totalDibayar = totalHutang - sisaHutang;
                        paymentForm.showValidationError = false;
                    }
                });

                // Validate payment amount on input
                $('#jumlah').on('input', function() {
                    const amount = parseFloat($(this).val() || 0);
                    const paymentForm = document.getElementById('payment-form').__x.$data;

                    paymentForm.totalDibayar = paymentForm.totalHutang - amount;

                    if (amount > paymentForm.currentSisaHutang) {
                        paymentForm.showValidationError = true;
                        paymentForm.errorMessage =
                            `Jumlah pembayaran tidak boleh melebihi sisa hutang (Rp ${formatNumber(paymentForm.currentSisaHutang)})`;
                    } else {
                        paymentForm.showValidationError = false;
                        paymentForm.errorMessage = '';
                    }
                });

                // Format number helper function
                function formatNumber(num) {
                    return new Intl.NumberFormat('id-ID').format(num);
                }
            });
        </script>
    @endpush
</x-app-layout>
