<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                            <span class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-primary-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Edit Penyesuaian Stok
                            </span>
                        </h1>
                        <div class="hidden md:block">
                            <a href="{{ route('inventaris.penyesuaian-stok.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-400 max-w-3xl">
                        Ubah data penyesuaian stok sesuai kebutuhan.
                    </p>
                </div>
                <div class="mt-5 md:hidden">
                    <span class="block">
                        <a href="{{ route('inventaris.penyesuaian-stok.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                    </span>
                </div>
            </div>
        </div>
        <form id="penyesuaian-form" action="{{ route('inventaris.penyesuaian-stok.update', $penyesuaian->id) }}"
            method="POST" x-data="penyesuaianStokFormEdit()"
            @submit="validateForm() ? addHiddenFields() : $event.preventDefault()">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Left Panel - Form Information --}}
                <div class="lg:col-span-1 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Informasi Penyesuaian
                        </h2>
                        <div class="space-y-4">
                            {{-- Nomor Penyesuaian --}}
                            <div>
                                <label for="nomor"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nomor" id="nomor" x-model="formData.nomor"
                                    value="{{ $penyesuaian->nomor }}" readonly
                                    class="bg-gray-50 dark:bg-gray-700/30 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                <div x-show="errors.nomor" class="text-red-500 text-sm mt-1" x-text="errors.nomor">
                                </div>
                            </div>
                            {{-- Tanggal --}}
                            <div>
                                <label for="tanggal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal
                                    <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal" id="tanggal" x-model="formData.tanggal"
                                    value="{{ $penyesuaian->tanggal }}"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                <div x-show="errors.tanggal" class="text-red-500 text-sm mt-1" x-text="errors.tanggal">
                                </div>
                            </div>
                            {{-- Gudang --}}
                            <div>
                                <label for="gudang_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gudang <span
                                        class="text-red-500">*</span></label>
                                <select name="gudang_id" id="gudang_id" x-model="formData.gudang_id"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white"
                                    disabled>
                                    @foreach ($gudangs as $gudang)
                                        <option value="{{ $gudang->id }}"
                                            @if ($penyesuaian->gudang_id == $gudang->id) selected @endif>{{ $gudang->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="gudang_id" :value="formData.gudang_id">
                                <div x-show="errors.gudang_id" class="text-red-500 text-sm mt-1"
                                    x-text="errors.gudang_id"></div>
                            </div>
                            {{-- Catatan --}}
                            <div>
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Catatan</label>
                                <textarea name="catatan" id="catatan" x-model="formData.catatan" rows="3"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white"
                                    placeholder="Masukkan keterangan tambahan jika ada...">{{ $penyesuaian->catatan }}</textarea>
                                <div x-show="errors.catatan" class="text-red-500 text-sm mt-1"
                                    x-text="errors.catatan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Bantuan
                            </h2>
                        </div>
                        <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-400">
                            <ul class="space-y-1 list-disc list-inside">
                                <li>Stok tercatat adalah jumlah stok yang terdata di sistem</li>
                                <li>Stok fisik adalah jumlah stok yang sebenarnya di gudang setelah dihitung</li>
                                <li>Selisih akan otomatis dihitung berdasarkan stok fisik dan stok tercatat</li>
                                <li>Status draft bisa diubah kapan saja, namun setelah diproses tidak bisa diubah lagi
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-center justify-end pt-4">
                        <div class="flex space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150"
                                :disabled="loading">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <span x-text="loading ? 'Menyimpan...' : 'Simpan'">Simpan</span>
                            </button>
                        </div>
                    </div>
                </div>
                {{-- Right Panel - Item List --}}
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-wrap items-center justify-between mb-4">
                                <h2
                                    class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Daftar Produk
                                </h2>
                                <button @click="addItem" type="button"
                                    class="inline-flex items-center px-3 py-1.5 bg-primary-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-1.5 h-4 w-4"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Tambah Produk
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-fixed">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-5/12">
                                                Produk <span class="text-red-500">*</span></th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-2/12 min-w-[120px]">
                                                Stok Tercatat</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-2/12 min-w-[120px]">
                                                Stok Fisik <span class="text-red-500">*</span></th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-2/12 min-w-[120px] relative">
                                                <span class="flex items-center">
                                                    Selisih
                                                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-3/12 min-w-[170px]">
                                                Keterangan</th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-10">
                                                <span class="sr-only">Aksi</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr>
                                                <td class="px-3 py-4">
                                                    <div class="relative" x-data="{
                                                        open: false,
                                                        search: '',
                                                        dropdownTop: 0,
                                                        dropdownLeft: 0,
                                                        dropdownWidth: 0,
                                                        get filteredProduk() {
                                                            if (!this.search) return produks;
                                                            return produks.filter(p => p.nama.toLowerCase().includes(this.search.toLowerCase()));
                                                        },
                                                        selectProduk(produk) {
                                                            item.produk_id = produk.produk_id;
                                                            item.satuan_id = produk.satuan_id || '';
                                                            item.satuan_nama = produk.satuan_nama || '';
                                                            $nextTick(() => { open = false; });
                                                            $dispatch('input');
                                                            updateStokInfo(index);
                                                        },
                                                        clearProduk() {
                                                            item.produk_id = '';
                                                            item.satuan_id = '';
                                                            item.satuan_nama = '';
                                                            open = false;
                                                        },
                                                        setDropdownPosition() {
                                                            this.$nextTick(() => {
                                                                const btn = this.$refs.inputBtn;
                                                                if (btn) {
                                                                    const rect = btn.getBoundingClientRect();
                                                                    this.dropdownTop = rect.bottom + window.scrollY;
                                                                    this.dropdownLeft = rect.left + window.scrollX;
                                                                    this.dropdownWidth = Math.min(rect.width, 350); // Limit to 350px max
                                                                }
                                                            });
                                                        }
                                                    }"
                                                        @click.away="open = false"
                                                        @keydown.escape.window="open = false">
                                                        <input type="hidden" :name="`produk_id[${index}]`"
                                                            x-model="item.produk_id">
                                                        <div class="relative w-full max-w-[300px]">
                                                            <button type="button"
                                                                @click="open = !open; if(open) setDropdownPosition()"
                                                                :disabled="isLoading"
                                                                class="w-full text-left bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 flex items-center justify-between"
                                                                x-ref="inputBtn">
                                                                <span
                                                                    x-text="(() => { 
                                                                        const nama = produks.find(p => p.produk_id == item.produk_id)?.nama || 'Cari produk...'; 
                                                                        return nama.length > 25 ? nama.substring(0, 25) + '...' : nama;
                                                                    })()"
                                                                    class="truncate text-gray-900 dark:text-white"
                                                                    :class="item.produk_id ? 'pr-12' : 'pr-8'"
                                                                    :title="produks.find(p => p.produk_id == item.produk_id)
                                                                        ?.nama || ''"></span>
                                                                <div
                                                                    class="absolute right-0 inset-y-0 flex items-center pr-3 pointer-events-none">
                                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 9l-7 7-7-7" />
                                                                    </svg>
                                                                </div>
                                                            </button>
                                                            <template x-if="item.produk_id">
                                                                <button type="button" @click="clearProduk()"
                                                                    class="absolute right-8 top-0 bottom-0 flex items-center justify-center px-2 text-gray-400 hover:text-red-500 focus:outline-none">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </template>
                                                        </div>
                                                        <template x-if="open">
                                                            <div class="fixed z-50 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto max-w-[350px]"
                                                                :style="`top: ${dropdownTop}px; left: ${dropdownLeft}px; width: ${dropdownWidth > 350 ? '350px' : dropdownWidth + 'px'}`">
                                                                <div class="p-2">
                                                                    <input type="text" x-model="search"
                                                                        placeholder="Ketik untuk mencari produk..."
                                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm"
                                                                        autofocus>
                                                                </div>
                                                                <ul>
                                                                    <template x-for="produk in filteredProduk"
                                                                        :key="produk.produk_id">
                                                                        <li>
                                                                            <button type="button"
                                                                                @click="selectProduk(produk)"
                                                                                :class="{
                                                                                    'bg-primary-100 dark:bg-primary-700 text-primary-700 dark:text-white': produk
                                                                                        .produk_id == item
                                                                                        .produk_id,
                                                                                    'hover:bg-primary-50 dark:hover:bg-primary-600': produk
                                                                                        .produk_id != item.produk_id
                                                                                }"
                                                                                class="w-full text-left px-3 py-2 text-sm cursor-pointer flex items-center">
                                                                                <span x-text="produk.nama"
                                                                                    class="truncate"
                                                                                    :title="produk.nama"></span>
                                                                            </button>
                                                                        </li>
                                                                    </template>
                                                                    <template x-if="filteredProduk.length === 0">
                                                                        <li>
                                                                            <span
                                                                                class="block px-3 py-2 text-sm text-gray-500">Tidak
                                                                                ada produk ditemukan</span>
                                                                        </li>
                                                                    </template>
                                                                </ul>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                                        x-show="item.satuan_nama && item.produk_id">
                                                        Satuan: <span x-text="item.satuan_nama"></span>
                                                        <input type="hidden" :name="`satuan_id[${index}]`"
                                                            x-model="item.satuan_id">
                                                    </div>
                                                    <div x-show="errors[`produk_id.${index}`]"
                                                        class="text-red-500 text-xs mt-1"
                                                        x-text="errors[`produk_id.${index}`]"></div>
                                                </td>
                                                <td class="px-3 py-4">
                                                    <div class="flex items-center">
                                                        <div class="relative w-full">
                                                            <input type="number" :name="`stok_tercatat[${index}]`"
                                                                x-model="item.stok_tercatat" step="0.01" readonly
                                                                class="bg-gray-50 dark:bg-gray-700/30 shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:text-white rounded-md min-w-[100px]">
                                                            <div
                                                                class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                                <svg class="h-4 w-4 text-gray-400" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400"
                                                            x-text="item.satuan_nama"></span>
                                                    </div>
                                                    <div x-show="errors[`stok_tercatat.${index}`]"
                                                        class="text-red-500 text-xs mt-1"
                                                        x-text="errors[`stok_tercatat.${index}`]"></div>
                                                </td>
                                                <td class="px-3 py-4">
                                                    <div class="flex items-center">
                                                        <div class="relative w-full">
                                                            <input type="number" :name="`stok_fisik[${index}]`"
                                                                x-model="item.stok_fisik" step="0.01"
                                                                min="0" @input="calculateSelisih(index)"
                                                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md min-w-[100px]">

                                                        </div>
                                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400"
                                                            x-text="item.satuan_nama"></span>
                                                    </div>
                                                    <div x-show="errors[`stok_fisik.${index}`]"
                                                        class="text-red-500 text-xs mt-1"
                                                        x-text="errors[`stok_fisik.${index}`]"></div>
                                                </td>
                                                <td class="px-3 py-4">
                                                    <div
                                                        class="flex items-center min-w-[100px] bg-gray-50 dark:bg-gray-700/30 rounded-md px-3 py-1.5 border border-transparent">
                                                        <span class="font-medium"
                                                            :class="{
                                                                'text-green-600 dark:text-green-400': parseFloat(item
                                                                    .selisih) > 0,
                                                                'text-red-600 dark:text-red-400': parseFloat(item
                                                                    .selisih) < 0,
                                                                'text-gray-600 dark:text-gray-400': parseFloat(item
                                                                    .selisih) === 0 || !item.selisih
                                                            }"
                                                            x-text="formatSelisih(item.selisih)"></span>
                                                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400"
                                                            x-text="item.satuan_nama"></span>
                                                    </div>
                                                </td>
                                                <td class="px-3 py-4">
                                                    <div class="relative">
                                                        <input type="text" :name="`keterangan[${index}]`"
                                                            x-model="item.keterangan"
                                                            class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md min-w-[150px] pl-2 pr-6"
                                                            placeholder="Keterangan">
                                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                                                            x-show="item.keterangan">
                                                            <svg class="h-4 w-4 text-gray-400" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-3 py-4 text-right">
                                                    <button type="button" @click="removeItem(index)"
                                                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="items.length === 0">
                                            <td colspan="6"
                                                class="px-3 py-10 text-center text-gray-500 dark:text-gray-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    <p class="mt-1 text-sm font-medium">Belum ada produk</p>
                                                    <button @click="addItem" type="button"
                                                        class="mt-3 inline-flex items-center px-3 py-1.5 bg-primary-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="-ml-0.5 mr-1.5 h-4 w-4" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Tambah Produk
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Alert when submitting --}}
            <div x-show="showAlert" x-cloak
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-md mx-auto">
                    <div class="flex items-center mb-4">
                        <svg class="h-10 w-10 text-primary-600 animate-spin mr-4" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Memproses Penyesuaian Stok</h3>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Sedang memproses penyesuaian stok. Mohon tunggu
                        sebentar...</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">Jangan tutup atau refresh halaman ini.</p>
                </div>
            </div>
        </form>
    </div>
    <script>
        function penyesuaianStokFormEdit() {
            return {
                formData: {
                    nomor: @json($penyesuaian->nomor),
                    tanggal: @json($penyesuaian->tanggal),
                    gudang_id: @json($penyesuaian->gudang_id),
                    catatan: @json($penyesuaian->catatan),
                },
                items: @json($items),
                produks: @json($allStokProduk),
                isLoading: false,
                loading: false,
                showAlert: false,
                errors: {},
                addItem() {
                    const index = this.items.length;
                    this.items.push({
                        produk_id: '',
                        stok_tercatat: 0,
                        stok_fisik: 0,
                        selisih: 0,
                        satuan_id: '',
                        satuan_nama: '',
                        keterangan: ''
                    });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                async updateStokInfo(index) {
                    const produkId = this.items[index].produk_id;
                    if (!produkId || !this.formData.gudang_id) return;
                    this.isLoading = true;
                    try {
                        const response = await fetch(
                            `/inventaris/penyesuaian-stok/get-stok?produk_id=${produkId}&gudang_id=${this.formData.gudang_id}`
                        );
                        const data = await response.json();
                        if (data.error) {
                            alert('Error: ' + data.error);
                            return;
                        }
                        this.items[index].stok_tercatat = data.stok;
                        this.items[index].satuan_id = data.satuan_id;
                        this.items[index].satuan_nama = data.satuan;
                        this.items[index].stok_fisik = data.stok;
                        this.calculateSelisih(index);
                    } catch (error) {
                        alert('Gagal mengambil informasi stok. Silahkan coba lagi.');
                    } finally {
                        this.isLoading = false;
                    }
                },
                calculateSelisih(index) {
                    const stokTercatat = parseFloat(this.items[index].stok_tercatat) || 0;
                    const stokFisik = parseFloat(this.items[index].stok_fisik) || 0;
                    this.items[index].selisih = stokFisik - stokTercatat;
                },
                formatSelisih(value) {
                    if (!value && value !== 0) return '-';
                    const numericValue = parseFloat(value);
                    return (numericValue > 0 ? '+' : '') + numericValue;
                },
                validateForm(silentMode = false) {
                    this.errors = {};
                    let valid = true;
                    if (!this.formData.nomor) {
                        this.errors.nomor = 'Nomor penyesuaian harus diisi.';
                        valid = false;
                    }
                    if (!this.formData.tanggal) {
                        this.errors.tanggal = 'Tanggal harus diisi.';
                        valid = false;
                    }
                    if (!this.formData.gudang_id) {
                        this.errors.gudang_id = 'Gudang harus dipilih.';
                        valid = false;
                    }
                    if (this.items.length === 0) {
                        if (!silentMode) alert('Tambahkan minimal 1 produk.');
                        return false;
                    }
                    for (let i = 0; i < this.items.length; i++) {
                        const item = this.items[i];
                        if (!item.produk_id) {
                            this.errors[`produk_id.${i}`] = 'Produk harus dipilih.';
                            valid = false;
                        }
                        if (!item.stok_fisik && item.stok_fisik !== 0) {
                            this.errors[`stok_fisik.${i}`] = 'Stok fisik harus diisi.';
                            valid = false;
                        }
                    }
                    return valid;
                },
                addHiddenFields() {
                    const form = document.getElementById('penyesuaian-form');
                    this.items.forEach((item, index) => {
                        const selisihInput = document.createElement('input');
                        selisihInput.type = 'hidden';
                        selisihInput.name = `selisih[${index}]`;
                        selisihInput.value = item.selisih || 0;
                        form.appendChild(selisihInput);
                    });
                    this.showAlert = true;
                    this.loading = true;
                    return true;
                }
            };
        }
    </script>
</x-app-layout>
