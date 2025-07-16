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
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    Tambah Uang Muka Penjualan
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    Buat transaksi uang muka penjualan baru dari customer
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('keuangan.uang-muka-penjualan.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
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
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('keuangan.uang-muka-penjualan.index') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Batal
                                    </a>
                                    <button type="button" @click="submitForm()"
                                        class="inline-flex items-center px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200"
                                        :disabled="isSubmitting"
                                        :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }">
                                        <svg x-show="!isSubmitting" class="w-4 h-4 mr-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <svg x-show="isSubmitting" x-cloak class="w-4 h-4 mr-2 animate-spin"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        <span x-show="!isSubmitting">Simpan Uang Muka</span>
                                        <span x-show="isSubmitting" x-cloak>Menyimpan...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div
                                        class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        Ringkasan Transaksi
                                    </h3>
                                </div>

                                <div class="space-y-4">
                                    <!-- Customer -->
                                    <div
                                        class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Customer
                                            </div>
                                        </div>
                                        <div class="font-semibold text-gray-900 dark:text-white text-sm"
                                            x-text="getSelectedCustomerText()">
                                            Belum dipilih
                                        </div>
                                    </div>

                                    <!-- Amount -->
                                    <div
                                        class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/30 rounded-lg border border-blue-200 dark:border-blue-700">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-blue-500 dark:text-blue-400 mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                </path>
                                            </svg>
                                            <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Jumlah
                                                Uang Muka</div>
                                        </div>
                                        <div class="text-lg font-bold text-blue-900 dark:text-blue-100"
                                            x-text="formatCurrency(jumlah)">
                                            Rp 0
                                        </div>
                                    </div>

                                    <!-- Payment Method -->
                                    <div
                                        class="p-4 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/30 rounded-lg border border-green-200 dark:border-green-700">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-green-500 dark:text-green-400 mr-2"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                </path>
                                            </svg>
                                            <div class="text-sm font-medium text-green-600 dark:text-green-400">Metode
                                                Pembayaran</div>
                                        </div>
                                        <div class="font-semibold text-green-900 dark:text-green-100 text-sm">
                                            <span x-show="!metode" class="text-gray-500 dark:text-gray-400">Belum
                                                dipilih</span>
                                            <span x-show="metode === 'kas'" class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                                Pembayaran Kas
                                            </span>
                                            <span x-show="metode === 'bank'" class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                    </path>
                                                </svg>
                                                Transfer Bank
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Sales Order -->
                                    <div x-show="selectedSalesOrder"
                                        class="p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/30 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                        <div class="flex items-center mb-2">
                                            <svg class="w-4 h-4 text-yellow-500 dark:text-yellow-400 mr-2"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <div class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Sales
                                                Order</div>
                                        </div>
                                        <div class="font-semibold text-yellow-900 dark:text-yellow-100 text-sm">
                                            <template x-if="selectedSalesOrder">
                                                <span x-text="getSelectedSalesOrderText()"></span>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tips -->
                                <div
                                    class="mt-6 p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/30 rounded-lg border border-indigo-200 dark:border-indigo-700">
                                    <div class="flex items-center mb-3">
                                        <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400 mr-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <h4 class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">Panduan
                                            Pengisian</h4>
                                    </div>
                                    <ul class="text-xs text-indigo-700 dark:text-indigo-300 space-y-1.5">
                                        <li class="flex items-start">
                                            <svg class="w-3 h-3 mt-0.5 mr-2 text-indigo-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Pilih customer terlebih dahulu
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-3 h-3 mt-0.5 mr-2 text-indigo-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Sales Order bersifat opsional
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-3 h-3 mt-0.5 mr-2 text-indigo-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Jumlah harus lebih dari Rp 0
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-3 h-3 mt-0.5 mr-2 text-indigo-500" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Pilih metode pembayaran yang sesuai
                                        </li>
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
                            console.log('Customer selected:', this.selectedCustomer); // Debug
                        });

                        // Set initial value if exists
                        if (this.selectedCustomer) {
                            $('#customer_id').val(this.selectedCustomer).trigger('change');
                        }
                    },

                    handlePaymentMethodChange(value) {
                        console.log('Payment method changed to:', value); // Debug
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
                    },

                    submitForm() {
                        if (this.isSubmitting) return;

                        console.log('Submit form called'); // Debug

                        // Basic client-side validation
                        if (!this.selectedCustomer) {
                            alert('Silakan pilih customer terlebih dahulu');
                            return;
                        }

                        if (!this.jumlah || this.jumlah <= 0) {
                            alert('Jumlah uang muka harus lebih dari 0');
                            return;
                        }

                        if (!this.metode) {
                            alert('Silakan pilih metode pembayaran');
                            return;
                        }

                        if (this.metode === 'kas') {
                            const kasSelect = document.getElementById('kas_id');
                            if (!kasSelect || !kasSelect.value) {
                                alert('Silakan pilih kas');
                                return;
                            }
                        }

                        if (this.metode === 'bank') {
                            const bankSelect = document.getElementById('rekening_bank_id');
                            if (!bankSelect || !bankSelect.value) {
                                alert('Silakan pilih rekening bank');
                                return;
                            }
                        }

                        console.log('Validation passed, submitting form'); // Debug

                        // Set submitting state
                        this.isSubmitting = true;

                        // Submit form
                        const form = document.querySelector('form[action*="uang-muka-penjualan"]');
                        console.log('Form found:', form); // Debug

                        if (form) {
                            // Set customer value from Select2
                            const customerSelect = document.getElementById('customer_id');
                            if (customerSelect) {
                                customerSelect.value = this.selectedCustomer;
                            }

                            console.log('Submitting form with data:', {
                                customer_id: this.selectedCustomer,
                                jumlah: this.jumlah,
                                metode: this.metode
                            }); // Debug

                            form.submit();
                        } else {
                            console.error('Form not found');
                            this.isSubmitting = false;
                        }
                    }
                };
            }
        </script>
    @endpush

</x-app-layout>
