<x-app-layout :breadcrumbs="$breadcrumbs" :currentPage="'Detail Jurnal Penyesuaian'">
    <div class="max-w-full mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Overview Header --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center">
                <div class="bg-primary-100 dark:bg-primary-900/30 p-3 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-600 dark:text-primary-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        Detail Jurnal Penyesuaian
                    </h1>
                    <p class="mt-1 text-gray-600 dark:text-gray-400 flex items-center">
                        <span
                            class="font-medium text-primary-600 dark:text-primary-400 mr-2">{{ $entries->first()->no_referensi }}</span>
                        |
                        <span
                            class="ml-2">{{ \Carbon\Carbon::parse($entries->first()->tanggal)->format('d F Y') }}</span>
                    </p>
                </div>
            </div>

            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                {{-- Status Badge --}}
                <div class="flex items-center">
                    @if ($entries->first()->is_posted)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Posted
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Draft
                        </span>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <a href="{{ route('keuangan.jurnal-penyesuaian.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:bg-gray-600">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>

                @if (!$entries->first()->is_posted)
                    {{-- Post Button --}}
                    <button type="button"
                        class="post-unpost-btn inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                        data-action="post" data-no-referensi="{{ $entries->first()->no_referensi }}"
                        data-form-action="{{ route('keuangan.jurnal-penyesuaian.post') }}">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Post Jurnal
                    </button>

                    {{-- Edit Button --}}
                    <a href="{{ route('keuangan.jurnal-penyesuaian.edit', $entries->first()->no_referensi) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                @else
                    {{-- Unpost Button --}}
                    <button type="button"
                        class="post-unpost-btn inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200"
                        data-action="unpost" data-no-referensi="{{ $entries->first()->no_referensi }}"
                        data-form-action="{{ route('keuangan.jurnal-penyesuaian.unpost') }}">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Unpost Jurnal
                    </button>
                @endif

                {{-- Delete Button --}}
                @if (!$entries->first()->is_posted)
                    <button type="button"
                        class="delete-button inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                        data-no-referensi="{{ $entries->first()->no_referensi }}"
                        data-tanggal="{{ \Carbon\Carbon::parse($entries->first()->tanggal)->format('d/m/Y') }}">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                @endif

            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Journal Information Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden h-full">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-750 border-b border-gray-200 dark:border-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-primary-600 dark:text-primary-400 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Informasi Jurnal</h3>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="flex flex-col space-y-4">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</span>
                                    <span class="mt-1 block text-base font-semibold text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($entries->first()->tanggal)->format('d F Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">No.
                                        Referensi</span>
                                    <span
                                        class="mt-1 block text-base font-semibold text-gray-900 dark:text-white font-mono">
                                        {{ $entries->first()->no_referensi }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-green-600 dark:text-green-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat
                                        Oleh</span>
                                    <span class="mt-1 block text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $entries->first()->user->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dibuat
                                        Pada</span>
                                    <span class="mt-1 block text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $entries->first()->created_at->format('d F Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-start">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <span
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400">Keterangan</span>
                                    <p class="mt-1 text-base text-gray-900 dark:text-white leading-relaxed">
                                        {{ $entries->first()->keterangan }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Journal Entries --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl overflow-hidden">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-primary-50 to-white dark:from-primary-900/20 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-primary-600 dark:text-primary-400 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Entri Jurnal</h3>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ count($entries) }} entri
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-gray-500 dark:text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14-4H3m16 8H1" />
                                            </svg>
                                            Akun
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-emerald-500 dark:text-emerald-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Debit
                                        </div>
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3.5 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                        <div class="flex items-center justify-end">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 mr-1.5 text-blue-500 dark:text-blue-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                            Kredit
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($entries as $entry)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="font-mono text-sm text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                {{ $entry->akun->kode }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $entry->akun->nama }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            @if ($entry->debit > 0)
                                                <span class="text-emerald-600 dark:text-emerald-400 font-medium">
                                                    {{ number_format($entry->debit, 2, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-600">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            @if ($entry->kredit > 0)
                                                <span class="text-blue-600 dark:text-blue-400 font-medium">
                                                    {{ number_format($entry->kredit, 2, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-600">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- Total row --}}
                                <tr
                                    class="bg-gradient-to-r from-primary-100 to-primary-50 dark:from-primary-900/40 dark:to-primary-900/20 border-t-2 border-primary-200 dark:border-primary-700">
                                    <td colspan="2"
                                        class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800 dark:text-white text-right">
                                        Total:
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white text-right font-bold">
                                        <span
                                            class="bg-white dark:bg-gray-800 px-3 py-1.5 rounded shadow-sm border border-primary-200 dark:border-primary-700">
                                            {{ number_format($totalDebit, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white text-right font-bold">
                                        <span
                                            class="bg-white dark:bg-gray-800 px-3 py-1.5 rounded shadow-sm border border-primary-200 dark:border-primary-700">
                                            {{ number_format($totalKredit, 2, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Balance Status --}}
                    <div
                        class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-center">
                            @if ($totalDebit == $totalKredit)
                                <span
                                    class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <svg class="-ml-0.5 mr-2 h-5 w-5 text-green-500" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Jurnal Seimbang
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                    <svg class="-ml-0.5 mr-2 h-5 w-5 text-red-500" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Jurnal Tidak Seimbang (Selisih: Rp
                                    {{ number_format(abs($totalDebit - $totalKredit), 0, ',', '.') }})
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="mt-6 text-right text-sm text-gray-500 dark:text-gray-400">
            <p>Terakhir diperbarui: {{ $entries->first()->updated_at->format('d F Y H:i') }}</p>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.882 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Apakah Anda yakin ingin menghapus jurnal penyesuaian:
                    </p>
                    <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            <span id="modal-no-referensi"></span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Tanggal: <span id="modal-tanggal"></span>
                        </p>
                    </div>
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-4">
                    <button id="cancelDelete" type="button"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                    <button id="confirmDelete" type="button"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Post/Unpost Confirmation Modal --}}
    <div id="postModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75 dark:bg-gray-900"></div>
            </div>

            {{-- Modal panel --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div id="postModalIcon"
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Icon will be set dynamically -->
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                id="postModalTitle">
                                <!-- Title will be set dynamically -->
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" id="postModalMessage">
                                    <!-- Dynamic message will be inserted here -->
                                </p>
                                <div id="postModalWarning" class="mt-3 p-3 rounded-md border">
                                    <!-- Warning content will be set dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmPostBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        <!-- Button text will be set dynamically -->
                    </button>
                    <button type="button" id="cancelPostBtn"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                'use strict';

                let showCurrentPostForm = null;

                // Safe DOM element getter with null check
                function safeGetElement(id) {
                    const element = document.getElementById(id);
                    if (!element) {
                        console.warn(`Element with ID '${id}' not found`);
                    }
                    return element;
                }

                // Helper function to generate delete route URL
                function getDeleteUrl(noReferensi) {
                    return `{{ url('keuangan/jurnal-penyesuaian') }}/${noReferensi}`;
                }

                // Delete modal functionality
                let showDeleteForm = null;
                const deleteModal = safeGetElement('deleteModal');
                const modalNoReferensi = safeGetElement('modal-no-referensi');
                const modalTanggal = safeGetElement('modal-tanggal');
                const confirmDeleteBtn = safeGetElement('confirmDelete');
                const cancelDeleteBtn = safeGetElement('cancelDelete');

                function attachDeleteEventListeners() {
                    document.querySelectorAll('.delete-button').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();

                            const noReferensi = this.dataset.noReferensi;
                            const tanggal = this.dataset.tanggal;

                            // Create form dynamically
                            showDeleteForm = document.createElement('form');
                            showDeleteForm.method = 'POST';
                            showDeleteForm.action = getDeleteUrl(noReferensi);

                            const csrfField = document.createElement('input');
                            csrfField.type = 'hidden';
                            csrfField.name = '_token';
                            csrfField.value = '{{ csrf_token() }}';

                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';

                            showDeleteForm.appendChild(csrfField);
                            showDeleteForm.appendChild(methodField);

                            // Update modal content
                            if (modalNoReferensi) modalNoReferensi.textContent = noReferensi;
                            if (modalTanggal) modalTanggal.textContent = tanggal;

                            // Show modal
                            if (deleteModal) {
                                deleteModal.classList.remove('hidden');
                                // Focus on cancel button for accessibility
                                if (cancelDeleteBtn) cancelDeleteBtn.focus();
                            }
                        });
                    });
                }

                // Confirm delete
                if (confirmDeleteBtn) {
                    confirmDeleteBtn.addEventListener('click', function() {
                        if (showDeleteForm) {
                            document.body.appendChild(showDeleteForm);
                            showDeleteForm.submit();
                        }
                    });
                }

                // Cancel delete
                function closeModal() {
                    if (deleteModal) {
                        deleteModal.classList.add('hidden');
                    }
                    showDeleteForm = null;
                }

                // Cancel delete event listeners
                if (cancelDeleteBtn) {
                    cancelDeleteBtn.addEventListener('click', closeModal);
                }

                // Close modal when clicking outside
                if (deleteModal) {
                    deleteModal.addEventListener('click', function(e) {
                        if (e.target === deleteModal) {
                            closeModal();
                        }
                    });
                }

                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && deleteModal && !deleteModal.classList.contains('hidden')) {
                        closeModal();
                    }
                });

                // Post/Unpost modal functionality
                // Use event delegation for post/unpost buttons
                document.addEventListener('click', function(e) {
                    const postButton = e.target.closest('.post-unpost-btn');
                    if (postButton) {
                        e.preventDefault();
                        e.stopPropagation();

                        const action = postButton.getAttribute('data-action');
                        const noReferensi = postButton.getAttribute('data-no-referensi') || 'N/A';
                        const formAction = postButton.getAttribute('data-form-action');

                        // Validate required data
                        if (!action || !formAction) {
                            console.error('Missing required data for post/unpost action:', {
                                action,
                                formAction
                            });
                            return;
                        }

                        // Create a form for submission
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = formAction;

                        // Add CSRF token
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        // Add no_referensi
                        const noRefInput = document.createElement('input');
                        noRefInput.type = 'hidden';
                        noRefInput.name = 'no_referensi';
                        noRefInput.value = noReferensi;
                        form.appendChild(noRefInput);

                        // Add form to body temporarily (required for submission)
                        document.body.appendChild(form);
                        form.style.display = 'none';

                        showPostModal(action, form, noReferensi);
                    }
                });

                function setupPostModalEventListeners() {
                    const confirmPostBtn = safeGetElement('confirmPostBtn');
                    const cancelPostBtn = safeGetElement('cancelPostBtn');
                    const postModal = safeGetElement('postModal');

                    if (confirmPostBtn) {
                        confirmPostBtn.addEventListener('click', function() {
                            if (showCurrentPostForm) {
                                // Disable the confirm button to prevent double submission
                                const originalText = confirmPostBtn.textContent;
                                confirmPostBtn.disabled = true;
                                confirmPostBtn.textContent = 'Memproses...';

                                showCurrentPostForm.submit();

                                // Clean up the form after submission
                                setTimeout(() => {
                                    if (showCurrentPostForm && showCurrentPostForm.parentNode) {
                                        showCurrentPostForm.parentNode.removeChild(showCurrentPostForm);
                                    }
                                    closePostModal();
                                }, 100);
                            } else {
                                console.error('No showCurrentPostForm set');
                                closePostModal();
                            }
                        });
                    }

                    if (cancelPostBtn) {
                        cancelPostBtn.addEventListener('click', function() {
                            closePostModal();
                        });
                    }

                    if (postModal) {
                        postModal.addEventListener('click', function(e) {
                            if (e.target === this) {
                                closePostModal();
                            }
                        });
                    }

                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape' && postModal && !postModal.classList.contains('hidden')) {
                            closePostModal();
                        }
                    });
                }

                function showPostModal(action, form, noReferensi = 'N/A') {
                    showCurrentPostForm = form;

                    const modal = safeGetElement('postModal');
                    const title = safeGetElement('postModalTitle');
                    const message = safeGetElement('postModalMessage');
                    const warning = safeGetElement('postModalWarning');
                    const icon = safeGetElement('postModalIcon');
                    const confirmBtn = safeGetElement('confirmPostBtn');

                    if (!modal || !title || !message || !warning || !icon || !confirmBtn) {
                        console.error('Modal elements not found:', {
                            modal: !!modal,
                            title: !!title,
                            message: !!message,
                            warning: !!warning,
                            icon: !!icon,
                            confirmBtn: !!confirmBtn
                        });
                        return;
                    }

                    if (action === 'post') {
                        // Post configuration
                        title.textContent = 'Konfirmasi Posting Jurnal Penyesuaian';

                        message.innerHTML = `
                            <div class="space-y-2">
                                <p>Apakah Anda yakin ingin memposting jurnal penyesuaian berikut?</p>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                                    <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                                </div>
                            </div>
                        `;

                        icon.className =
                            'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10';
                        icon.innerHTML = `
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        `;

                        warning.className =
                            'mt-3 p-3 bg-green-50 dark:bg-green-900/20 rounded-md border border-green-200 dark:border-green-800';
                        warning.innerHTML = `
                            <div class="flex">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700 dark:text-green-300">
                                        <strong>Informasi:</strong> Setelah diposting, jurnal penyesuaian ini akan mempengaruhi saldo akun dan tidak dapat diedit lagi. Pastikan semua entri sudah benar sebelum melanjutkan.
                                    </p>
                                </div>
                            </div>
                        `;

                        confirmBtn.className =
                            'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 hover:bg-green-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
                        confirmBtn.textContent = 'Ya, Posting Jurnal';

                    } else {
                        // Unpost configuration
                        title.textContent = 'Konfirmasi Pembatalan Posting';

                        message.innerHTML = `
                            <div class="space-y-2">
                                <p>Apakah Anda yakin ingin membatalkan posting jurnal penyesuaian berikut?</p>
                                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md text-sm">
                                    <div><strong>No. Referensi:</strong> ${noReferensi}</div>
                                </div>
                            </div>
                        `;

                        icon.className =
                            'mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900/30 sm:mx-0 sm:h-10 sm:w-10';
                        icon.innerHTML = `
                            <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                        `;

                        warning.className =
                            'mt-3 p-3 bg-orange-50 dark:bg-orange-900/20 rounded-md border border-orange-200 dark:border-orange-800';
                        warning.innerHTML = `
                            <div class="flex">
                                <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-orange-700 dark:text-orange-300">
                                        <strong>Perhatian:</strong> Pembatalan posting akan membalik perubahan saldo akun yang telah dilakukan. Jurnal akan kembali ke status draft dan dapat diedit kembali.
                                    </p>
                                </div>
                            </div>
                        `;

                        confirmBtn.className =
                            'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 hover:bg-orange-700 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200';
                        confirmBtn.textContent = 'Ya, Unpost';
                    }

                    // Show modal
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                    confirmBtn.focus();
                }

                function closePostModal() {
                    const modal = safeGetElement('postModal');
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = ''; // Restore scrolling
                    }

                    // Clean up the form if it exists
                    if (showCurrentPostForm && showCurrentPostForm.parentNode) {
                        showCurrentPostForm.parentNode.removeChild(showCurrentPostForm);
                    }
                    showCurrentPostForm = null;

                    // Reset confirm button state
                    const confirmBtn = safeGetElement('confirmPostBtn');
                    if (confirmBtn) {
                        confirmBtn.disabled = false;
                        confirmBtn.textContent = confirmBtn.textContent.includes('Posting') ? 'Ya, Posting Jurnal' :
                            'Ya, Unpost';
                    }
                }

                // Initial setup
                document.addEventListener('DOMContentLoaded', function() {
                    attachDeleteEventListeners();
                    setupPostModalEventListeners();
                });

            })(); // End IIFE
        </script>
    @endpush
</x-app-layout>
