<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800">
            <tr>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <a href="javascript:void(0)" onclick="sortTable('tanggal')"
                        class="flex items-center hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tanggal
                        @if (request('sort') == 'tanggal')
                            @if (request('direction') == 'asc')
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </a>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <a href="javascript:void(0)" onclick="sortTable('no_referensi')"
                        class="flex items-center hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        No. Referensi
                        @if (request('sort') == 'no_referensi')
                            @if (request('direction') == 'asc')
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </a>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Jumlah Entri
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <a href="javascript:void(0)" onclick="sortTable('keterangan')"
                        class="flex items-center hover:text-indigo-600 dark:hover:text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Keterangan
                        @if (request('sort') == 'keterangan')
                            @if (request('direction') == 'asc')
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7"></path>
                                </svg>
                            @else
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            @endif
                        @endif
                    </a>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1.5 text-emerald-500 dark:text-emerald-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0c-1.657 0-3-.895-3-2s1.343-2 3-2 3-.895 3-2-1.343-2-3-2m0 8c1.11 0 2.08-.402 2.599-1M12 16v1" />
                        </svg>
                        Debit
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1.5 text-blue-500 dark:text-blue-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0c-1.657 0-3-.895-3-2s1.343-2 3-2 3-.895 3-2-1.343-2-3-2m0 8c1.11 0 2.08-.402 2.599-1M12 16v1" />
                        </svg>
                        Kredit
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center justify-end">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0c-1.657 0-3-.895-3-2s1.343-2 3-2 3-.895 3-2-1.343-2-3-2m0 8c1.11 0 2.08-.402 2.599-1M12 16v1" />
                        </svg>
                        Total
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Status
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        Aksi
                    </div>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse ($jurnals as $jurnal)
                @php
                    // Get all related entries for this journal group
                    $allRelatedEntries = \App\Models\JurnalUmum::with('akun')
                        ->where('no_referensi', $jurnal->no_referensi)
                        ->where('tanggal', $jurnal->tanggal)
                        ->where('jenis_jurnal', 'penyesuaian')
                        ->get();

                    $totalDebitForEntry = $allRelatedEntries->sum('debit');
                    $totalKreditForEntry = $allRelatedEntries->sum('kredit');
                    $countEntries = $allRelatedEntries->count();
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                    <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span
                                class="font-medium">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 ml-6">
                            {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('l') }}
                        </div>
                    </td>
                    <td class="px-6 py-3.5 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs font-medium">
                            {{ $jurnal->no_referensi }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm text-gray-700 dark:text-gray-300">
                        <span
                            class="bg-indigo-100 dark:bg-indigo-900/30 px-2 py-1 rounded text-xs font-medium text-indigo-700 dark:text-indigo-400">
                            {{ $countEntries }} entri
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-sm text-gray-700 dark:text-gray-300">
                        <div class="max-w-xs">
                            <p class="truncate" title="{{ $jurnal->keterangan }}">
                                {{ Str::limit($jurnal->keterangan, 40) }}</p>
                        </div>
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
                    <td
                        class="px-6 py-3.5 whitespace-nowrap text-right text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <span class="text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($totalDebitForEntry, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm">
                        @if ($jurnal->is_posted)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Posted
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            {{-- Tombol Lihat Detail - selalu ada --}}
                            <a href="{{ route('keuangan.jurnal-penyesuaian.show', $jurnal->no_referensi) }}"
                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-blue-100 text-blue-600 dark:text-white dark:bg-blue-900/20 dark:hover:bg-blue-900/30 transition-colors border border-dashed border-blue-300 dark:border-blue-700"
                                title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            @if (!$jurnal->is_posted)
                                {{-- Tombol Edit - hanya untuk draft --}}
                                <a href="{{ route('keuangan.jurnal-penyesuaian.edit', $jurnal->no_referensi) }}"
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-yellow-100 text-yellow-600 dark:text-white dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 transition-colors border border-dashed border-yellow-300 dark:border-yellow-700"
                                    title="Edit Jurnal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Tombol Hapus - hanya untuk draft --}}
                                <button type="button"
                                    class="delete-button inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-red-100 text-red-600 dark:text-white dark:bg-red-900/20 dark:hover:bg-red-900/30 transition-colors border border-dashed border-red-300 dark:border-red-700"
                                    data-no-referensi="{{ $jurnal->no_referensi }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}"
                                    title="Hapus Jurnal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @else
                                {{-- Tombol Edit Disabled - untuk jurnal yang sudah posted --}}
                                <span
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-600 border border-dashed border-gray-300 dark:border-gray-600"
                                    title="Jurnal sudah diposting, tidak dapat diedit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </span>

                                {{-- Tombol Hapus Disabled - untuk jurnal yang sudah posted --}}
                                <span
                                    class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-600 border border-dashed border-gray-300 dark:border-gray-600"
                                    title="Jurnal sudah diposting, tidak dapat dihapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </span>
                            @endif

                            @if ($jurnal->is_posted)
                                <button type="button"
                                    class="post-unpost-btn inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-orange-100 text-orange-600 dark:text-white dark:bg-orange-900/20 dark:hover:bg-orange-900/30 transition-colors border border-dashed border-orange-300 dark:border-orange-700"
                                    data-action="unpost" data-no-referensi="{{ $jurnal->no_referensi }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}"
                                    data-form-action="{{ route('keuangan.jurnal-penyesuaian.unpost') }}"
                                    title="Unpost Jurnal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                    </svg>
                                </button>
                            @else
                                <button type="button"
                                    class="post-unpost-btn inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-green-100 text-green-600 dark:text-white dark:bg-green-900/20 dark:hover:bg-green-900/30 transition-colors border border-dashed border-green-300 dark:border-green-700"
                                    data-action="post" data-no-referensi="{{ $jurnal->no_referensi }}"
                                    data-tanggal="{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}"
                                    data-form-action="{{ route('keuangan.jurnal-penyesuaian.post') }}"
                                    title="Post Jurnal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <p class="text-lg font-medium">Belum ada jurnal penyesuaian</p>
                            <p class="text-sm mt-1">Buat jurnal penyesuaian pertama Anda</p>
                            <a href="{{ route('keuangan.jurnal-penyesuaian.create') }}"
                                class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Buat Jurnal Penyesuaian
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
