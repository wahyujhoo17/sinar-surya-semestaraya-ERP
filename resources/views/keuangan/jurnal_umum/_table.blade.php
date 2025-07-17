@php
    $totalDebit = 0;
    $totalKredit = 0;
@endphp

@forelse ($jurnals as $jurnal)
    @php
        // Get all related entries for this journal group
        $allRelatedEntries = \App\Models\JurnalUmum::where('no_referensi', $jurnal->no_referensi)
            ->where('tanggal', $jurnal->tanggal)
            ->get();

        $totalDebitForEntry = $allRelatedEntries->sum('debit');
        $totalKreditForEntry = $allRelatedEntries->sum('kredit');
        $countEntries = $allRelatedEntries->count();

        // Add to overall totals
        $totalDebit += $totalDebitForEntry;
        $totalKredit += $totalKreditForEntry;
    @endphp

    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
        <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
            {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}
        </td>
        <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
            <span class="font-mono">{{ $jurnal->no_referensi }}</span>
        </td>
        <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
            <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs font-medium">
                {{ $countEntries }} entri
            </span>
        </td>
        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-300">
            {{ Str::limit($jurnal->keterangan, 40) }}
        </td>
        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-300">
            @php
                $debitEntries = $allRelatedEntries->where('debit', '>', 0);
            @endphp
            @if ($debitEntries->count() > 0)
                <div class="space-y-1">
                    @foreach ($debitEntries as $entry)
                        <div class="flex flex-col">
                            <span class="text-xs font-medium text-gray-800 dark:text-gray-200">
                                {{ $entry->akun->kode }} - {{ Str::limit($entry->akun->nama, 25) }}
                            </span>
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                                {{ number_format($entry->debit, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <span class="text-xs text-gray-400">-</span>
            @endif
        </td>
        <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-300">
            @php
                $kreditEntries = $allRelatedEntries->where('kredit', '>', 0);
            @endphp
            @if ($kreditEntries->count() > 0)
                <div class="space-y-1">
                    @foreach ($kreditEntries as $entry)
                        <div class="flex flex-col">
                            <span class="text-xs font-medium text-gray-800 dark:text-gray-200">
                                {{ $entry->akun->kode }} - {{ Str::limit($entry->akun->nama, 25) }}
                            </span>
                            <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                {{ number_format($entry->kredit, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <span class="text-xs text-gray-400">-</span>
            @endif
        </td>
        <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
            <div class="flex flex-col items-center space-y-1">
                <span class="font-medium text-purple-600 dark:text-purple-400">
                    Rp {{ number_format($totalDebitForEntry, 0, ',', '.') }}
                </span>
                @if ($totalDebitForEntry == $totalKreditForEntry)
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Seimbang
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Tidak Seimbang
                    </span>
                @endif
            </div>
        </td>
        <td class="px-6 py-3.5 whitespace-nowrap text-sm text-center">
            @if ($jurnal->is_posted)
                <span
                    class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 px-2 py-1 rounded text-xs font-medium">
                    Posted
                </span>
            @else
                <span
                    class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 px-2 py-1 rounded text-xs font-medium">
                    Draft
                </span>
            @endif
        </td>
        <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm font-medium">
            <div class="flex justify-center space-x-2">
                {{-- Tombol Lihat Detail - selalu ada --}}
                <a href="{{ route('keuangan.jurnal-umum.show', $jurnal->id) }}"
                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-blue-600 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300 dark:border-blue-700"
                    title="Lihat Detail">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>

                @if (!$jurnal->is_posted)
                    {{-- Tombol Edit - hanya untuk draft --}}
                    <a href="{{ route('keuangan.jurnal-umum.edit', $jurnal->id) }}"
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-yellow-600 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300 dark:border-yellow-700"
                        title="Edit Jurnal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    {{-- Tombol Hapus - hanya untuk draft --}}
                    <button type="button" data-delete-id="{{ $jurnal->id }}"
                        data-no-referensi="{{ $jurnal->no_referensi }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}"
                        class="btn-delete inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-red-600 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300 dark:border-red-700"
                        title="Hapus Jurnal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                @else
                    {{-- Tombol Edit Disabled - untuk jurnal yang sudah posted --}}
                    <span
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-600 border border-dashed border-gray-300 dark:border-gray-600"
                        title="Jurnal sudah diposting, tidak dapat diedit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </span>

                    {{-- Tombol Hapus Disabled - untuk jurnal yang sudah posted --}}
                    <span
                        class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-600 border border-dashed border-gray-300 dark:border-gray-600"
                        title="Jurnal sudah diposting, tidak dapat dihapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </span>
                @endif

                {{-- Button Post/Unpost --}}
                @if ($jurnal->is_posted)
                    <button type="button"
                        class="post-unpost-btn inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-orange-100 text-orange-600 dark:text-white dark:bg-orange-900/20 dark:hover:bg-orange-900/30 transition-colors border border-dashed border-orange-300 dark:border-orange-700"
                        data-action="unpost" data-no-referensi="{{ $jurnal->no_referensi }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}"
                        data-form-action="{{ route('keuangan.jurnal-umum.unpost') }}" title="Unpost Jurnal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                    </button>
                @else
                    <button type="button"
                        class="post-unpost-btn inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-green-100 text-green-600 dark:text-white dark:bg-green-900/20 dark:hover:bg-green-900/30 transition-colors border border-dashed border-green-300 dark:border-green-700"
                        data-action="post" data-no-referensi="{{ $jurnal->no_referensi }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}"
                        data-form-action="{{ route('keuangan.jurnal-umum.post') }}" title="Post Jurnal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                @endif

                <form id="delete-form-{{ $jurnal->id }}"
                    action="{{ route('keuangan.jurnal-umum.destroy', $jurnal->id) }}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="px-6 py-10 text-center">
            <div class="flex flex-col items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 dark:text-gray-500 mb-4"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada data jurnal umum</span>
                <p class="text-gray-400 dark:text-gray-500 mt-1 max-w-md text-center">
                    Tidak ada data jurnal umum yang ditemukan. Cobalah mengubah filter pencarian
                    atau tambahkan jurnal baru.
                </p>
                <a href="{{ route('keuangan.jurnal-umum.create') }}"
                    class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Jurnal Baru
                </a>
            </div>
        </td>
    </tr>
@endforelse
