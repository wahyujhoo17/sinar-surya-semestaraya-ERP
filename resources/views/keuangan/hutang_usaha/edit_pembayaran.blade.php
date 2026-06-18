<x-app-layout :breadcrumbs="[
    ['name' => 'Keuangan', 'url' => '#'],
    ['name' => 'Hutang Usaha', 'url' => route('keuangan.hutang-usaha.index')],
    ['name' => 'Edit Pembayaran', 'url' => '#'],
]" :currentPage="'Edit Pembayaran Hutang'">

    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @push('styles')
            <style>
                .form-card { transition: all 0.3s ease; }
                .form-card:hover { box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -2px rgba(0,0,0,.05); }
                .payment-method-card { transition: all 0.3s ease; }
                .payment-method-card:hover { transform: translateY(-2px); }
                .info-badge {
                    position: absolute; top: -10px; right: -10px;
                    background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%);
                    color: white; border-radius: 9999px; padding: 0.25rem 0.75rem;
                    font-size: 0.75rem; font-weight: 600;
                    box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
                }
            </style>
        @endpush

        <form action="{{ route('keuangan.pembayaran-hutang.update', $payment->id) }}" method="POST"
            x-data="{
                metode: '{{ old('metode_pembayaran', $payment->metode_pembayaran) }}',
                sisaHutang: {{ $sisaHutang + $payment->jumlah }},
                jumlahLama: {{ $payment->jumlah }},
                showValidationError: false,
                errorMessage: '',
                isSubmitting: false,
                init() {
                    this.$nextTick(() => {
                        if (this.metode === 'kas') this.showKasFields();
                        else if (this.metode === 'bank') this.showBankFields();
                        else this.hideAllFields();
                    });
                    this.$watch('metode', (value) => {
                        if (value === 'kas') this.showKasFields();
                        else if (value === 'bank') this.showBankFields();
                        else this.hideAllFields();
                    });
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
                    let amount = parseFloat(document.getElementById('jumlah').value || 0);
                    let maxAllowed = this.sisaHutang;
                    if (amount <= 0) {
                        this.showValidationError = true;
                        this.errorMessage = 'Jumlah pembayaran harus lebih dari 0.';
                        return false;
                    }
                    if (amount > maxAllowed) {
                        this.showValidationError = true;
                        this.errorMessage = `Jumlah tidak boleh melebihi sisa hutang (Rp ${new Intl.NumberFormat('id-ID').format(maxAllowed)}).`;
                        return false;
                    }
                    this.showValidationError = false;
                    this.errorMessage = '';
                    return true;
                },
                formatRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
                }
            }"
            @submit.prevent="if(validatePayment()) { isSubmitting = true; $el.submit(); }">
            @csrf
            @method('PUT')

            {{-- Header --}}
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 mb-6 form-card">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between md:items-center gap-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-750">
                    <div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-gradient-to-br from-amber-500 to-orange-600 h-10 w-1 rounded-full mr-3"></div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Pembayaran Hutang</h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Nomor: <span class="font-semibold text-amber-700 dark:text-amber-400">{{ $payment->nomor }}</span>
                                    &mdash; PO: <span class="font-semibold">{{ $po->nomor }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('keuangan.hutang-usaha.show', $po->id) }}"
                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            Batal
                        </a>
                        <button type="submit" x-bind:disabled="isSubmitting"
                            class="px-4 py-2 bg-amber-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors">
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                        </button>
                    </div>
                </div>

                {{-- Info Ringkasan Hutang --}}
                <div class="px-6 pt-5 pb-2">
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl p-5 mb-5 shadow-sm relative">
                        <div class="info-badge">Edit Mode</div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            Informasi Hutang — {{ $po->supplier->nama ?? '-' }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-amber-100 dark:border-amber-900/50 flex flex-col">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Hutang PO</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                                    Rp {{ number_format($po->total, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-amber-100 dark:border-amber-900/50 flex flex-col">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Jumlah Pembayaran Ini (lama)</span>
                                <span class="text-lg font-bold text-amber-600 dark:text-amber-400 mt-1">
                                    Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-amber-100 dark:border-amber-900/50 flex flex-col">
                                <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Maks. Bisa Diisi (sisa + ini)</span>
                                <span class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mt-1"
                                    x-text="formatRupiah(sisaHutang)">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="px-6 py-5">
                    {{-- Error Messages --}}
                    @if ($errors->any())
                        <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-lg">
                            <ul class="list-disc pl-4 space-y-1 text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Left Column --}}
                        <div class="space-y-5">

                            {{-- Nomor (readonly) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Pembayaran
                                    <span class="ml-1 text-xs text-gray-400">(tidak dapat diubah)</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" value="{{ $payment->nomor }}" readonly
                                        class="bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 block w-full rounded-md shadow-sm pl-10 cursor-not-allowed">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <input type="hidden" name="nomor" value="{{ $payment->nomor }}">
                            </div>

                            {{-- PO (readonly) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Purchase Order
                                    <span class="ml-1 text-xs text-gray-400">(tidak dapat diubah)</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" value="{{ $po->nomor }} — Rp {{ number_format($po->total, 0, ',', '.') }}" readonly
                                        class="bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 block w-full rounded-md shadow-sm pl-10 cursor-not-allowed">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm6 2a1 1 0 10-2 0v1H7a1 1 0 100 2h2v1a1 1 0 102 0v-1h2a1 1 0 100-2h-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <input type="hidden" name="purchase_order_id" value="{{ $po->id }}">
                                <input type="hidden" name="supplier_id" value="{{ $po->supplier_id }}">
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="date" id="tanggal" name="tanggal" required
                                        value="{{ old('tanggal', $payment->tanggal ? \Carbon\Carbon::parse($payment->tanggal)->format('Y-m-d') : '') }}"
                                        class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 block w-full rounded-md shadow-sm pl-10">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                @error('tanggal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jumlah --}}
                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                                    </div>
                                    <input type="number" id="jumlah" name="jumlah" required
                                        value="{{ old('jumlah', $payment->jumlah) }}"
                                        min="1"
                                        class="pl-10 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 block w-full rounded-md shadow-sm"
                                        @input="validatePayment()">
                                </div>
                                <div x-show="showValidationError" x-cloak class="mt-2">
                                    <p class="text-sm text-red-600 dark:text-red-400" x-text="errorMessage"></p>
                                </div>
                                @error('jumlah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Right Column — Metode Pembayaran --}}
                        <div class="space-y-4">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-750 p-6 rounded-xl border border-gray-200 dark:border-gray-700">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                                    </svg>
                                    Metode Pembayaran
                                </h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="payment-method-card cursor-pointer bg-white dark:bg-gray-800 rounded-lg border-2 p-4 flex flex-col items-center"
                                        :class="{ 'border-amber-500 dark:border-amber-400 shadow-md': metode === 'kas', 'border-gray-200 dark:border-gray-700': metode !== 'kas' }">
                                        <input type="radio" name="metode_pembayaran" value="kas" class="sr-only" x-model="metode">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3"
                                            :class="{ 'text-amber-500': metode === 'kas', 'text-gray-400 dark:text-gray-500': metode !== 'kas' }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        <div class="font-medium text-sm"
                                            :class="{ 'text-amber-600 dark:text-amber-400': metode === 'kas', 'text-gray-900 dark:text-white': metode !== 'kas' }">Kas</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">Pembayaran langsung</div>
                                    </label>

                                    <label class="payment-method-card cursor-pointer bg-white dark:bg-gray-800 rounded-lg border-2 p-4 flex flex-col items-center"
                                        :class="{ 'border-amber-500 dark:border-amber-400 shadow-md': metode === 'bank', 'border-gray-200 dark:border-gray-700': metode !== 'bank' }">
                                        <input type="radio" name="metode_pembayaran" value="bank" class="sr-only" x-model="metode">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3"
                                            :class="{ 'text-amber-500': metode === 'bank', 'text-gray-400 dark:text-gray-500': metode !== 'bank' }"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <div class="font-medium text-sm"
                                            :class="{ 'text-amber-600 dark:text-amber-400': metode === 'bank', 'text-gray-900 dark:text-white': metode !== 'bank' }">Transfer Bank</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 text-center mt-1">Via transfer bank</div>
                                    </label>
                                </div>
                                @error('metode_pembayaran') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Kas Fields --}}
                            <div class="kas-field" style="display: none;">
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-4 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                        </svg>
                                        Pilih Akun Kas
                                    </h3>
                                    <div class="relative rounded-md shadow-sm">
                                        <select id="kas_id" name="kas_id" x-bind:required="metode === 'kas'"
                                            class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 block w-full rounded-md shadow-sm pl-10">
                                            <option value="">-- Pilih Akun Kas --</option>
                                            @foreach ($kasAccounts as $kas)
                                                <option value="{{ $kas->id }}"
                                                    {{ old('kas_id', $payment->kas_id) == $kas->id ? 'selected' : '' }}>
                                                    {{ $kas->nama }} &mdash; Saldo: Rp {{ number_format($kas->saldo, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    @error('kas_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Bank Fields --}}
                            <div class="bank-field" style="display: none;">
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                                    <h3 class="font-medium text-gray-900 dark:text-white mb-4 text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Detail Transfer Bank
                                    </h3>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="rekening_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Rekening Bank <span x-show="metode === 'bank'" class="text-red-500">*</span>
                                            </label>
                                            <div class="relative rounded-md shadow-sm">
                                                <select id="rekening_id" name="rekening_id" x-bind:required="metode === 'bank'"
                                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 block w-full rounded-md shadow-sm pl-10">
                                                    <option value="">-- Pilih Rekening Bank --</option>
                                                    @foreach ($bankAccounts as $bank)
                                                        <option value="{{ $bank->id }}"
                                                            {{ old('rekening_id', $payment->rekening_id) == $bank->id ? 'selected' : '' }}>
                                                            {{ $bank->nama_bank }} — {{ $bank->nomor_rekening }} — Saldo: Rp {{ number_format($bank->saldo, 0, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('rekening_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label for="no_referensi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                No. Referensi Transfer
                                            </label>
                                            <div class="relative rounded-md shadow-sm">
                                                <input type="text" id="no_referensi" name="no_referensi"
                                                    value="{{ old('no_referensi', $payment->no_referensi) }}"
                                                    placeholder="Nomor referensi / bukti transfer"
                                                    class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 block w-full rounded-md shadow-sm pl-10">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                    </svg>
                                                </div>
                                            </div>
                                            @error('no_referensi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="mt-6">
                        <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                        <div class="relative rounded-md shadow-sm">
                            <textarea id="catatan" name="catatan" rows="3"
                                class="shadow-sm block w-full focus:ring-amber-500 focus:border-amber-500 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md pl-10"
                                placeholder="Catatan tambahan untuk pembayaran ini...">{{ old('catatan', $payment->catatan) }}</textarea>
                            <div class="absolute top-3 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Footer --}}
                <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-800 dark:to-gray-750 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <svg class="h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            Jurnal akuntansi akan diperbarui secara otomatis setelah penyimpanan.
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('keuangan.hutang-usaha.show', $po->id) }}"
                                class="px-4 py-2.5 flex items-center bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                <svg class="h-4 w-4 mr-1.5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Batal
                            </a>
                            <button type="submit" x-bind:disabled="isSubmitting"
                                class="px-4 py-2.5 flex items-center bg-amber-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors disabled:opacity-60">
                                <svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
