<x-app-layout>
    <div class="max-w-full mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-br from-primary-500 to-primary-600 h-10 w-1 rounded-full mr-3">
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Edit Retur Pembelian</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Form untuk mengubah retur pembelian {{ $returPembelian->nomor }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm font-medium">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Dashboard</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('pembelian.retur-pembelian.index') }}"
                        class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Retur
                        Pembelian</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </li>
                <li class="text-gray-800 dark:text-gray-100">
                    Edit
                </li>
            </ol>
        </nav>

        <form action="{{ route('pembelian.retur-pembelian.update', $returPembelian->id) }}" method="POST"
            x-data="returPembelianEditForm()">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left column - Header Info -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Retur</h2>

                        <!-- Nomor Retur -->
                        <div class="mb-4">
                            <label for="nomor"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor
                                Retur</label>
                            <input type="text" name="nomor" id="nomor" value="{{ $returPembelian->nomor }}"
                                readonly required
                                class="w-full rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            @error('nomor')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Retur -->
                        <div class="mb-4">
                            <label for="tanggal"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                Retur</label>
                            <input type="date" name="tanggal" id="tanggal"
                                value="{{ old('tanggal', $returPembelian->tanggal->format('Y-m-d')) }}" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                            @error('tanggal')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Supplier -->
                        <div class="mb-4">
                            <label for="supplier_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier</label>
                            <input type="hidden" name="supplier_id" value="{{ $returPembelian->supplier_id }}">
                            <input type="text" value="{{ $returPembelian->supplier->nama }}" readonly
                                class="w-full rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>

                        <!-- Purchase Order -->
                        <div class="mb-4">
                            <label for="purchase_order_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Purchase
                                Order</label>
                            <input type="hidden" name="purchase_order_id"
                                value="{{ $returPembelian->purchase_order_id }}">
                            <input type="text" value="{{ $returPembelian->purchaseOrder->nomor }}" readonly
                                class="w-full rounded-md bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>

                        <!-- Gudang  -->
                        <div class="mb-4">
                            <label for="gudang_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gudang</label>
                            <select name="gudang_id" id="gudang_id" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                <option value="">-- Pilih Gudang --</option>
                                @foreach ($gudangs as $gudang)
                                    <option value="{{ $gudang->id }}"
                                        {{ old('gudang_id', $returPembelian->gudang_id) == $gudang->id ? 'selected' : '' }}>
                                        {{ $gudang->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gudang_id')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="mb-4">
                            <label for="catatan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">{{ old('catatan', $returPembelian->catatan) }}</textarea>
                            @error('catatan')
                                <span class="text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold leading-5 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ ucfirst($returPembelian->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right column - Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Detail Barang</h2>
                            <button type="button" @click="addItem()"
                                class="inline-flex items-center px-3 py-1.5 bg-primary-600 hover:bg-primary-700 rounded-md text-white text-sm font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Baris
                            </button>
                        </div>

                        <!-- Items table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Produk
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Kuantitas
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Alasan
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                        <th scope="col"
                                            class="px-2 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800"
                                    id="items-container">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-2 py-4">
                                                <input type="hidden" :name="`items[${index}][id]`"
                                                    :value="item.id">
                                                <input type="hidden" :name="`items[${index}][produk_id]`"
                                                    :value="item.produk_id">
                                                <div class="text-sm text-gray-900 dark:text-gray-100 font-medium"
                                                    x-text="item.nama_item"></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400"
                                                    x-text="'Kode: ' + item.kode_produk"></div>
                                            </td>
                                            <td class="px-2 py-4">
                                                <div class="flex items-center">
                                                    <input type="number" :name="`items[${index}][quantity]`"
                                                        x-model="item.quantity" min="0.01" step="0.01"
                                                        :max="item.max_quantity" required
                                                        class="w-20 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm mr-2">
                                                    <input type="hidden" :name="`items[${index}][satuan_id]`"
                                                        :value="item.satuan_id">
                                                    <span class="text-sm text-gray-500 dark:text-gray-400"
                                                        x-text="item.satuan_nama"></span>
                                                </div>
                                            </td>
                                            <td class="px-2 py-4">
                                                <select :name="`items[${index}][alasan]`" x-model="item.alasan"
                                                    required
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                    <option value="">-- Pilih Alasan --</option>
                                                    <option value="Rusak">Rusak</option>
                                                    <option value="Cacat">Cacat</option>
                                                    <option value="Tidak Sesuai">Tidak Sesuai</option>
                                                    <option value="Kadaluarsa">Kadaluarsa</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </td>
                                            <td class="px-2 py-4">
                                                <input type="text" :name="`items[${index}][keterangan]`"
                                                    x-model="item.keterangan" placeholder="Keterangan tambahan"
                                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                            </td>
                                            <td class="px-2 py-4 text-center">
                                                <button type="button" @click="removeItem(index)"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>

                            <!-- No items case -->
                            <div class="text-center py-4" x-show="items.length === 0">
                                <p class="text-gray-500 dark:text-gray-400">
                                    Tidak ada item yang ditambahkan dalam retur pembelian.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('pembelian.retur-pembelian.show', $returPembelian->id) }}"
                            class="px-5 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-md text-sm font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md text-sm font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function returPembelianEditForm() {
                return {
                    items: @json($returItems),
                    availableItems: @json($availableItems),

                    init() {
                        // Add max_quantity to each item based on original quantity plus current value
                        this.items = this.items.map(item => ({
                            ...item,
                            max_quantity: parseFloat(item.original_quantity) + parseFloat(item.quantity)
                        }));
                    },

                    addItem() {
                        // Find items that aren't already in the items array
                        const availableToAdd = this.availableItems.filter(available =>
                            !this.items.some(item => item.produk_id === available.produk_id)
                        );

                        if (availableToAdd.length === 0) {
                            alert('Semua item yang tersedia sudah ditambahkan.');
                            return;
                        }

                        // Add the first available item
                        this.items.push({
                            ...availableToAdd[0],
                            id: null, // New item doesn't have an ID yet
                            quantity: Math.min(1, availableToAdd[0].quantity),
                            alasan: '',
                            keterangan: '',
                            max_quantity: availableToAdd[0].quantity
                        });
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                    }
                }
            }
        </script>
    @endpush
</x-app-layout>
