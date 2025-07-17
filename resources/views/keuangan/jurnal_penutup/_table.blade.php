<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800">
            <tr>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <a href="javascript:void(0)" onclick="sortTable('tanggal')"
                        class="flex items-center hover:text-red-600 dark:hover:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tanggal
                        @if (request('sort') === 'tanggal')
                            @if (request('direction') === 'asc')
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
                        class="flex items-center hover:text-red-600 dark:hover:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        No. Referensi
                        @if (request('sort') === 'no_referensi')
                            @if (request('direction') === 'asc')
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
                        class="flex items-center hover:text-red-600 dark:hover:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Keterangan
                        @if (request('sort') === 'keterangan')
                            @if (request('direction') === 'asc')
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
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Akun Debit
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1.5 text-blue-500 dark:text-blue-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        Akun Kredit
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1.5 text-purple-500 dark:text-purple-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Total & Status
                    </div>
                </th>
                <th scope="col"
                    class="px-6 py-3.5 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">
                        <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs">
                            {{ $jurnal->no_referensi }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            {{ $jurnal->count_entries ?? 0 }} entri
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                        <div class="max-w-xs truncate" title="{{ $jurnal->keterangan }}">
                            {{ $jurnal->keterangan }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                        <div class="space-y-1">
                            @if (isset($jurnal->debit_accounts) && count($jurnal->debit_accounts) > 0)
                                @foreach ($jurnal->debit_accounts as $account)
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-gray-800 dark:text-gray-200">
                                            {{ $account->kode_akun }} - {{ Str::limit($account->nama_akun, 25) }}
                                        </span>
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                                            Rp {{ number_format($account->jumlah, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                        <div class="space-y-1">
                            @if (isset($jurnal->kredit_accounts) && count($jurnal->kredit_accounts) > 0)
                                @foreach ($jurnal->kredit_accounts as $account)
                                    <div class="flex flex-col">
                                        <span class="text-xs font-medium text-gray-800 dark:text-gray-200">
                                            {{ $account->kode_akun }} - {{ Str::limit($account->nama_akun, 25) }}
                                        </span>
                                        <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                            Rp {{ number_format($account->jumlah, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <div class="flex flex-col items-center space-y-1">
                            {{-- Total Amount --}}
                            <span class="font-medium text-purple-600 dark:text-purple-400">
                                Rp {{ number_format($jurnal->total_debit ?? 0, 0, ',', '.') }}
                            </span>

                            {{-- Balance Status --}}
                            @if (($jurnal->total_debit ?? 0) == ($jurnal->total_kredit ?? 0))
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
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
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
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            {{-- Tombol Lihat Detail - selalu ada --}}
                            <a href="{{ route('keuangan.jurnal-penutup.show', $jurnal->no_referensi) }}"
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
                                <a href="{{ route('keuangan.jurnal-penutup.edit', $jurnal->no_referensi) }}"
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

                            {{-- Button Post/Unpost --}}
                            @if ($jurnal->is_posted)
                                <button type="button"
                                    class="post-unpost-btn inline-flex items-center justify-center h-8 w-8 rounded-lg hover:bg-orange-100 text-orange-600 dark:text-white dark:bg-orange-900/20 dark:hover:bg-orange-900/30 transition-colors border border-dashed border-orange-300 dark:border-orange-700"
                                    data-action="unpost" data-no-referensi="{{ $jurnal->no_referensi }}"
                                    data-form-action="{{ url('keuangan/jurnal-penutup/' . $jurnal->no_referensi . '/toggle-post') }}"
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
                                    data-form-action="{{ url('keuangan/jurnal-penutup/' . $jurnal->no_referensi . '/toggle-post') }}"
                                    title="Post Jurnal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada jurnal
                                penutup
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan membuat jurnal
                                penutup
                                baru.</p>
                            <div class="mt-6">
                                <a href="{{ route('keuangan.jurnal-penutup.create') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Buat Jurnal Penutup
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
