<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Detail Product Bundle</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Informasi lengkap bundle: {{ $productBundle->nama }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    @hasPermission('product_bundle.edit')
                        <a href="{{ route('master.product-bundle.edit', $productBundle->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </a>
                    @endhasPermission
                    <a href="{{ route('master.product-bundle.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Bundle Info -->
            <div class="xl:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex justify-between items-center">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Bundle</h4>
                            <div class="flex items-center space-x-3">
                                @hasPermission('product_bundle.edit')
                                    <a href="{{ route('master.product-bundle.edit', $productBundle->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
                                @endhasPermission
                                @hasPermission('product_bundle.delete')
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                        onclick="confirmDelete({{ $productBundle->id }})">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                @endhasPermission
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            {{-- Informasi Utama Bundle --}}
                            <div class="lg:col-span-2">
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6 mb-6">
                                    <h3
                                        class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Informasi Dasar
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="space-y-3">
                                            <div>
                                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Kode
                                                    Bundle</label>
                                                <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $productBundle->kode }}</div>
                                            </div>
                                            <div>
                                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400">Nama
                                                    Bundle</label>
                                                <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ $productBundle->nama }}</div>
                                            </div>
                                            <div>
                                                <label
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">Kategori</label>
                                                <div class="mt-1">
                                                    @if ($productBundle->kategori)
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                                </path>
                                                            </svg>
                                                            {{ $productBundle->kategori->nama }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-500 dark:text-gray-400 italic">Belum ada
                                                            kategori</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <label
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                                                <div class="mt-1">
                                                    @if ($productBundle->is_active)
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Aktif
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Tidak Aktif
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <label
                                                    class="text-sm font-medium text-gray-600 dark:text-gray-400">Tanggal
                                                    Dibuat</label>
                                                <div class="mt-1 text-gray-900 dark:text-white">
                                                    {{ $productBundle->created_at->format('d M Y H:i') }}</div>
                                            </div>
                                            @if ($productBundle->updated_at != $productBundle->created_at)
                                                <div>
                                                    <label
                                                        class="text-sm font-medium text-gray-600 dark:text-gray-400">Terakhir
                                                        Diupdate</label>
                                                    <div class="mt-1 text-gray-900 dark:text-white">
                                                        {{ $productBundle->updated_at->format('d M Y H:i') }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Informasi Harga --}}
                                <div
                                    class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg p-6 mb-6">
                                    <h3
                                        class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                            </path>
                                        </svg>
                                        Informasi Harga
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                        <div class="text-center bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                Harga Bundle</div>
                                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                                Rp {{ number_format($productBundle->harga_bundle, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="text-center bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                Harga Normal</div>
                                            <div class="text-xl font-semibold text-gray-700 dark:text-gray-300">
                                                Rp
                                                {{ number_format($productBundle->harga_normal ?? $productBundle->total_harga_normal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div class="text-center bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">
                                                Penghematan</div>
                                            @php
                                                $hemat =
                                                    ($productBundle->harga_normal ??
                                                        $productBundle->total_harga_normal) -
                                                    $productBundle->harga_bundle;
                                                $persentase =
                                                    ($productBundle->harga_normal ??
                                                        $productBundle->total_harga_normal) >
                                                    0
                                                        ? ($hemat /
                                                                ($productBundle->harga_normal ??
                                                                    $productBundle->total_harga_normal)) *
                                                            100
                                                        : 0;
                                            @endphp
                                            <div class="text-lg font-bold text-orange-600 dark:text-orange-400">
                                                Rp {{ number_format($hemat, 0, ',', '.') }}
                                            </div>
                                            <div class="text-sm text-orange-500">{{ number_format($persentase, 1) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Deskripsi --}}
                                @if ($productBundle->deskripsi)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                        <h3
                                            class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Deskripsi Bundle
                                        </h3>
                                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                            {{ $productBundle->deskripsi }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Gambar Bundle --}}
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                    <h3
                                        class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Gambar Bundle
                                    </h3>
                                    <div
                                        class="aspect-square bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm">
                                        @if ($productBundle->gambar && Storage::disk('public')->exists($productBundle->gambar))
                                            <img src="{{ Storage::url($productBundle->gambar) }}"
                                                alt="{{ $productBundle->nama }}"
                                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300 cursor-pointer"
                                                onclick="openImageModal('{{ Storage::url($productBundle->gambar) }}', '{{ $productBundle->nama }}')">
                                        @else
                                            <div
                                                class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                                <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                <p class="text-sm font-medium">Tidak ada gambar</p>
                                                <p class="text-xs text-center mt-1">Upload gambar untuk menampilkan
                                                    preview bundle</p>
                                            </div>
                                        @endif
                                    </div>
                                    @hasPermission('product_bundle.edit')
                                        <div class="mt-4">
                                            <a href="{{ route('master.product-bundle.edit', $productBundle->id) }}"
                                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                                Upload/Ganti Gambar
                                            </a>
                                        </div>
                                    @endhasPermission
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bundle Items -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Produk dalam Bundle
                            ({{ $productBundle->items->count() }} item)</h4>
                    </div>
                    <div class="p-6">
                        @if ($productBundle->items->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Produk</th>
                                            <th
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Quantity</th>
                                            <th
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Harga Satuan</th>
                                            <th
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Subtotal</th>
                                            <th
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800">
                                        @foreach ($productBundle->items as $item)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td
                                                    class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                    <div>
                                                        <div class="font-semibold">{{ $item->produk->nama }}</div>
                                                        <div class="text-gray-500 dark:text-gray-400 text-xs">Kode:
                                                            {{ $item->produk->kode }}</div>
                                                    </div>
                                                </td>
                                                <td
                                                    class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm text-gray-900 dark:text-white text-center">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </td>
                                                <td
                                                    class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                                    Rp
                                                    {{ number_format($item->quantity * $item->harga_satuan, 0, ',', '.') }}
                                                </td>
                                                <td
                                                    class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm text-gray-900 dark:text-white text-center">
                                                    @php
                                                        $stokTersedia = $item->produk->stokTersedia();
                                                    @endphp
                                                    @if ($stokTersedia >= $item->quantity)
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            {{ $stokTersedia }} tersedia
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            {{ $stokTersedia }} (kurang
                                                            {{ $item->quantity - $stokTersedia }})
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <td colspan="3"
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white text-right">
                                                Total Harga Normal:
                                            </td>
                                            <td colspan="2"
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-bold text-gray-900 dark:text-white">
                                                Rp
                                                {{ number_format($productBundle->items->sum(function ($item) {return $item->quantity * $item->harga_satuan;}),0,',','.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400 text-right">
                                                Harga Bundle:
                                            </td>
                                            <td colspan="2"
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-bold text-green-600 dark:text-green-400">
                                                Rp {{ number_format($productBundle->harga_bundle, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-semibold text-orange-600 dark:text-orange-400 text-right">
                                                Penghematan:
                                            </td>
                                            <td colspan="2"
                                                class="border border-gray-300 dark:border-gray-600 px-4 py-3 text-sm font-bold text-orange-600 dark:text-orange-400">
                                                @php
                                                    $totalNormal = $productBundle->items->sum(function ($item) {
                                                        return $item->quantity * $item->harga_satuan;
                                                    });
                                                    $hemat = $totalNormal - $productBundle->harga_bundle;
                                                    $persentase = $totalNormal > 0 ? ($hemat / $totalNormal) * 100 : 0;
                                                @endphp
                                                Rp {{ number_format($hemat, 0, ',', '.') }}
                                                ({{ number_format($persentase, 1) }}%)
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-gray-400 dark:text-gray-500 py-8">
                                <svg class="w-16 h-16 mx-auto mb-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <p>Belum ada produk dalam bundle ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Summary Info -->
            <div class="xl:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Bundle</h4>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-600">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Produk:</span>
                                <span
                                    class="text-sm font-semibold text-gray-900 dark:text-white">{{ $productBundle->items->count() }}
                                    item</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-600">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Total Quantity:</span>
                                <span
                                    class="text-sm font-semibold text-gray-900 dark:text-white">{{ $productBundle->items->sum('quantity') }}
                                    pcs</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-600">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Harga Normal:</span>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Rp
                                    {{ number_format($productBundle->items->sum(function ($item) {return $item->quantity * $item->harga_satuan;}),0,',','.') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-600">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Harga Bundle:</span>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">Rp
                                    {{ number_format($productBundle->harga_bundle, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Penghematan:</span>
                                <span class="text-sm font-bold text-orange-600 dark:text-orange-400">
                                    @php
                                        $totalNormal = $productBundle->items->sum(function ($item) {
                                            return $item->quantity * $item->harga_satuan;
                                        });
                                        $hemat = $totalNormal - $productBundle->harga_bundle;
                                        $persentase = $totalNormal > 0 ? ($hemat / $totalNormal) * 100 : 0;
                                    @endphp
                                    Rp {{ number_format($hemat, 0, ',', '.') }} ({{ number_format($persentase, 1) }}%)
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            @hasPermission('product_bundle.edit')
                                <a href="{{ route('master.product-bundle.edit', $productBundle->id) }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit Bundle
                                </a>
                            @endhasPermission
                            @hasPermission('product_bundle.create')
                                <a href="{{ route('master.product-bundle.create') }}"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Buat Bundle Baru
                                </a>
                            @endhasPermission
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk Gambar --}}
    <div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-75">
        <div class="relative max-w-4xl max-h-screen mx-4">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <img id="modalImage" src="" alt=""
                class="max-w-full max-h-screen object-contain rounded-lg">
        </div>
    </div>

    <script>
        function openImageModal(src, alt) {
            document.getElementById('modalImage').src = src;
            document.getElementById('modalImage').alt = alt;
            document.getElementById('imageModal').classList.remove('hidden');
            document.getElementById('imageModal').classList.add('flex');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('imageModal').classList.remove('flex');
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        function confirmDelete(bundleId) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus bundle ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/master/product-bundle/${bundleId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>
