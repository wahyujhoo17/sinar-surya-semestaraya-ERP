<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="mb-6">
                        <nav class="mb-5">
                            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                <li>
                                    <a href="{{ route('dashboard') }}"
                                        class="hover:text-primary-600 dark:hover:text-primary-400">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <svg class="w-4 h-4 inline mx-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </li>
                                <li>
                                    <a href="{{ route('pembelian.retur-pembelian.index') }}"
                                        class="hover:text-primary-600 dark:hover:text-primary-400">
                                        Retur Pembelian
                                    </a>
                                </li>
                                <li>
                                    <svg class="w-4 h-4 inline mx-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </li>
                                <li>
                                    <a href="{{ route('pembelian.retur-pembelian.show', $returPembelian->id) }}"
                                        class="hover:text-primary-600 dark:hover:text-primary-400">
                                        {{ $returPembelian->nomor }}
                                    </a>
                                </li>
                                <li>
                                    <svg class="w-4 h-4 inline mx-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </li>
                                <li>Terima Barang Pengganti</li>
                            </ol>
                        </nav>

                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Terima Barang Pengganti untuk Retur #{{ $returPembelian->nomor }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Supplier: {{ $returPembelian->supplier->nama }}
                        </p>
                    </div>

                    @if ($errors->any())
                        <div
                            class="mb-4 bg-red-50 dark:bg-red-900/50 p-4 rounded-lg border border-red-200 dark:border-red-800">
                            <div class="font-medium text-red-600 dark:text-red-400">
                                {{ __('Whoops! Ada masalah dengan input Anda.') }}</div>

                            <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        action="{{ route('pembelian.retur-pembelian.proses-terima-barang-pengganti', $returPembelian->id) }}"
                        method="POST" x-data="barangPenggantiForm()">
                        @csrf

                        <div class="space-y-8">
                            <!-- Informasi Dasar -->
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <h4 class="text-base font-medium text-gray-900 dark:text-white mb-4">Informasi
                                    Penerimaan
                                </h4>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="tanggal_penerimaan"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                            Penerimaan <span class="text-red-500">*</span></label>
                                        <input type="date" name="tanggal_penerimaan" id="tanggal_penerimaan"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            value="{{ old('tanggal_penerimaan', date('Y-m-d')) }}" required>
                                    </div>

                                    <div>
                                        <label for="gudang_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gudang
                                            <span class="text-red-500">*</span></label>
                                        <select id="gudang_id" name="gudang_id"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            required>
                                            <option value="">Pilih Gudang</option>
                                            @foreach ($gudangs as $gudang)
                                                <option value="{{ $gudang->id }}"
                                                    {{ old('gudang_id') == $gudang->id ? 'selected' : '' }}>
                                                    {{ $gudang->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="no_referensi"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">No
                                            Referensi
                                            (Opsional)</label>
                                        <input type="text" name="no_referensi" id="no_referensi"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            value="{{ old('no_referensi') }}" placeholder="Nomor Surat Jalan / DN">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="catatan_penerimaan"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan
                                            Penerimaan (Opsional)</label>
                                        <textarea id="catatan_penerimaan" name="catatan_penerimaan" rows="2"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                            placeholder="Catatan terkait penerimaan barang pengganti">{{ old('catatan_penerimaan') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Barang yang Diretur -->
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Barang yang
                                        Dikembalikan
                                    </h4>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-100 dark:bg-gray-800">
                                            <tr>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Kode</th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Nama Produk</th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Quantity</th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Satuan</th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Alasan</th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($returPembelian->details as $detail)
                                                <tr>
                                                    <td
                                                        class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ $detail->produk->kode }}</td>
                                                    <td
                                                        class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ $detail->produk->nama }}</td>
                                                    <td
                                                        class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ number_format($detail->quantity, 2, ',', '.') }}</td>
                                                    <td
                                                        class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ $detail->satuan->nama }}</td>
                                                    <td
                                                        class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                        {{ $detail->alasan }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Barang Pengganti -->
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Barang Pengganti
                                    </h4>
                                    <button type="button" @click="addItem"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Tambah Item
                                    </button>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-100 dark:bg-gray-800">
                                            <tr>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Produk</th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Quantity</th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                    Satuan</th>
                                                <th scope="col"
                                                    class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <template x-for="(item, index) in items" :key="index">
                                                <tr>
                                                    <td class="px-3 py-4">
                                                        <select x-model="item.produk_id"
                                                            :name="`items[${index}][produk_id]`"
                                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                                            required>
                                                            <option value="">Pilih Produk</option>
                                                            @foreach ($produks as $produk)
                                                                <option value="{{ $produk->id }}">
                                                                    {{ $produk->nama }}
                                                                    ({{ $produk->kode }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="px-3 py-4">
                                                        <input type="number" x-model="item.quantity"
                                                            :name="`items[${index}][quantity]`" step="0.01"
                                                            min="0.01"
                                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                                            required>
                                                    </td>
                                                    <td class="px-3 py-4">
                                                        <select x-model="item.satuan_id"
                                                            :name="`items[${index}][satuan_id]`"
                                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
                                                            required>
                                                            <option value="">Pilih Satuan</option>
                                                            @foreach ($produks as $produk)
                                                                @if ($produk->satuan)
                                                                    <template
                                                                        x-if="item.produk_id == {{ $produk->id }}">
                                                                        <option value="{{ $produk->satuan->id }}"
                                                                            selected>{{ $produk->satuan->nama }}
                                                                        </option>
                                                                    </template>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="px-3 py-4">
                                                        <button type="button" @click="removeItem(index)"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </template>
                                            <tr x-show="items.length === 0">
                                                <td colspan="4"
                                                    class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                    Belum ada barang pengganti yang ditambahkan. Silakan klik tombol
                                                    "Tambah
                                                    Item" untuk menambahkan.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="flex justify-between mt-6">
                                <a href="{{ route('pembelian.retur-pembelian.show', $returPembelian->id) }}"
                                    class="px-5 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-md text-sm font-medium">
                                    Batal
                                </a>
                                <button type="submit" x-bind:disabled="items.length === 0"
                                    x-bind:class="{ 'opacity-50 cursor-not-allowed': items.length === 0 }"
                                    class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md text-sm font-medium">
                                    Proses Penerimaan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function barangPenggantiForm() {
                return {
                    items: [],

                    addItem() {
                        this.items.push({
                            produk_id: '',
                            quantity: 1.0,
                            satuan_id: ''
                        });
                    },

                    removeItem(index) {
                        this.items.splice(index, 1);
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
