<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 h-10 w-1 rounded-full mr-3">
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Edit Pengembalian Dana
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $refund->nomor }}
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
                    <a href="#"
                        class="text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Keuangan</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
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
                    Edit
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

                    <form action="{{ route('keuangan.pengembalian-dana.update', $refund->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nomor Bukti Pengembalian -->
                            <div>
                                <label for="nomor"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor Bukti Pengembalian
                                </label>
                                <input type="text" id="nomor" name="nomor"
                                    value="{{ old('nomor', $refund->nomor) }}"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 @error('nomor') border-red-500 dark:border-red-400 @enderror">
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
                                    value="{{ old('tanggal', $refund->tanggal->format('Y-m-d')) }}"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 @error('tanggal') border-red-500 dark:border-red-400 @enderror"
                                    required>
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Purchase Order -->
                            <div>
                                <label for="purchase_order_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Purchase Order
                                </label>
                                <select id="purchase_order_id" name="purchase_order_id"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 select2 @error('purchase_order_id') border-red-500 dark:border-red-400 @enderror"
                                    required>
                                    <option value="">-- Pilih Purchase Order --</option>
                                    @foreach (\App\Models\PurchaseOrder::whereIn('status_pembayaran', ['kelebihan_bayar', 'lunas'])->with('supplier')->get() as $purchaseOrder)
                                        <option value="{{ $purchaseOrder->id }}"
                                            {{ old('purchase_order_id', $refund->purchase_order_id) == $purchaseOrder->id ? 'selected' : '' }}>
                                            {{ $purchaseOrder->nomor }} - {{ $purchaseOrder->supplier->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('purchase_order_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Supplier -->
                            <div>
                                <label for="supplier_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Supplier
                                </label>
                                <select id="supplier_id" name="supplier_id"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 select2 @error('supplier_id') border-red-500 dark:border-red-400 @enderror"
                                    required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id', $refund->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->nama }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
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
                                        value="{{ old('jumlah_display', number_format($refund->jumlah, 0, ',', '.')) }}"
                                        class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-none rounded-r-lg block w-full p-2.5 currency-format @error('jumlah') border-red-500 dark:border-red-400 @enderror"
                                        required>
                                    <input type="hidden" id="jumlah" name="jumlah"
                                        value="{{ old('jumlah', $refund->jumlah) }}">
                                </div>
                                @error('jumlah')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Metode Penerimaan -->
                            <div>
                                <label for="metode_penerimaan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Metode Penerimaan
                                </label>
                                <select id="metode_penerimaan" name="metode_penerimaan"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 @error('metode_penerimaan') border-red-500 dark:border-red-400 @enderror"
                                    required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="kas"
                                        {{ old('metode_penerimaan', $refund->metode_penerimaan) == 'kas' ? 'selected' : '' }}>
                                        Kas</option>
                                    <option value="bank"
                                        {{ old('metode_penerimaan', $refund->metode_penerimaan) == 'bank' ? 'selected' : '' }}>
                                        Bank</option>
                                </select>
                                @error('metode_penerimaan')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Referensi -->
                            <div>
                                <label for="no_referensi"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    No. Referensi
                                </label>
                                <input type="text" id="no_referensi" name="no_referensi"
                                    value="{{ old('no_referensi', $refund->no_referensi) }}"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 @error('no_referensi') border-red-500 dark:border-red-400 @enderror"
                                    placeholder="No. Transfer / Cek / Bukti Lainnya">
                                @error('no_referensi')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="kas-container" class="mb-6" style="display: none;">
                            <div>
                                <label for="kas_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Akun Kas
                                </label>
                                <select id="kas_id" name="kas_id"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 @error('kas_id') border-red-500 dark:border-red-400 @enderror">
                                    <option value="">-- Pilih Kas --</option>
                                    @foreach ($kasAccounts as $kas)
                                        <option value="{{ $kas->id }}"
                                            {{ old('kas_id', $refund->kas_id) == $kas->id ? 'selected' : '' }}>
                                            {{ $kas->nama }} (Rp {{ number_format($kas->saldo, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kas_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="bank-container" class="mb-6" style="display: none;">
                            <div>
                                <label for="rekening_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Rekening Bank
                                </label>
                                <select id="rekening_id" name="rekening_id"
                                    class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 @error('rekening_id') border-red-500 dark:border-red-400 @enderror">
                                    <option value="">-- Pilih Rekening --</option>
                                    @foreach ($bankAccounts as $rekening)
                                        <option value="{{ $rekening->id }}"
                                            {{ old('rekening_id', $refund->rekening_id) == $rekening->id ? 'selected' : '' }}>
                                            {{ $rekening->nama_bank }} - {{ $rekening->nomor }}
                                            ({{ $rekening->atas_nama }})
                                            - Rp {{ number_format($rekening->saldo, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
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
                                class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg block w-full p-2.5 @error('catatan') border-red-500 dark:border-red-400 @enderror">{{ old('catatan', $refund->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('keuangan.pengembalian-dana.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @push('styles')
            <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
            <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        @endpush

        @push('scripts')
            <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
            <script>
                $(function() {
                    $('.select2').select2({
                        theme: 'bootstrap4'
                    });

                    // Format currency input
                    $('.currency-format').on('input', function() {
                        let value = $(this).val().replace(/[^\d]/g, '');
                        if (value === '') value = '0';
                        let formattedValue = new Intl.NumberFormat('id-ID').format(value);
                        $(this).val(formattedValue);

                        // Update hidden field for jumlah
                        if ($(this).attr('id') === 'jumlah_display') {
                            $('#jumlah').val(value);
                        }
                    });

                    // Handle payment method selection
                    $('#metode_penerimaan').change(function() {
                        const method = $(this).val();

                        if (method === 'kas') {
                            $('#kas-container').show();
                            $('#bank-container').hide();
                            $('#rekening_id').val('');
                        } else if (method === 'bank') {
                            $('#kas-container').hide();
                            $('#bank-container').show();
                            $('#kas_id').val('');
                        } else {
                            $('#kas-container').hide();
                            $('#bank-container').hide();
                            $('#kas_id').val('');
                            $('#rekening_id').val('');
                        }
                    }).trigger('change');
                });
            </script>
        @endpush
