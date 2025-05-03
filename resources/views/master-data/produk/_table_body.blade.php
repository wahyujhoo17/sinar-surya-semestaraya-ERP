@forelse ($produks as $produk)
    @php
        // Hitung margin keuntungan
        $margin =
            $produk->harga_jual > 0 && $produk->harga_beli > 0
                ? (($produk->harga_jual - $produk->harga_beli) / $produk->harga_beli) * 100
                : 0;

        // Status stok berdasarkan stok_minimum
        $stokStatus = 'normal';
        if ($produk->total_stok <= 0) {
            $stokStatus = 'habis';
        } elseif ($produk->total_stok <= $produk->stok_minimum) {
            $stokStatus = 'warning';
        }
    @endphp
    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-150">
        {{-- Checkbox --}}
        <td class="px-3 py-4">
            <input type="checkbox" name="product_ids[]" value="{{ $produk->id }}"
                @click="updateSelectedProducts($event, '{{ $produk->id }}')"
                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
        </td>

        {{-- Gambar --}}
        @if ($visibleColumns['gambar'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex-shrink-0">
                    @if ($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}"
                            class="h-11 w-11 rounded-md object-cover border border-gray-200 dark:border-gray-600 shadow-sm"
                            onerror="this.onerror=null; this.src='{{ asset('images/placeholder-product.png') }}'; this.classList.add('bg-gray-100', 'dark:bg-gray-700');">
                    @else
                        <div
                            class="h-11 w-11 rounded-md bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 dark:text-gray-500 border border-gray-200 dark:border-gray-600">
                            {{-- Placeholder SVG --}}
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    @endif
                </div>
            </td>
        @endif

        {{-- Produk (Nama & Kode) --}}
        @if ($visibleColumns['produk'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap">
                <div class="ml-0 truncate w-48"> {{-- Removed margin, relies on spacing from image column --}}
                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $produk->nama }}">
                        {{ $produk->nama }}
                    </div>
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">
                            {{ $produk->kode }}
                        </span>
                    </div>
                </div>
            </td>
        @endif

        {{-- SKU --}}
        @if ($visibleColumns['sku'] ?? false)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 font-mono">
                {{ $produk->product_sku ?? '-' }}
            </td>
        @endif

        {{-- Kategori --}}
        @if ($visibleColumns['kategori'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap">
                @if ($produk->kategori)
                    <span
                        class="px-2 py-1 text-xs rounded-full {{ 'bg-' . ['purple', 'blue', 'green', 'yellow', 'indigo', 'red', 'pink'][$produk->kategori->id % 7] . '-100 dark:bg-' . ['purple', 'blue', 'green', 'yellow', 'indigo', 'red', 'pink'][$produk->kategori->id % 7] . '-900/30 text-' . ['purple', 'blue', 'green', 'yellow', 'indigo', 'red', 'pink'][$produk->kategori->id % 7] . '-800 dark:text-' . ['purple', 'blue', 'green', 'yellow', 'indigo', 'red', 'pink'][$produk->kategori->id % 7] . '-300' }}">
                        {{ $produk->kategori->nama }}
                    </span>
                @else
                    <span
                        class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">-</span>
                @endif
            </td>
        @endif

        {{-- Jenis --}}
        @if ($visibleColumns['jenis'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ $produk->jenis->nama ?? '-' }}
            </td>
        @endif

        {{-- Stok --}}
        @if ($visibleColumns['stok'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    @if ($stokStatus === 'habis')
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Habis
                        </span>
                    @elseif($stokStatus === 'warning')
                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            {{ $produk->total_stok }}
                        </span>
                    @else
                        <span
                            class="px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                            {{ $produk->total_stok }}
                        </span>
                    @endif
                    @if ($produk->stok_minimum > 0 && ($visibleColumns['stok_minimum'] ?? false))
                        {{-- Only show if stok_minimum column is also visible --}}
                        <span class="ml-1.5 text-xs text-gray-400 dark:text-gray-500">Min:
                            {{ $produk->stok_minimum }}</span>
                    @endif
                </div>
            </td>
        @endif

        {{-- Satuan --}}
        @if ($visibleColumns['satuan'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ $produk->satuan->nama ?? '-' }}
            </td>
        @endif

        {{-- Harga Jual --}}
        @if ($visibleColumns['harga_jual'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-right text-sm text-gray-800 dark:text-gray-300">
                Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
            </td>
        @endif

        {{-- Harga Beli --}}
        @if ($visibleColumns['harga_beli'] ?? false)
            <td class="px-5 py-4 whitespace-nowrap text-right text-sm text-gray-600 dark:text-gray-400">
                Rp {{ number_format($produk->harga_beli ?? 0, 0, ',', '.') }}
            </td>
        @endif

        {{-- Merek --}}
        @if ($visibleColumns['merek'] ?? false)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ $produk->merek ?? '-' }}
            </td>
        @endif

        {{-- Ukuran --}}
        @if ($visibleColumns['ukuran'] ?? false)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ $produk->ukuran ?? '-' }}
            </td>
        @endif

        {{-- Material --}}
        @if ($visibleColumns['material'] ?? false)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ $produk->type_material ?? '-' }}
            </td>
        @endif

        {{-- Kualitas --}}
        @if ($visibleColumns['kualitas'] ?? false)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ $produk->kualitas ?? '-' }}
            </td>
        @endif

        {{-- Sub Kategori --}}
        @if ($visibleColumns['sub_kategori'] ?? false)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ $produk->sub_kategori ?? '-' }}
            </td>
        @endif

        {{-- Deskripsi --}}
        @if ($visibleColumns['deskripsi'] ?? false)
            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate"
                title="{{ $produk->deskripsi }}">
                {{ $produk->deskripsi ? \Illuminate\Support\Str::limit($produk->deskripsi, 50) : '-' }}
            </td>
        @endif

        {{-- Bahan --}}
        @if ($visibleColumns['bahan'] ?? false)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                {{ $produk->bahan ?? '-' }}
            </td>
        @endif

        {{-- Status --}}
        @if ($visibleColumns['status'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap">
                @if ($produk->is_active)
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                @else
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                @endif
            </td>
        @endif

        {{-- Action Column --}}
        <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <button @click="open = !open" @click.outside="open = false"
                    class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full dark:text-gray-400 dark:hover:text-gray-300 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-150">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                    </svg>
                </button>

                {{-- Floating Action Menu --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 z-50"
                    style="transform-origin: top right;">
                    <div class="py-1" role="none">
                        <a href="{{ route('master.produk.show', $produk->id) }}"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                            <svg class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639l4.443-4.443a1.012 1.012 0 011.43 0l4.443 4.443a1.012 1.012 0 010 .639l-4.443 4.443a1.012 1.012 0 01-1.43 0l-4.443-4.443z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Lihat Detail
                        </a>
                        <a href="#" @click.prevent="openEditModal('{{ $produk->id }}')"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                            <svg class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Edit Produk
                        </a>
                    </div>
                    <div class="py-1">
                        <button type="button"
                            @click="open = false; confirmDelete('Apakah Anda yakin ingin menghapus produk {{ $produk->nama }}?', () => document.getElementById('delete-form-{{ $produk->id }}').submit())"
                            class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <svg class="w-4 h-4 mr-3 text-red-500 dark:text-red-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>

            {{-- Hidden Delete Form --}}
            <form id="delete-form-{{ $produk->id }}" action="{{ route('master.produk.destroy', $produk->id) }}"
                method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </td>
    </tr>
@empty
    {{-- Empty State --}}
    <tr>
        <td id="empty-row-td" colspan="9" class="px-5 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-gray-300 dark:text-gray-600 mb-4"
                    fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Tidak ada produk ditemukan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-md mb-6">
                    @if (request()->hasAny(['search', 'kategori_id', 'status', 'min_price', 'max_price']))
                        Tidak ada produk yang sesuai dengan pencarian atau filter Anda.
                        <a href="{{ route('master.produk.index') }}"
                            class="text-primary-600 dark:text-primary-400 hover:underline">Reset filter</a>.
                    @else
                        Belum ada produk yang ditambahkan. Mulai dengan menambahkan produk baru.
                    @endif
                </p>
                <a href="{{ route('master.produk.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 dark:bg-primary-700 dark:hover:bg-primary-600">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Produk
                </a>
            </div>
        </td>
    </tr>
@endforelse
