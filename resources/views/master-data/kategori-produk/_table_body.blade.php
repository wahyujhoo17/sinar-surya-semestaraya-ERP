@forelse ($kategoris as $kategori)
    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-150">
        {{-- Checkbox --}}
        <td class="px-3 py-4">
            <input type="checkbox" name="kategori_ids[]" value="{{ $kategori->id }}"
                @click="updateSelectedKategoris($event, '{{ $kategori->id }}')"
                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
        </td>
        {{-- Kode --}}
        <td class="px-5 py-4 whitespace-nowrap font-mono text-xs text-gray-700 dark:text-gray-300">
            {{ $kategori->kode }}
        </td>
        {{-- Nama --}}
        <td class="text-sm font-medium text-gray-900 dark:text-white truncate">
            {{ $kategori->nama }}
        </td>
        {{-- Deskripsi --}}
        <td class="px-5 py-4 whitespace-nowrap text-gray-700 dark:text-gray-400 text-sm">
            {{ $kategori->deskripsi ? \Illuminate\Support\Str::limit($kategori->deskripsi, 40) : '-' }}
        </td>
        {{-- Status --}}
        <td class="px-5 py-4 whitespace-nowrap text-center">
            @if ($kategori->is_active)
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                    Aktif
                </span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                    Tidak Aktif
                </span>
            @endif
        </td>
        {{-- Jumlah Produk --}}
        <td class="px-5 py-4 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
            {{ $kategori->produk_count ?? 0 }}
        </td>
        {{-- Aksi --}}
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
                <div x-show="open" x-transition
                    class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 z-50"
                    style="transform-origin: top right;">
                    <div class="py-1" role="none">
                        <a href="#" @click.prevent="openEditKategoriModal('{{ $kategori->id }}')"
                            class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                            <svg class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            Edit Kategori
                        </a>
                    </div>
                    <div class="py-1">
                        <button type="button"
                            @click="open = false; confirmDelete('Apakah Anda yakin ingin menghapus kategori {{ $kategori->nama }}?', () => document.getElementById('delete-form-{{ $kategori->id }}').submit())"
                            class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <svg class="w-4 h-4 mr-3 text-red-500 dark:text-red-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
            <form id="delete-form-{{ $kategori->id }}"
                action="{{ route('master.kategori-produk.destroy', $kategori->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-5 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-gray-300 dark:text-gray-600 mb-4"
                    fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Tidak ada kategori ditemukan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-md mb-6">
                    @if (request()->has('search'))
                        Tidak ada kategori yang sesuai dengan pencarian Anda.
                        <a href="{{ route('master.kategori-produk.index') }}"
                            class="text-primary-600 dark:text-primary-400 hover:underline">Reset filter</a>.
                    @else
                        Belum ada kategori yang ditambahkan. Mulai dengan menambahkan kategori baru.
                    @endif
                </p>
                <a href="#"
                    @click.prevent="window.dispatchEvent(new CustomEvent('open-kategori-produk-modal', {detail: {}}))"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 dark:bg-primary-700 dark:hover:bg-primary-600">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kategori
                </a>
            </div>
        </td>
    </tr>
@endforelse
