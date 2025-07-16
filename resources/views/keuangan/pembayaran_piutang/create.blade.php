<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Piutang Usaha', 'url' => route('keuangan.piutang-usaha.index')],
    ['name' => $invoice ? 'Proses Pembayaran' : 'Buat Pembayaran', 'url' => '#'],
]" :currentPage="$invoice ? 'Proses Pembayaran Piutang' : 'Buat Pembayaran Piutang Baru'">

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
                    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
                    /* Green gradient */
                    color: white;
                    border-radius: 9999px;
                    padding: 0.25rem 0.75rem;
                    font-size: 0.75rem;
                    font-weight: 600;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                }
            </style>
        @endpush

        <form action="{{ route('keuangan.pembayaran-piutang.store') }}" method="POST" id="payment-form"
            x-data="{
                metode: '{{ old('metode_pembayaran', '') }}',
                currentSisaPiutang: {{ $sisaPiutang ?? 0 }},
                totalPiutang: {{ $invoice->total ?? ($sisaPiutang ?? 0) }},
                sisaSetelahPembayaran: {{ $sisaPiutang ?? 0 }} - {{ old('jumlah_pembayaran', 0) }},
                showValidationError: false,
                errorMessage: '',
                isSubmitting: false,
                init() {
                    // Initialize sisaSetelahPembayaran properly
                    this.$nextTick(() => {
                        let currentAmount = parseFloat(document.getElementById('jumlah_pembayaran').value || 0);
                        this.sisaSetelahPembayaran = this.currentSisaPiutang - currentAmount;
                    });
            
                    this.$watch('metode', (value) => {
                        if (value === 'Kas') {
                            this.showKasFields();
                        } else if (value === 'Bank Transfer') {
                            this.showBankFields();
                        } else {
                            this.hideAllFields();
                        }
                    });
            
                    if (this.metode) {
                        this.$nextTick(() => {
                            if (this.metode === 'Kas') {
                                this.showKasFields();
                            } else if (this.metode === 'Bank Transfer') {
                                this.showBankFields();
                            }
                        });
                    }
                },
                showKasFields() {
                    document.getElementById('kas_field').style.display = 'block';
                    document.getElementById('bank_field').style.display = 'none';
                    document.getElementById('kas_id').required = true;
                    document.getElementById('rekening_bank_id').required = false;
                },
                showBankFields() {
                    document.getElementById('kas_field').style.display = 'none';
                    document.getElementById('bank_field').style.display = 'block';
                    document.getElementById('kas_id').required = false;
                    document.getElementById('rekening_bank_id').required = true;
                },
                hideAllFields() {
                    document.getElementById('kas_field').style.display = 'none';
                    document.getElementById('bank_field').style.display = 'none';
                    document.getElementById('kas_id').required = false;
                    document.getElementById('rekening_bank_id').required = false;
                },
                validatePayment() {
                    let amount = parseFloat(document.getElementById('jumlah_pembayaran').value || 0);
                    if ({{ $invoice ? 'true' : 'false' }} && amount > this.currentSisaPiutang) {
                        this.showValidationError = true;
                        this.errorMessage = `Jumlah pembayaran tidak boleh melebihi sisa piutang (Rp ${new Intl.NumberFormat('id-ID').format(this.currentSisaPiutang)})`;
                        return false;
                    }
                    if (amount <= 0) {
                        this.showValidationError = true;
                        this.errorMessage = 'Jumlah pembayaran harus lebih dari 0';
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
                    let amount = parseFloat(document.getElementById('jumlah_pembayaran').value || 0);
                    if (isNaN(amount)) amount = 0;
            
                    if ({{ $invoice ? 'true' : 'false' }}) {
                        this.sisaSetelahPembayaran = this.currentSisaPiutang - amount; // If invoice, calculate from current sisa piutang
                    } else {
                        this.sisaSetelahPembayaran = this.totalPiutang - amount; // If no invoice, this is effectively total piutang - amount
                    }
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
                                class="flex-shrink-0 bg-gradient-to-br from-green-500 to-green-600 h-10 w-1 rounded-full mr-3">
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $invoice ? 'Proses Pembayaran Piutang' : 'Buat Pembayaran Piutang Baru' }}
                                </h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Formulir pembayaran piutang usaha dari customer PT Sinar Surya Semestaraya
                                </p>
                                @if ($invoice)
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Invoice: <span
                                            class="font-semibold text-green-600 dark:text-green-400">{{ $invoice->nomor }}</span>
                                        | Customer: {{ $invoice->customer->company ?? $invoice->customer->nama }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ $invoice ? route('keuangan.piutang-usaha.show', $invoice->id) : route('keuangan.piutang-usaha.index') }}"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit" :disabled="isSubmitting"
                            class="px-4 py-2 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition-colors duration-200 disabled:opacity-50">
                            <span x-show="!isSubmitting">Simpan Pembayaran</span>
                            <span x-show="isSubmitting">Menyimpan...</span>
                        </button>
                    </div>
                </div>

                {{-- Payment Summary Section --}}
                @if ($invoice)
                    <div class="px-6 pt-5 pb-2">
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-xl p-5 mb-5 shadow-sm relative">
                            <div class="summary-badge">Ringkasan</div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Informasi Piutang
                            </h3>

                            {{-- Breakdown pembayaran dan potongan --}}
                            @if ($invoice->uang_muka_terapkan > 0 || $invoice->kredit_terapkan > 0 || $invoice->pembayaranPiutang->count() > 0)
                                <div
                                    class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <h4
                                        class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Rincian Pembayaran & Potongan
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                        @if ($invoice->uang_muka_terapkan > 0)
                                            <div class="flex justify-between items-center">
                                                <span class="text-blue-700 dark:text-blue-300">Uang Muka
                                                    Diterapkan:</span>
                                                <span
                                                    class="font-semibold text-blue-800 dark:text-blue-200">{{ number_format($invoice->uang_muka_terapkan, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @if ($invoice->kredit_terapkan > 0)
                                            <div class="flex justify-between items-center">
                                                <span class="text-blue-700 dark:text-blue-300">Nota Kredit
                                                    Diterapkan:</span>
                                                <span
                                                    class="font-semibold text-blue-800 dark:text-blue-200">{{ number_format($invoice->kredit_terapkan, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        @if ($invoice->pembayaranPiutang->count() > 0)
                                            <div class="flex justify-between items-center">
                                                <span class="text-blue-700 dark:text-blue-300">Pembayaran
                                                    Sebelumnya:</span>
                                                <span
                                                    class="font-semibold text-blue-800 dark:text-blue-200">{{ number_format($invoice->pembayaranPiutang->sum('jumlah'), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div
                                    class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-green-100 dark:border-green-900/50 flex flex-col">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Piutang
                                        (Invoice)</span>
                                    <span x-text="formatRupiah(totalPiutang)"
                                        class="text-xl font-bold text-gray-900 dark:text-white mt-1"></span>
                                </div>
                                <div
                                    class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-green-100 dark:border-green-900/50 flex flex-col">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Tagihan Belum
                                        Dibayar</span>
                                    <span x-text="formatRupiah(currentSisaPiutang)"
                                        class="text-xl font-bold text-red-600 dark:text-red-400 mt-1"></span>
                                </div>
                                <div
                                    class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-green-100 dark:border-green-900/50 flex flex-col">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sisa Setelah Pembayaran
                                        Ini</span>
                                    <span x-text="formatRupiah(sisaSetelahPembayaran)"
                                        class="text-xl font-bold text-green-600 dark:text-green-400 mt-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form Section - Basic Details --}}
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Left Column --}}
                        <div class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="nomor_pembayaran"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nomor Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" id="nomor_pembayaran" name="nomor_pembayaran"
                                            value="{{ old('nomor_pembayaran', $nomorPembayaran) }}" readonly
                                            class="bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm pl-10">
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
                                    @error('nomor_pembayaran')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tanggal_pembayaran"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Tanggal Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="date" id="tanggal_pembayaran" name="tanggal_pembayaran"
                                            value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}" required
                                            class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm pl-10">
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
                                    @error('tanggal_pembayaran')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            @if ($invoice)
                                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                <input type="hidden" name="customer_id" value="{{ $invoice->customer_id }}">
                                <div>
                                    <label for="customer_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Customer
                                    </label>
                                    <input type="text" id="customer_name"
                                        value="{{ $invoice->customer->company ?? $invoice->customer->nama }}" readonly
                                        class="bg-gray-50 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm">
                                </div>
                            @else
                                <div>
                                    <label for="customer_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Customer <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <select id="customer_id" name="customer_id" required
                                            class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm pl-10">
                                            <option value="">-- Pilih Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('customer_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="invoice_id_optional"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Invoice (Opsional)
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <select id="invoice_id_optional" name="invoice_id"
                                            class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm pl-10">
                                            <option value="">-- Pilih Invoice --</option>
                                            {{-- TODO: Populate with customer's invoices via JS or load all if not too many --}}
                                        </select>
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm6 2a1 1 0 10-2 0v1H7a1 1 0 100 2h2v1a1 1 0 102 0v-1h2a1 1 0 100-2h-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih invoice jika
                                        pembayaran ini spesifik untuk satu invoice.</p>
                                    @error('invoice_id')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <div>
                                <label for="jumlah_pembayaran"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400">Rp</span>
                                    </div>
                                    <input type="number" id="jumlah_pembayaran" name="jumlah_pembayaran"
                                        value="{{ old('jumlah_pembayaran') }}" required min="0.01"
                                        step="0.01"
                                        class="pl-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm"
                                        @input="updateJumlah()" {{ $invoice ? 'max="' . $sisaPiutang . '"' : '' }}
                                        placeholder="{{ $sisaPiutang > 0 ? number_format($sisaPiutang, 0, ',', '.') : 'Masukkan jumlah pembayaran' }}">
                                </div>
                                <div x-show="showValidationError && {{ $invoice ? 'true' : 'false' }}" x-cloak
                                    class="mt-2">
                                    <p class="text-sm text-red-600 dark:text-red-500" x-text="errorMessage"></p>
                                </div>
                                @error('jumlah_pembayaran')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="space-y-5">
                            <div
                                class="payment-section bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                        <path fill-rule="evenodd"
                                            d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Metode Pembayaran <span class="text-red-500 ml-1">*</span>
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label
                                        class="payment-method-card cursor-pointer bg-white dark:bg-gray-800 rounded-lg border-2 p-4 flex flex-col items-center"
                                        :class="{ 'border-green-500 dark:border-green-400 shadow-md': metode === 'Kas', 'border-gray-200 dark:border-gray-700': metode !== 'Kas' }">
                                        <input type="radio" name="metode_pembayaran" value="Kas"
                                            class="sr-only" x-model="metode" required>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3"
                                            :class="{ 'text-green-500': metode === 'Kas', 'text-gray-400 dark:text-gray-500': metode !== 'Kas' }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        <div class="font-medium"
                                            :class="{ 'text-green-600 dark:text-green-400': metode === 'Kas', 'text-gray-900 dark:text-white': metode !== 'Kas' }">
                                            Kas</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">
                                            Pembayaran diterima melalui kas</div>
                                    </label>

                                    <label
                                        class="payment-method-card cursor-pointer bg-white dark:bg-gray-800 rounded-lg border-2 p-4 flex flex-col items-center"
                                        :class="{ 'border-green-500 dark:border-green-400 shadow-md': metode === 'Bank Transfer', 'border-gray-200 dark:border-gray-700': metode !== 'Bank Transfer' }">
                                        <input type="radio" name="metode_pembayaran" value="Bank Transfer"
                                            class="sr-only" x-model="metode" required>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3"
                                            :class="{ 'text-green-500': metode === 'Bank Transfer', 'text-gray-400 dark:text-gray-500': metode !== 'Bank Transfer' }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <div class="font-medium"
                                            :class="{ 'text-green-600 dark:text-green-400': metode === 'Bank Transfer', 'text-gray-900 dark:text-white': metode !== 'Bank Transfer' }">
                                            Transfer Bank</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">
                                            Pembayaran diterima via transfer bank</div>
                                    </label>
                                </div>
                                @error('metode_pembayaran')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="kas_field" style="display: none;">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        Detail Pembayaran Kas
                                    </h3>
                                    <div>
                                        <label for="kas_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Akun Kas <span x-show="metode === 'Kas'" class="text-red-500">*</span>
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <select id="kas_id" name="kas_id"
                                                x-bind:required="metode === 'Kas'"
                                                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm pl-10">
                                                <option value="">-- Pilih Akun Kas --</option>
                                                @foreach ($kasAccounts as $kas)
                                                    <option value="{{ $kas->id }}"
                                                        {{ old('kas_id') == $kas->id ? 'selected' : '' }}>
                                                        {{ $kas->nama }} (Saldo: Rp
                                                        {{ number_format($kas->saldo, 0, ',', '.') }})
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

                            <div id="bank_field" style="display: none;">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Detail Pembayaran Bank
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="rekening_bank_id"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Rekening Bank <span x-show="metode === 'Bank Transfer'"
                                                    class="text-red-500">*</span>
                                            </label>
                                            <div class="relative rounded-md shadow-sm">

                                                <select id="rekening_bank_id" name="rekening_bank_id"
                                                    x-bind:required="metode === 'Bank Transfer'"
                                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm pl-10">
                                                    <option value="">-- Pilih Rekening Bank --</option>
                                                    @foreach ($bankAccounts as $bank)
                                                        <option value="{{ $bank->id }}"
                                                            {{ old('rekening_bank_id') == $bank->id ? 'selected' : '' }}>
                                                            {{ $bank->nama_bank }} - {{ $bank->nomor_rekening }}
                                                            ({{ $bank->atas_nama }})
                                                            - Saldo: Rp
                                                            {{ number_format($bank->saldo, 0, ',', '.') }}
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
                                            @error('rekening_bank_id')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan (Opsional)
                                </label>
                                <textarea id="catatan" name="catatan" rows="3"
                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-green-500 focus:border-green-500 block w-full rounded-md shadow-sm">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Alpine.js handles the show/hide logic within its x-data block.
            // The old script is no longer needed here as Alpine.js manages the state.
            // If there's a need to populate invoices based on customer selection (for non-invoice specific payments):
            // document.addEventListener('DOMContentLoaded', function() {
            //     const customerSelect = document.getElementById('customer_id');
            //     const invoiceSelect = document.getElementById('invoice_id_optional');

            //     if (customerSelect && invoiceSelect) {
            //         customerSelect.addEventListener('change', function() {
            //             const customerId = this.value;
            //             invoiceSelect.innerHTML = '<option value="">-- Memuat Invoice --</option>';

            //             if (customerId) {
            //                 fetch(`/api/customers/${customerId}/invoices`) // Adjust API endpoint as needed
            //                     .then(response => response.json())
            //                     .then(data => {
            //                         invoiceSelect.innerHTML = '<option value="">-- Pilih Invoice --</option>';
            //                         data.forEach(invoice => {
            //                             invoiceSelect.add(new Option(`${invoice.nomor_invoice} (Rp ${new Intl.NumberFormat('id-ID').format(invoice.sisa_piutang || invoice.total)}))`, invoice.id));
            //                         });
            //                     })
            //                     .catch(error => {
            //                         console.error('Error fetching invoices:', error);
            //                         invoiceSelect.innerHTML = '<option value="">Gagal memuat invoice</option>';
            //                     });
            //             } else {
            //                 invoiceSelect.innerHTML = '<option value="">-- Pilih Customer Dahulu --</option>';
            //             }
            //         });
            //     }
            // });
        </script>
    @endpush

</x-app-layout>
