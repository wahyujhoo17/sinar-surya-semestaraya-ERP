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
                                Buat Penyesuaian Stok Baru
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
                        Sesuaikan pencatatan stok sistem dengan stok fisik aktual di gudang.
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

        <form id="penyesuaian-form" action="{{ route('inventaris.penyesuaian-stok.store') }}" method="POST"
            x-data="penyesuaianStokForm()" @submit="validateForm() ? addHiddenFields() : $event.preventDefault()">
            @csrf

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
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Nomor <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nomor" id="nomor" x-model="formData.nomor"
                                    value="{{ $nomor }}" readonly
                                    class="bg-gray-50 dark:bg-gray-700/30 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                <div x-show="errors.nomor" class="text-red-500 text-sm mt-1" x-text="errors.nomor">
                                </div>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label for="tanggal"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" id="tanggal" x-model="formData.tanggal"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                <div x-show="errors.tanggal" class="text-red-500 text-sm mt-1" x-text="errors.tanggal">
                                </div>
                            </div>

                            {{-- Gudang --}}
                            <div>
                                <label for="gudang_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Gudang <span class="text-red-500">*</span>
                                </label>
                                <select name="gudang_id" id="gudang_id" x-model="formData.gudang_id"
                                    @change="changeGudang"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white">
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach ($gudangs as $gudang)
                                        <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                    @endforeach
                                </select>
                                <div x-show="errors.gudang_id" class="text-red-500 text-sm mt-1"
                                    x-text="errors.gudang_id"></div>
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label for="catatan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Catatan
                                </label>
                                <textarea name="catatan" id="catatan" x-model="formData.catatan" rows="3"
                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white"
                                    placeholder="Masukkan keterangan tambahan jika ada..."></textarea>
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
                                <li>Pilih gudang terlebih dahulu untuk menampilkan produk yang tersedia</li>
                                <li>Stok tercatat adalah jumlah stok yang terdata di sistem</li>
                                <li>Stok fisik adalah jumlah stok yang sebenarnya di gudang setelah dihitung</li>
                                <li>Selisih akan otomatis dihitung berdasarkan stok fisik dan stok tercatat</li>
                                <li>Status draft bisa diubah kapan saja, namun setelah diproses tidak bisa diubah lagi
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end pt-4">
                        <div class="flex space-x-2">
                            <button type="button" id="btn-print-draft" @click="handlePrintDraft"
                                x-show="validateForm(true)"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-600 focus:outline-none focus:border-blue-600 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                                </svg>
                                Cetak Draft
                            </button>
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

                            <div x-show="!formData.gudang_id"
                                class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-md mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-600"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-400">Perhatian
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>
                                                Silakan pilih gudang terlebih dahulu untuk menampilkan daftar produk
                                                yang akan disesuaikan
                                                stoknya.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-fixed">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-5/12">
                                                Produk <span class="text-red-500">*</span>
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-2/12 min-w-[120px]">
                                                Stok Tercatat
                                            </th>
                                            <th scope="col"
                                                class="px-3 py-3.5 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-2/12 min-w-[120px]">
                                                Stok Fisik <span class="text-red-500">*</span>
                                            </th>
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
                                                Keterangan
                                            </th>
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
                                                            item.produk_id = produk.id;
                                                            item.satuan_id = produk.satuan_id || '';
                                                            item.satuan_nama = produk.satuan || '';
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
                                                                        const nama = produks.find(p => p.id == item.produk_id)?.nama || 'Cari produk...'; 
                                                                        return nama.length > 25 ? nama.substring(0, 25) + '...' : nama;
                                                                    })()"
                                                                    class="truncate text-gray-900 dark:text-white"
                                                                    :class="item.produk_id ? 'pr-12' : 'pr-8'"
                                                                    :title="produks.find(p => p.id == item.produk_id)?.nama ||
                                                                        ''"></span>
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
                                                                        :key="produk.id">
                                                                        <li>
                                                                            <button type="button"
                                                                                @click="selectProduk(produk)"
                                                                                :class="{
                                                                                    'bg-primary-100 dark:bg-primary-700 text-primary-700 dark:text-white': produk
                                                                                        .id == item
                                                                                        .produk_id,
                                                                                    'hover:bg-primary-50 dark:hover:bg-primary-600': produk
                                                                                        .id != item.produk_id
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
                                                    <p class="text-xs mt-1" x-show="!formData.gudang_id">Pilih gudang
                                                        terlebih dahulu untuk
                                                        menambahkan produk</p>
                                                    <button @click="addItem" type="button"
                                                        x-show="formData.gudang_id"
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
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Sedang memproses penyesuaian stok. Mohon tunggu sebentar...
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">
                        Jangan tutup atau refresh halaman ini.
                    </p>
                </div>
            </div>
        </form>
    </div>

    <script>
        function penyesuaianStokForm() {
            return {
                formData: {
                    nomor: "{{ $nomor }}",
                    tanggal: "{{ date('Y-m-d') }}",
                    gudang_id: "",
                    catatan: "",
                },
                items: [],
                produks: [],
                isLoading: false,
                loading: false,
                showAlert: false,
                errors: {},

                init() {
                    this.addItem();

                    // Watch for gudang changes to initialize Select2 after products are loaded
                    this.$watch('produks', (newValue, oldValue) => {
                        if (newValue.length > 0) {
                            this.$nextTick(() => {
                                // Initialize Select2 for each item
                                this.items.forEach((_, index) => {
                                    this.initializeSelect2(index);
                                });
                            });
                        }
                    });
                },

                async changeGudang() {
                    this.produks = [];
                    this.items = [];
                    this.addItem();

                    if (this.formData.gudang_id) {
                        this.isLoading = true;
                        try {
                            const response = await fetch(`/inventaris/gudang/${this.formData.gudang_id}/produks`);
                            const data = await response.json();
                            this.produks = data;
                        } catch (error) {
                            console.error('Error fetching products: ', error);
                            alert('Gagal mengambil data produk. Silahkan coba lagi.');
                        } finally {
                            this.isLoading = false;
                        }
                    }
                },

                addItem() {
                    const index = this.items.length;
                    this.items.push({
                        produk_id: "",
                        stok_tercatat: 0,
                        stok_fisik: 0,
                        selisih: 0,
                        satuan_id: "",
                        satuan_nama: "",
                        keterangan: ""
                    });

                    // Initialize Select2 for the new item after Vue has updated the DOM
                    this.$nextTick(() => {
                        this.initializeSelect2(index);
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

                        // Update item data
                        this.items[index].stok_tercatat = data.stok;
                        this.items[index].satuan_id = data.satuan_id;
                        this.items[index].satuan_nama = data.satuan;

                        // Initialize stok_fisik with same value as stok_tercatat for convenience
                        this.items[index].stok_fisik = data.stok;

                        // Calculate selisih
                        this.calculateSelisih(index);
                    } catch (error) {
                        console.error('Error fetching stock info: ', error);
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

                    // Validate form data
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

                    // Validate items
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

                handlePrintDraft() {
                    if (!this.validateForm()) {
                        return;
                    }

                    this.loading = true;

                    // Create a temporary form for the POST request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('inventaris.penyesuaian-stok.store') }}?print_draft=1';
                    form.target = '_blank';

                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrfToken);

                    // Add form data
                    const addHiddenField = (name, value) => {
                        const field = document.createElement('input');
                        field.type = 'hidden';
                        field.name = name;
                        field.value = value;
                        form.appendChild(field);
                    };

                    addHiddenField('nomor', this.formData.nomor);
                    addHiddenField('tanggal', this.formData.tanggal);
                    addHiddenField('gudang_id', this.formData.gudang_id);
                    addHiddenField('catatan', this.formData.catatan);

                    // Add items data
                    this.items.forEach((item, index) => {
                        addHiddenField(`produk_id[${index}]`, item.produk_id);
                        addHiddenField(`stok_tercatat[${index}]`, item.stok_tercatat);
                        addHiddenField(`stok_fisik[${index}]`, item.stok_fisik);
                        addHiddenField(`selisih[${index}]`, item.selisih);
                        addHiddenField(`satuan_id[${index}]`, item.satuan_id);
                        addHiddenField(`keterangan[${index}]`, item.keterangan || '');
                    });

                    // Append the form to the body and submit
                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);

                    this.loading = false;
                },

                handleSubmit() {
                    // This function is now unused - replaced by @submit="validateForm() ? true : $event.preventDefault()"
                    if (!this.validateForm()) {
                        return false;
                    }

                    this.loading = true;
                    this.showAlert = true;
                    return true;
                },

                // Called before form submission to add hidden fields
                addHiddenFields() {
                    // Make sure any hidden inputs for items are included
                    const form = document.getElementById('penyesuaian-form');

                    // Add hidden inputs for selisih values which might not have form elements
                    this.items.forEach((item, index) => {
                        // Only add selisih as it doesn't have an input field
                        const selisihInput = document.createElement('input');
                        selisihInput.type = 'hidden';
                        selisihInput.name = `selisih[${index}]`;
                        selisihInput.value = item.selisih || 0;
                        form.appendChild(selisihInput);
                    });

                    this.showAlert = true;
                    this.loading = true;
                    return true;
                },

                initializeSelect2(index) {
                    // Wait for the DOM to be updated
                    setTimeout(() => {
                        const elementId = `product-select-${index}`;
                        const selectElement = document.getElementById(elementId);

                        if (selectElement && typeof $.fn.select2 !== 'undefined') {
                            $(selectElement).select2({
                                placeholder: "Cari produk...",
                                theme: document.documentElement.classList.contains('dark') ?
                                    'select2-dark' : 'select2-light',
                                dropdownCssClass: 'select2-dropdown-clear',
                                allowClear: true,
                                width: '100%'
                            }).on('select2:select', (e) => {
                                // Trigger the Alpine.js change event
                                this.items[index].produk_id = e.target.value;
                                this.updateStokInfo(index);
                            });
                        }
                    }, 100);
                }
            };
        }
    </script>

    <!-- Include Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        /* Select2 Dark Theme */
        .select2-dropdown-clear {
            z-index: 10000;
            /* Ensure dropdowns appear above other elements */
        }

        .select2-container--select2-dark .select2-selection--single,
        .select2-container--select2-dark .select2-selection--multiple {
            background-color: rgba(55, 65, 81, 0.7) !important;
            border-color: rgb(75, 85, 99) !important;
            color: white !important;
        }

        .select2-container--select2-dark .select2-dropdown {
            background-color: rgb(31, 41, 55) !important;
            border-color: rgb(75, 85, 99) !important;
            color: white !important;
        }

        .select2-container--select2-dark .select2-results__option {
            color: rgb(209, 213, 219) !important;
        }

        .select2-container--select2-dark .select2-results__option--highlighted[aria-selected] {
            background-color: rgb(59, 130, 246) !important;
            color: white !important;
        }

        .select2-container--select2-dark .select2-results__option[aria-selected=true] {
            background-color: rgba(59, 130, 246, 0.6) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Alpine.store('penyesuaianStok', {
                initializeSelect2ForNewItem(index) {
                    // Initialize select2 for the newly added item
                    if (typeof Alpine.raw !== 'undefined') {
                        const component = Alpine.raw(document.querySelector(
                            '[x-data="penyesuaianStokForm()"]'));
                        if (component && typeof component.initializeSelect2 === 'function') {
                            component.initializeSelect2(index);
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
