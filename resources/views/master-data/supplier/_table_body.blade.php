@forelse ($suppliers as $supplier)
    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-all duration-150">
        {{-- Checkbox --}}
        @if (auth()->user()->hasPermission('supplier.delete'))
            <td class="px-3 py-4">
                <input type="checkbox" name="supplier_ids[]" value="{{ $supplier->id }}"
                    @click="updateSelectedSuppliers($event, '{{ $supplier->id }}')"
                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600">
            </td>
        @endif
        {{-- Kode --}}
        @if ($visibleColumns['kode'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $supplier->kode }}</td>
        @endif
        {{-- Nama --}}
        @if ($visibleColumns['nama'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $supplier->nama }}</td>
        @endif
        {{-- Telepon --}}
        @if ($visibleColumns['telepon'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                {{ $supplier->telepon ?? '-' }}</td>
        @endif
        {{-- Email --}}
        @if ($visibleColumns['email'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                {{ $supplier->email ?? '-' }}</td>
        @endif
        {{-- No HP --}}
        @if ($visibleColumns['no_hp'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                {{ $supplier->no_hp ?? '-' }}</td>
        @endif
        {{-- Alamat --}}
        @if ($visibleColumns['alamat'] ?? true)
            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                {{ $supplier->alamat ? \Illuminate\Support\Str::limit($supplier->alamat, 50) : '-' }}
            </td>
        @endif
        {{-- Nama Kontak --}}
        @if ($visibleColumns['nama_kontak'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                {{ $supplier->nama_kontak ?? '-' }}</td>
        @endif
        {{-- Tipe Produksi --}}
        @if ($visibleColumns['type_produksi'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                {{ $supplier->type_produksi ?? '-' }}</td>
        @endif
        {{-- NPWP --}}
        @if ($visibleColumns['NPWP'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                {{ $supplier->NPWP ?? '-' }}</td>
        @endif
        {{-- Status --}}
        @if ($visibleColumns['status'] ?? true)
            <td class="px-5 py-4 whitespace-nowrap">
                @if ($supplier->is_active)
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Aktif</span>
                @else
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Nonaktif</span>
                @endif
            </td>
        @endif
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
                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-full mr-2 top-0 w-44 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 z-50"
                    style="transform-origin: top right; display: none;">
                    <div class="py-1" role="none">
                        @if (auth()->user()->hasPermission('supplier.view'))
                            <a href="{{ route('master.supplier.show', $supplier->id) }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                                <svg class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639l4.443-4.443a1.012 1.012 0 011.43 0l4.443 4.443a1.012 1.012 0 010 .639l-4.443 4.443a1.012 1.012 0 01-1.43 0l-4.443-4.443z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lihat Detail
                            </a>
                        @endif
                        @if (auth()->user()->hasPermission('supplier.edit'))
                            <a href="#" @click.prevent="open = false; openEditSupplier('{{ $supplier->id }}')"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50">
                                <svg class="w-4 h-4 mr-3 text-gray-500 dark:text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Edit Supplier
                            </a>
                        @endif
                    </div>
                    @if (auth()->user()->hasPermission('supplier.delete'))
                        <div class="py-1">
                            <form id="delete-form-{{ $supplier->id }}"
                                action="{{ route('master.supplier.destroy', $supplier->id) }}" method="POST"
                                class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button"
                                @click="open = false; confirmDelete('Apakah Anda yakin ingin menghapus supplier {{ $supplier->nama }}?', () => document.getElementById('delete-form-{{ $supplier->id }}').submit())"
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
                    @endif
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        {{-- Adjust colspan value (e.g., if all columns are visible, it's now 10) --}}
        <td id="empty-row-td" colspan="10" class="px-5 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-gray-300 dark:text-gray-600 mb-4"
                    fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 01-8 0M12 3v4m0 0a4 4 0 01-4 4H4m8-4a4 4 0 004 4h4" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Tidak ada supplier ditemukan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-md mb-6">
                    @if (request()->has('search'))
                        Tidak ada supplier yang sesuai dengan pencarian Anda.
                        <a href="{{ route('master.supplier.index') }}"
                            class="text-primary-600 dark:text-primary-400 hover:underline">Reset filter</a>.
                    @else
                        Belum ada supplier yang ditambahkan. Mulai dengan menambahkan supplier baru.
                    @endif
                </p>
                @if (auth()->user()->hasPermission('supplier.create'))
                    <a href="#"
                        @click.prevent="window.dispatchEvent(new CustomEvent('open-supplier-modal', {detail: {}}))"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 dark:bg-primary-700 dark:hover:bg-primary-600">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Supplier
                    </a>
                @else
                    <div
                        class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 text-sm font-medium rounded-lg opacity-60 cursor-not-allowed">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v7.5a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Tidak Ada Akses
                    </div>
                @endif
            </div>
        </td>
    </tr>
@endforelse
