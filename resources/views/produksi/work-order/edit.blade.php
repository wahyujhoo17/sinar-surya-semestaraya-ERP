<x-app-layout :breadcrumbs="[
    ['label' => 'Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Perintah Produksi', 'url' => route('produksi.work-order.index')],
    ['label' => 'Edit'],
]" :currentPage="'Edit Perintah Produksi'">

    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Perintah Produksi: {{ $workOrder->nomor }}
                </h1>
                <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
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

        <form action="{{ route('produksi.work-order.update', $workOrder->id) }}" method="POST" id="form-work-order">
            @csrf
            @method('PUT')

            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Perencanaan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nomor Perencanaan</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $workOrder->perencanaanProduksi->nomor ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $workOrder->perencanaanProduksi->tanggal ? date('d/m/Y', strtotime($workOrder->perencanaanProduksi->tanggal)) : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sales Order</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $workOrder->salesOrder->nomor ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Customer</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $workOrder->salesOrder->customer->nama ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Produk</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Produk</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $workOrder->produk->nama ?? 'Produk tidak ditemukan' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kode Produk</p>
                            <p class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $workOrder->produk->kode ?? '-' }}
                            </p>
                        </div>
                    </div>
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
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="text" name="tanggal" id="tanggal"
                                    value="{{ old('tanggal', $workOrder->tanggal ? date('d/m/Y', strtotime($workOrder->tanggal)) : '') }}"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md datepicker"
                                    placeholder="Pilih tanggal">
                            </div>
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bom_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Bill of Material <span class="text-red-500">*</span>
                            </label>
                            <select id="bom_id" name="bom_id"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih Bill of Material --</option>
                                @foreach ($boms as $bom)
                                    <option value="{{ $bom->id }}"
                                        {{ old('bom_id', $workOrder->bom_id) == $bom->id ? 'selected' : '' }}>
                                        {{ $bom->nama }} ({{ $bom->versi }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bom_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quantity"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Jumlah Produksi <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0" name="quantity" id="quantity"
                                value="{{ old('quantity', $workOrder->quantity) }}"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="satuan_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Satuan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" value="{{ $workOrder->satuan->nama ?? '-' }}" disabled
                                class="bg-gray-100 dark:bg-gray-600 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:text-gray-300 rounded-md">
                            <input type="hidden" name="satuan_id" value="{{ $workOrder->satuan_id }}">
                        </div>

                        <div>
                            <label for="tanggal_mulai"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="text" name="tanggal_mulai" id="tanggal_mulai"
                                    value="{{ old('tanggal_mulai', $workOrder->tanggal_mulai ? date('d/m/Y', strtotime($workOrder->tanggal_mulai)) : '') }}"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md datepicker"
                                    placeholder="Pilih tanggal mulai">
                            </div>
                            @error('tanggal_mulai')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deadline"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Deadline <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <input type="text" name="deadline" id="deadline"
                                    value="{{ old('deadline', $workOrder->deadline ? date('d/m/Y', strtotime($workOrder->deadline)) : '') }}"
                                    class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md datepicker"
                                    placeholder="Pilih deadline">
                            </div>
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gudang_produksi_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gudang Produksi <span class="text-red-500">*</span>
                            </label>
                            <select id="gudang_produksi_id" name="gudang_produksi_id"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih Gudang Produksi --</option>
                                @foreach ($gudang as $g)
                                    <option value="{{ $g->id }}"
                                        {{ old('gudang_produksi_id', $workOrder->gudang_produksi_id) == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }} ({{ $g->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('gudang_produksi_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gudang_hasil_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Gudang Hasil <span class="text-red-500">*</span>
                            </label>
                            <select id="gudang_hasil_id" name="gudang_hasil_id"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                                <option value="">-- Pilih Gudang Hasil --</option>
                                @foreach ($gudang as $g)
                                    <option value="{{ $g->id }}"
                                        {{ old('gudang_hasil_id', $workOrder->gudang_hasil_id) == $g->id ? 'selected' : '' }}>
                                        {{ $g->nama }} ({{ $g->kode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('gudang_hasil_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Catatan
                            </label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">{{ old('catatan', $workOrder->catatan) }}</textarea>
                            @error('catatan')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('produksi.work-order.show', $workOrder->id) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Datepicker
                flatpickr('.datepicker', {
                    dateFormat: 'd/m/Y',
                    locale: 'id'
                });

                // Form validation
                document.getElementById('form-work-order').addEventListener('submit', function(e) {
                    const bomId = document.getElementById('bom_id').value;
                    const gudangProduksiId = document.getElementById('gudang_produksi_id').value;
                    const gudangHasilId = document.getElementById('gudang_hasil_id').value;

                    // Validation checks
                    if (gudangProduksiId === gudangHasilId) {
                        e.preventDefault();
                        alert('Gudang Produksi dan Gudang Hasil tidak boleh sama!');
                        return false;
                    }

                    if (!bomId) {
                        e.preventDefault();
                        alert('Bill of Material harus dipilih!');
                        return false;
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
