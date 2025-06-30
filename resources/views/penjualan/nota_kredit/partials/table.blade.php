@foreach ($notaKredits as $index => $notaKredit)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            {{ $index + 1 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
            {{ $notaKredit->nomor }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            {{ $notaKredit->tanggal ? \Carbon\Carbon::parse($notaKredit->tanggal)->format('d/m/Y') : '-' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            {{ $notaKredit->customer->company ?? ($notaKredit->customer->nama ?? 'N/A') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            {{ $notaKredit->returPenjualan->nomor ?? 'N/A' }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            Rp {{ number_format($notaKredit->total, 0, ',', '.') }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            @if ($notaKredit->status == 'draft')
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-800 dark:text-amber-100">
                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-amber-500" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Draft
                </span>
            @elseif($notaKredit->status == 'diproses')
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-500" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Diproses
                </span>
            @elseif($notaKredit->status == 'selesai')
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-100">
                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-emerald-500" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Selesai
                </span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                    {{ $notaKredit->status }}
                </span>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex justify-end space-x-2">
                @if (auth()->user()->hasPermission('nota_kredit.view'))
                    <a href="{{ route('penjualan.nota-kredit.show', $notaKredit->id) }}"
                        class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300"
                        title="Lihat Detail">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </a>
                @endif

                @if (auth()->user()->hasPermission('nota_kredit.edit') && $notaKredit->status == 'draft')
                    <a href="{{ route('penjualan.nota-kredit.edit', $notaKredit->id) }}"
                        class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300"
                        title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </a>

                    @if (auth()->user()->hasPermission('nota_kredit.delete'))
                        <button type="button"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 btn-delete"
                            data-modal-target="deleteModal" data-modal-toggle="deleteModal"
                            data-id="{{ $notaKredit->id }}" data-name="{{ $notaKredit->nomor }}" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    @endif
                @endif

                <a href="{{ route('penjualan.nota-kredit.pdf', $notaKredit->id) }}" target="_blank"
                    class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                    title="Download PDF">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                </a>
            </div>
        </td>
    </tr>
@endforeach

@if (count($notaKredits) === 0)
    <tr>
        <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
            <div class="flex flex-col items-center justify-center">
                <div
                    class="flex flex-col items-center justify-center w-24 h-24 rounded-full bg-gray-50 dark:bg-gray-700/50 mb-4">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mb-1">Tidak ada data nota kredit</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">Silakan tambahkan nota kredit baru atau ubah filter
                    pencarian Anda</p>
            </div>
        </td>
    </tr>
@endif
