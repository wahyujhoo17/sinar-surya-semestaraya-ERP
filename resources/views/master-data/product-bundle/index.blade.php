<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="$currentPage">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Product Bundle</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Kelola paket produk dengan harga khusus dan stok individual
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('master.product-bundle.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Bundle
                    </a>
                </div>
            </div>
        </div>

        {{-- Success Alert --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg relative mb-6">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" @click="show = false"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 text-green-800 dark:text-green-200 hover:text-green-600 dark:hover:text-green-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg relative mb-6">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" @click="show = false"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 text-red-800 dark:text-red-200 hover:text-red-600 dark:hover:text-red-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Search and Filters Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                {{-- Search Form --}}
                <form method="GET" action="{{ route('master.product-bundle.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- Quick Search --}}
                        <div class="md:col-span-2">
                            <label for="search"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Pencarian Cepat
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Cari nama bundle, kode, atau deskripsi..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400">
                            </div>
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label for="status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status Bundle
                            </label>
                            <select name="status" id="status"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-300">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                </option>
                                <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>
                                    Non-Aktif</option>
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-end space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('master.product-bundle.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modern Table Section --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            {{-- Table Header --}}
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daftar Product Bundle</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola bundle produk dan paket penjualan</p>
                </div>
                <div class="flex items-center space-x-3">
                    {{-- Results Counter --}}
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Total: <span
                            class="font-medium text-gray-900 dark:text-gray-100">{{ $bundles->total() }}</span> bundle
                    </div>
                </div>
            </div>

            {{-- Table Content --}}
            <div class="overflow-x-auto">
                {{-- Per Page Selector --}}
                <div
                    class="px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan <span class="font-medium">{{ $bundles->firstItem() ?? 0 }}</span>
                        sampai <span class="font-medium">{{ $bundles->lastItem() ?? 0 }}</span>
                        dari <span class="font-medium">{{ $bundles->total() }}</span> hasil
                    </div>
                    <div class="flex items-center space-x-2">
                        <label for="per-page" class="text-xs text-gray-500 dark:text-gray-400">
                            Tampilkan:
                        </label>
                        <select id="per-page"
                            onchange="window.location.href = updateUrlParameter(window.location.href, 'per_page', this.value)"
                            class="bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs border border-gray-300 dark:border-gray-600 rounded py-1 pl-2 pr-6 focus:outline-none focus:ring-1 focus:ring-primary-500">
                            @foreach ([10, 25, 50, 100] as $option)
                                <option value="{{ $option }}"
                                    {{ request('per_page', 10) == $option ? 'selected' : '' }}>{{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            {{-- Bundle Name --}}
                            <th scope="col"
                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                <div class="flex items-center">
                                    <span>Bundle Produk</span>
                                </div>
                            </th>
                            {{-- Code --}}
                            <th scope="col"
                                class="px-5 py-3.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                Kode Bundle
                            </th>
                            {{-- Price --}}
                            <th scope="col"
                                class="px-5 py-3.5 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                Total Harga
                            </th>
                            {{-- Items Count --}}
                            <th scope="col"
                                class="px-5 py-3.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                Jumlah Item
                            </th>
                            {{-- Status --}}
                            <th scope="col"
                                class="px-5 py-3.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                Status
                            </th>
                            {{-- Actions --}}
                            <th scope="col"
                                class="px-5 py-3.5 text-center text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($bundles as $bundle)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                {{-- Bundle Information --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if (isset($bundle->gambar) && $bundle->gambar)
                                                <img src="{{ Storage::url($bundle->gambar) }}"
                                                    alt="{{ $bundle->nama }}"
                                                    class="h-10 w-10 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-white" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                {{ $bundle->nama ?? 'Nama bundle tidak tersedia' }}
                                            </div>
                                            @if (isset($bundle->deskripsi) && $bundle->deskripsi)
                                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ Str::limit($bundle->deskripsi, 60) }}
                                                </div>
                                            @endif
                                            @if ($bundle->kategori)
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                        {{ $bundle->kategori->nama }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                Dibuat {{ $bundle->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Code --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div
                                        class="text-sm font-mono text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                        {{ $bundle->kode ?? 'Tidak ada kode' }}
                                    </div>
                                </td>

                                {{-- Price --}}
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Rp {{ number_format($bundle->harga_bundle ?? 0, 0, ',', '.') }}
                                    </div>
                                    @if ($bundle->items && $bundle->items->count() > 0)
                                        @php
                                            $totalNormal = $bundle->total_harga_normal;
                                        @endphp
                                        @if ($totalNormal > $bundle->harga_bundle)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Normal: Rp {{ number_format($totalNormal, 0, ',', '.') }}
                                            </div>
                                            <div class="text-xs text-green-600 dark:text-green-400 font-medium">
                                                Hemat
                                                {{ number_format((($totalNormal - $bundle->harga_bundle) / $totalNormal) * 100, 1) }}%
                                            </div>
                                        @endif
                                    @endif
                                </td>

                                {{-- Items Count --}}
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    @if ($bundle->items && $bundle->items->count() > 0)
                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                                            title="Total {{ $bundle->items->sum('quantity') }} item dari {{ $bundle->items->count() }} produk berbeda">
                                            {{ $bundle->items->sum('quantity') }} item
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $bundle->items->count() }} produk
                                        </div>
                                    @else
                                        <div
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            0 item
                                        </div>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $bundle->is_active ?? false
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        <svg class="w-1.5 h-1.5 mr-1.5 {{ $bundle->is_active ?? false ? 'text-green-400' : 'text-red-400' }}"
                                            fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ $bundle->is_active ?? false ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        {{-- View Button --}}
                                        <a href="{{ route('master.product-bundle.show', $bundle) }}"
                                            class="inline-flex items-center p-1.5 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        {{-- Edit Button --}}
                                        <a href="{{ route('master.product-bundle.edit', $bundle->id) }}"
                                            class="inline-flex items-center p-1.5 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200"
                                            title="Edit Bundle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        {{-- Delete Button --}}
                                        <form action="{{ route('master.product-bundle.destroy', $bundle) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200"
                                                title="Hapus Bundle"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus bundle \"{{ $bundle->nama }}\"?\n\nTindakan ini tidak dapat dibatalkan.')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                            Belum ada bundle produk
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 max-w-sm">
                                            Mulai dengan membuat bundle produk pertama untuk mengelompokkan
                                            produk-produk terkait.
                                        </p>
                                        <a href="{{ route('master.product-bundle.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Buat Bundle Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Enhanced Pagination --}}
            @if ($bundles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if ($bundles->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $bundles->previousPageUrl() }}"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                    Sebelumnya
                                </a>
                            @endif

                            @if ($bundles->hasMorePages())
                                <a href="{{ $bundles->nextPageUrl() }}"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                                    Selanjutnya
                                </a>
                            @else
                                <span
                                    class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">
                                    Selanjutnya
                                </span>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Menampilkan
                                    <span class="font-medium">{{ $bundles->firstItem() ?? 0 }}</span>
                                    sampai
                                    <span class="font-medium">{{ $bundles->lastItem() ?? 0 }}</span>
                                    dari
                                    <span class="font-medium">{{ $bundles->total() }}</span>
                                    hasil
                                </p>
                            </div>
                            <div>
                                {{ $bundles->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <script>
            function updateUrlParameter(url, param, paramVal) {
                var newAdditionalURL = "";
                var tempArray = url.split("?");
                var baseURL = tempArray[0];
                var additionalURL = tempArray[1];
                var temp = "";

                if (additionalURL) {
                    tempArray = additionalURL.split("&");
                    for (var i = 0; i < tempArray.length; i++) {
                        if (tempArray[i].split('=')[0] != param) {
                            newAdditionalURL += temp + tempArray[i];
                            temp = "&";
                        }
                    }
                }

                var rows_txt = temp + "" + param + "=" + paramVal;
                return baseURL + "?" + newAdditionalURL + rows_txt;
            }
        </script>
    </div>
</x-app-layout>
