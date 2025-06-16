<x-app-layout>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Modern Select2 styling */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
            padding: 8px 12px;
            border-color: #D1D5DB;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #9CA3AF;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827;
            font-weight: 500;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #6B7280;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
            display: flex;
            align-items: center;
            right: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6B7280 transparent transparent transparent;
            border-width: 5px 5px 0 5px;
            transition: transform 0.2s ease;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6B7280 transparent;
            border-width: 0 5px 5px 5px;
        }

        .select2-dropdown {
            border-color: #D1D5DB;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-top: 4px;
            animation: select2DropdownFadeIn 0.2s ease;
        }

        @keyframes select2DropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            transition: all 0.15s ease;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6366f1;
            color: white;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #EEF2FF;
            color: #4F46E5;
            font-weight: 500;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: #D1D5DB;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        /* Dark mode support */
        .dark .select2-container--default .select2-selection--single {
            background-color: #374151;
            border-color: #4B5563;
            color: #F9FAFB;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #F9FAFB;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #D1D5DB;
        }

        .dark .select2-dropdown {
            background-color: #1F2937;
            border-color: #4B5563;
        }

        .dark .select2-container--default .select2-results__option {
            color: #F9FAFB;
        }

        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #374151;
            border-color: #4B5563;
            color: #F9FAFB;
        }
    </style>

    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header Section --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Pengiriman Barang
                                Pengganti</h1>
                            <div class="mt-1 flex items-center gap-2">
                                <a href="{{ route('dashboard') }}"
                                    class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Dashboard</a>
                                <span class="text-sm text-gray-400 dark:text-gray-600">/</span>
                                <a href="{{ route('penjualan.retur.index') }}"
                                    class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Retur
                                    Penjualan</a>
                                <span class="text-sm text-gray-400 dark:text-gray-600">/</span>
                                <a href="{{ route('penjualan.retur.show', $returPenjualan->id) }}"
                                    class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Detail
                                    Retur</a>
                                <span class="text-sm text-gray-400 dark:text-gray-600">/</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Pengiriman Barang
                                    Pengganti</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('penjualan.retur.show', $returPenjualan->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 gap-6">
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Retur</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nomor
                                    Retur</label>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $returPenjualan->nomor }}</p>
                            </div>
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal</label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ date('d F Y', strtotime($returPenjualan->tanggal)) }}</p>
                            </div>
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer</label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $returPenjualan->customer->company ?? $returPenjualan->customer->nama }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Sales
                                    Order</label>
                                <p class="text-sm text-gray-900 dark:text-white">
                                    {{ $returPenjualan->salesOrder->nomor }}</p>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tipe
                                    Retur</label>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                    Tukar Barang
                                </span>
                            </div>
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1"></span>
                                    Menunggu Barang Pengganti
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Item yang Diretur --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Item yang Diretur</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        No</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Produk</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Quantity</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Satuan</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Alasan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($returPenjualan->details as $index => $detail)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $detail->produk->nama }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-300">
                                                {{ $detail->produk->kode }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $detail->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $detail->satuan->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300">
                                                {{ $detail->alasan }}
                                            </span>
                                            @if ($detail->keterangan)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $detail->keterangan }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Form Penggantian Barang --}}
            <div
                class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Form Pengiriman Barang Pengganti
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('penjualan.retur.proses-kirim-barang-pengganti', $returPenjualan->id) }}"
                        method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="tanggal_pengiriman"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal Pengiriman <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="tanggal_pengiriman" name="tanggal_pengiriman"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('tanggal_pengiriman') border-red-500 @enderror"
                                    value="{{ old('tanggal_pengiriman', date('Y-m-d')) }}" required>
                                @error('tanggal_pengiriman')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="gudang_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Gudang Asal <span class="text-red-500">*</span>
                                </label>
                                <select id="gudang_id" name="gudang_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('gudang_id') border-red-500 @enderror"
                                    required>
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach ($gudangs as $gudang)
                                        <option value="{{ $gudang->id }}"
                                            {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                            {{ $gudang->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gudang_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="no_referensi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    No. Referensi
                                </label>
                                <input type="text" id="no_referensi" name="no_referensi"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('no_referensi') border-red-500 @enderror"
                                    value="{{ old('no_referensi') }}">
                                @error('no_referensi')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="catatan_pengiriman"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan Pengiriman
                                </label>
                                <textarea id="catatan_pengiriman" name="catatan_pengiriman" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('catatan_pengiriman') border-red-500 @enderror">{{ old('catatan_pengiriman') }}</textarea>
                                @error('catatan_pengiriman')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Items to replace --}}
                        <div class="mb-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700"
                                    id="barang-pengganti-table">
                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-2/5">
                                                Produk</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/5">
                                                Quantity</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/5">
                                                Satuan</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-1/5">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <tr id="barang-row-0">
                                            <td class="px-6 py-4">
                                                <select
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm select2 produk-select"
                                                    name="items[0][produk_id]" required data-row="0"
                                                    id="produk-0">
                                                    <option value="">-- Pilih Produk --</option>
                                                    @foreach ($produks as $produk)
                                                        <option value="{{ $produk->id }}"
                                                            data-satuan="{{ $produk->satuan_id }}">
                                                            {{ $produk->kode }} - {{ $produk->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                                    id="stok-info-0" style="display: none;">
                                                    Stok tersedia: <span id="stok-jumlah-0">0</span> <span
                                                        id="stok-satuan-0"></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="number"
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm quantity-input"
                                                    name="items[0][quantity]" min="0.01" step="0.01" required
                                                    id="quantity-0">
                                                <div class="mt-1 text-xs text-red-500 dark:text-red-400"
                                                    id="quantity-error-0" style="display: none;">
                                                    Quantity melebihi stok tersedia!
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <select
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm satuan-select"
                                                    name="items[0][satuan_id]" required id="satuan-0">
                                                    <option value="">-- Pilih Satuan --</option>
                                                    @foreach ($produks->pluck('satuan')->unique() as $satuan)
                                                        @if ($satuan)
                                                            <option value="{{ $satuan->id }}">{{ $satuan->nama }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button type="button"
                                                    class="inline-flex items-center justify-center p-2 rounded-md text-red-600 hover:text-red-800 hover:bg-red-100 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30 focus:outline-none transition-colors duration-200"
                                                    onclick="removeRow(0)" disabled>
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="px-6 py-4">
                                                <button type="button" id="add-item-btn"
                                                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Tambah Item
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('penjualan.retur.show', $returPenjualan->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-600 hover:bg-gray-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengiriman Barang Pengganti
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Include Select2 JS -->
        <!-- jQuery (required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize select2
                initializeSelect2();

                // Handle gudang change
                document.getElementById('gudang_id').addEventListener('change', function() {
                    // Reset all product selections when warehouse changes
                    document.querySelectorAll('.produk-select').forEach(function(select) {
                        $(select).val('').trigger('change');
                    });
                });

                // Add row button handler
                let rowCount = 1;
                document.getElementById('add-item-btn').addEventListener('click', function() {
                    let tbody = document.querySelector('#barang-pengganti-table tbody');

                    let tr = document.createElement('tr');
                    tr.id = `barang-row-${rowCount}`;
                    tr.innerHTML = `
                    <td class="px-6 py-4">
                        <select class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm select2 produk-select" name="items[${rowCount}][produk_id]" required data-row="${rowCount}" id="produk-${rowCount}">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produks as $produk)
                                <option value="{{ $produk->id }}" data-satuan="{{ $produk->satuan_id }}">
                                    {{ $produk->kode }} - {{ $produk->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="stok-info-${rowCount}" style="display: none;">
                            Stok tersedia: <span id="stok-jumlah-${rowCount}">0</span> <span id="stok-satuan-${rowCount}"></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <input type="number" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm quantity-input" name="items[${rowCount}][quantity]" min="0.01" step="0.01" required id="quantity-${rowCount}">
                        <div class="mt-1 text-xs text-red-500 dark:text-red-400" id="quantity-error-${rowCount}" style="display: none;">
                            Quantity melebihi stok tersedia!
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <select class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm satuan-select" name="items[${rowCount}][satuan_id]" required id="satuan-${rowCount}">
                            <option value="">-- Pilih Satuan --</option>
                            @foreach ($produks->pluck('satuan')->unique() as $satuan)
                                @if ($satuan)
                                <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-red-600 hover:text-red-800 hover:bg-red-100 dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/30 focus:outline-none transition-colors duration-200" onclick="removeRow(${rowCount})">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </td>
                `;

                    tbody.appendChild(tr);

                    // Initialize select2 for new row
                    initializeSelect2ForRow(rowCount);

                    rowCount++;
                });

                // Form submit validation
                document.querySelector('form').addEventListener('submit', function(e) {
                    const hasErrors = validateQuantities();
                    if (hasErrors) {
                        e.preventDefault();
                        alert(
                            'Ada item dengan quantity melebihi stok tersedia. Mohon periksa kembali quantity yang diinput.'
                        );
                    }
                });
            });

            // Function to validate all quantities against available stock
            function validateQuantities() {
                let hasErrors = false;
                document.querySelectorAll('.quantity-input').forEach(function(input) {
                    const row = input.id.split('-')[1];
                    const quantity = parseFloat(input.value);
                    const stockElement = document.getElementById(`stok-jumlah-${row}`);
                    if (stockElement) {
                        const availableStock = parseFloat(stockElement.textContent);
                        const errorElement = document.getElementById(`quantity-error-${row}`);

                        if (quantity > availableStock) {
                            errorElement.style.display = 'block';
                            input.classList.add('border-red-500');
                            hasErrors = true;
                        } else {
                            errorElement.style.display = 'none';
                            input.classList.remove('border-red-500');
                        }
                    }
                });
                return hasErrors;
            }

            // Function to initialize all Select2 instances
            function initializeSelect2() {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    // Initialize Select2 for product dropdown
                    $('.select2').select2({
                        placeholder: "-- Pilih Produk --",
                        allowClear: true,
                        width: '100%'
                    });

                    // Product selection change handler
                    $('.produk-select').change(function() {
                        let row = $(this).data('row');
                        let satuanId = $(this).find(':selected').data('satuan');
                        let produkId = $(this).val();
                        let gudangId = document.getElementById('gudang_id').value;

                        // Clear quantity input when product changes
                        document.getElementById(`quantity-${row}`).value = '';

                        // Hide stock info if no product selected
                        if (!produkId) {
                            document.getElementById(`stok-info-${row}`).style.display = 'none';
                            document.getElementById(`quantity-error-${row}`).style.display = 'none';
                            document.getElementById(`quantity-${row}`).classList.remove('border-red-500');
                        }

                        // Set the satuan dropdown value if product selected
                        if (satuanId) {
                            $(`#satuan-${row}`).val(satuanId).trigger('change');
                        }

                        // Fetch stock information for the selected product
                        if (produkId && gudangId) {
                            fetchStockInfo(produkId, gudangId, row);
                        }
                    });

                    // Quantity input change handler to validate against stock
                    $('.quantity-input').on('input', function() {
                        const row = this.id.split('-')[1];
                        const quantity = parseFloat(this.value);
                        const stockElement = document.getElementById(`stok-jumlah-${row}`);
                        if (stockElement) {
                            const availableStock = parseFloat(stockElement.textContent);
                            const errorElement = document.getElementById(`quantity-error-${row}`);

                            if (quantity > availableStock) {
                                errorElement.style.display = 'block';
                                this.classList.add('border-red-500');
                            } else {
                                errorElement.style.display = 'none';
                                this.classList.remove('border-red-500');
                            }
                        }
                    });
                }
            }

            // Function to initialize Select2 for a specific row
            function initializeSelect2ForRow(rowId) {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                    // Initialize Select2 for the product dropdown in this row
                    $(`#barang-row-${rowId} .select2`).select2({
                        placeholder: "-- Pilih Produk --",
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $(`#barang-row-${rowId}`).parent()
                    }).on('select2:open', function() {
                        document.querySelector('.select2-search__field').focus();
                    });

                    // Add event handler for product selection
                    $(`#barang-row-${rowId} .produk-select`).change(function() {
                        let row = $(this).data('row');
                        let satuanId = $(this).find(':selected').data('satuan');
                        let produkId = $(this).val();
                        let gudangId = document.getElementById('gudang_id').value;

                        // Clear quantity input when product changes
                        document.getElementById(`quantity-${row}`).value = '';

                        // Hide stock info if no product selected
                        if (!produkId) {
                            document.getElementById(`stok-info-${row}`).style.display = 'none';
                            document.getElementById(`quantity-error-${row}`).style.display = 'none';
                            document.getElementById(`quantity-${row}`).classList.remove('border-red-500');
                        }

                        // Set the satuan dropdown value if product selected
                        if (satuanId) {
                            $(`#satuan-${row}`).val(satuanId).trigger('change');
                        }

                        // Fetch stock information for the selected product
                        if (produkId && gudangId) {
                            fetchStockInfo(produkId, gudangId, row);
                        }
                    });

                    // Quantity input change handler to validate against stock
                    $(`#quantity-${rowId}`).on('input', function() {
                        const row = this.id.split('-')[1];
                        const quantity = parseFloat(this.value);
                        const stockElement = document.getElementById(`stok-jumlah-${row}`);
                        if (stockElement) {
                            const availableStock = parseFloat(stockElement.textContent);
                            const errorElement = document.getElementById(`quantity-error-${row}`);

                            if (quantity > availableStock) {
                                errorElement.style.display = 'block';
                                this.classList.add('border-red-500');
                            } else {
                                errorElement.style.display = 'none';
                                this.classList.remove('border-red-500');
                            }
                        }
                    });
                }
            }

            // Function to fetch stock information from the server
            async function fetchStockInfo(produkId, gudangId, rowId) {
                try {
                    const response = await fetch(`/penjualan/retur/get-stok?produk_id=${produkId}&gudang_id=${gudangId}`);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    // Update stock info display
                    document.getElementById(`stok-jumlah-${rowId}`).textContent = data.stok;
                    document.getElementById(`stok-satuan-${rowId}`).textContent = data.satuan;
                    document.getElementById(`stok-info-${rowId}`).style.display = 'block';

                    // If stock is zero, show warning
                    if (parseFloat(data.stok) <= 0) {
                        document.getElementById(`stok-info-${rowId}`).classList.add('text-red-500');
                        document.getElementById(`stok-info-${rowId}`).classList.remove('text-gray-500');
                    } else {
                        document.getElementById(`stok-info-${rowId}`).classList.remove('text-red-500');
                        document.getElementById(`stok-info-${rowId}`).classList.add('text-gray-500');
                    }

                    // Validate quantity if already entered
                    const quantityInput = document.getElementById(`quantity-${rowId}`);
                    if (quantityInput.value) {
                        const quantity = parseFloat(quantityInput.value);
                        const availableStock = parseFloat(data.stok);
                        const errorElement = document.getElementById(`quantity-error-${rowId}`);

                        if (quantity > availableStock) {
                            errorElement.style.display = 'block';
                            quantityInput.classList.add('border-red-500');
                        } else {
                            errorElement.style.display = 'none';
                            quantityInput.classList.remove('border-red-500');
                        }
                    }
                } catch (error) {
                    console.error('Error fetching stock info:', error);
                    document.getElementById(`stok-info-${rowId}`).textContent = 'Error fetching stock data';
                    document.getElementById(`stok-info-${rowId}`).style.display = 'block';
                    document.getElementById(`stok-info-${rowId}`).classList.add('text-red-500');
                }
            }

            // Remove row function
            function removeRow(rowId) {
                const row = document.getElementById(`barang-row-${rowId}`);
                if (row) {
                    // Destroy Select2 instance before removing the row to prevent memory leaks
                    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                        $(`#barang-row-${rowId} .select2`).select2('destroy');
                    }
                    row.remove();
                }
            }
        </script>
    @endpush
</x-app-layout>
