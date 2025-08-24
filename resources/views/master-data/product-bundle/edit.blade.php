<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="min-h-screen px-4 py-6 sm:px-6 lg:px-8" x-data="bundleEditForm()">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Edit Product
                        Bundle</h1>
                    <p class="mt-1 text-sm md:text-base text-gray-600 dark:text-gray-400">
                        Edit paket produk: {{ $productBundle->nama }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('master.product-bundle.show', $productBundle->id) }}"
                        class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Lihat
                    </a>
                    <a href="{{ route('master.product-bundle.index') }}"
                        class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200 text-sm">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <form id="bundleEditForm" method="POST"
            action="{{ route('master.product-bundle.update', $productBundle->id) }}" enctype="multipart/form-data"
            class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-4 lg:gap-6">
                {{-- Bundle Info --}}
                <div class="xl:col-span-3">
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-base lg:text-lg font-medium text-gray-900 dark:text-white">Informasi Bundle
                            </h3>
                        </div>
                        <div class="p-4 lg:p-6 space-y-4 lg:space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Kode Bundle <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="kode" value="{{ old('kode', $productBundle->kode) }}"
                                        required
                                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('kode') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                    @error('kode')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori</label>
                                    <select name="kategori_id"
                                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('kategori_id') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}"
                                                {{ old('kategori_id', $productBundle->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nama Bundle <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama', $productBundle->nama) }}"
                                    required
                                    class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nama') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                @error('nama')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                                <textarea name="deskripsi" rows="3"
                                    class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('deskripsi') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">{{ old('deskripsi', $productBundle->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Harga Bundle <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="harga_bundle"
                                            value="{{ old('harga_bundle', $productBundle->harga_bundle) }}"
                                            x-model="hargaBundle" @input="onHargaBundleChange()" required
                                            class="w-full pl-10 pr-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600"
                                            :class="{
                                                'ring-2 ring-blue-300 border-blue-300': !manualPriceEdit &&
                                                    isCalculating,
                                                'ring-2 ring-amber-300 border-amber-300': manualPriceEdit
                                            }">
                                    </div>
                                    @error('harga_bundle')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm">
                                        <span x-show="!manualPriceEdit && !isCalculating" class="text-blue-600">
                                            💡 Harga otomatis terisi dari total produk
                                        </span>
                                        <span x-show="!manualPriceEdit && isCalculating"
                                            class="text-blue-600 animate-pulse">
                                            🔄 Menghitung ulang...
                                        </span>
                                        <span x-show="manualPriceEdit" class="text-amber-600">
                                            ✏️ Harga telah diubah manual
                                        </span>
                                        <button type="button" x-show="manualPriceEdit" @click="resetToAutoCalculate()"
                                            class="ml-2 text-xs bg-blue-100 hover:bg-blue-200 text-blue-800 px-2 py-1 rounded transition-colors duration-200">
                                            🔄 Reset ke Auto
                                        </button>
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                    <label class="flex items-center cursor-pointer" x-data="{ isActive: {{ old('is_active', $productBundle->is_active) ? 'true' : 'false' }} }">
                                        <input type="checkbox" name="is_active" value="1" x-model="isActive"
                                            class="sr-only">
                                        <div class="relative">
                                            <div class="block w-14 h-8 rounded-full transition-colors duration-200"
                                                :class="isActive ? 'bg-green-400' : 'bg-gray-400'"></div>
                                            <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-200 ease-in-out transform"
                                                :class="isActive ? 'translate-x-6' : 'translate-x-0'">
                                            </div>
                                        </div>
                                        <span class="ml-3 text-gray-700 dark:text-gray-300"
                                            x-text="isActive ? 'Aktif' : 'Tidak Aktif'"></span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gambar
                                    Bundle</label>

                                @if ($productBundle->gambar && Storage::disk('public')->exists($productBundle->gambar))
                                    <div class="mb-3">
                                        <div class="w-32 h-32 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                                            <img src="{{ Storage::url($productBundle->gambar) }}"
                                                alt="{{ $productBundle->nama }}" class="w-full h-full object-cover">
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">Gambar saat ini</p>
                                    </div>
                                @endif

                                <input type="file" name="gambar" accept="image/*"
                                    class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('gambar') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                @error('gambar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika
                                    tidak ingin mengubah gambar.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Bundle Items --}}
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 mt-4">
                        <div
                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                            <h3 class="text-base lg:text-lg font-medium text-gray-900 dark:text-white">Produk dalam
                                Bundle</h3>
                            <button type="button" @click="addItem()"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Produk
                            </button>
                        </div>
                        <div class="p-3 lg:p-4">
                            <div class="overflow-x-auto">
                                <table
                                    class="w-full border-collapse border border-gray-300 dark:border-gray-600 text-xs lg:text-sm table-fixed">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300"
                                                style="width: 220px; min-width: 220px;">
                                                Produk</th>
                                            <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300"
                                                style="width: 60px; min-width: 60px;">
                                                Qty</th>
                                            <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300"
                                                style="width: 100px; min-width: 100px;">
                                                Harga</th>
                                            <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300"
                                                style="width: 100px; min-width: 100px;">
                                                Subtotal</th>
                                            <th class="border border-gray-300 dark:border-gray-600 px-2 py-2 text-left text-xs font-medium text-gray-700 dark:text-gray-300"
                                                style="width: 50px; min-width: 50px;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="border border-gray-300 dark:border-gray-600 px-2 py-2"
                                                    style="width: 220px; min-width: 220px;">
                                                    <select :name="`items[${index}][produk_id]`"
                                                        x-model="item.produk_id" @change="updateItemPrice(index)"
                                                        required :id="`produk_select_${index}`"
                                                        class="produk-select w-full">
                                                        <option value="">Pilih Produk</option>
                                                        @foreach ($produk as $p)
                                                            <option value="{{ $p->id }}"
                                                                data-price="{{ $p->harga_jual }}"
                                                                data-name="{{ $p->nama }}">
                                                                {{ $p->kode }} - {{ $p->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    {{-- Hidden field for existing item ID --}}
                                                    <input type="hidden" :name="`items[${index}][id]`"
                                                        x-model="item.id" x-show="item.id">
                                                </td>
                                                <td class="border border-gray-300 dark:border-gray-600 px-2 py-2"
                                                    style="width: 60px; min-width: 60px;">
                                                    <input type="number" :name="`items[${index}][quantity]`"
                                                        x-model="item.quantity" @input="calculateItemSubtotal(index)"
                                                        min="0.01" step="0.01" required
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                                </td>
                                                <td class="border border-gray-300 dark:border-gray-600 px-2 py-2"
                                                    style="width: 100px; min-width: 100px;">
                                                    <input type="number" :name="`items[${index}][harga_satuan]`"
                                                        x-model="item.harga_satuan"
                                                        @input="calculateItemSubtotal(index)" min="0"
                                                        step="0.01"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                                </td>
                                                <td class="border border-gray-300 dark:border-gray-600 px-2 py-2"
                                                    style="width: 100px; min-width: 100px;">
                                                    <span class="font-medium text-gray-900 dark:text-white text-xs"
                                                        x-text="formatCurrency(item.subtotal)"></span>
                                                </td>
                                                <td class="border border-gray-300 dark:border-gray-600 px-2 py-2"
                                                    style="width: 50px; min-width: 50px;">
                                                    <button type="button" @click="removeItem(index)"
                                                        class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition-colors duration-200">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <template x-if="items.length === 0">
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium mb-2">Belum ada produk dalam bundle</h3>
                                    <p class="mb-4">Tambahkan produk untuk melengkapi bundle ini.</p>
                                    <div class="mt-6">
                                        <button type="button" @click="addItem()"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Tambah Produk Pertama
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Summary Sidebar --}}
                <div class="space-y-4">
                    <div
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-base lg:text-lg font-medium text-gray-900 dark:text-white">Ringkasan Bundle
                            </h3>
                        </div>
                        <div class="p-4">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-xs lg:text-sm font-medium text-gray-500 dark:text-gray-400">Total
                                        Harga
                                        Normal:</dt>
                                    <dd class="text-xs lg:text-sm font-semibold text-gray-900 dark:text-white"
                                        x-text="formatCurrency(totalHargaNormal)"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-xs lg:text-sm font-medium text-gray-500 dark:text-gray-400">Harga
                                        Bundle:</dt>
                                    <dd class="text-xs lg:text-sm font-semibold text-green-600 dark:text-green-400"
                                        x-text="formatCurrency(hargaBundle)"></dd>
                                </div>
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex justify-between">
                                        <dt class="text-sm lg:text-base font-medium text-gray-900 dark:text-white">
                                            Penghematan:
                                        </dt>
                                        <dd class="text-sm lg:text-base font-semibold text-green-600 dark:text-green-400"
                                            x-text="formatCurrency(penghematan)"></dd>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs lg:text-sm text-gray-500 dark:text-gray-400"
                                            x-text="`(${persentaseHemat}%)`"></span>
                                    </div>
                                </div>
                            </dl>

                            <div class="mt-4 space-y-3">
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Update Bundle
                                </button>
                                <a href="{{ route('master.product-bundle.show', $productBundle->id) }}"
                                    class="w-full inline-flex justify-center items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Lihat Detail
                                </a>
                                <a href="{{ route('master.product-bundle.index') }}"
                                    class="w-full inline-flex justify-center items-center px-3 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200 text-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Wider Select2 for better usability */
            .select2-container {
                width: 100% !important;
            }

            /* Responsive adjustments */
            @media (max-width: 408px) {

                .overflow-x-auto table th:first-child,
                .overflow-x-auto table td:first-child {
                    width: 220px !important;
                    min-width: 220px !important;
                }

                .select2-dropdown {
                    min-width: 280px !important;
                    max-width: 350px !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <!-- jQuery (required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        {{-- Pass data to JavaScript via hidden div --}}
        <div id="bundle-data"
            data-items="{{ collect($productBundle->items)->map(function ($item) {return ['id' => $item->id, 'produk_id' => $item->produk_id, 'quantity' => $item->quantity, 'harga_satuan' => $item->harga_satuan, 'subtotal' => $item->quantity * $item->harga_satuan];})->toJson() }}"
            data-harga-bundle="{{ $productBundle->harga_bundle }}" style="display: none;">
        </div>

        <script>
            function bundleEditForm() {
                // Get data from hidden div
                const bundleDataEl = document.getElementById('bundle-data');
                const itemsData = JSON.parse(bundleDataEl.getAttribute('data-items'));
                const hargaBundleData = parseFloat(bundleDataEl.getAttribute('data-harga-bundle'));

                return {
                    items: itemsData,
                    hargaBundle: hargaBundleData,
                    totalHargaNormal: 0,
                    penghematan: 0,
                    persentaseHemat: 0,
                    selectCounter: 0,
                    manualPriceEdit: false,
                    debounceTimer: null,
                    isCalculating: false,

                    addItem() {
                        const newIndex = this.items.length;
                        this.items.push({
                            id: null,
                            produk_id: '',
                            quantity: 1,
                            harga_satuan: 0,
                            subtotal: 0
                        });

                        // Initialize Select2 for the new dropdown after DOM update
                        this.$nextTick(() => {
                            this.initializeSelect2(newIndex);
                        });
                    },

                    removeItem(index) {
                        // Destroy Select2 instance before removing
                        const selectId = `#produk_select_${index}`;
                        if ($(selectId).hasClass('select2-hidden-accessible')) {
                            $(selectId).select2('destroy');
                        }

                        this.items.splice(index, 1);
                        this.calculateTotal();

                        // Re-initialize all Select2 instances with updated indices
                        this.$nextTick(() => {
                            this.reinitializeAllSelect2();
                        });
                    },

                    initializeSelect2(index) {
                        const selectId = `#produk_select_${index}`;

                        $(selectId).select2({
                            placeholder: 'Pilih...',
                            allowClear: true,
                            width: '184px',
                            dropdownAutoWidth: false,
                            language: {
                                noResults: function() {
                                    return "Tidak ditemukan";
                                },
                                searching: function() {
                                    return "Cari...";
                                }
                            }
                        }).on('change', (e) => {
                            const value = $(e.target).val();
                            this.items[index].produk_id = value;
                            this.updateItemPrice(index);
                        });
                    },

                    reinitializeAllSelect2() {
                        // Destroy all existing Select2 instances
                        $('.produk-select').each(function() {
                            if ($(this).hasClass('select2-hidden-accessible')) {
                                $(this).select2('destroy');
                            }
                        });

                        // Reinitialize all Select2 instances
                        this.items.forEach((item, index) => {
                            this.initializeSelect2(index);
                        });
                    },

                    updateItemPrice(index) {
                        // Debounce untuk performa lebih baik
                        clearTimeout(this.debounceTimer);
                        this.debounceTimer = setTimeout(() => {
                            const select = document.querySelector(`select[name="items[${index}][produk_id]"]`);
                            const selectedOption = select.selectedOptions[0];
                            if (selectedOption && selectedOption.dataset.price) {
                                this.items[index].harga_satuan = parseFloat(selectedOption.dataset.price);
                                this.calculateItemSubtotal(index);
                            }
                        }, 100); // Delay singkat untuk mengurangi kalkulasi berlebihan
                    },

                    calculateItemSubtotal(index) {
                        const item = this.items[index];
                        item.subtotal = (item.quantity || 0) * (item.harga_satuan || 0);

                        // Debounce total calculation untuk performa lebih baik
                        clearTimeout(this.debounceTimer);
                        this.debounceTimer = setTimeout(() => {
                            this.calculateTotal();
                        }, 50);
                    },

                    calculateTotal() {
                        this.isCalculating = true;

                        this.totalHargaNormal = this.items.reduce((total, item) => total + (item.subtotal || 0), 0);

                        // Auto-update harga bundle hanya jika belum pernah diedit manual
                        if (!this.manualPriceEdit) {
                            this.hargaBundle = this.totalHargaNormal;
                        }

                        this.calculateSavings();

                        // Reset calculating indicator setelah selesai
                        setTimeout(() => {
                            this.isCalculating = false;
                        }, 100);
                    },

                    calculateSavings() {
                        this.penghematan = Math.max(0, this.totalHargaNormal - this.hargaBundle);
                        this.persentaseHemat = this.totalHargaNormal > 0 ?
                            ((this.penghematan / this.totalHargaNormal) * 100).toFixed(1) : 0;
                    },

                    // Method untuk handle manual edit harga bundle
                    onHargaBundleChange() {
                        this.manualPriceEdit = true;
                        this.calculateSavings();
                    },

                    // Method untuk reset ke auto-calculate
                    resetToAutoCalculate() {
                        this.manualPriceEdit = false;
                        this.hargaBundle = this.totalHargaNormal;
                        this.calculateSavings();
                    },

                    formatCurrency(amount) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount || 0);
                    },

                    init() {
                        // Load existing items and calculate totals
                        this.calculateTotal();

                        // Initialize Select2 for existing items
                        this.$nextTick(() => {
                            this.items.forEach((item, index) => {
                                this.initializeSelect2(index);
                            });
                        });
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
