<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Perintah Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Buat Baru'],
]" :currentPage="'Buat Perintah Produksi'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Buat Perintah Produksi
                </h1>
                <a href="{{ route('produksi.work-order.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('produksi.work-order.store') }}" method="POST" id="form-work-order">
            @csrf
            <input type="hidden" name="perencanaan_produksi_id" value="{{ $perencanaan->id }}">
            @if ($detailPerencanaan)
                <input type="hidden" name="produk_id" value="{{ $detailPerencanaan->produk_id }}">
                <input type="hidden" name="satuan_id" value="{{ $detailPerencanaan->satuan_id }}">
            @else
                <input type="hidden" name="produk_id" value="">
                <input type="hidden" name="satuan_id" value="">
            @endif

            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Perencanaan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nomor Perencanaan</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">{{ $perencanaan->nomor }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ date('d/m/Y', strtotime($perencanaan->tanggal)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sales Order</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $perencanaan->salesOrder->nomor ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Customer</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $perencanaan->salesOrder->customer->nama ?? ($perencanaan->salesOrder->customer->company ?? '-') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Produk</h2>
                    @if ($detailPerencanaan && $detailPerencanaan->produk)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Produk</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ $detailPerencanaan->produk->nama }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kode Produk</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ $detailPerencanaan->produk->kode ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Perencanaan</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ number_format($detailPerencanaan->jumlah_produksi, 2, ',', '.') }}
                                    {{ $detailPerencanaan->satuan->nama ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Stok Tersedia</p>
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ number_format($detailPerencanaan->stok_tersedia, 2, ',', '.') }}
                                    {{ $detailPerencanaan->satuan->nama ?? '-' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-red-900/20 dark:text-red-400"
                            role="alert">
                            <span class="font-medium">Perhatian!</span> Data produk tidak tersedia atau tidak lengkap.
                            Silakan kembali ke halaman sebelumnya dan pilih produk terlebih dahulu.
                        </div>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Perintah Produksi</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', date('Y-m-d')) }}" required
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bom_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Bill of Material <span class="text-red-500">*</span>
                            </label>
                            <select name="bom_id" id="bom_id" required
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih BOM --</option>
                                @foreach ($boms as $bom)
                                    <option value="{{ $bom->id }}"
                                        {{ old('bom_id') == $bom->id ? 'selected' : '' }}>
                                        {{ $bom->kode }} - {{ $bom->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bom_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quantity"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jumlah Produksi <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="quantity" id="quantity"
                                value="{{ old('quantity', $detailPerencanaan ? $detailPerencanaan->jumlah_produksi : 0) }}"
                                required min="0.01" step="0.01"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Satuan:
                                @if ($detailPerencanaan && $detailPerencanaan->satuan)
                                    {{ $detailPerencanaan->satuan->nama }}
                                @else
                                    -
                                @endif
                            </p>
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_mulai"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Mulai Produksi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            @error('tanggal_mulai')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deadline"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="deadline" id="deadline"
                                value="{{ old('deadline', date('Y-m-d', strtotime('+1 week'))) }}" required
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gudang_produksi_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gudang Produksi <span class="text-red-500">*</span>
                            </label>
                            <select name="gudang_produksi_id" id="gudang_produksi_id" required
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih Gudang Produksi --</option>
                                @foreach ($gudang as $g)
                                    <option value="{{ $g->id }}"
                                        {{ old('gudang_produksi_id') == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gudang_produksi_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gudang_hasil_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gudang Hasil <span class="text-red-500">*</span>
                            </label>
                            <select name="gudang_hasil_id" id="gudang_hasil_id" required
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih Gudang Hasil --</option>
                                @foreach ($gudang as $g)
                                    <option value="{{ $g->id }}"
                                        {{ old('gudang_hasil_id') == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gudang_hasil_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Catatan
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-8">
                <div class="flex space-x-3">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('produksi.work-order.select-product', $perencanaan->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const deadline = document.getElementById('deadline');
            const produkIdField = document.querySelector('input[name="produk_id"]');
            const satuanIdField = document.querySelector('input[name="satuan_id"]');
            const formWorkOrder = document.getElementById('form-work-order');

            // Ensure deadline is after or equal to tanggal_mulai
            tanggalMulai.addEventListener('change', function() {
                if (deadline.value < tanggalMulai.value) {
                    deadline.value = tanggalMulai.value;
                }
            });

            // Validate form before submit
            formWorkOrder.addEventListener('submit', function(e) {
                // Get all required field values
                const produkId = produkIdField.value;
                const satuanId = satuanIdField.value;
                const bomId = document.getElementById('bom_id').value;
                const gudangProduksiId = document.getElementById('gudang_produksi_id').value;
                const gudangHasilId = document.getElementById('gudang_hasil_id').value;
                const quantity = document.getElementById('quantity').value;
                const tanggalMulaiValue = tanggalMulai.value;
                const deadlineValue = deadline.value;

                // Check product and unit data
                if (!produkId || produkId === '') {
                    e.preventDefault();
                    alert(
                        'Produk tidak ditemukan! Silahkan kembali ke halaman sebelumnya dan pilih produk.');
                    return false;
                }

                if (!satuanId || satuanId === '') {
                    e.preventDefault();
                    alert(
                        'Satuan tidak ditemukan! Silahkan kembali ke halaman sebelumnya dan pilih produk.');
                    return false;
                }

                // Check BOM selection
                if (!bomId || bomId === '') {
                    e.preventDefault();
                    alert('Pilih Bill of Material terlebih dahulu!');
                    return false;
                }

                // Check warehouse selections
                if (!gudangProduksiId || gudangProduksiId === '') {
                    e.preventDefault();
                    alert('Pilih Gudang Produksi terlebih dahulu!');
                    return false;
                }

                if (!gudangHasilId || gudangHasilId === '') {
                    e.preventDefault();
                    alert('Pilih Gudang Hasil terlebih dahulu!');
                    return false;
                }

                if (gudangProduksiId === gudangHasilId) {
                    e.preventDefault();
                    alert('Gudang Produksi dan Gudang Hasil tidak boleh sama!');
                    return false;
                }

                // Check quantity
                if (!quantity || quantity <= 0) {
                    e.preventDefault();
                    alert('Jumlah Produksi harus lebih dari 0!');
                    return false;
                }

                // Check dates
                if (!tanggalMulaiValue) {
                    e.preventDefault();
                    alert('Pilih Tanggal Mulai Produksi terlebih dahulu!');
                    return false;
                }

                if (!deadlineValue) {
                    e.preventDefault();
                    alert('Pilih Deadline terlebih dahulu!');
                    return false;
                }

                if (deadlineValue < tanggalMulaiValue) {
                    e.preventDefault();
                    alert('Deadline tidak boleh lebih awal dari Tanggal Mulai Produksi!');
                    return false;
                }

                return true;
            });
        });
    </script>
</x-app-layout>
