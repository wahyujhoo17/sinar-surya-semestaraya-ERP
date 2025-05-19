<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div x-show="selectedOption.value && !hideSelectedLabel" class="mt-1.5 flex items-center">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Terpilih: </span>
                    <span class="ml-1 text-sm text-gray-900 dark:text-white font-medium"
                        x-text="selectedOption.label"></span>
                </div>

                @error('purchase_order_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 h-10 w-1 rounded-full mr-3">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Tambah Pengembalian
                            Dana
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Formulir untuk menambahkan pengembalian dana kelebihan bayar dari supplier
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Dashboard</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('keuangan.pengembalian-dana.index') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Pengembalian
                        Dana</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </li>
                <li class="text-gray-800 dark:text-gray-100">
                    Tambah
                </li>
            </ol>
        </nav>
        <div class="max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('error'))
                        <div
                            class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-4 mb-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 dark:text-red-300">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('keuangan.pengembalian-dana.store') }}" method="POST"
                        x-data="formHandler" @option-selected.window="handleOptionSelected" id="pengembalianForm"
                        @submit="console.log('Form submitted from Alpine')">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nomor Bukti Pengembalian -->
                            <div>
                                <label for="nomor"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Bukti Pengembalian
                                </label>
                                <input type="text" id="nomor" name="nomor"
                                    value="{{ old('nomor', $refundNumber) }}"
                                    class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5"
                                    readonly>
                                @error('nomor')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal
                                </label>
                                <input type="date" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', date('Y-m-d')) }}"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 @error('tanggal') border-red-500 dark:border-red-400 @enderror"
                                    required>
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Purchase Order -->
                            <div x-data="dropdownSearch('purchase_order_id',
                                {{ Js::from(
                                    $po
                                        ? [['value' => strval($po->id), 'label' => $po->nomor . ' - ' . $po->supplier->nama]]
                                        : \App\Models\PurchaseOrder::where('status_pembayaran', 'kelebihan_bayar')->with('supplier')->get()->map(function ($purchaseOrder) {
                                                return [
                                                    'value' => strval($purchaseOrder->id),
                                                    'label' => $purchaseOrder->nomor . ' - ' . $purchaseOrder->supplier->nama,
                                                ];
                                            }),
                                ) }}, { 'value': '{{ old('purchase_order_id', $po->id ?? '') }}', 'label': '{{ $po ? $po->nomor . ' - ' . $po->supplier->nama : '' }}' }
                            )" @click.away="open = false" class="relative">
                                <label for="purchase_order_search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Purchase Order
                                </label>
                                <input type="hidden" name="purchase_order_id" :value="selectedOption.value"
                                    x-model="selectedOption.value">
                                <div class="relative">
                                    <input type="text" id="purchase_order_search"
                                        :placeholder="!selectedOption.value ? '-- Pilih Purchase Order --' : ''"
                                        class="bg-white dark:bg-gray-700 border @error('purchase_order_id') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 pr-10"
                                        @click="open = true" :value="displayValue()"
                                        :readonly="selectedOption.value !== ''" autocomplete="off"
                                        {{ $po ? 'disabled' : '' }} @change="handlePurchaseOrderChange()">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div x-show="open" x-transition
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-lg max-h-60 overflow-auto"
                                    style="display: none;">
                                    <div class="p-2">
                                        <input type="text"
                                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                            placeholder="Cari Purchase Order..." x-model="search" @click.stop>
                                    </div>
                                    <ul>
                                        <template x-for="option in filteredOptions()" :key="option.value">
                                            <li>
                                                <button type="button"
                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm"
                                                    @click="select(option); $dispatch('purchase-order-changed', {value: option.value})"
                                                    x-text="option.label">
                                                </button>
                                            </li>
                                        </template>
                                        <li x-show="filteredOptions().length === 0"
                                            class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Tidak ada data
                                        </li>
                                    </ul>
                                </div>

                                @error('purchase_order_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div x-data="dropdownSearch('supplier_id',
                                {{ Js::from(
                                    $suppliers->map(function ($supplier) {
                                        return ['value' => strval($supplier->id), 'label' => $supplier->nama];
                                    }),
                                ) }}, { 'value': '{{ old('supplier_id', $po->supplier_id ?? '') }}', 'label': '{{ $po && $po->supplier ? $po->supplier->nama : '' }}' })" @click.away="open = false" class="relative">
                                <label for="supplier_search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Supplier
                                </label>
                                <input type="hidden" name="supplier_id" :value="selectedOption.value"
                                    x-model="selectedOption.value">
                                <div class="relative">
                                    <input type="text" id="supplier_search"
                                        :placeholder="!selectedOption.value ? '-- Pilih Supplier --' : ''"
                                        class="bg-white dark:bg-gray-700 border @error('supplier_id') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 pr-10"
                                        @click="open = true" :value="displayValue()"
                                        :readonly="selectedOption.value !== ''" {{ $po ? 'disabled' : '' }}
                                        autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div x-show="open" x-transition
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-lg max-h-60 overflow-auto"
                                    style="display: none;">
                                    <div class="p-2">
                                        <input type="text"
                                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                            placeholder="Cari Supplier..." x-model="search" @click.stop>
                                    </div>
                                    <ul>
                                        <template x-for="option in filteredOptions()" :key="option.value">
                                            <li>
                                                <button type="button"
                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm"
                                                    @click="select(option)" x-text="option.label">
                                                </button>
                                            </li>
                                        </template>
                                        <li x-show="filteredOptions().length === 0"
                                            class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Tidak ada data
                                        </li>
                                    </ul>
                                </div>

                                <div x-show="selectedOption.value" class="mt-1.5 flex items-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Terpilih: </span>
                                    <span class="ml-1 text-sm text-gray-900 dark:text-white font-medium"
                                        x-text="selectedOption.label"></span>
                                </div>

                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kelebihan Pembayaran -->
                            <div>
                                <label for="kelebihan_bayar"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Kelebihan Pembayaran
                                </label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 text-sm text-gray-900 dark:text-white bg-gray-200 dark:bg-gray-600 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                        Rp
                                    </span>
                                    <input type="text" id="kelebihan_bayar" x-model="kelebihanBayar"
                                        class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-none rounded-r-lg block w-full p-2.5"
                                        readonly>
                                </div>
                            </div>

                            <!-- Jumlah Pengembalian -->
                            <div>
                                <label for="jumlah_display"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jumlah Pengembalian
                                </label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 text-sm text-gray-900 dark:text-white bg-gray-200 dark:bg-gray-600 border border-r-0 border-gray-300 dark:border-gray-600 rounded-l-md">
                                        Rp
                                    </span>
                                    <input type="text" id="jumlah_display" name="jumlah_display"
                                        x-model="jumlahDisplay" @input="formatCurrency($event)"
                                        class="bg-white dark:bg-gray-700 border @error('jumlah') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror text-gray-900 dark:text-white text-sm rounded-none rounded-r-lg block w-full p-2.5"
                                        required>
                                    <input type="hidden" id="jumlah" name="jumlah" x-model="jumlahValue">
                                </div>
                                @error('jumlah')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan jumlah yang
                                    dikembalikan oleh supplier.</p>
                            </div>

                            <!-- Metode Penerimaan -->
                            <div>
                                <label for="metode_penerimaan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Metode Penerimaan
                                </label>
                                <select id="metode_penerimaan" name="metode_penerimaan" x-model="metodePenerimaan"
                                    @change="handleMetodePenerimaan"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5"
                                    required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="kas">Kas</option>
                                    <option value="bank">Bank</option>
                                </select>
                                @error('metode_penerimaan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_referensi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    No. Referensi
                                </label>
                                <input type="text" id="no_referensi" name="no_referensi"
                                    value="{{ old('no_referensi') }}"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5"
                                    placeholder="No. Transfer / Cek / Bukti Lainnya">
                                @error('no_referensi')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6" x-show="kasVisible">
                            <div x-data="dropdownSearch('kas_id',
                                {{ Js::from(
                                    $kasAccounts->map(function ($kas) {
                                        return [
                                            'value' => strval($kas->id),
                                            'label' => $kas->nama . ' (Rp ' . number_format($kas->saldo, 0, ',', '.') . ')',
                                        ];
                                    }),
                                ) }}, { 'value': '{{ old('kas_id', '') }}', 'label': '' }
                            )" @click.away="open = false" class="relative">
                                <label for="kas_search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Akun Kas
                                </label>
                                <input type="hidden" name="kas_id" :value="selectedOption.value"
                                    x-model="selectedOption.value">
                                <div class="relative">
                                    <input type="text" id="kas_search"
                                        :placeholder="!selectedOption.value ? '-- Pilih Kas --' : ''"
                                        class="bg-white dark:bg-gray-700 border @error('kas_id') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 pr-10"
                                        @click="open = true" :value="displayValue()"
                                        :readonly="selectedOption.value !== ''" autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div x-show="open" x-transition
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-lg max-h-60 overflow-auto"
                                    style="display: none;">
                                    <div class="p-2">
                                        <input type="text"
                                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                            placeholder="Cari Kas..." x-model="search" @click.stop>
                                    </div>
                                    <ul>
                                        <template x-for="option in filteredOptions()" :key="option.value">
                                            <li>
                                                <button type="button"
                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm"
                                                    @click="select(option)" x-text="option.label">
                                                </button>
                                            </li>
                                        </template>
                                        <li x-show="filteredOptions().length === 0"
                                            class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Tidak ada data
                                        </li>
                                    </ul>
                                </div>

                                <div x-show="selectedOption.value" class="mt-1.5 flex items-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Terpilih: </span>
                                    <span class="ml-1 text-sm text-gray-900 dark:text-white font-medium"
                                        x-text="selectedOption.label"></span>
                                </div>

                                @error('kas_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6" x-show="bankVisible">
                            <div x-data="dropdownSearch('rekening_id',
                                {{ Js::from(
                                    $bankAccounts->map(function ($rekening) {
                                        return [
                                            'value' => strval($rekening->id),
                                            'label' =>
                                                $rekening->nama_bank .
                                                ' - ' .
                                                $rekening->nomor .
                                                ' (' .
                                                $rekening->atas_nama .
                                                ') - Rp ' .
                                                number_format($rekening->saldo, 0, ',', '.'),
                                        ];
                                    }),
                                ) }}, { 'value': '{{ old('rekening_id', '') }}', 'label': '' }
                            )" @click.away="open = false" class="relative">
                                <label for="rekening_search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Rekening Bank
                                </label>
                                <input type="hidden" name="rekening_id" :value="selectedOption.value"
                                    x-model="selectedOption.value">
                                <div class="relative">
                                    <input type="text" id="rekening_search"
                                        placeholder="-- Pilih Rekening Bank --"
                                        class="bg-white dark:bg-gray-700 border @error('rekening_id') border-red-500 dark:border-red-400 @else border-gray-300 dark:border-gray-600 @enderror text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 pr-10"
                                        @click="open = true" :readonly="selectedOption.value !== ''"
                                        :value="displayValue()" autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div x-show="open" x-transition
                                    class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 shadow-lg rounded-lg max-h-60 overflow-auto"
                                    style="display: none;">
                                    <div class="p-2">
                                        <input type="text"
                                            class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                            placeholder="Cari Rekening Bank..." x-model="search" @click.stop>
                                    </div>
                                    <ul>
                                        <template x-for="option in filteredOptions()" :key="option.value">
                                            <li>
                                                <button type="button"
                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-900 dark:text-white text-sm"
                                                    @click="select(option)" x-text="option.label">
                                                </button>
                                            </li>
                                        </template>
                                        <li x-show="filteredOptions().length === 0"
                                            class="px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">Tidak ada data
                                        </li>
                                    </ul>
                                </div>

                                <div x-show="selectedOption.value" class="mt-1.5 flex items-center">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Terpilih: </span>
                                    <span class="ml-1 text-sm text-gray-900 dark:text-white font-medium"
                                        x-text="selectedOption.label"></span>
                                </div>

                                @error('rekening_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Catatan
                            </label>
                            <textarea id="catatan" name="catatan" rows="3"
                                class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                </div>

                <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('keuangan.pengembalian-dana.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            Batal
                        </a>
                        <button type="submit"
                            onclick="console.log('Form submission attempt'); debugFormSubmission(); return true;"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Hide all "Terpilih:" labels and update input fields when the page loads
            document.addEventListener('DOMContentLoaded', function() {
                // Function to handle dropdowns when Alpine initializes
                window.updateDropdowns = function() {
                    // Find all elements with class that matches the Terpilih labels
                    const terpilihLabels = document.querySelectorAll('.mt-1\\.5.flex.items-center');
                    terpilihLabels.forEach(function(label) {
                        // Check if it contains "Terpilih:" text
                        if (label.textContent.includes('Terpilih:')) {
                            label.style.display = 'none';

                            // Find the dropdown container
                            const container = label.closest('[x-data^="dropdownSearch"]');
                            if (container) {
                                // Get the selected value from the label
                                const selectedText = label.querySelector('span:last-child').innerText;

                                // Find the input field and set its value
                                const input = container.querySelector('input[id$="_search"]');
                                if (input && selectedText.trim()) {
                                    input.value = selectedText;
                                }
                            }
                        }
                    });
                };

                // Call once immediately and again after a slight delay to ensure Alpine has initialized
                setTimeout(window.updateDropdowns, 100);
            });

            document.addEventListener('alpine:init', () => {
                Alpine.data('dropdownSearch', function(fieldName, options, selected) {
                    return {
                        search: '',
                        open: false,
                        options: options || [],
                        selectedOption: selected || {
                            value: '',
                            label: ''
                        },

                        filteredOptions() {
                            if (!this.search) return this.options;
                            return this.options.filter(option =>
                                option.label.toLowerCase().includes(this.search.toLowerCase())
                            );
                        },

                        select(option) {
                            this.selectedOption = option;
                            this.open = false;
                            this.search = '';

                            // Hide the "Terpilih:" label for this dropdown and update input
                            const selectedDisplay = this.$el.querySelector('[x-show="selectedOption.value"]');
                            if (selectedDisplay) {
                                selectedDisplay.style.display = 'none';
                            }

                            // Update the input to show the selected value
                            const input = this.$el.querySelector('input[id$="_search"]');
                            if (input) {
                                input.value = option.label;
                            }

                            // Call our global update function if it exists
                            if (typeof window.updateDropdowns === 'function') {
                                setTimeout(window.updateDropdowns, 10);
                            }

                            this.$dispatch('option-selected', {
                                fieldName: fieldName,
                                value: option.value
                            });
                        },

                        init() {
                            // If we have a selected value but no full object, find it in options
                            if (typeof this.selectedOption === 'string' && this.selectedOption !== '') {
                                const foundOption = this.options.find(opt => opt.value === this.selectedOption);
                                if (foundOption) {
                                    this.selectedOption = foundOption;
                                }
                            }

                            // If selectedOption is a primitive value, convert it to object
                            if (this.selectedOption && typeof this.selectedOption.value === 'undefined') {
                                this.selectedOption = {
                                    value: this.selectedOption,
                                    label: ''
                                };
                            }
                        },

                        // Display selected value in the input field
                        displayValue() {
                            return this.selectedOption && this.selectedOption.value ? this.selectedOption
                                .label : '';
                        },

                        // Hide the "Terpilih:" label
                        hideSelectedLabel: true
                    };
                });

                Alpine.data('formHandler', function() {
                    return {
                        kelebihanBayar: '{{ number_format($kelebihanBayar, 0, ',', '.') }}',
                        metodePenerimaan: '{{ old('metode_penerimaan', '') }}',
                        kasVisible: {{ old('metode_penerimaan') == 'kas' ? 'true' : 'false' }},
                        bankVisible: {{ old('metode_penerimaan') == 'bank' ? 'true' : 'false' }},
                        selectedPo: '{{ old('purchase_order_id', $po->id ?? '') }}',
                        selectedSupplier: '{{ old('supplier_id', $po->supplier_id ?? '') }}',
                        selectedKas: '{{ old('kas_id', '') }}',
                        selectedRekening: '{{ old('rekening_id', '') }}',
                        jumlahDisplay: '{{ old('jumlah_display', number_format($kelebihanBayar, 0, ',', '.')) }}',
                        jumlahValue: '{{ old('jumlah', $kelebihanBayar) }}',

                        init() {
                            this.updateVisibility();

                            // Hide all "Terpilih:" labels and modify inputs to show the selected value
                            document.querySelectorAll('[x-show="selectedOption.value"]').forEach(el => {
                                el.style.display = 'none';
                            });

                            // Update inputs to show selected values
                            document.querySelectorAll('input[id$="_search"]').forEach(input => {
                                const component = Alpine.$data(input.closest('[x-data]'));
                                if (component && component.selectedOption && component.selectedOption
                                    .value) {
                                    input.value = component.selectedOption.label;
                                }
                            });
                        },

                        handleMetodePenerimaan() {
                            this.metodePenerimaan = this.$event.target.value;
                            this.updateVisibility();
                        },

                        updateVisibility() {
                            this.kasVisible = this.metodePenerimaan === 'kas';
                            this.bankVisible = this.metodePenerimaan === 'bank';

                            // Update required attributes
                            if (this.metodePenerimaan === 'kas') {
                                this.selectedRekening = '';
                                const kasElement = document.getElementById('kas_id');
                                const rekeningElement = document.getElementById('rekening_id');
                                if (kasElement) kasElement.setAttribute('required', '');
                                if (rekeningElement) rekeningElement.removeAttribute('required');
                            } else if (this.metodePenerimaan === 'bank') {
                                this.selectedKas = '';
                                const kasElement = document.getElementById('kas_id');
                                const rekeningElement = document.getElementById('rekening_id');
                                if (kasElement) kasElement.removeAttribute('required');
                                if (rekeningElement) rekeningElement.setAttribute('required', '');
                            } else {
                                this.selectedKas = '';
                                this.selectedRekening = '';
                                const kasElement = document.getElementById('kas_id');
                                const rekeningElement = document.getElementById('rekening_id');
                                if (kasElement) kasElement.removeAttribute('required');
                                if (rekeningElement) rekeningElement.removeAttribute('required');
                            }
                        },

                        formatCurrency(event) {
                            let value = event.target.value.replace(/[^\d]/g, '');
                            if (value === '') value = '0';
                            this.jumlahValue = value;
                            this.jumlahDisplay = new Intl.NumberFormat('id-ID').format(value);
                        },

                        async handlePurchaseOrderChange() {
                            const poId = this.$event.target.value || this.selectedPo;
                            if (!poId) return;

                            this.selectedPo = poId;

                            try {
                                const response = await fetch(
                                    `{{ route('keuangan.pengembalian-dana.get-po-data') }}?po_id=${poId}`, {
                                        headers: {
                                            'Accept': 'application/json'
                                        }
                                    });

                                const data = await response.json();

                                if (data.success) {
                                    // Update nilai kelebihan bayar
                                    const kelebihanBayar = data.data.kelebihan_bayar;
                                    this.kelebihanBayar = new Intl.NumberFormat('id-ID').format(
                                        kelebihanBayar);
                                    this.jumlahDisplay = new Intl.NumberFormat('id-ID').format(
                                        kelebihanBayar);
                                    this.jumlahValue = kelebihanBayar;

                                    // Update supplier
                                    if (data.data.supplier_id) {
                                        this.selectedSupplier = data.data.supplier_id;
                                    }
                                }
                            } catch (error) {
                                console.error('Error fetching PO data:', error);
                            }
                        },

                        handleOptionSelected(event) {
                            const fieldName = event.detail.fieldName;
                            const value = event.detail.value;

                            if (fieldName === 'purchase_order_id') {
                                this.selectedPo = value;
                                this.handlePurchaseOrderChange();
                            } else if (fieldName === 'supplier_id') {
                                this.selectedSupplier = value;
                            } else if (fieldName === 'kas_id') {
                                this.selectedKas = value;
                            } else if (fieldName === 'rekening_id') {
                                this.selectedRekening = value;
                            }

                            // Hide the "Terpilih:" label whenever any option is selected
                            setTimeout(() => {
                                document.querySelectorAll('[x-show="selectedOption.value"]').forEach(
                                    el => {
                                        el.style.display = 'none';
                                    });
                            }, 100);
                        }
                    }
                });
            });

            // Add form submission debug
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('pengembalianForm');
                if (form) {

                    form.addEventListener('submit', function(event) {


                        // Log form data
                        const formData = new FormData(form);
                        const formDataObj = {};
                        formData.forEach((value, key) => {
                            formDataObj[key] = value;
                        });

                    });
                } else {
                    console.error('Form not found');
                }
            });
        </script>
    @endpush
</x-app-layout>
