{{-- Child accounts recursive partial template --}}
{{-- Data debug --}}
<!-- Child Count: {{ count($children) }} -->
@foreach ($children as $child)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 child-row hidden" data-id="{{ $child->id }}"
        data-parent="{{ $parent_id }}" data-level="{{ $level }}" data-kategori="{{ $child->kategori }}"
        data-status="{{ $child->is_active ? 'active' : 'inactive' }}">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
            <span class="flex items-center">
                <span class="inline-block w-{{ $level * 4 }}"></span>
                @if ($child->children && $child->children->count() > 0)
                    <button type="button" class="toggle-children mr-2" onclick="return toggleChildren(this);">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 transform transition-transform duration-200" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                @else
                    <span class="w-6"></span>
                @endif
                {{ $child->kode }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-medium">
            {{ $child->nama }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
            @switch($child->kategori)
                @case('asset')
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300">
                        Aset
                    </span>
                @break

                @case('liability')
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-300">
                        Kewajiban
                    </span>
                @break

                @case('equity')
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-300">
                        Ekuitas
                    </span>
                @break

                @case('income')
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-300">
                        Pendapatan
                    </span>
                @break

                @case('expense')
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-800/30 dark:text-orange-300">
                        Beban
                    </span>
                @break

                @default
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-300">
                        Lainnya
                    </span>
            @endswitch
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
            {{ $child->tipe === 'header' ? 'Header (Grup)' : 'Detail (Transaksi)' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
            @if ($child->ref_type == 'App\Models\Kas')
                <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-300">
                    Kas
                </span>
            @elseif ($child->ref_type == 'App\Models\RekeningBank')
                <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-300">
                    Rekening Bank
                </span>
            @else
                -
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
            @if ($child->is_active)
                <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-300">
                    Aktif
                </span>
            @else
                <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-300">
                    Nonaktif
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex justify-end space-x-2">
                <a href="{{ route('keuangan.coa.show', $child->id) }}"
                    class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                    title="Lihat Detail">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                <a href="{{ route('keuangan.coa.edit', $child->id) }}"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                    title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form action="{{ route('keuangan.coa.destroy', $child->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')"
                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                        title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </td>
    </tr>

    {{-- Recursively render grandchildren --}}
    @if ($child->children && $child->children->count() > 0)
        @include('keuangan.COA.partials.child_accounts', [
            'children' => $child->children,
            'level' => $level + 1,
            'parent_id' => $child->id,
        ])
    @endif
@endforeach
